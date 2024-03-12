<?php

namespace App\Controller;

use App\Repository\AromaRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ProductRepository $productRepository , AromaRepository $aromaRepository): Response
    {
        $product= $productRepository->findBy([],["name" => "ASC"]);// on cherche  les produit par ordre alphabetique
        $category=$aromaRepository->findBy([], ["categoryName" => "ASC"]);
        return $this->render('home/index.html.twig', [
            'categorys'=> $category,
            'products' => $product,
        ]);
    }


   


    #[Route('/home/CGV', name: 'app_CGV')]
    public function CGV( ): Response
    {  
 
        return $this->render('home/cgv.html.twig');
}

#[Route('/home/CGU', name: 'app_CGU')]
public function CGU( ): Response
{  

    return $this->render('home/cgu.html.twig');
}
}
