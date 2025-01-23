<?php
namespace App\Services;

use App\Repositories\FileRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class FileService
{
    private $fileRepository;

    public function __construct(FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function uploadFile($path, $file, $fileName)
    {
        if ($file->getSize() > 1048576) {
            throw new \Exception('File size exceeds the limit');
        }

        if (!preg_match('/^[a-zA-Z0-9]+$/', $fileName)) {
            throw new \Exception('File name contains invalid characters');
        }

        $tempPath = $file->storeAs('temp', $fileName);

        $this->fileRepository->save($tempPath, $path);

        return $tempPath;
    }

    public function getFile($filePath)
    {
        return $this->fileRepository->get($filePath);
    }
}