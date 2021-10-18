<?php

namespace App\Controller;

use App\Repository\FileRepository;
use App\Repository\FolderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Finder;

class FileStructureController extends AbstractController
{
    public $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function index(FolderRepository $folderRepository, FileRepository $fileRepository): Response
    {
        $folders = $folderRepository->findNotSubFolder();
        $files = $fileRepository->findAll();

        return $this->render('file_structure.html.twig', [
            'folders' => $folders,
            'files' => $files
        ]);
    }
}
