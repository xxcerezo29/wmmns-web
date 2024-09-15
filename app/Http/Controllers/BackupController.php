<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BackupController extends Controller
{
    public function list()
    {

        $files = Storage::allFiles('backups');

        $files = array_map(function ($file) {
            $path = storage_path('app/' . $file);
            $name = basename($file);

            $date = Carbon::createFromTimestamp(filemtime($path), 'Asia/Manila')->toDayDateTimeString();
            return [
                'name' => $name,
                'date' => $date
            ];
        }, $files);

        usort($files, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return Inertia::render('Backup/List', [
            'files' => $files,
        ]);
    }

    public function backup()
    {
        try {
            // Artisan::call('backup:run');
            // $output = Artisan::output();

            shell_exec('artisan backup:run');

            // Log::info('Backup Output: ' .$output);

            return back()->with(['message' => 'Backup was successfully created.', 'status' => 'success']);
        } catch (Exception $e) {
            Log::error("Backup failed: " . $e->getMessage());

            // Optionally, return an error message
            return back()->with(['message' => 'Backup failed. Please check the logs.', 'status' => 'success']);
        }
    }

    public function download($fileName)
    {
        if (!Storage::exists("backups/{$fileName}")) {
            abort(404);
        }


        return Storage::download("backups/{$fileName}");
    }
}
