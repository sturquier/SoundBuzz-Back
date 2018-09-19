<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class GenrePhotoUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
    	$fileName = $file->getClientOriginalName();
    	$file->move($this->getTargetDirectory(), $fileName);

    	return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}