$ErrorActionPreference = "Stop"

$backendRoot = "resources/views/Backendpages"

function Rename-ToTemp {
    param ($path)
    $folderName = Split-Path $path -Leaf
    $parentPath = Split-Path $path -Parent
    $tempName = "__TEMP_$folderName"
    $tempPath = Join-Path $parentPath $tempName

    if (Test-Path $path) {
        Rename-Item -Path $path -NewName $tempName
        git add -A
        git commit -m "TEMP rename folder: $folderName → $tempName"
        return $tempPath
    } else {
        Write-Host "❌ Path not found: $path" -ForegroundColor Red
        return $null
    }
}

function Rename-ToLowercase {
    param ($originalPath, $tempPath)
    if (-not (Test-Path $tempPath)) {
        Write-Host "❌ TEMP path does not exist: $tempPath" -ForegroundColor Red
        return
    }

    $lowerName = (Split-Path $originalPath -Leaf).ToLower()
    $parentPath = Split-Path $originalPath -Parent
    $finalPath = Join-Path $parentPath $lowerName

    Rename-Item -Path $tempPath -NewName $lowerName
    git add -A
    git commit -m "Renamed folder: $(Split-Path $originalPath -Leaf) → $lowerName"

    return $finalPath
}

function Rename-BladeFilesToLowercase {
    param ($folder)
    $files = Get-ChildItem -Path $folder -Filter "*.blade.php" -File
    foreach ($file in $files) {
        $originalName = $file.Name
        $tempName = "$originalName.tmp"
        $tempPath = Join-Path $file.DirectoryName $tempName

        Rename-Item $file.FullName $tempPath
        git add -A
        git commit -m "TEMP rename file: $originalName → $tempName"

        $finalName = $originalName.ToLower()
        $finalPath = Join-Path $file.DirectoryName $finalName

        Rename-Item $tempPath $finalPath
        git add -A
        git commit -m "Renamed file: $originalName → $finalName"
    }
}

# MAIN EXECUTION
if (-Not (Test-Path $backendRoot)) {
    Write-Host "❌ Folder '$backendRoot' not found. Check your path." -ForegroundColor Red
    exit 1
}

$folders = Get-ChildItem -Path $backendRoot -Directory

foreach ($folder in $folders) {
    $absFolder = $folder.FullName
    $folderName = Split-Path $absFolder -Leaf

    # Skip if already lowercase
    if ($folderName -cmatch '^[a-z0-9_]+$') {
        Write-Host "⚠️  Skipping (already lowercase): $folderName"
        Rename-BladeFilesToLowercase $absFolder
        continue
    }

    $tempFolder = Rename-ToTemp $absFolder
    if ($tempFolder) {
        $finalFolder = Rename-ToLowercase $absFolder $tempFolder
        if ($finalFolder) {
            Rename-BladeFilesToLowercase $finalFolder
        }
    }
}

# OPTIONAL: Rename Backendpages → backendpages
if (Test-Path $backendRoot) {
    Rename-Item -Path $backendRoot -NewName "__TEMP_Backendpages"
    git add -A
    git commit -m "TEMP rename Backendpages → __TEMP_Backendpages"

    Rename-Item -Path "resources/views/__TEMP_Backendpages" -NewName "backendpages"
    git add -A
    git commit -m "Renamed Backendpages → backendpages"
}

git push

Write-Host "`n✅ All folders and .blade.php files renamed to lowercase and pushed to Git." -ForegroundColor Green
