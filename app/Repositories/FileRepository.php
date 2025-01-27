<?php

namespace App\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class FileRepository implements FileRepositoryInterface 
{
    public function putObject(string $path, UploadedFile $file, string $fileName): string 
    {
        try {
            $fullPath = "{$path}/{$fileName}";
            if (!Storage::put($fullPath, file_get_contents($file))) {
                throw new RuntimeException('Failed to store file');
            }
            return $fullPath;
        } catch (\Exception $e) {
            throw new RuntimeException("Error storing file: {$e->getMessage()}");
        }
    }

    public function getObject(string $path): string 
    {
        if (!Storage::exists($path)) {
            throw new RuntimeException('File not found');
        }
        return Storage::get($path);
    }
}