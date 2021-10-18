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
    private $folder;
    #[Route('/', name: 'folder_index', methods: ['GET', 'POST'])]
    public function index(Request $request, DataTableFactory $dataTableFactory):Response {
        $table = $dataTableFactory->create()
        ->add('id', TextColumn::class, [
            'label' => '#',
            'searchable' => true
        ])
        ->add('name', TextColumn::class, [
            'label' => 'Nom',
            'searchable' => true
        ])
        ->add('brochureFilename', TextColumn::class, [
            'render' => function($value, $context){
                $type = $context->getType();
                if(str_contains($type, 'video')){
                    return sprintf("<video controls><source type='$type' src='/uploads/$value' width='400'</source></video>");
                }
                return sprintf("<embed type='$type' src='/uploads/$value' width='400'/>");
            },
            'label' => 'Aperçu',
            'searchable' => false
        ])
        ->add('actions', TextColumn::class, [
            'data' => function($context) {
                return $context->getId();
            }, 
            'render' => function($value, $context){
                $show = sprintf('<a href="%s" class="btn btn-primary">Regarder</a>', $this->generateUrl('file_show', ['id' => $value]));
                $edit = sprintf('<a href="%s" class="btn btn-primary">Modifier</a>', $this->generateUrl('file_edit', ['id' => $value]));
                return $show.$edit;
            }, 
            'label' => 'Actions'
        ])
        ->createAdapter(ORMAdapter::class, [
            'entity' => File::class,
            'query' => function(QueryBuilder $builder) {
                return $builder
                ->select('f')
                ->where('f.subFolder IS NULL')
                ->from(File::class, 'f');
            }
        ])
        ->handleRequest($request);

        if($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('base.html.twig', [
            'datatable' => $table
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
    public function show(Request $request, Folder $folder, DataTableFactory $dataTableFactory): Response
    {
        $this->folder = $folder;
        $table = $dataTableFactory->create()
        ->add('id', TextColumn::class, [
            'label' => '#',
            'searchable' => true
        ])
        ->add('name', TextColumn::class, [
            'label' => 'Nom',
            'searchable' => true
        ])
        ->add('brochureFilename', TextColumn::class, [
            'render' => function($value, $context){
                $type = $context->getType();
                if(str_contains($type, 'video')){
                    return sprintf("<video controls><source type='$type' src='/uploads/$value' width='400'</source></video>");
                }
                return sprintf("<embed type='$type' src='/uploads/$value' width='400'/>");
            },
            'label' => 'Aperçu',
            'searchable' => false
        ])
        ->add('actions', TextColumn::class, [
            'data' => function($context) {
                return $context->getId();
            }, 
            'render' => function($value, $context){
                $show = sprintf('<a href="%s" class="btn btn-primary">Regarder</a>', $this->generateUrl('file_show', ['id' => $value]));
                $edit = sprintf('<a href="%s" class="btn btn-primary">Modifier</a>', $this->generateUrl('file_edit', ['id' => $value]));
                return $show.$edit;
            }, 
            'label' => 'Actions'
        ])
        ->createAdapter(ORMAdapter::class, [
            'entity' => File::class,
            'query' => function(QueryBuilder $builder) {
                return $builder
                ->select('f')
                ->where('f.subFolder = :f')
                ->setParameter('f', $this->folder)
                ->from(File::class, 'f');
            }
        ])
        ->handleRequest($request);

        if($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('base.html.twig', [
            'datatable' => $table
        ]);
    }
}
