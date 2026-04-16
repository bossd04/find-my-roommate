# Find My Roommate System Backup Instructions

## 🎯 Purpose
This document provides step-by-step instructions to create a complete backup of your Find My Roommate application, including all system files, folders, and features.

## 📁 What Will Be Backed Up

### Core Laravel Structure:
- **app/** - Controllers (Admin, Auth, User), Models, Middleware, Notifications, Providers, Services
- **bootstrap/** - Application bootstrap files
- **config/** - All configuration files (app, database, auth, etc.)
- **database/** - Migrations, seeders, factories, backups
- **resources/** - Views (admin, auth, profiles, messages, pages, partials, activity), lang files, assets
- **routes/** - All route files (web, api, console, channels)
- **storage/** - Application storage, framework files, logs
- **public/** - Public assets and entry point

### Configuration Files:
- `.env`, `.env.example` - Environment settings
- `composer.json`, `composer.lock` - PHP dependencies
- `package.json`, `package-lock.json` - Node dependencies
- `artisan` - Laravel CLI tool
- `phpunit.xml` - Testing configuration
- `tailwind.config.js`, `vite.config.js` - Build configurations
- `.gitignore`, `.gitattributes`, `.editorconfig` - Version control settings
- `README.md` - Documentation

## 🚀 Backup Methods

### Method 1: Windows File Explorer (Recommended)
1. Open **File Explorer**
2. Navigate to: `C:\Users\Edrian C Cervantes\OneDrive\Documents\Dev Dynamos\Find My Roommate`
3. Select all these folders and files:
   - `app/`
   - `bootstrap/`
   - `config/`
   - `database/`
   - `resources/`
   - `routes/`
   - `storage/`
   - `public/`
   - `vendor/`
   - `.env`
   - `.env.example`
   - `composer.json`
   - `composer.lock`
   - `package.json`
   - `package-lock.json`
   - `artisan`
   - `phpunit.xml`
   - `tailwind.config.js`
   - `vite.config.js`
   - `postcss.config.js`
   - `.gitignore`
   - `.gitattributes`
   - `.editorconfig`
   - `README.md`
4. Right-click on selected items → **Send to** → **Compressed (zipped) folder**
5. Rename the zip file to: `find_my_roommate_backup_YYYYMMDD_HHMMSS.zip`

### Method 2: PowerShell (Advanced)
Open PowerShell as Administrator and run:
```powershell
cd "C:\Users\Edrian C Cervantes\OneDrive\Documents\Dev Dynamos\Find My Roommate"
$timestamp = Get-Date -Format 'yyyyMMdd_HHmmss'
Compress-Archive -Path ".\app", ".\bootstrap", ".\config", ".\database", ".\resources", ".\routes", ".\storage", ".\public", ".\vendor", ".env", ".env.example", "composer.json", "composer.lock", "package.json", "package-lock.json", "artisan", "phpunit.xml", "tailwind.config.js", "vite.config.js", "postcss.config.js", ".gitignore", ".gitattributes", ".editorconfig", "README.md" -DestinationPath ".\find_my_roommate_backup_$timestamp.zip" -Force
```

### Method 3: Using Provided Scripts
1. **Simple Batch File**: Double-click `simple_backup.bat`
2. **PowerShell Script**: Run `powershell -ExecutionPolicy Bypass -File create_backup.ps1`

## ✅ Features Included in Backup

Your backup will contain ALL system features:
- ✅ User authentication and authorization
- ✅ Admin dashboard and management
- ✅ Profile management system
- ✅ Roommate matching algorithm
- ✅ Messaging system
- ✅ Payment processing
- ✅ Activity logging
- ✅ Location-based search (48 Pangasinan locations)
- ✅ File upload functionality
- ✅ Email notifications
- ✅ API endpoints
- ✅ Responsive design
- ✅ Database migrations and seeders
- ✅ All custom controllers and models
- ✅ Blade templates and views
- ✅ Middleware and services

## 🔄 Restoration Process

To restore from backup:
1. Extract the zip file to your desired location
2. Run `composer install` to install PHP dependencies
3. Run `npm install` to install Node dependencies
4. Copy `.env.example` to `.env` and configure
5. Run `php artisan key:generate`
6. Run `php artisan migrate` to set up database
7. Run `php artisan storage:link`
8. Configure your web server to point to the `public/` directory

## 📋 Backup Checklist

Before creating backup, ensure:
- [ ] All recent changes are saved
- [ ] Database is backed up separately if needed
- [ ] You have sufficient disk space (typically 50-200MB)
- [ ] No files are locked by other applications

## 🎉 Success Indicators

Your backup is successful when:
- Zip file is created without errors
- File size is reasonable (not 0 bytes)
- All major folders are included in the zip
- You can extract and verify the contents

---

**Note**: This backup contains your complete application. Store it in a safe location for future restoration or deployment.
