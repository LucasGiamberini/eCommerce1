<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/m4st3rAdm1n', name: 'app_admin')]
    public function index(ProductRepository $productRepository): Response
    {

        $product= $productRepository->findBy([],["name" => "ASC"]);// on cherche  les produit par ordre alphabetique
        
        return $this->render('admin/index.html.twig', [
            
            'products' => $product,
        ]);
    }






}
