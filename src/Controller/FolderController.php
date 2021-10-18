<?php

namespace App\Controller;

use App\Entity\Folder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class FolderController extends AbstractController
{
    #[Route('/folder', name: 'folder')]
    public function index(): Response
    {
        return $this->render('folder/index.html.twig', [
            'controller_name' => 'FolderController',
        ]);
    }
}
