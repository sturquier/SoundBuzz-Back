<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class MusicUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    // TODO voir pour ranger les musiques par user
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