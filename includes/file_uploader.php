<?php

require_once __DIR__ . '/image_cropper.php';

class FileUploader
{
    private string $uploadDir;
    private string $fullUploadPath;
    private string $fileName;
    private array $allowedTypes;
    private int $maxFileSize;
    private string $aspectRatio;
    private array $errors = [];

    public function __construct(
        string $fileName = '',
        string $uploadDir = '/database/assets/',
        array $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'],
        int $maxFileSize = 2097152, // 2MB (2 * 1024 * 1024) to match PHP's default upload_max_filesize
        string $aspectRatio = ImageCropper::ASPECT_RATIO_SQUARE,
    ) {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
        $this->allowedTypes = $allowedTypes;
        $this->maxFileSize = $maxFileSize;
        $this->fileName = $fileName;
        $this->aspectRatio = $aspectRatio;

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
            // Provide more descriptive error messages based on error codes
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $maxSize = ini_get('upload_max_filesize');
                    $this->errors[] = "The uploaded file exceeds the upload_max_filesize directive in php.ini ($maxSize)";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $this->errors[] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $this->errors[] = "The uploaded file was only partially uploaded";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $this->errors[] = "No file was uploaded";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $this->errors[] = "Missing a temporary folder";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $this->errors[] = "Failed to write file to disk";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $this->errors[] = "A PHP extension stopped the file upload";
                    break;
                default:
                    $this->errors[] = 'Unknown upload error: ' . ($file['error']);
                    break;
            }
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

        if (!ImageCropper::cropToAspectRatio($fullPath, $this->aspectRatio)) {
            $this->errors[] = 'Failed to crop image to ' . $this->aspectRatio . ' aspect ratio.';
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

    public function setAspectRatio(string $aspectRatio): void
    {
        $this->aspectRatio = $aspectRatio;
    }

    public function getAspectRatio(): string
    {
        return $this->aspectRatio;
    }
}
