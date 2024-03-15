<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(ProductRepository $productRepo): Response
    {
        $textToSearch = filter_input(INPUT_POST,'textSearch',FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $searchProduct = $productRepo->searchProduct($textToSearch);

      


        return $this->render('search/index.html.twig', [
            
            'textToSearch' =>  $textToSearch,
            'products' => $searchProduct,

        ]);
    }
}
