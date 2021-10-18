<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Folder;
use App\Form\FileType;
use App\Form\FolderType;
use App\Repository\FileRepository;
use App\Repository\FolderRepository;
use App\Service\UploadManager;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class FileController extends AbstractController
{
    private $file;

    #[Route('/', name: 'default', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->redirectToRoute('folder_index');
    }

    #[Route('/{id}', name: 'file_show', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function show(File $file): Response
    {
        return $this->render('file/show.html.twig', [
            'file' => $file
        ]);
    }


    #[Route('/new', name: 'file_new', methods: ['POST'])]
    public function new(Request $request, UploadManager $uploadManager): Response
    {
        $file = new File();
        $form = $this->createForm(FileType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                [$brochureFileName, $size, $type] = $uploadManager->upload($brochureFile);
                $file->setBrochureFilename($brochureFileName);
                $file->setSize($size);
                $file->setType($type);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($file);
            $entityManager->flush();

            $this->addFlash('success', 'Folder added successfully !');
            return $this->redirectToRoute('default');
        }

        $this->addFlash('error', 'Une erreur est survenue !');
        return $this->redirectToRoute('default');
    }

    #[Route('/{id}/edit', name: 'file_edit', methods: ['GET','POST'])]
    public function edit(Request $request, File $file): Response
    {
        $form = $this->createForm(FileType::class, $file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('file_show', [
                'id' => $file->getId()
            ]);
        }

        return $this->renderForm('file/edit.html.twig', [
            'file' => $file,
            'fileForm' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'file_delete', methods: ['POST'])]
    public function delete(Request $request, File $file, UploadManager $uploadManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$file->getId(), $request->request->get('_token'))) {
            $uploadManager->delete($file->getBrochureFilename());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($file);
            $entityManager->flush();
        }

        return $this->redirectToRoute('default');
    }
}
