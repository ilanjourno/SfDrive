<?php

namespace App\Controller;

use App\Repository\FileRepository;
use App\Repository\FolderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\SerializerInterface;

class FileStructureController extends AbstractController
{
    public $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function index(FolderRepository $folderRepository, FileRepository $fileRepository, SerializerInterface $serializerInterface): Response
    {
        $folders = $folderRepository->findAll();
        $files = $fileRepository->findAll();

        return $this->render('file_structure.html.twig', [
            'folders' => $serializerInterface->serialize($folders, 'json', ['groups' => ['public']]),
            'files' => $files
        ]);
    }
}
