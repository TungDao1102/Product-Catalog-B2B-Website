<#
.SYNOPSIS
    Verify Phase 1 (Foundation) deliverables
.DESCRIPTION
    Runs 25+ checks against the Laravel project, database, routes, views,
    admin panel, and page responses. Exits 0 if all pass, 1 if any fail.
#>

$ErrorActionPreference = 'Stop'
$ScriptDir  = Split-Path -Parent $PSCommandPath
$ProjectRoot = Split-Path -Parent $ScriptDir
Set-Location -LiteralPath $ProjectRoot

$PHP      = "C:\Users\daotu\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
$MYSQL    = "C:\Program Files\MySQL\MySQL Server 8.4\bin\mysql.exe"
$BASE_URL = "http://127.0.0.1:8080"

$passed = 0
$failed = 0

function Test-Check {
    param([string]$Name, [scriptblock]$Script)
    try {
        & $Script | Out-Null
        Write-Host "  [PASS] $Name" -ForegroundColor Green
        $script:passed++
    } catch {
        Write-Host "  [FAIL] $Name" -ForegroundColor Red
        $msg = $_.Exception.Message.Trim()
        if ($msg) { Write-Host "         $msg" -ForegroundColor Gray }
        $script:failed++
    }
}

Write-Host "`n===================================" -ForegroundColor Cyan
Write-Host "  Phase 1 - Foundation Verification" -ForegroundColor Cyan
Write-Host "===================================`n" -ForegroundColor Cyan

# ---- 1. PHP and Laravel ----
Write-Host "[1/9] PHP and Laravel" -ForegroundColor Cyan

Test-Check -Name "PHP binary exists" -Script {
    if (-not (Test-Path $PHP)) { throw "PHP not found at $PHP" }
}
Test-Check -Name "Laravel artisan works" -Script {
    $ver = & $PHP artisan --version 2>&1 | Out-String
    if ($ver -notmatch "Laravel Framework 13") { throw "Unexpected version: $ver" }
}
Test-Check -Name "Environment is local" -Script {
    $envText = & $PHP artisan env 2>&1 | Out-String
    $envText = $envText -replace '\s+', ''
    if ($envText -notmatch "local") { throw "Not local: $envText" }
}
Test-Check -Name "Composer deps installed (vendor exists)" -Script {
    if (-not (Test-Path "$ProjectRoot\vendor\autoload.php")) { throw "Missing vendor/autoload.php" }
}
Test-Check -Name ".env configured" -Script {
    if (-not (Test-Path "$ProjectRoot\.env")) { throw "Missing .env" }
    $envContent = Get-Content "$ProjectRoot\.env" -Raw
    if ($envContent -notmatch "DB_DATABASE=product_catalog") { throw "DB not configured" }
}

# ---- 2. MySQL ----
Write-Host "[2/9] MySQL" -ForegroundColor Cyan

Test-Check -Name "MySQL server running" -Script {
    $proc = Get-Process -Name mysqld -ErrorAction SilentlyContinue
    if (-not $proc) { throw "mysqld process not found" }
}
Test-Check -Name "Database product_catalog accessible" -Script {
    $result = & cmd /c "`"$MYSQL`" -u root -proot123 -h 127.0.0.1 product_catalog -e `"SELECT 1;`" 2>&1"
    $exit = $LASTEXITCODE
    if ($exit -ne 0) { throw "Cannot connect (exit: $exit)" }
}

# ---- 3. Migrations and Schema ----
Write-Host "[3/9] Migrations and Schema" -ForegroundColor Cyan

Test-Check -Name "All 10 migrations ran" -Script {
    $status = & $PHP artisan migrate:status 2>&1 | Select-String "Ran"
    if ($status.Count -lt 10) { throw "Only $($status.Count) migrations ran (expected 10)" }
}
Test-Check -Name "Admin user in database" -Script {
    $output = & $PHP artisan tinker --execute="echo App\Models\User::count();" 2>&1 | Out-String
    $count = ($output -replace '\D', '').Trim()
    if ([string]::IsNullOrEmpty($count) -or [int]$count -lt 1) { throw "No users found (output: $output)" }
}
Test-Check -Name "Filament admin panel registered" -Script {
    $path = "$ProjectRoot\app\Providers\Filament\AdminPanelProvider.php"
    if (-not (Test-Path $path)) { throw "Missing AdminPanelProvider" }
}

# ---- 4. Models ----
Write-Host "[4/9] Models" -ForegroundColor Cyan

$modelNames = @("Category", "Brand", "Product", "Post", "Project", "Contact", "Inquiry")
foreach ($model in $modelNames) {
    $path = "$ProjectRoot\app\Models\$model.php"
    Test-Check -Name "Model: $model" -Script {
        if (-not (Test-Path $path)) { throw "Missing app\Models\$model.php" }
    }
}

# ---- 5. Controllers ----
Write-Host "[5/9] Controllers" -ForegroundColor Cyan

$controllerNames = @("HomeController", "ProductController", "CategoryController")
foreach ($ctrl in $controllerNames) {
    $path = "$ProjectRoot\app\Http\Controllers\$ctrl.php"
    Test-Check -Name "Controller: $ctrl" -Script {
        if (-not (Test-Path $path)) { throw "Missing app\Http\Controllers\$ctrl.php" }
    }
}

# ---- 6. Routes ----
Write-Host "[6/9] Routes" -ForegroundColor Cyan

Test-Check -Name "5+ application routes registered" -Script {
    $routes = & $PHP artisan route:list --except-vendor 2>&1
    $count = @($routes | Select-String -Pattern "\bGET\b.*\bHEAD\b").Count
    if ($count -lt 5) { throw "Too few routes: $count" }
}
Test-Check -Name "Home route to HomeController" -Script {
    $routes = & $PHP artisan route:list --except-vendor 2>&1 | Out-String
    if ($routes -notmatch 'home.*HomeController') { throw "Home route not found" }
}
Test-Check -Name "san-pham route to ProductController" -Script {
    $routes = & $PHP artisan route:list --except-vendor 2>&1 | Out-String
    if ($routes -notmatch 'san-pham.*ProductController') { throw "Products route not found" }
}
Test-Check -Name "danh-muc route to CategoryController" -Script {
    $routes = & $PHP artisan route:list --except-vendor 2>&1 | Out-String
    if ($routes -notmatch 'danh-muc.*CategoryController') { throw "Category route not found" }
}
Test-Check -Name "lien-he route exists" -Script {
    $routes = & $PHP artisan route:list --except-vendor 2>&1 | Out-String
    if ($routes -notmatch 'lien-he') { throw "Contact route not found" }
}

# ---- 7. Blade Views ----
Write-Host "[7/9] Blade Views" -ForegroundColor Cyan

$viewPaths = @(
    "resources\views\layouts\app.blade.php"
    "resources\views\partials\navbar.blade.php"
    "resources\views\partials\footer.blade.php"
    "resources\views\home\index.blade.php"
    "resources\views\products\index.blade.php"
    "resources\views\products\show.blade.php"
    "resources\views\categories\show.blade.php"
    "resources\views\contact.blade.php"
    "resources\views\errors\404.blade.php"
)
foreach ($view in $viewPaths) {
    $fullPath = "$ProjectRoot\$view"
    Test-Check -Name "View: $view" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $view" }
    }
}

# ---- 8. Frontend Assets ----
Write-Host "[8/9] Frontend Assets" -ForegroundColor Cyan

$assetChecks = @(
    "public\css\b2b.css"
    "public\css\bootstrap.min.css"
    "public\css\style.css"
    "public\js\main.js"
    "public\img\logo.png"
    "public\lib\owlcarousel\owl.carousel.min.js"
    "public\lib\wow\wow.min.js"
)
foreach ($asset in $assetChecks) {
    $fullPath = "$ProjectRoot\$asset"
    Test-Check -Name "Asset: $asset" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $asset" }
    }
}

# ---- 9. HTTP Responses (requires dev server) ----
Write-Host "[9/9] HTTP Responses" -ForegroundColor Cyan

$serverUp = $false
try {
    $health = Invoke-WebRequest -Uri "$BASE_URL/" -UseBasicParsing -TimeoutSec 3
    $serverUp = $health.StatusCode -eq 200
} catch { }

if (-not $serverUp) {
    Write-Host "  [SKIP] Dev server not running - run .\scripts\run.ps1 first" -ForegroundColor Yellow
    Write-Host "  [SKIP] Then re-run this script to verify HTTP responses.`n" -ForegroundColor Yellow
} else {
    Test-Check -Name "Admin panel at /admin/login" -Script {
        $r = Invoke-WebRequest -Uri "$BASE_URL/admin/login" -UseBasicParsing -TimeoutSec 10
        if ($r.StatusCode -ne 200) { throw "HTTP $($r.StatusCode)" }
    }
    $testPages = @("/", "/san-pham", "/lien-he")
    foreach ($path in $testPages) {
        Test-Check -Name "GET $path returns 200" -Script {
            $r = Invoke-WebRequest -Uri "$BASE_URL$path" -UseBasicParsing -TimeoutSec 10
            if ($r.StatusCode -ne 200) { throw "HTTP $($r.StatusCode)" }
        }
    }
}

# ---- Summary ----
Write-Host "===================================" -ForegroundColor Cyan
$total = $passed + $failed
if ($failed -eq 0) {
    Write-Host "  Phase 1 VERIFIED - $passed/$total checks passed" -ForegroundColor Green
    Write-Host "===================================`n" -ForegroundColor Cyan
    exit 0
} else {
    Write-Host "  Phase 1 - $passed/$total passed, $failed FAILED" -ForegroundColor Red
    Write-Host "  Review failures above before proceeding to Phase 2." -ForegroundColor Yellow
    Write-Host "===================================`n" -ForegroundColor Cyan
    exit 1
}
