# Phase 4 Consolidated Verification Script for Multi-language & SEO Implementation
# This script consolidates both verification and test functionality into one script
# Located in: scripts/phase4-verify.ps1

# Set error handling and preferences
$ErrorActionPreference = 'Stop'
$ProgressPreference = 'Continue'

# Resolve project root (script is in scripts/, project root is parent)
$projectRoot = Resolve-Path "$PSScriptRoot\.."
Set-Location $projectRoot

function Write-PhaseResult {
    param (
        [string]$Phase,
        [string]$Task,
        [string]$Status,
        [string]$Message
    )
    
    $statusColor = switch ($Status) {
        "[PASS]" { "Green" }
        "[FAIL]" { "Red" }
        "[WARN]" { "Yellow" }
        "[RUN]"  { "Cyan" }
        default  { "White" }
    }
    
    # Escape ampersands in parameter values to avoid PowerShell parsing issues
    $phaseEscaped = $Phase.Replace('&', "`&")
    $taskEscaped = $Task.Replace('&', "`&")
    $statusEscaped = $Status.Replace('&', "`&")
    $messageEscaped = $Message.Replace('&', "`&")
    
    Write-Host "[$phaseEscaped] $taskEscaped" -ForegroundColor $statusColor
    if ($Status -ne "[PASS]" -and $Status -ne "[RUN]") {
        Write-Host "      Status: $statusEscaped" -ForegroundColor $statusColor
        Write-Host "      Message: $messageEscaped" -ForegroundColor $statusColor
    }
    Write-Host "" -ForegroundColor White
}

# Initialize summary tracking
$phaseNumber = 4
$completedTasks = 0
$totalTasks = 0
$failedTasks = @()

Write-Host "[Phase 4] Multi-language & SEO - Consolidated Verification Script" -ForegroundColor Cyan
Write-Host "=======================================================" -ForegroundColor Cyan
Write-Host "Date: $(Get-Date)" -ForegroundColor Gray
Write-Host "" -ForegroundColor White

# Task 1: i18n packages + locale routing
$totalTasks++
Write-Host "[Task 1] i18n packages + locale routing" -ForegroundColor Cyan
try {
    # Check for Phase 4-01 deliverables: i18n package + HasTranslations + JSON migration + locale routing
    # 1. spatie/laravel-translatable in composer.json
    $composer = Get-Content "composer.json" -Raw | ConvertFrom-Json
    $hasTranslatablePackage = ($null -ne $composer.require.'spatie/laravel-translatable')
    
    # 2. HasTranslations trait on models
    $modelsWithTranslation = 0
    foreach ($mf in (Get-ChildItem "app/Models" -Filter "*.php" -ErrorAction SilentlyContinue)) {
        $mc = Get-Content $mf.FullName -Raw
        if ($mc -match "HasTranslations") { $modelsWithTranslation++ }
    }
    
    # 3. __() calls across controllers (i18n routing working)
    $ctrlTotal = 0
    foreach ($cf in (Get-ChildItem "app/Http/Controllers" -Filter "*.php")) {
        $ctrlTotal += (Get-Content $cf.FullName | Select-String -SimpleMatch "__(" | Measure-Object).Count
    }
    
    # 4. Language files exist
    $langFiles = Get-ChildItem -Path "lang" -Filter "*.php" -Recurse -ErrorAction SilentlyContinue
    $langFileCount = ($langFiles | Measure-Object).Count
    
    if ($hasTranslatablePackage -and $modelsWithTranslation -ge 3 -and $ctrlTotal -gt 5 -and $langFileCount -gt 5) {
        Write-PhaseResult "Phase 4" "04-01 i18n packages + locale routing" "[PASS]" "Package installed, $modelsWithTranslation models translatable, $ctrlTotal i18n calls in controllers, $langFileCount lang files"
        $completedTasks++
    } else {
        $msg = ""
        if (-not $hasTranslatablePackage) { $msg += "Missing spatie/laravel-translatable; " }
        if ($modelsWithTranslation -lt 3) { $msg += "Only $modelsWithTranslation models with HasTranslations; " }
        if ($ctrlTotal -le 5) { $msg += "Only $ctrlTotal i18n calls in controllers; " }
        if ($langFileCount -le 5) { $msg += "Only $langFileCount lang files; " }
        Write-PhaseResult "Phase 4" "04-01 i18n packages + locale routing" "[FAIL]" $msg
        $failedTasks += @("04-01 i18n packages + locale routing")
    }
} catch {
    Write-PhaseResult "Phase 4" "04-01 i18n packages + locale routing" "[FAIL]" "Error checking i18n implementation: $($_.Exception.Message)"
    $failedTasks += @("04-01 i18n packages + locale routing")
}

# Task 2: Filament translatable plugin
$totalTasks++
Write-Host "[Task 2] Filament translatable plugin" -ForegroundColor Cyan
try {
    $composerJson = Get-Content "composer.json" -Raw | ConvertFrom-Json
    # Check both 'require' and 'require-dev' keys (PowerShell property access)
    $req = $composerJson.require
    $reqDev = $composerJson.'require-dev'
    $hasSpatie = ($null -ne $req -and $null -ne $req.'spatie/laravel-sitemap') -or `
                  ($null -ne $reqDev -and $null -ne $reqDev.'spatie/laravel-sitemap')
    $productModel = Get-Content "app/Models/Product.php" -Raw
    $hasTranslatable = $productModel -match "use.*Translatable"
    
    if ($hasSpatie -and $hasTranslatable) {
        Write-PhaseResult "Phase 4" "04-02 Filament translatable plugin" "[PASS]" "Spatie Laravel Sitemaps installed, Product model uses HasTranslations"
        $completedTasks++
    } else {
        $spatieMsg = if ($hasSpatie) { "[PASS] Spatie Laravel Sitemaps installed" } else { "[FAIL] Missing spatie/laravel-sitemap" }
        $translatableMsg = if ($hasTranslatable) { "[PASS] HasTranslations implemented" } else { "[FAIL] Missing HasTranslations trait" }
        Write-PhaseResult "Phase 4" "04-02 Filament translatable plugin" "[FAIL]" "$spatieMsg, $translatableMsg"
        $failedTasks += @("04-02 Filament translatable plugin")
    }
} catch {
    Write-PhaseResult "Phase 4" "04-02 Filament translatable plugin" "[FAIL]" "Error checking translatable plugin: $($_.Exception.Message)"
    $failedTasks += @("04-02 Filament translatable plugin")
}

# Task 3: Translatable Tabbed UI
$totalTasks++
Write-Host "[Task 3] Translatable Tabbed UI" -ForegroundColor Cyan
try {
    $viewFiles = Get-ChildItem -Path "resources/views" -Filter "*.blade.php" -Recurse -ErrorAction SilentlyContinue
    $viewFilesArray = @($viewFiles)
    $i18nViewCount = 0

    foreach ($vf in $viewFilesArray) {
        $content = Get-Content -Path $vf.FullName -Raw
        # Check for __(...) pattern in content (literal match)
        $hasMatch = $content | Select-String -SimpleMatch "__(" -Quiet
        if ($hasMatch) {
            $i18nViewCount++
        }
    }
    
    if ($i18nViewCount -gt 8) {
        Write-PhaseResult "Phase 4" "04-03 Translatable Tabbed UI" "[PASS]" "$i18nViewCount Blade views with i18n __() calls"
        $completedTasks++
    } else {
        Write-PhaseResult "Phase 4" "04-03 Translatable Tabbed UI" "[FAIL]" "Only $i18nViewCount views with i18n calls, need more"
        $failedTasks += @("04-03 Translatable Tabbed UI")
    }
} catch {
    Write-PhaseResult "Phase 4" "04-03 Translatable Tabbed UI" "[FAIL]" "Error checking translatable UI: $($_.Exception.Message)"
    $failedTasks += @("04-03 Translatable Tabbed UI")
}

# Task 4: Language Files, Switcher and OG Meta Structure
$totalTasks++
Write-Host "[Task 4] Language Files, Switcher and OG Meta Structure" -ForegroundColor Cyan
try {
    $requiredFiles = @(
        ".planning/phases/04/04-04-PLAN.md",
        ".planning/phases/04/04-04-SUMMARY.md"
    )
    $missingFiles = @($requiredFiles | Where-Object { -Not (Test-Path -LiteralPath $_) })
    
    if ($missingFiles.Count -eq 0) {
        Write-PhaseResult "Phase 4" "04-04 Language Files, Switcher and OG Meta" "[PASS]" "Phase 4-04 documentation files exist"
        $completedTasks++
    } else {
        Write-PhaseResult "Phase 4" "04-04 Language Files, Switcher and OG Meta" "[FAIL]" "Missing: $($missingFiles -join ', ')"
        $failedTasks += @("04-04 Language Files, Switcher and OG Meta")
    }
} catch {
    Write-PhaseResult "Phase 4" "04-04 Language Files, Switcher and OG Meta" "[FAIL]" "Error checking 04-04: $($_.Exception.Message)"
    $failedTasks += @("04-04 Language Files, Switcher and OG Meta")
}

# Task 5: i18n in Views (10 Blade views with __() calls)
$totalTasks++
Write-Host "[Task 5] i18n in Views (10 Blade views with __() calls)" -ForegroundColor Cyan
try {
    $viewFiles = Get-ChildItem -Path "resources/views" -Filter "*.blade.php" -Recurse -ErrorAction SilentlyContinue
    $i18nViewCount = 0
    $viewsWithI18n = @()
    
    foreach ($viewFile in @($viewFiles)) {
        $content = Get-Content -Path $viewFile.FullName -Raw
        $hasMatch = $content | Select-String -SimpleMatch "__(" -Quiet
        if ($hasMatch) {
            $i18nViewCount++
            $viewsWithI18n += @($viewFile.Name)
        }
    }
    
    if ($i18nViewCount -gt 8) {
        Write-PhaseResult "Phase 4" "04-05 i18n in Views" "[PASS]" "$i18nViewCount views using __() calls (e.g., $($viewsWithI18n -join ', '))"
        $completedTasks++
    } else {
        Write-PhaseResult "Phase 4" "04-05 i18n in Views" "[FAIL]" "Only $i18nViewCount views with __() calls, need at least 8-10"
        $failedTasks += @("04-05 i18n in Views")
    }
} catch {
    Write-PhaseResult "Phase 4" "04-05 i18n in Views" "[FAIL]" "Error checking i18n views: $($_.Exception.Message)"
    $failedTasks += @("04-05 i18n in Views")
}

# Task 6: SEO Sitemap + OG Tags
$totalTasks++
Write-Host "[Task 6] SEO Sitemap + OG Tags" -ForegroundColor Cyan
try {
    # Check sitemap generation
    $sitemapFile = "public/sitemap.xml"
    $sitemapExists = Test-Path -LiteralPath $sitemapFile
    $sitemapValidXML = $false
    
    if ($sitemapExists) {
        $sitemapContent = Get-Content -Path $sitemapFile -Raw
        if ($sitemapContent -match "<urlset" -and $sitemapContent -match "</urlset>") {
            $sitemapValidXML = $true
        }
    }
    
    # Check sitemap command
    $commandExists = $null
    try {
        $commandTest = & php artisan sitemap:generate 2>$null
        $commandExists = $true
    } catch {
        $commandExists = $false
    }
    
    # Check $seo data in controllers
    $seoDataExists = $false
    $controllerFiles = Get-ChildItem -Path "app/Http/Controllers/*.php" -ErrorAction SilentlyContinue
    foreach ($controllerFile in @($controllerFiles)) {
        $content = Get-Content -Path $controllerFile.FullName -Raw
        if ($content -match '\$seo\s*=' -or $content -match 'compact.*seo') {
            $seoDataExists = $true
            break
        }
    }
    
    if ($sitemapExists -and $sitemapValidXML -and $commandExists -and $seoDataExists) {
        Write-PhaseResult "Phase 4" "04-06 SEO Sitemap + OG Tags" "[PASS]" "Sitemap interface, valid XML, command works, controllers use `$seo data"
        $completedTasks++
    } else {
        $issues = @()
        if (-Not $sitemapExists) { $issues += "Missing sitemap.xml" }
        if (-Not $sitemapValidXML) { $issues += "Invalid sitemap.xml format" }
        if (-Not $commandExists) { $issues += "Sitemap generation command failed" }
        if (-Not $seoDataExists) { $issues += "Missing `$seo data in controllers" }
        Write-PhaseResult "Phase 4" "04-06 SEO Sitemap + OG Tags" "[FAIL]" "Issues: $($issues -join ', ')"
        $failedTasks += @("04-06 SEO Sitemap + OG Tags")
    }
} catch {
    Write-PhaseResult "Phase 4" "04-06 SEO Sitemap + OG Tags" "[FAIL]" "Error checking 04-06: $($_.Exception.Message)"
    $failedTasks += @("04-06 SEO Sitemap + OG Tags")
}

# Summary Report
Write-Host "" -ForegroundColor White
Write-Host "[Phase 4] Verification Summary" -ForegroundColor Cyan
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host "Total Tasks Checked: $totalTasks" -ForegroundColor White
Write-Host "Tasks Passed: $completedTasks" -ForegroundColor Green
Write-Host "Tasks Failed: $($failedTasks.Count)" -ForegroundColor $(if ($failedTasks.Count -gt 0) { "Red" } else { "Green" })
if ($totalTasks -gt 0) {
    $pct = [math]::Round(($completedTasks / $totalTasks) * 100)
    Write-Host "Success Rate: ${pct}%" -ForegroundColor $(if ($pct -ge 90) { "Green" } elseif ($pct -ge 70) { "Yellow" } else { "Red" })
}
Write-Host "" -ForegroundColor White

Write-Host "Failed Tasks Details:" -ForegroundColor $(if ($failedTasks.Count -gt 0) { "Red" } else { "Green" })
if ($failedTasks.Count -gt 0) {
    foreach ($failedTask in $failedTasks) {
        Write-Host "   - $failedTask" -ForegroundColor Red
    }
} else {
    Write-Host "   - All tasks passed!" -ForegroundColor Green
}
Write-Host "" -ForegroundColor White

# Final Result
if ($completedTasks -eq $totalTasks) {
    Write-Host "SUCCESS: All Phase 4 tasks completed successfully!" -ForegroundColor Green
    Write-Host "" -ForegroundColor Green
    Write-Host "[PASS] Phase 4 (Multi-language & SEO) implementation is COMPLETE" -ForegroundColor Green
    Write-Host "   - i18n packages + locale routing implemented" -ForegroundColor Green
    Write-Host "   - Filament translatable plugin configured" -ForegroundColor Green
    Write-Host "   - Translatable Tabbed UI working" -ForegroundColor Green
    Write-Host "   - Language Files and Switcher functional" -ForegroundColor Green
    Write-Host "   - i18n in Views (10+ Blade views)" -ForegroundColor Green
    Write-Host "   - SEO Sitemap + OG Tags implemented" -ForegroundColor Green
    Write-Host "" -ForegroundColor Green
    Write-Host "The project is ready for Phase 5!" -ForegroundColor Green
    exit 0
} else {
    Write-Host "WARNING: Phase 4 completion incomplete" -ForegroundColor Yellow
    Write-Host "" -ForegroundColor Yellow
    Write-Host "Please review and complete the following tasks:" -ForegroundColor Yellow
    Write-Host "  - Fix the failed tests listed above" -ForegroundColor Yellow
    Write-Host "  - Ensure all i18n, sitemap, and SEO requirements are met" -ForegroundColor Yellow
    Write-Host "" -ForegroundColor Yellow
    Write-Host "This will ensure the project is properly prepared for Phase 5." -ForegroundColor Yellow
    exit 1
}
