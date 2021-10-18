<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Folder;
use App\Form\FolderType;
use App\Service\UploadManager;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/folder')]
class FolderController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('folder/index.html.twig', [
            'controller_name' => 'FolderController',
        ]);
    }

    #[Route('/new', name: 'folder_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $folder = new Folder();
        $form = $this->createForm(FolderType::class, $folder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($folder);
            $entityManager->flush();
            $this->addFlash('success', 'Folder added successfully !');
            return $this->redirectToRoute('default');
        }
        $this->addFlash('error', 'Une erreur est survenue !');
        return $this->redirectToRoute('default');
    }

    #[Route('/{id}/subFolders', name: 'folder_subFolders', methods: ['GET'])]
    public function getSubFolders(Folder $folder, SerializerInterface $serializerInterface): Response {
        $subFolders = $folder->getSubFolders();
        $response = json_encode([
            "status" => 200, 
            "data" => json_decode($serializerInterface->serialize($subFolders, 'json', ["groups" => ["public"]]))
        ]);
        return new Response($response,  Response::HTTP_OK,
            [
                'Content-type' => 'application/json'
            ]
        );
    }

    #[Route('/{id}', name: 'folder_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Folder $file, DataTableFactory $dataTableFactory): Response
    {
        $this->file = $file;
        $table = $dataTableFactory->create()
        ->add('id', TextColumn::class, [
            'label' => '#'
        ])
        ->add('name', TextColumn::class, [
            'label' => 'Nom'
        ])
        ->createAdapter(ORMAdapter::class, [
            'entity' => File::class,
            'query' => function(QueryBuilder $builder) {
                return $builder
                ->select('f')
                ->where('f = :f')
                ->setParameter('f', $this->file)
                ->from(CustomerFiles::class, 'f');
            }
        ])
        ->handleRequest($request);

        if($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('folder/show.html.twig', [
            'folder' => $file,
        ]);
    }
}
