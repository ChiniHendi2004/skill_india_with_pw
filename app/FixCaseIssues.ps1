$ErrorActionPreference = "Stop"

$backendRoot = "resources/views/Backendpages"

function Rename-ToTemp {
    param ($path)
    $folderName = Split-Path $path -Leaf
    $parentPath = Split-Path $path -Parent
    $tempName = "__TEMP_$folderName"
    $tempPath = Join-Path $parentPath $tempName

    if (Test-Path $path) {
        Rename-Item -LiteralPath $path -NewName $tempName
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
    $lowerName = (Split-Path $originalPath -Leaf).ToLower()
    $parentPath = Split-Path $originalPath -Parent
    $finalPath = Join-Path $parentPath $lowerName

    if (Test-Path -LiteralPath $tempPath) {
        Rename-Item -LiteralPath $tempPath -NewName $lowerName
        git add -A
        git commit -m "Renamed folder: $(Split-Path $originalPath -Leaf) → $lowerName"
        return $finalPath
    } else {
        Write-Host "❌ TEMP path does not exist: $tempPath" -ForegroundColor Red
        return $null
    }
}

function Rename-BladeFilesToLowercase {
    param ($folderPath)
    $files = Get-ChildItem -Path $folderPath -Filter "*.blade.php" -File

    foreach ($file in $files) {
        $originalName = $file.Name
        $tempName = "$originalName.__tmp"
        $tempPath = Join-Path $file.DirectoryName $tempName

        Rename-Item -LiteralPath $file.FullName -NewName $tempName
        git add -A
        git commit -m "TEMP rename file: $originalName → $tempName"

        $finalName = $originalName.ToLower()
        $finalPath = Join-Path $file.DirectoryName $finalName

        Rename-Item -LiteralPath $tempPath -NewName $finalName
        git add -A
        git commit -m "Renamed file: $originalName → $finalName"
    }
}

# Check base folder exists
if (-Not (Test-Path $backendRoot)) {
    Write-Host "❌ Folder '$backendRoot' not found. Check your path." -ForegroundColor Red
    exit 1
}

$folders = Get-ChildItem -Path $backendRoot -Directory

foreach ($folder in $folders) {
    $absFolder = $folder.FullName
    $folderName = Split-Path $absFolder -Leaf

    # Skip already lowercase folders
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

# OPTIONAL: Rename Backendpages folder itself to lowercase if needed
if (Test-Path $backendRoot) {
    $parentPath = "resources/views"
    Rename-Item -LiteralPath $backendRoot -NewName "__TEMP_Backendpages"
    git add -A
    git commit -m "TEMP rename Backendpages → __TEMP_Backendpages"

    Rename-Item -LiteralPath (Join-Path $parentPath "__TEMP_Backendpages") -NewName "backendpages"
    git add -A
    git commit -m "Renamed Backendpages → backendpages"
}

# Final push
git push

Write-Host "`n✅ All folders and blade files renamed to lowercase and pushed to Git." -ForegroundColor Green
