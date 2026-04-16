@echo off
echo Starting backup process...
echo.

REM Create backup folder if it doesn't exist
if not exist "backups" mkdir backups

REM Get timestamp
for /f %%i in ('powershell -Command "Get-Date -Format 'yyyyMMdd_HHmmss'"') do set timestamp=%%i

set backup_file=backups\find_my_roommate_backup_%timestamp%.zip
echo Creating backup: %backup_file%

REM Create backup using PowerShell
powershell -Command "Compress-Archive -Path 'app', 'bootstrap', 'config', 'database', 'resources', 'routes', 'storage', 'public', '.env', '.env.example', 'composer.json', 'composer.lock', 'package.json', 'package-lock.json', 'artisan', 'phpunit.xml', 'tailwind.config.js', 'vite.config.js', 'postcss.config.js', '.gitignore', '.gitattributes', '.editorconfig', 'README.md' -DestinationPath '%backup_file%' -Force"

if exist "%backup_file%" (
    echo.
    echo ✓ BACKUP SUCCESSFUL!
    echo File: %backup_file%
    
    REM Show file info
    for %%F in ("%backup_file%") do (
        echo Size: %%~zF bytes
    )
    
    echo.
    echo Your complete Find My Roommate system has been backed up!
    echo All features, controllers, models, views, and configurations are included.
) else (
    echo.
    echo ✗ BACKUP FAILED!
    echo Please try running as administrator.
)

echo.
pause
