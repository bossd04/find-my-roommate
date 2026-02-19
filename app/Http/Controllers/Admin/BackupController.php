<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    /**
     * Display the backup management page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $backupFiles = [];
        $backupPath = storage_path('app/backups');
        
        if (File::exists($backupPath)) {
            $backupFiles = array_reverse(File::files($backupPath));
        }
        
        return view('admin.backup.index', compact('backupFiles'));
    }

    /**
     * Create a new database backup.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            // Create backup directory if it doesn't exist
            $backupPath = storage_path('app/backups');
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }
            
            $filename = 'backup-' . now()->format('Y-m-d-His') . '.sql';
            $filepath = $backupPath . '/' . $filename;
            
            // For SQLite
            if (config('database.default') === 'sqlite') {
                $databasePath = database_path('database.sqlite');
                if (File::exists($databasePath)) {
                    File::copy($databasePath, $filepath);
                }
            } 
            // For MySQL/PostgreSQL
            else {
                $command = sprintf(
                    'mysqldump --user=%s --password=%s --host=%s %s > %s',
                    config('database.connections.mysql.username'),
                    config('database.connections.mysql.password'),
                    config('database.connections.mysql.host'),
                    config('database.connections.mysql.database'),
                    $filepath
                );
                
                $process = Process::fromShellCommandline($command);
                $process->run();
                
                if (!$process->isSuccessful()) {
                    throw new \RuntimeException($process->getErrorOutput());
                }
            }
            
            return back()->with('success', 'Backup created successfully: ' . $filename);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Download a backup file.
     *
     * @param  string  $filename
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        
        if (!File::exists($filepath)) {
            abort(404);
        }
        
        return response()->download($filepath);
    }
    
    /**
     * Delete a backup file.
     *
     * @param  string  $filename
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        
        if (File::exists($filepath)) {
            File::delete($filepath);
            return back()->with('success', 'Backup deleted successfully: ' . $filename);
        }
        
        return back()->with('error', 'Backup not found: ' . $filename);
    }
}
