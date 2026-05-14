# Find My Roommate Backup Script
# Creates comprehensive backup of all system files and folders

$timestamp = Get-Date -Format 'yyyyMMdd_HHmmss'
$backupName = "find_my_roommate_backup_$timestamp.zip"

Write-Host "Creating backup: $backupName" -ForegroundColor Green
Write-Host "This will backup all essential system files and folders..." -ForegroundColor Yellow

# Define folders and files to backup
$itemsToBackup = @(
    ".\app",
    ".\bootstrap", 
    ".\config",
    ".\database",
    ".\resources",
    ".\routes",
    ".\storage",
    ".\public",
    ".\vendor",
    ".env",
    ".env.example",
    "composer.json",
    "composer.lock",
    "package.json",
    "package-lock.json",
    "artisan",
    "phpunit.xml",
    "tailwind.config.js",
    "vite.config.js",
    "postcss.config.js",
    ".gitignore",
    ".gitattributes",
    ".editorconfig",
    "README.md"
)

# Create the backup
try {
    Compress-Archive -Path $itemsToBackup -DestinationPath ".\$backupName" -Force
    Write-Host "Backup created successfully: $backupName" -ForegroundColor Green
    
    # Show backup size
    $backupSize = (Get-Item ".\$backupName").Length / 1MB
    Write-Host "Backup size: $([math]::Round($backupSize, 2)) MB" -ForegroundColor Cyan
    
} catch {
    Write-Host "Error creating backup: $_" -ForegroundColor Red
}

Write-Host "Backup process completed." -ForegroundColor Green
