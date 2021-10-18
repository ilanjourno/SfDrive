<?php

namespace App\Service;

use Error;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadManager {

    private $targetDirectory;
    private $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file)
    {
        $size = $file->getSize();
        $type = $file->getMimeType();
        $fileName = uniqid().'.'.$file->guessExtension();
        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            throw new Error("Error upload file");
        }

        return [$fileName, $size, $type];
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function delete(string $fileName){
        $path = $this->getTargetDirectory().'/'.$fileName;
        return unlink($path);
    }
}