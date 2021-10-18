<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Folder;
use App\Form\FileType;
use App\Form\FolderType;
use App\Service\UploadManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends AbstractController
{
    public function formAction(): Response
    {
        $file = new File();
        $fileForm = $this->createForm(FileType::class, $file, ["action" => $this->generateUrl('file_new')]);

        $folder = new Folder();
        $folderForm = $this->createForm(FolderType::class, $folder, ["action" => $this->generateUrl('folder_new')]);

        return $this->renderForm('layouts/menu.html.twig', [
            'folderForm' => $folderForm,
            'fileForm' => $fileForm,
            'memory_used' => round(memory_get_usage() /1048576,2),
            'memory_limit' => ini_get('memory_limit')
        ]);
    }

}
