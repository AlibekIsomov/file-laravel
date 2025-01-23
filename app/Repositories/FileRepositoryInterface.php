<?php
namespace App\Repositories;

use App\Models\File;

interface FileRepositoryInterface
{
    public function save($tempPath, $path);
    public function get($filePath);
}