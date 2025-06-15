$ErrorActionPreference = "Stop"

$backendRoot = "resources/views/Backendpages"

function Rename-ToTemp {
    param ($path)
    $folderName = Split-Path $path -Leaf

    # ✅ Skip if already renamed
    if ($folderName -like '__TEMP_*') {
        Write-Host "⏭ Skipping TEMP rename for: $folderName (already TEMP renamed)" -ForegroundColor Yellow
        return $path
    }

    $parentPath = Split-Path $path -Parent
    $tempName = "__TEMP_$folderName"
    $tempPath = Join-Path $parentPath $tempName

    if (Test-Path -LiteralPath "$path") {
        Rename-Item -LiteralPath "$path" -NewName "$tempName"
        git add -A
        git commit -m "TEMP rename folder: $folderName → $tempName"
        return "$tempPath"
    } else {
        Write-Host "❌ Path not found: $path" -ForegroundColor Red
        return $null
    }
}


function Rename-ToLower {
    param ($path)
    $folderName = Split-Path $path -Leaf
    $parentPath = Split-Path $path -Parent
    $lowerName = $folderName.ToLower()
    $lowerPath = Join-Path $parentPath $lowerName

    # ✅ Only rename if names differ in actual string (not just case)
    if ($folderName -ceq $lowerName) {
        Write-Host "⏭ Already lowercase: $folderName" -ForegroundColor Gray
        return
    }

    if (Test-Path -LiteralPath "$path") {
        Rename-Item -LiteralPath "$path" -NewName "$lowerName"
        git add -A
        git commit -m "Renamed folder to lowercase: $folderName → $lowerName"
    } else {
        Write-Host "❌ TEMP path does not exist: $path" -ForegroundColor Red
    }
}


function Rename-BladeFilesToLowercase {
    param ($folderPath)
    $files = Get-ChildItem -Path "$folderPath" -Filter "*.blade.php" -File

    foreach ($file in $files) {
        $originalName = $file.Name
        $tempName = "$originalName.__tmp"
        $tempPath = Join-Path $file.DirectoryName $tempName

        Rename-Item -LiteralPath "$($file.FullName)" -NewName "$tempName"
        git add -A
        git commit -m "TEMP rename file: $originalName → $tempName"

        $finalName = $originalName.ToLower()
        $finalPath = Join-Path $file.DirectoryName $finalName

        Rename-Item -LiteralPath "$tempPath" -NewName "$finalName"
        git add -A
        git commit -m "Renamed file: $originalName → $finalName"
    }
}

# Check base folder exists
if (-Not (Test-Path "$backendRoot")) {
    Write-Host "❌ Folder '$backendRoot' not found. Check your path." -ForegroundColor Red
    exit 1
}

$folders = Get-ChildItem -Path "$backendRoot" -Directory

foreach ($folder in $folders) {
    $absFolder = "$($folder.FullName)"
    $folderName = Split-Path "$absFolder" -Leaf

    if ($folderName -cmatch '^[a-z0-9_]+$') {
        Write-Host "⚠️  Skipping (already lowercase): $folderName"
        Rename-BladeFilesToLowercase "$absFolder"
        continue
    }

    $tempFolder = Rename-ToTemp "$absFolder"
    if ($tempFolder) {
        $finalFolder = Rename-ToLowercase "$absFolder" "$tempFolder"
        if ($finalFolder) {
            Rename-BladeFilesToLowercase "$finalFolder"
        }
    }
}

# Rename the parent folder if necessary
if ((Split-Path -Leaf $backendRoot) -ne "backendpages") {
    $parentPath = "resources/views"
    Rename-Item -LiteralPath "$backendRoot" -NewName "__TEMP_Backendpages"
    git add -A
    git commit -m "TEMP rename Backendpages → __TEMP_Backendpages"

    Rename-Item -LiteralPath (Join-Path $parentPath "__TEMP_Backendpages") -NewName "backendpages"
    git add -A
    git commit -m "Renamed Backendpages → backendpages"
}

# Push changes
git push

Write-Host "`n✅ All folders and blade files renamed to lowercase and pushed to Git." -ForegroundColor Green
