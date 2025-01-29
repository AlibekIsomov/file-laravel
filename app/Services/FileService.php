<?php

namespace App\Services;

use App\Repositories\FileRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class FileService
{
    private $fileRepository;

    public function __construct(FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function putObject(array $allowedPaths, string $path, UploadedFile $file, string $fileName): string
    {
        Log::info('FileService: putObject method called', [
            'path' => $path,
            'fileName' => $fileName,
            'fileSize' => $file->getSize(),
            'fileMimeType' => $file->getMimeType()
        ]);

        if (!in_array($path, $allowedPaths)) {
            Log::warning('FileService: Invalid path', ['path' => $path, 'allowedPaths' => $allowedPaths]);
            throw new \Exception('Invalid path');
        }

        if ($file->getSize() > 1048576) { // 1MB limit
            Log::warning('FileService: File size exceeds limit', ['fileSize' => $file->getSize()]);
            throw new \Exception('File size exceeds the limit');
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $fileName)) {
            Log::warning('FileService: Invalid file name', ['fileName' => $fileName]);
            throw new \Exception('File name contains invalid characters');
        }

        Log::info('FileService: Calling repository to store file');
        $result = $this->fileRepository->putObject($path, $file, $fileName);
        Log::info('FileService: File stored successfully', ['result' => $result]);

        return $result;
    }

    public function getObject(string $filePath): string
    {
        Log::info('FileService: getObject method called', ['filePath' => $filePath]);

        try {
            $result = $this->fileRepository->getObject($filePath);
            Log::info('FileService: File retrieved successfully', ['fileSize' => strlen($result)]);
            return $result;
        } catch (\Exception $e) {
            Log::error('FileService: Error retrieving file', [
                'filePath' => $filePath,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}

