<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Folder;
use App\Form\FileType;
use App\Form\FolderType;
use App\Service\UploadManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default')]
    public function index(Request $request, UploadManager $uploadManager): Response
    {
        $folder = new Folder();
        $folderForm = $this->createForm(FolderType::class, $folder);
        $folderForm->handleRequest($request);

        if ($folderForm->isSubmitted() && $folderForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($folder);
            $entityManager->flush();

            $this->addFlash('success', 'New folder added successfully !');
            return $this->redirectToRoute('default');
        }

        $file = new File();
        $fileForm = $this->createForm(FileType::class, $file);
        $fileForm->handleRequest($request);

        if ($fileForm->isSubmitted() && $fileForm->isValid()) {
            $brochureFile = $fileForm->get('brochure')->getData();
            if ($brochureFile) {
                [$brochureFileName, $size, $type] = $uploadManager->upload($brochureFile);
                $file->setBrochureFilename($brochureFileName);
                $file->setSize($size);
                $file->setType($type);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($file);
            $entityManager->flush();

            $this->addFlash('success', 'New file added successfully !');
            return $this->redirectToRoute('default');
        }
        
        return $this->renderForm('default/index.html.twig', [
            'folder' => $folder,
            'folderForm' => $folderForm,
            'fileForm' => $fileForm
        ]);
    }
}
