<?php

namespace App\Controller;

use App\Repository\AromaRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/category/searchProductByCategory/{id}', name: 'product_category')]
    public function SearchProductByCategory($id,ProductRepository $productRepo, AromaRepository $AromaRepo ): Response
    {   $category = $AromaRepo->findOneBy(["id" => $id] );
        
        $products = $productRepo->findBy(["Aroma" => $category ]);
       
        return $this->render('category/category.html.twig', [
            'products' => $products,
            
        ]);
    }

    #[Route('/category/newProducts', name: 'new_product')]
    public function newProducts(ProductRepository $productRepo, AromaRepository $AromaRepo ): Response
    {  
        $products= $productRepo->findBy([],["id" => "Desc"]);
       
       
        return $this->render('category/new.html.twig', [
            'products' => $products,
        ]);
    }


    #[Route('/category/showCategory', name: 'show_category')]
    public function ShowCategory(ProductRepository $productRepo, AromaRepository $aromaRepo ): Response
    {  
       
        $category=$aromaRepo->findBy([], ["categoryName" => "ASC"]);
       
        return $this->render('category/index.html.twig', [
            
            'categorys'=> $category,
        ]);
    }
}
