<?php

namespace App\Tests\Unit\Service;

use App\Service\FileUploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderTest extends TestCase
{
    private $targetCover;
    private UploadedFile $fileUploaded;
    private UploadedFile $fileUploadedError;

    public function setUp(): void
    {
        $this->targetCover = __DIR__ . '/../../../public/assets/covers/';
        file_put_contents($this->targetCover . 'tests/cover.txt', 'Ecriture dans un fichier');
        $this->fileUploaded = new UploadedFile(
            __DIR__ . '/../../../public/assets/covers/tests/cover.txt',
            'cover.txt',
            'text/plain',
            UPLOAD_ERR_OK,
            true
        );

        $this->fileUploadedError = new UploadedFile(
            __DIR__ . '/../../../public/assets/covers/tests/cover.txt',
            'cover.txt',
            'text/plain',
            UPLOAD_ERR_PARTIAL,
            true
        );
        

    }

    public function testUploadFileSuccess(): void
    {
        $fileUploader = new FileUploader($this->targetCover);
        $result = $fileUploader->upload($this->fileUploaded, 'cover');
        $this->assertFileExists($this->targetCover . $result);

        $fileUploader->remove($result, 'cover');

    }

    public function testUploadFileFail(): void
    {
        $fileUploader = new FileUploader($this->targetCover);
        $result = $fileUploader->upload($this->fileUploadedError, 'cover');
        $this->assertFileDoesNotExist($this->targetCover . $result);
    }

}
