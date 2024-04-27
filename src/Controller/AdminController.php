<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Repository\ReviewRepository;
use App\Repository\ProductRepository;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/m4st3rAdm1n', name: 'app_admin')]
    public function index(ProductRepository $productRepository, ): Response
    {

        $product= $productRepository->findBy([],["name" => "ASC"]);// on cherche  les produit par ordre alphabetique
       
        return $this->render('admin/index.html.twig', [
          
            'products' => $product,
        ]);
    }


    #[Route('/m4st3rAdm1n/invoice', name: 'admin_invoice')]
    public function adminInvoice(PurchaseRepository $purchaseRepository ): Response
    {

        
        $invoice = $purchaseRepository->findBy([],["PurchaseDate" =>"DESC" ] ) ;  
        return $this->render('admin/invoices.html.twig', [
            'invoices' => $invoice,
            
        ]);
    }


    
    #[Route('/m4st3rAdm1n/moderateProduct/{id}', name: 'admin_moderateReview')]
    public function adminModerate($id,ProductRepository $productRepository ): Response
    {

        
        $product = $productRepository->findOneBy(["id" => $id]  ) ;  
        return $this->render('admin/commentary.html.twig', [
            'product' => $product,
            
        ]);
    }
      

    #[Route('/m4st3rAdm1n/anonimise/{idReview}/{idProduct}', name: 'anonimise_review')]
    public function anonimiseReview($idReview,$idProduct,ReviewRepository $reviewRepository,EntityManagerInterface $entityManager ): Response
    {

        
        $review = $reviewRepository->findOneBy(["id" => $idReview]  ) ;  
        $review->setCommentary("Commentaire Suprimer");
        $entityManager->persist($review);
        $entityManager->flush();

        return $this->redirectToRoute('admin_moderateReview',['id' => $idProduct] );

}

}