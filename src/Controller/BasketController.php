<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BasketController extends AbstractController
{
    #[Route('/basket', name: 'app_basket')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $basket=$session->get("basket");
//dd($basket);
        if ($basket !== [] && $basket !== NULL){
         
        $dataBasket= [];
        $total= 0;
        $totalQuantity= 0;

        foreach($basket as $id=>$quantity)
        {
        $product = $productRepository->find($id);
        $dataBasket[] = [
            "product" => $product,
            "quantity" => $quantity
        ];
        $total +=$product->getPrice() * $quantity;
        $totalQuantity += $quantity;
        }
    
        $session->set("total", $total);
        
        return $this->render('basket/index.html.twig', [
            'baskets' => $dataBasket,
            'total' => $total,
            'totalQuantity' => $totalQuantity,
            'empty' => false 
        ]);
    }
    else{
        return $this->render('basket/index.html.twig',
    [ 'empty' =>true]);
    }



    }

    #[Route('/basket/add/{id}', name:'add_basket')]
    public function add(Product $product, SessionInterface $session, ): Response
    {
        $basket=$session->get("basket", []); // on crée un tableau vide si le panier est vide
        $id=$product->getId();

        if(!empty($basket[$id])){ //si le panier n'est pas vide
            $basket[$id]++;// j'ajoute l'id du produit
        }
        else{
            $basket[$id]= 1;
        }

        // sauvegarde dans la session 
        $session->set("basket", $basket);

       
        return $this->redirectToRoute('app_basket');


    }

    //pour ajouter un nombre definis de produit
    #[Route('/basket/addnumber/{id}', name:'addNumber_basket')]
    public function addNumber(Product $product, SessionInterface $session,Request $request ): Response
    {   
        $quantity = filter_input(INPUT_POST, 'qtt', FILTER_SANITIZE_NUMBER_INT);
        $basket=$session->get("basket", []); // on crée un tableau vide si le panier est vide
        $id=$product->getId();


        if(!empty($basket[$id])){ //si le panier n'est pas vide
            $basket[$id]=++$quantity ;// j'ajoute l'id du produit
        }
        else{
            $basket[$id]= $quantity;
        }

        // sauvegarde dans la session 
        $session->set("basket", $basket);

       
        return $this->redirectToRoute('app_basket');

        


    }




    #[Route('/basket/remove/{id}', name:'remove_basket')]
    public function remove(Product $product, SessionInterface $session): Response
    {   
        $basket=$session->get("basket", []); 
        $id=$product->getId();


        if(!empty($basket[$id])){ 
          if($basket[$id] > 1){// si la quantité du panierest superieur a 1
            $basket[$id]--; // on enleve un
          }else{
            unset($basket[$id]);// sinon on supprime completement l'article du panier
          }
        }
       
        $session->set("basket", $basket);

        return $this->redirectToRoute('app_basket');
    }

    //pour supprimer une ligne d'un panier
    #[Route('/basket/delete/{id}', name:'delete_basket')]
    public function delete(Product $product, SessionInterface $session): Response
    {   
        $basket=$session->get("basket", []); 
        $id=$product->getId();


        if(!empty($basket[$id])){ 
            unset($basket[$id]);
          }
        
          $session->set("basket", $basket);

        return $this->redirectToRoute('app_basket');
    }

    //vider le panier
    #[Route('/basket/deleteAll', name:'deleteAll_basket')]
    public function deleteAll( SessionInterface $session): Response
    {   
       
        $session->remove('basket');

        return $this->redirectToRoute('app_basket');
    }


}
