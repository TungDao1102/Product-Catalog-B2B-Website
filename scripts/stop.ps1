<#
.SYNOPSIS
    Stop Product Catalog B2B development environment (Laravel dev server + Vite + MySQL)

.DESCRIPTION
    Stops the PHP Laravel development server, Vite/Node process, and optionally MySQL.
    Run this before restarting to apply code changes.
#>

$ErrorActionPreference = 'Stop'

Write-Host "Stopping development servers..." -ForegroundColor Yellow

# -- Stop Laravel Dev Server (artisan serve) -------------------------------
$phpProcesses = Get-Process -Name php* -ErrorAction SilentlyContinue |
    Where-Object { $_.CommandLine -match 'artisan serve' -or $_.MainWindowTitle -match 'artisan' } |
    Select-Object -First 1

if (-not $phpProcesses) {
    # Fallback: find PHP process by command line via WMI
    try {
        $phpProcesses = Get-CimInstance -ClassName Win32_Process -Filter "Name LIKE 'php%' AND CommandLine LIKE '%artisan%'" -ErrorAction SilentlyContinue |
            Select-Object -First 1
        if ($phpProcesses) {
            Stop-Process -Id $phpProcesses.ProcessId -Force -ErrorAction SilentlyContinue
            Write-Host "  Stopped Laravel dev server (PID: $($phpProcesses.ProcessId))" -ForegroundColor Green
        }
    } catch { }
}

if ($phpProcesses -and $phpProcesses.Id) {
    Stop-Process -Id $phpProcesses.Id -Force -ErrorAction SilentlyContinue
    Write-Host "  Stopped Laravel dev server (PID: $($phpProcesses.Id))" -ForegroundColor Green
} else {
    Write-Host "  No Laravel dev server found running." -ForegroundColor DarkGray
}

# Also kill any lingering php.exe that's running artisan
Get-Process -Name php* -ErrorAction SilentlyContinue |
    Where-Object { $_.CommandLine -match 'artisan' } |
    ForEach-Object {
        Stop-Process -Id $_.Id -Force -ErrorAction SilentlyContinue
        Write-Host "  Stopped additional artisan PHP process (PID: $($_.Id))" -ForegroundColor Green
    }

# -- Stop Vite / Node ---------------------------------------------------------
$nodeProcesses = Get-Process -Name node* -ErrorAction SilentlyContinue

if ($nodeProcesses) {
    # Find node processes related to Vite (running from this project)
    $viteProcesses = $nodeProcesses |
        Where-Object { $_.CommandLine -match 'vite' -or $_.CommandLine -match (Get-Location).Path.Replace('\', '\\') } |
        Select-Object -First 1

    if (-not $viteProcesses) {
        # WMI fallback
        try {
            $cwd = Get-Location | Select-Object -ExpandProperty Path
            $viteProcesses = Get-CimInstance -ClassName Win32_Process -Filter "Name LIKE 'node%' AND CommandLine LIKE '%vite%'" -ErrorAction SilentlyContinue |
                Where-Object { $_.CommandLine -match [regex]::Escape($cwd) -or $_.CommandLine -match 'vite' } |
                Select-Object -First 1
            if ($viteProcesses) {
                Stop-Process -Id $viteProcesses.ProcessId -Force -ErrorAction SilentlyContinue
                Write-Host "  Stopped Vite dev server (PID: $($viteProcesses.ProcessId))" -ForegroundColor Green
            }
        } catch { }
    }

    if ($viteProcesses -and $viteProcesses.Id) {
        Stop-Process -Id $viteProcesses.Id -Force -ErrorAction SilentlyContinue
        Write-Host "  Stopped Vite dev server (PID: $($viteProcesses.Id))" -ForegroundColor Green
    }
}

# Also find and kill any vite node process via WMI
try {
    $anyVite = Get-CimInstance -ClassName Win32_Process -Filter "Name LIKE 'node%' AND CommandLine LIKE '%vite%'" -ErrorAction SilentlyContinue
    foreach ($proc in $anyVite) {
        Stop-Process -Id $proc.ProcessId -Force -ErrorAction SilentlyContinue
        Write-Host "  Stopped Vite/Node process (PID: $($proc.ProcessId))" -ForegroundColor Green
    }
} catch { }

if (-not $viteProcesses -and -not $anyVite) {
    Write-Host "  No Vite dev server found running." -ForegroundColor DarkGray
}

# -- Stop MySQL (optional) ---------------------------------------------------
$mysqlProcess = Get-Process -Name mysqld -ErrorAction SilentlyContinue |
    Select-Object -First 1

if ($mysqlProcess) {
    Write-Host ""
    Write-Host "MySQL is running (PID: $($mysqlProcess.Id)). Stop it too? (y/N) " -ForegroundColor Yellow -NoNewline
    $response = Read-Host
    if ($response -eq 'y' -or $response -eq 'Y') {
        Stop-Process -Id $mysqlProcess.Id -Force -ErrorAction SilentlyContinue
        Start-Sleep -Seconds 2
        if ($null -eq (Get-Process -Name mysqld -ErrorAction SilentlyContinue)) {
            Write-Host "  Stopped MySQL." -ForegroundColor Green
        } else {
            Write-Warning "  Could not stop MySQL. Try stopping it manually."
        }
    } else {
        Write-Host "  MySQL left running." -ForegroundColor DarkGray
    }
} else {
    Write-Host "  MySQL is not running." -ForegroundColor DarkGray
}

Write-Host ""
Write-Host "Done." -ForegroundColor Cyan
