@echo off
echo Creating comprehensive backup of Find My Roommate System...
echo.

REM Get current timestamp
for /f "tokens=2 delims==" %%a in ('wmic OS Get localdatetime /value') do set "dt=%%a"
set "YYYY=%dt:~0,4%"
set "MM=%dt:~4,2%"
set "DD=%dt:~6,2%"
set "HH=%dt:~8,2%"
set "Min=%dt:~10,2%"
set "Sec=%dt:~12,2%"
set "timestamp=%YYYY%%MM%%DD%_%HH%%Min%%Sec%"

set "backup_file=find_my_roommate_backup_%timestamp%.zip"

echo Backup file will be named: %backup_file%
echo.

REM Use PowerShell to create the backup
powershell -Command "& { Compress-Archive -Path '.\app', '.\bootstrap', '.\config', '.\database', '.\resources', '.\routes', '.\storage', '.\public', '.\vendor', '.env', '.env.example', 'composer.json', 'composer.lock', 'package.json', 'package-lock.json', 'artisan', 'phpunit.xml', 'tailwind.config.js', 'vite.config.js', 'postcss.config.js', '.gitignore', '.gitattributes', '.editorconfig', 'README.md' -DestinationPath '.\%backup_file%' -Force }"

if exist "%backup_file%" (
    echo.
    echo SUCCESS: Backup created successfully!
    echo Backup file: %backup_file%
    
    REM Show file size
    for %%F in ("%backup_file%") do (
        set /a "size=%%~zF/1024/1024"
        echo Size: %%~zF bytes
    )
    
    echo.
    echo The backup contains all system components:
    echo - Controllers, Models, Middleware, Notifications
    echo - Views, Profiles, Messages, Admin panels
    echo - Database migrations, seeders, factories
    echo - Configuration files and dependencies
    echo - All user features and functionality
    echo.
) else (
    echo.
    echo ERROR: Backup creation failed!
    echo Please check if you have sufficient permissions.
)

echo.
pause
