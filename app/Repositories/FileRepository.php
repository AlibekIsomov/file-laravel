<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Storage;

class FileRepository implements FileRepositoryInterface
{
    public function save($tempPath, $path)
    {
        return Storage::putFileAs($path, $tempPath, basename($tempPath));
    }

    public function get($filePath)
    {
        return Storage::get($filePath);
    }
}