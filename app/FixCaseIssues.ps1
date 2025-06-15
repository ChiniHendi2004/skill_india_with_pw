# Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

$folders = @(
    "resources/views/Backendpages/Course",
    "resources/views/Backendpages/Students",
    "resources/views/Backendpages/Groups",
    "resources/views/Backendpages/CoursesDetails",
    "resources/views/Backendpages/Layout"
)

function Rename-ToTemp {
    param ($path)
    $tempPath = "$path-TEMP"
    Rename-Item -Path $path -NewName $tempPath
    git add -A
    git commit -m "TEMP rename: $path to $tempPath"
    return $tempPath
}

function Rename-ToLowercase {
    param ($originalPath, $tempPath)
    $lowercasePath = (Split-Path $originalPath -Parent) + "\" + ((Split-Path $originalPath -Leaf).ToLower())
    Rename-Item -Path $tempPath -NewName $lowercasePath
    git add -A
    git commit -m "Rename folder: $originalPath → $lowercasePath"
}

function Rename-BladeFilesToLowercase {
    param ($folder)
    $files = Get-ChildItem -Path $folder -Filter "*.blade.php"
    foreach ($file in $files) {
        $originalPath = $file.FullName
        $tempPath = $originalPath + ".tmp"
        Rename-Item $originalPath $tempPath
        git add -A
        git commit -m "TEMP rename: $($file.Name) → $($file.Name).tmp"

        $lowerName = ($file.Name).ToLower()
        $lowerPath = Join-Path $file.DirectoryName $lowerName
        Rename-Item $tempPath $lowerPath
        git add -A
        git commit -m "Rename file: $($file.Name) → $lowerName"
    }
}

# Main process
foreach ($folder in $folders) {
    $absFolder = Resolve-Path $folder

    # Step 1: Temporarily rename the folder to force Git case tracking
    $tempFolder = Rename-ToTemp $absFolder

    # Step 2: Rename it to the desired lowercase folder name
    Rename-ToLowercase $absFolder $tempFolder

    # Step 3: Rename blade files inside to lowercase
    $newFolder = (Split-Path $absFolder -Parent) + "\" + ((Split-Path $absFolder -Leaf).ToLower())
    Rename-BladeFilesToLowercase $newFolder
}

# Final step: Push all changes
git push

Write-Host "✅ Case renaming complete and changes pushed to Git." -ForegroundColor Green
