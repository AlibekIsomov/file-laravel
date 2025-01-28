<?php
namespace App\Services;

use App\Repositories\FileRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    private $fileRepository;

    public function __construct(FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function uploadFile(array $allowedPaths, string $path, UploadedFile $file, string $fileName): string
    {
        if (!in_array($path, $allowedPaths)) {
            throw new \Exception('Invalid path');
        }

        if ($file->getSize() > 1048576) {
            throw new \Exception('File size exceeds the limit');
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $fileName)) {
            throw new \Exception('File name contains invalid characters');
        }

        $fullPath = "uploads/{$path}/{$fileName}";
        Storage::putFileAs('public', $file, $fullPath);

        return $fullPath;
    }

    public function getFile($filePath)
    {
        return $this->fileRepository->getObject($filePath);
    }
}