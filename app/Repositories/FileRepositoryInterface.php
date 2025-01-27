<?php

namespace App\Repositories;

use Illuminate\Http\UploadedFile;

interface FileRepositoryInterface
{
    public function putObject(string $path, UploadedFile $file, string $fileName): string;
    public function getObject(string $path): string;
}