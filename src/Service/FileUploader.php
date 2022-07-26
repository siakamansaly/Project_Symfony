<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploader
{
    private $targetCover;

    public function __construct($targetCover)
    {
        $this->targetCover = $targetCover;
    }

    public function upload(UploadedFile $file, string $target = 'cover')
    {
        $targetPath = $this->getTargetCover();
                
        $target = $target.'-'.date("Ymd-His");
        $fileName = $target . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($targetPath, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function remove(string $fileName, string $target = 'cover')
    {
        $targetPath = $this->getTargetCover();

        $filesystemEdit = new Filesystem();
        $filesystemEdit->remove($targetPath . '/' . $fileName);
    }


    public function getTargetCover()
    {
        return $this->targetCover;
    }
}
