<?php

namespace App\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class FileRepository implements FileRepositoryInterface
{
    public function putObject(string $path, UploadedFile $file, string $fileName): string
    {
        Log::info('FileRepository: putObject method called', [
            'path' => $path,
            'fileName' => $fileName
        ]);

        try {
            $fullPath = "{$path}/{$fileName}";
            Log::info('FileRepository: Attempting to store file', ['fullPath' => $fullPath]);

            if (!Storage::put($fullPath, file_get_contents($file))) {
                Log::error('FileRepository: Failed to store file', ['fullPath' => $fullPath]);
                throw new RuntimeException('Failed to store file');
            }

            Log::info('FileRepository: File stored successfully', ['fullPath' => $fullPath]);
            return $fullPath;
        } catch (\Exception $e) {
            Log::error('FileRepository: Error storing file', [
                'path' => $path,
                'fileName' => $fileName,
                'error' => $e->getMessage()
            ]);
            throw new RuntimeException("Error storing file: {$e->getMessage()}");
        }
    }

    public function getObject(string $path): string
    {
        Log::info('FileRepository: getObject method called', ['path' => $path]);

        if (!Storage::exists($path)) {
            Log::error('FileRepository: File not found', ['path' => $path]);
            throw new RuntimeException('File not found');
        }

        $content = Storage::get($path);
        Log::info('FileRepository: File retrieved successfully', [
            'path' => $path,
            'fileSize' => strlen($content)
        ]);

        return $content;
    }
}

