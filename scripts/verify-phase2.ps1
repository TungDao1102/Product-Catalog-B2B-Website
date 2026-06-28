<#
.SYNOPSIS
    Verify Phase 2 (Product Management) deliverables
.DESCRIPTION
    Runs 40+ checks against models, seeders, Filament resources, Blade views,
    controllers, routes, and automated tests. Exits 0 if all pass, 1 if any fail.
#>

$ErrorActionPreference = 'Stop'
$ScriptDir  = Split-Path -Parent $PSCommandPath
$ProjectRoot = Split-Path -Parent $ScriptDir
Set-Location -LiteralPath $ProjectRoot

$PHP      = "C:\Users\daotu\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
$PHPUNIT  = "$ProjectRoot\vendor\bin\phpunit.bat"
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

Write-Host "`n=========================================" -ForegroundColor Cyan
Write-Host "  Phase 2 - Product Management Verification" -ForegroundColor Cyan
Write-Host "=========================================`n" -ForegroundColor Cyan

# ---- 1. Models ----
Write-Host "[1/7] Models" -ForegroundColor Cyan

$modelPaths = @(
    "app\Models\Category.php",
    "app\Models\Brand.php",
    "app\Models\Product.php"
)
foreach ($m in $modelPaths) {
    $fullPath = "$ProjectRoot\$m"
    Test-Check -Name "Model exists: $m" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $m" }
    }
}

Test-Check -Name "Category has Fillable attribute" -Script {
    $content = Get-Content "$ProjectRoot\app\Models\Category.php" -Raw
    if ($content -notmatch '#\[Fillable\(') { throw "Missing #[Fillable] attribute" }
    if ($content -notmatch 'parent_id') { throw "Missing parent_id in Fillable" }
    if ($content -notmatch "function parent\(\)") { throw "Missing parent() relationship" }
    if ($content -notmatch "function children\(\)") { throw "Missing children() relationship" }
    if ($content -notmatch "function products\(\)") { throw "Missing products() relationship" }
}

Test-Check -Name "Brand has Fillable attribute" -Script {
    $content = Get-Content "$ProjectRoot\app\Models\Brand.php" -Raw
    if ($content -notmatch '#\[Fillable\(') { throw "Missing #[Fillable] attribute" }
    if ($content -notmatch "function products\(\)") { throw "Missing products() relationship" }
}

Test-Check -Name "Product has Fillable + array casts + relationships" -Script {
    $content = Get-Content "$ProjectRoot\app\Models\Product.php" -Raw
    if ($content -notmatch '#\[Fillable\(') { throw "Missing #[Fillable] attribute" }
    if ($content -notmatch "'images' => 'array'") { throw "Missing images array cast" }
    if ($content -notmatch "'technical_specs' => 'array'") { throw "Missing technical_specs array cast" }
    if ($content -notmatch "'price' => 'decimal:2'") { throw "Missing price decimal cast" }
    if ($content -notmatch "function category\(\)") { throw "Missing category() relationship" }
    if ($content -notmatch "function brand\(\)") { throw "Missing brand() relationship" }
}

# ---- 2. Seeders ----
Write-Host "[2/7] Seeders" -ForegroundColor Cyan

$seederPaths = @(
    "database\seeders\CategorySeeder.php",
    "database\seeders\BrandSeeder.php",
    "database\seeders\ProductSeeder.php",
    "database\seeders\DatabaseSeeder.php"
)
foreach ($s in $seederPaths) {
    $fullPath = "$ProjectRoot\$s"
    Test-Check -Name "Seeder exists: $s" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $s" }
    }
}

Test-Check -Name "DatabaseSeeder chains CategorySeeder" -Script {
    $content = Get-Content "$ProjectRoot\database\seeders\DatabaseSeeder.php" -Raw
    if ($content -notmatch 'CategorySeeder::class') { throw "Missing CategorySeeder call" }
    if ($content -notmatch 'BrandSeeder::class') { throw "Missing BrandSeeder call" }
    if ($content -notmatch 'ProductSeeder::class') { throw "Missing ProductSeeder call" }
}

Test-Check -Name "CategorySeeder has 3-level hierarchy" -Script {
    $content = Get-Content "$ProjectRoot\database\seeders\CategorySeeder.php" -Raw
    $count = [regex]::Matches($content, "Category::create").Count
    if ($count -lt 15) { throw "Only $count categories (expected 15+)" }
    if ($content -notmatch "'parent_id' => null") { throw "Missing root categories (parent_id => null)" }
}

Test-Check -Name "BrandSeeder has 5 brands" -Script {
    $content = Get-Content "$ProjectRoot\database\seeders\BrandSeeder.php" -Raw
    $count = [regex]::Matches($content, "Brand::create").Count
    if ($count -lt 5) { throw "Only $count brands (expected 5+)" }
}

Test-Check -Name "ProductSeeder has 10+ products" -Script {
    $content = Get-Content "$ProjectRoot\database\seeders\ProductSeeder.php" -Raw
    $count = [regex]::Matches($content, "Product::create").Count
    if ($count -lt 10) { throw "Only $count products (expected 10+)" }
}

Test-Check -Name "ProductSeeder uses array-of-objects specs format" -Script {
    $content = Get-Content "$ProjectRoot\database\seeders\ProductSeeder.php" -Raw
    if ($content -notmatch "attribute_name") { throw "Missing attribute_name in specs" }
    if ($content -notmatch "attribute_value") { throw "Missing attribute_value in specs" }
    if ($content -notmatch "'is_featured' => true") { throw "Missing featured products" }
}

# ---- 3. Filament Resources ----
Write-Host "[3/7] Filament Resources" -ForegroundColor Cyan

$resourcePaths = @(
    "app\Filament\Resources\Categories\CategoryResource.php",
    "app\Filament\Resources\Categories\Schemas\CategoryForm.php",
    "app\Filament\Resources\Categories\Tables\CategoriesTable.php",
    "app\Filament\Resources\Brands\BrandResource.php",
    "app\Filament\Resources\Brands\Schemas\BrandForm.php",
    "app\Filament\Resources\Brands\Tables\BrandsTable.php",
    "app\Filament\Resources\Products\ProductResource.php",
    "app\Filament\Resources\Products\Schemas\ProductForm.php",
    "app\Filament\Resources\Products\Tables\ProductsTable.php",
    "app\Filament\Resources\Products\Pages\CreateProduct.php",
    "app\Filament\Resources\Products\Pages\EditProduct.php"
)
foreach ($r in $resourcePaths) {
    $fullPath = "$ProjectRoot\$r"
    Test-Check -Name "Resource exists: $r" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $r" }
    }
}

Test-Check -Name "ProductForm has 3-level cascading selects" -Script {
    $content = Get-Content "$ProjectRoot\app\Filament\Resources\Products\Schemas\ProductForm.php" -Raw
    if ($content -notmatch "category_level_0") { throw "Missing category_level_0 select" }
    if ($content -notmatch "category_level_1") { throw "Missing category_level_1 select" }
    if ($content -notmatch "dehydrated\(false\)") { throw "Missing dehydrated(false)" }
    if ($content -notmatch "->live\(\)") { throw "Missing live() on cascading selects" }
}

Test-Check -Name "ProductForm has FileUpload + Repeater" -Script {
    $content = Get-Content "$ProjectRoot\app\Filament\Resources\Products\Schemas\ProductForm.php" -Raw
    if ($content -notmatch "FileUpload.*images") { throw "Missing images FileUpload" }
    if ($content -notmatch "Repeater.*technical_specs") { throw "Missing specs Repeater" }
    if ($content -notmatch "attribute_name") { throw "Missing attribute_name in Repeater" }
    if ($content -notmatch "attribute_value") { throw "Missing attribute_value in Repeater" }
    if ($content -notmatch "FileUpload.*brochure") { throw "Missing brochure FileUpload" }
}

Test-Check -Name "CreateProduct has afterSave Intervention resize" -Script {
    $content = Get-Content "$ProjectRoot\app\Filament\Resources\Products\Pages\CreateProduct.php" -Raw
    if ($content -notmatch "afterSave") { throw "Missing afterSave hook" }
    if ($content -notmatch "Intervention") { throw "Missing Intervention Image" }
    if ($content -notmatch "resize\(width: 600") { throw "Missing 600x600 resize" }
}

Test-Check -Name "EditProduct has afterSave + DeleteAction" -Script {
    $content = Get-Content "$ProjectRoot\app\Filament\Resources\Products\Pages\EditProduct.php" -Raw
    if ($content -notmatch "afterSave") { throw "Missing afterSave hook" }
    if ($content -notmatch "DeleteAction") { throw "Missing DeleteAction" }
}

Test-Check -Name "CategoryResource navigation config" -Script {
    $content = Get-Content "$ProjectRoot\app\Filament\Resources\Categories\CategoryResource.php" -Raw
    if ($content -notmatch "navigationSort.*= 1") { throw "Wrong navigation sort" }
    if ($content -notmatch "heroicon-o-rectangle-stack") { throw "Wrong navigation icon" }
}

Test-Check -Name "Navigation sort order (Cat=1, Brand=2, Product=3)" -Script {
    $cat = Get-Content "$ProjectRoot\app\Filament\Resources\Categories\CategoryResource.php" -Raw
    $brand = Get-Content "$ProjectRoot\app\Filament\Resources\Brands\BrandResource.php" -Raw
    $prod = Get-Content "$ProjectRoot\app\Filament\Resources\Products\ProductResource.php" -Raw
    if ($cat -notmatch "navigationSort.*= 1") { throw "Category sort != 1" }
    if ($brand -notmatch "navigationSort.*= 2") { throw "Brand sort != 2" }
    if ($prod -notmatch "navigationSort.*= 3") { throw "Product sort != 3" }
}

# ---- 4. Blade Views ----
Write-Host "[4/7] Blade Views" -ForegroundColor Cyan

$viewPaths = @(
    "resources\views\products\show.blade.php",
    "resources\views\products\index.blade.php",
    "resources\views\components\category-tree.blade.php",
    "resources\views\categories\show.blade.php"
)
foreach ($v in $viewPaths) {
    $fullPath = "$ProjectRoot\$v"
    Test-Check -Name "View exists: $v" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $v" }
    }
}

Test-Check -Name "Product detail uses array-of-objects specs" -Script {
    $content = Get-Content "$ProjectRoot\resources\views\products\show.blade.php" -Raw
    # Check for the key parts of array-of-objects iteration pattern
    if ($content -notmatch '\$spec\[''attribute_name''\]') { throw "Missing attribute_name in loop" }
    if ($content -notmatch '\$spec\[''attribute_value''\]') { throw "Missing attribute_value in loop" }
    if ($content -notmatch 'is_array.*technical_specs') { throw "Missing array guard check" }
    if ($content -notmatch "asset\('storage/") { throw "Missing storage asset() path" }
    if ($content -notmatch "nl2br\(e\(") { throw "Missing XSS-safe nl2br(e()) pattern" }
}

Test-Check -Name "Product index uses category tree component" -Script {
    $content = Get-Content "$ProjectRoot\resources\views\products\index.blade.php" -Raw
    if ($content -notmatch "x-category-tree") { throw "Missing category-tree component" }
    if ($content -notmatch "Không tìm thấy sản phẩm nào") { throw "Missing empty state" }
    if ($content -notmatch "links\(\)") { throw "Missing pagination" }
}

Test-Check -Name "Controller: ProductController with search/sort/filter" -Script {
    $content = Get-Content "$ProjectRoot\app\Http\Controllers\ProductController.php" -Raw
    if ($content -notmatch "like.*%") { throw "Missing MySQL LIKE search" }
    if ($content -notmatch "paginate\(9\)") { throw "Missing paginate(9)" }
    if ($content -notmatch "Category::with\('children'\)") { throw "Missing category tree eager load" }
}

# ---- 5. Tests ----
Write-Host "[5/7] Tests" -ForegroundColor Cyan

$testPaths = @(
    "tests\Feature\Filament\CategoryResourceTest.php",
    "tests\Feature\Filament\BrandResourceTest.php",
    "tests\Feature\Filament\ProductResourceTest.php",
    "tests\Feature\Filament\ProductSpecsTest.php",
    "tests\Feature\Filament\ResourceNavigationTest.php",
    "tests\Feature\Http\HomePageTest.php",
    "tests\Feature\Http\CategoryPageTest.php",
    "tests\Feature\Http\ProductDetailTest.php",
    "tests\Feature\Http\ProductSearchTest.php",
    "tests\Feature\Http\ProductCardRenderingTest.php"
)
foreach ($t in $testPaths) {
    $fullPath = "$ProjectRoot\$t"
    Test-Check -Name "Test exists: $t" -Script {
        if (-not (Test-Path $fullPath)) { throw "Missing $t" }
    }
}

# ---- 6. Dependencies & Config ----
Write-Host "[6/7] Dependencies & Config" -ForegroundColor Cyan

Test-Check -Name "intervention/image-laravel in composer.json" -Script {
    $content = Get-Content "$ProjectRoot\composer.json" -Raw
    if ($content -notmatch "intervention/image-laravel") { throw "Missing intervention/image-laravel dependency" }
}

Test-Check -Name "phpunit.xml configured for MySQL test DB" -Script {
    $content = [xml](Get-Content "$ProjectRoot\phpunit.xml" -Raw)
    $db = $Content.phpunit.php.env | Where-Object { $_.name -eq 'DB_DATABASE' }
    if ($db.value -ne 'b2b_catalog_testing') { throw "Test DB not b2b_catalog_testing" }
}

Test-Check -Name "Vite HMR refresh enabled" -Script {
    $content = Get-Content "$ProjectRoot\vite.config.js" -Raw
    if ($content -notmatch "refresh.*true") { throw "Missing Vite HMR refresh" }
}

# ---- 7. Database Seed (smoke test) ----
Write-Host "[7/7] Database (seed smoke test)" -ForegroundColor Cyan

Test-Check -Name "MySQL server running" -Script {
    $proc = Get-Process -Name mysqld -ErrorAction SilentlyContinue
    if (-not $proc) { throw "mysqld process not found" }
}

Test-Check -Name "Test database accessible" -Script {
    $result = & cmd /c "`"$MYSQL`" -u root -proot123 -h 127.0.0.1 b2b_catalog_testing -e `"SELECT 1;`" 2>&1"
    $exit = $LASTEXITCODE
    if ($exit -ne 0) { throw "Cannot connect to test DB (exit: $exit)" }
}

# ---- Run PHPUnit tests ----
Write-Host "`n--- Running PHPUnit tests ---" -ForegroundColor Yellow
try {
    $testOutput = & $PHP artisan test --filter='Filament|Http' 2>&1
    $exitCode = $LASTEXITCODE
    # Strip ANSI escape codes for clean parsing
    $ansiRegex = [regex]'\x1b\[[0-9;]*[mK]'
    $cleanOutput = $testOutput | Out-String
    $cleanOutput = $ansiRegex.Replace($cleanOutput, '')
    $cleanOutput | Write-Host

    if ($exitCode -ne 0) {
        throw "PHPUnit exited with code $exitCode"
    }
    Write-Host "  [PASS] All PHPUnit tests passed (exit code 0)" -ForegroundColor Green
    $script:passed++
} catch {
    Write-Host "  [FAIL] PHPUnit tests" -ForegroundColor Red
    Write-Host "         $_" -ForegroundColor Gray
    $script:failed++
}

# ---- Summary ----
Write-Host "=========================================" -ForegroundColor Cyan
$total = $passed + $failed
if ($failed -eq 0) {
    Write-Host "  Phase 2 VERIFIED - $passed/$total checks passed" -ForegroundColor Green
    Write-Host "=========================================`n" -ForegroundColor Cyan
    exit 0
} else {
    Write-Host "  Phase 2 - $passed/$total passed, $failed FAILED" -ForegroundColor Red
    Write-Host "  Review failures above before proceeding to Phase 3." -ForegroundColor Yellow
    Write-Host "=========================================`n" -ForegroundColor Cyan
    exit 1
}
