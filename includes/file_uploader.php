<?php

class FileUploader
{
    private string $uploadDir;
    private string $fullUploadPath;
    private string $fileName;
    private array $allowedTypes;
    private int $maxFileSize;
    private array $errors = [];

    public function __construct(
        string $fileName = '',
        string $uploadDir = 'database/assets/',
        array $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'],
        int $maxFileSize = 5242880,
    ) {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
        $this->allowedTypes = $allowedTypes;
        $this->maxFileSize = $maxFileSize;
        $this->fileName = $fileName;

        $projectRoot = dirname(__DIR__);
        $this->fullUploadPath = $projectRoot . '/' . $this->uploadDir;

        if (!is_dir($this->fullUploadPath)) {
            if (!mkdir($this->fullUploadPath, 0755, true)) {
                $this->errors[] = 'Failed to create upload directory: ' . $this->fullUploadPath;
            }
        }
    }

    public function uploadFile(array $file): ?string
    {
        if (!empty($this->errors)) {
            return null;
        }

        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = 'File upload error: ' . ($file['error'] ?? 'Unknown');
            return null;
        }

        if (!in_array($file['type'], $this->allowedTypes)) {
            $this->errors[] = 'Invalid file type: ' . $file['type'];
            return null;
        }

        if ($file['size'] > $this->maxFileSize) {
            $this->errors[] = 'File size exceeds the maximum limit of ' . ($this->maxFileSize / 1024 / 1024) . ' MB';
            return null;
        }


        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if ($this->fileName === '') {
            $newFileName = uniqid('upload_') . '.' . $extension;
        } else {
            $newFileName = $this->fileName . '.' . $extension;
        }

        $fullPath = $this->fullUploadPath . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            $this->errors[] = 'Failed to move uploaded file to: ' . $fullPath;
            return null;
        }

        require_once __DIR__ . '/image_cropper.php';
        if (!ImageCropper::cropToSquare($fullPath)) {
            $this->errors[] = 'Failed to crop image to square.';
            return null;
        }

        return $this->uploadDir . $newFileName;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasInitErrors(): bool
    {
        return !empty($this->errors);
    }
}
