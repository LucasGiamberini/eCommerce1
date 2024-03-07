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
        $basket=$session->get("basket");// prend le tableau du panier stocker en session
//dd($basket);
        if ($basket !== [] && $basket !== NULL){// si le panier en session existe
         
        $dataBasket= [];// on crée un tableau vide
        $total= 0;// total de la commande
        $totalQuantity= 0;// quantité total

        foreach($basket as $id=>$quantity)
        {
        $product = $productRepository->find($id);//on cherche l'objet  produit par son id 
        $dataBasket[] = [//on a ajoute l'objet produit et sa quantité
            "product" => $product,
            "quantity" => $quantity
        ];
        $total +=$product->getPrice() * $quantity;//on  ajoute au total en multipliant la quantité par le prix
        $totalQuantity += $quantity;// on ajoute a la quantité total la quantité du produit
        }
    
        $session->set("total", $total);// on enregistre le montant total dans la session
        
        return $this->render('basket/index.html.twig', [
            'baskets' => $dataBasket,
            'total' => $total,
            'totalQuantity' => $totalQuantity,
            'empty' => false 
        ]);
    }
    else{// si le panier en session est vide
        return $this->render('basket/index.html.twig',
    [ 'empty' =>true]);
    }



    }

    #[Route('/basket/add/{id}', name:'add_basket')]
    public function add(Product $product, SessionInterface $session, ): Response
    {
        $basket=$session->get("basket", []); // on crée un tableau vide si le panier est vide
        $id=$product->getId();// on recupere l'id du produit

        if(!empty($basket[$id])){ //si le panier a  deja ce produit 
            $basket[$id]++;// j'ajoute un produit a  la quantité produit deja presente
        }
        else{// si le panier n'a pas encore ce produit  
            $basket[$id]= 1;// on ajoute un produit au panier 
        }

        // sauvegarde dans la session 
        $session->set("basket", $basket);

       
        return $this->redirectToRoute('app_basket');


    }

    //pour ajouter un nombre definis de produit
    #[Route('/basket/addnumber/{id}', name:'addNumber_basket')]
    public function addNumber(Product $product, SessionInterface $session,Request $request ): Response
    {   
        $quantity = filter_input(INPUT_POST, 'qtt', FILTER_SANITIZE_NUMBER_INT);// recuperation du nombre  de produit transmise via le formulaire 
        $basket=$session->get("basket", []); // recuperation du panier present en session
        $id=$product->getId();// recuperation de l'id du produit


        if(!empty($basket[$id])){ //si le panier contient deja le produit
            $basket[$id]=++$quantity ;// j'ajoute la quantité  du produit
        }
        else{// si le panier ne contient pas encore produit
            $basket[$id]= $quantity;// on ajoute la quantité du produit
        }

        // sauvegarde dans la session 
        $session->set("basket", $basket);

       
        return $this->redirectToRoute('app_basket');

        


    }



// enlever un unité de quantité d'un produit du panier
    #[Route('/basket/remove/{id}', name:'remove_basket')]
    public function remove(Product $product, SessionInterface $session): Response
    {   
        $basket=$session->get("basket", []); 
        $id=$product->getId();


        if(!empty($basket[$id])){ 
          if($basket[$id] > 1){// si la quantité du panier est superieur a 1
            $basket[$id]--; // on enleve un
          }else{
            unset($basket[$id]);// sinon on supprime completement l'article du panier
          }
        }
       
        $session->set("basket", $basket);//sauvegarde dans la session

        return $this->redirectToRoute('app_basket');
    }

    //pour supprimer un article  du panier
    #[Route('/basket/delete/{id}', name:'delete_basket')]
    public function delete(Product $product, SessionInterface $session): Response
    {   
        $basket=$session->get("basket", []); 
        $id=$product->getId();


        if(!empty($basket[$id])){ // si le panier contient l'article
            unset($basket[$id]);//alors on supprime l'article 
          }
        
          $session->set("basket", $basket);// on sauvegarde dans la session le fait que l'on a supprimer l'article du panier

        return $this->redirectToRoute('app_basket');
    }




    //vider  completement le panier
    #[Route('/basket/deleteAll', name:'deleteAll_basket')]
    public function deleteAll( SessionInterface $session): Response
    {   
       
        $session->remove('basket');//supression du panier present en session

        return $this->redirectToRoute('app_basket');
    }


}
