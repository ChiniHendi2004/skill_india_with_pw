$ErrorActionPreference = "Stop"

$backendRoot = "resources/views/Backendpages"

function Rename-ToTemp {
    param ($path)
    $folderName = Split-Path $path -Leaf
    $tempPath = Join-Path (Split-Path $path -Parent) ("__TEMP_" + $folderName)
    Rename-Item -Path $path -NewName $tempPath
    git add -A
    git commit -m "TEMP rename folder: $folderName → __TEMP_$folderName"
    return $tempPath
}

function Rename-ToLowercase {
    param ($originalPath, $tempPath)
    $lowerName = (Split-Path $originalPath -Leaf).ToLower()
    $finalPath = Join-Path (Split-Path $originalPath -Parent) $lowerName
    Rename-Item -Path $tempPath -NewName $lowerName
    git add -A
    git commit -m "Renamed folder: $(Split-Path $originalPath -Leaf) → $lowerName"
    return $finalPath
}

function Rename-BladeFilesToLowercase {
    param ($folder)
    $files = Get-ChildItem -Path $folder -Filter "*.blade.php"
    foreach ($file in $files) {
        $originalName = $file.Name
        $tempName = $originalName + ".tmp"
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

# MAIN: Get all folders under Backendpages
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

    # Step 1: Temp rename folder
    $tempFolder = Rename-ToTemp $absFolder

    # Step 2: Rename to lowercase
    $finalFolder = Rename-ToLowercase $absFolder $tempFolder

    # Step 3: Lowercase blade files
    Rename-BladeFilesToLowercase $finalFolder
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

# FINAL PUSH
git push

Write-Host "`n✅ All folders and blade files renamed to lowercase and pushed to Git." -ForegroundColor Green
