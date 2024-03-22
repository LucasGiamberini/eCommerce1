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

   // pour voir tout les produits si aucune categorie n'est selectionné
    #[Route('/category/showAllProduct', name: 'showAll_product')]
    public function SearchAllProduct(ProductRepository $productRepo ): Response
    {   
        
        $products = $productRepo->findBy([],["name" => "Desc"], 4);
       
        return $this->render('category/homeProductCategory.html.twig', [
            'products' => $products,
            
        ]);
    }
   
   
   
   
   
   // pour afficher les resultat des produits par categorie 
    #[Route('/category/searchProductByCategory/{id}', name: 'product_category')]
    public function SearchProductByCategory($id,ProductRepository $productRepo, AromaRepository $AromaRepo ): Response
    {   $category = $AromaRepo->findOneBy(["id" => $id] );
        
        $products = $productRepo->findBy(["Aroma" => $category ],[], 4);
       
        return $this->render('category/homeProductCategory.html.twig', [
            'products' => $products,
            
        ]);
    }

    // pour afficher les derniers produit arriver
    #[Route('/category/newProducts', name: 'new_product')]
    public function newProducts(ProductRepository $productRepo, AromaRepository $AromaRepo ): Response
    {  
        $products= $productRepo->findBy([],["id" => "Desc"], 4);
       
       
        return $this->render('category/new.html.twig', [
            'products' => $products,
        ]);
    }

// pour afficher la page du selecteur de categorie
    #[Route('/category/showCategory', name: 'show_category')]
    public function ShowCategory(ProductRepository $productRepo, AromaRepository $aromaRepo ): Response
    {  
       
        $category=$aromaRepo->findBy([], ["categoryName" => "ASC"]);
       
        return $this->render('category/index.html.twig', [
            
            'categorys'=> $category,
        ]);
    }


    #[Route('/category/showCategoryMenu', name: 'show_categoryMenu')]
    public function ShowCategoryMenu( AromaRepository $aromaRepo ): Response
    {  
       
        $category=$aromaRepo->findBy([], ["categoryName" => "ASC"]);
       
        return $this->render('category/categoryMenu.html.twig', [
            
            'categorys'=> $category,
        ]);
    }



    #[Route('/category/showProductCategory', name: 'show_categoryProductMenu')]
    public function ShowProductCategory(ProductRepository $productRepo, AromaRepository $aromaRepo ): Response
    {   $category = $AromaRepo->findOneBy(["id" => $id] );
        
        $products = $productRepo->findBy(["Aroma" => $category ],[]);
       
      
       
        return $this->render('category/productCategory.html.twig', [  
            'products' => $products,
        ]);
    }






}
