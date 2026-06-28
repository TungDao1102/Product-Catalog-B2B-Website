<# 
.SYNOPSIS
    Start Product Catalog B2B development environment (MySQL + Laravel dev server)

.DESCRIPTION
    Starts MySQL (if not running) and launches the Laravel PHP development server.
    Run this to quickly get the project up and running.
#>

$ErrorActionPreference = 'Stop'
$ScriptDir  = Split-Path -Parent $PSCommandPath
$ProjectRoot = Split-Path -Parent $ScriptDir
Set-Location -LiteralPath $ProjectRoot

# -- Config -----------------------------------------------------------------
$PHP      = "C:\Users\daotu\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
$MYSQLD   = "C:\Program Files\MySQL\MySQL Server 8.4\bin\mysqld.exe"
$MYSQL    = "C:\Program Files\MySQL\MySQL Server 8.4\bin\mysql.exe"
$DATADIR  = "$env:LOCALAPPDATA\MySQL\Data"
$PORT     = 8080

# -- Check MySQL ------------------------------------------------------------
$mysqlRunning = $null -ne (Get-Process -Name mysqld -ErrorAction SilentlyContinue)

if (-not $mysqlRunning) {
    Write-Host "Starting MySQL..." -ForegroundColor Yellow
    if (-not (Test-Path $MYSQLD)) {
        Write-Error "mysqld not found at $MYSQLD. Install MySQL or fix path."
        exit 1
    }
    if (-not (Test-Path $DATADIR)) {
        Write-Error "MySQL data directory not found at $DATADIR. Run init first."
        exit 1
    }
    Start-Process -FilePath $MYSQLD -ArgumentList "--datadir=`"$DATADIR`" --port=3306" -WindowStyle Hidden
    Start-Sleep -Seconds 3

    # Verify MySQL started
    if ($null -eq (Get-Process -Name mysqld -ErrorAction SilentlyContinue)) {
        Write-Error "MySQL failed to start. Check the data directory."
        exit 1
    }
    Write-Host "MySQL started (PID: $((Get-Process -Name mysqld).Id))" -ForegroundColor Green
} else {
    Write-Host "MySQL already running (PID: $((Get-Process -Name mysqld).Id))" -ForegroundColor Green
}

# -- Check PHP ---------------------------------------------------------------
if (-not (Test-Path $PHP)) {
    Write-Error "PHP not found at $PHP. Install PHP or fix path."
    exit 1
}

# -- Start Laravel Dev Server -----------------------------------------------
# Check if server already running by testing the URL
$serverRunning = $false
try {
    $r = Invoke-WebRequest -Uri "http://127.0.0.1:$PORT/" -UseBasicParsing -TimeoutSec 2
    $serverRunning = $r.StatusCode -eq 200
} catch { }
if ($serverRunning) {
    Write-Host "Laravel dev server already running on port $PORT" -ForegroundColor Green
} else {
    Write-Host "Starting Laravel dev server on http://127.0.0.1:$PORT ..." -ForegroundColor Yellow
    Start-Process -FilePath "cmd.exe" -ArgumentList "/c `"$PHP`" artisan serve --port=$PORT --host=127.0.0.1" -WindowStyle Hidden
    Start-Sleep -Seconds 3

    # Verify
    try {
        $r = Invoke-WebRequest -Uri "http://127.0.0.1:$PORT/" -UseBasicParsing -TimeoutSec 5
        Write-Host "Laravel dev server running at http://127.0.0.1:$PORT/ (HTTP $($r.StatusCode))" -ForegroundColor Green
    } catch {
        Write-Warning "Laravel dev server may not be ready yet. Try http://127.0.0.1:$PORT/ in a moment."
    }
}

# -- Open Browser ------------------------------------------------------------
Start-Process "http://127.0.0.1:$PORT/"

# -- Start Vite HMR (auto-refresh frontend) ---------------------------------
$viteRunning = $null -ne (Get-CimInstance -ClassName Win32_Process -Filter "Name LIKE 'node%' AND CommandLine LIKE '%vite%'" -ErrorAction SilentlyContinue)
if (-not $viteRunning) {
    Write-Host "Starting Vite HMR (auto-refresh browser on save)..." -ForegroundColor Yellow
    Start-Process -FilePath "npm" -ArgumentList "run dev" -WindowStyle Hidden
    Start-Sleep -Seconds 2
} else {
    Write-Host "Vite HMR already running" -ForegroundColor Green
}

# -- Summary ----------------------------------------------------------------
Write-Host "`n========== Product Catalog B2B ==========" -ForegroundColor Cyan
Write-Host "  Site:      http://127.0.0.1:$PORT/" -ForegroundColor White
Write-Host "  Admin:     http://127.0.0.1:$PORT/admin/login" -ForegroundColor White
Write-Host "  Database:  product_catalog (root/root123)" -ForegroundColor White
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "-- Quick Commands ------------------------------------------------" -ForegroundColor Cyan
Write-Host "  .\scripts\run.ps1  Start all + auto-refresh browser" -ForegroundColor White
Write-Host "  .\scripts\stop.ps1 Stop all servers" -ForegroundColor White
Write-Host "----------------------------------------------------------------" -ForegroundColor Cyan
