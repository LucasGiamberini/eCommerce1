<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Nicotine;
use App\Repository\ProductRepository;
use App\Repository\NicotineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BasketController extends AbstractController
{
    #[Route('/basket', name: 'app_basket')]
    public function index(SessionInterface $session, ProductRepository $productRepository,NicotineRepository $nicotineRepository): Response
    {
        $basket=$session->get("basket");// prend le tableau du panier stocker en session

        if ($basket !== [] && $basket !== NULL){// si le panier en session existe
   
        $dataBasket= [];// on crée un tableau vide
        $total= 0;// total de la commande
        $totalQuantity= 0;// quantité total

        foreach ($basket as $id => $data) {
            $product = $productRepository->find($id);
            $nicotine = $nicotineRepository->find($data['nicotine_id']);
            $quantity= $data['quantity'];

            $dataBasket[] = [
                "product" => $product,
                "quantity" => $quantity,
                "nicotine" => $nicotine
            ];
        $total +=$product->getPrice() * $quantity;//on  ajoute au total en multipliant la quantité par le prix
       // $totalQuantity += $quantity;// on ajoute a la quantité total la quantité du produit
        }
    
        $session->set("total", $total);// on enregistre le montant total dans la session
        
        return $this->render('basket/index.html.twig', [
            'baskets' => $dataBasket,
            'total' => $total,
            'empty' => false 
        ]);
    }
    else{// si le panier en session est vide
        return $this->render('basket/index.html.twig',
    [ 'empty' =>true]);
    }



    }

    #[Route('/basket/add/{idProduct}{idNicotine}', name:'add_basket')]
    public function add($idProduct,$idNicotine, SessionInterface $session, Request $request,): Response
    {
        $basket = $session->get("basket", []);
        // recherche la clé unique en combinant l'ID du produit et l'ID de la nicotine
        $key = $idProduct . '-' . $idNicotine;
    
        $basket[$key]['quantity'] += 1;
       
        $session->set("basket", $basket);
    
        return $this->redirectToRoute('app_basket');
    }




    //pour ajouter un nombre definis de produit
    #[Route('/basket/addnumber/{id}', name:'addNumber_basket')]
    public function addNumber(Product $product, SessionInterface $session,Request $request  ): Response
    {   
        $quantity = filter_input(INPUT_POST, 'qtt', FILTER_SANITIZE_NUMBER_INT);// recuperation du nombre  de produit transmise via le formulaire 
        $basket=$session->get("basket", []); // recuperation du panier present en session
        $productId=$product->getId();// recuperation de l'id du produit
        $nicotineId = $request->request->get('nicotine');// recuperation de l'identifiant de nicotine
   


    
        // Créez une clé unique en combinant l'ID du produit et l'ID de la nicotine
        $key =  $productId .'-'. $nicotineId;
    
        if (isset($basket[$key])) {
            $basket[$key]['quantity'] += $quantity;
        } else {
            $basket[$key] = [
                'product_id' =>  $productId,
                'nicotine_id' => $nicotineId,
                'quantity' => $quantity
            ];
        }
    
        $session->set("basket", $basket);
      
        return $this->redirectToRoute('app_basket');

    }



// enlever un unité de quantité d'un produit du panier
    #[Route('/basket/remove/{idProduct}{idNicotine}', name:'remove_basket')]
    public function remove($idProduct,$idNicotine, SessionInterface $session): Response
    {   
        $basket=$session->get("basket", []); 
        
       

        $key= $idProduct .'-'. $idNicotine ;        

         
          if($basket[$key]['quantity'] > 1){// si la quantité du panier est superieur a 1
            $basket[$key]['quantity'] -= 1; // on enleve un
          }else{
            unset($basket[$key]);// sinon on supprime completement l'article du panier
          }
        
       
        $session->set("basket", $basket);//sauvegarde dans la session

        return $this->redirectToRoute('app_basket');
    }


    //pour supprimer un article  du panier
    #[Route('/basket/delete/{idProduct}{idNicotine}', name:'delete_basket')]
    public function delete($idProduct,$idNicotine, SessionInterface $session ): Response
    {   
        $basket=$session->get("basket", []); 
       

        $key= $idProduct .'-'. $idNicotine ;   

      
            unset($basket[$key]);// on supprime l'article 
          
        
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
