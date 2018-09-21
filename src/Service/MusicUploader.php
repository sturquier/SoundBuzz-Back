<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class MusicUploader
{
    private $musicsFilesDirectory;
    private $musicsPhotosDirectory;

    public function __construct($musicsFilesDirectory, $musicsPhotosDirectory)
    {
        $this->musicsFilesDirectory = $musicsFilesDirectory;
        $this->musicsPhotosDirectory = $musicsPhotosDirectory;
    }

    public function uploadFile(UploadedFile $file)
    {
    	$fileName = $file->getClientOriginalName();
    	$file->move($this->getMusicsFilesDirectory(), $fileName);

    	return $fileName;
    }

    public function uploadPhoto(UploadedFile $photo)
    {
        $photoName = $photo->getClientOriginalName();
        $photo->move($this->getMusicsPhotosDirectory(), $photoName);

        return $photoName;
    }

    private function getMusicsFilesDirectory()
    {
        return $this->musicsFilesDirectory;
    }

    private function getMusicsPhotosDirectory()
    {
        return $this->musicsPhotosDirectory;
    }
}