<?php

namespace App\Controller;

use DateTime;
use Stripe\Stripe;

use App\Entity\Basket;
use DateTimeImmutable;
use App\Entity\Purchase;
use Stripe\StripeClient;
use App\Form\AddAdressType;
use Stripe\Checkout\Session;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchaseController extends AbstractController
{   #[Route('/purchase/edit', name: 'edit_adress')]
    #[Route('/purchase', name: 'app_purchase')]
    public function index(Security $security, SessionInterface $session, Request $request): Response
    {
        $userOnline=$security->getUser();
       

        if($userOnline == NULL){
            $this->addFlash('success', 'Vous devez etre connecter pour passer commande');
            return $this->redirectToRoute('app_login');
        }

        else{
     //       $adress=new Purchase();
            $form = $this->createForm(AddAdressType::class);
           $form->handleRequest($request);
          
            if ($form->isSubmitted() && $form->isValid()) {
              
                $adress = $form->getData();
             
                $session->set("adress", $adress);
                return $this->redirectToRoute('app_recap');
            }



            return $this->render('purchase/index.html.twig', [
                'AddAdressform' => $form
            ]);
        }

    }


    #[Route('/purchase/recap', name: 'app_recap')]
    public function recap(Security $security, SessionInterface $session, ProductRepository $productRepository): Response
    {
        $basket=$session->get("basket");
        $adress=$session->get("adress");
        $total=$session->get("total");

  
         
            $dataBasket= [];
            
            $totalQuantity= 0;
    
            foreach($basket as $id=>$quantity)
            {
            $product = $productRepository->find($id);
            $dataBasket[] = [
                "product" => $product,
                "quantity" => $quantity
            ];
            
            $totalQuantity += $quantity;
            }
        
          
        return $this->render('purchase/recap.html.twig', 
        [ 'address' => $adress,
        'baskets' => $dataBasket,
         'total' => $total 
    ]);
    }

    #[Route('/purchase/pay', name: 'app_success')]
    public function pay(Security $security ,SessionInterface $sessions, ): Response
    {   
        $total=$sessions->get("total");
        $totalStripe= $total*100;

        Stripe::setApiKey(" sk_test_51OnlndHnA0hVdX3flNZVqT1rojwqtKP9cE1H5wq5UORVj0FaFLA1r2F4dX3XSRyDA5Rbxrga8AhEdvVGatu5q14o00d0vH3sE0 ");

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' =>  $totalStripe , // Montant total en centimes (par exemple, 50 EUR)
                        'product_data' => [
                            'name' => 'Votre produit ou service', // Nom de votre produit ou service
                            // Autres détails du produit...
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_success', [], UrlGeneratorInterface::ABSOLUTE_URL), // Redirection vers app_home après un paiement réussi
            'cancel_url' => $this->generateUrl('app_home', [], UrlGeneratorInterface::ABSOLUTE_URL),
           
        ]);


        // Rediriger l'utilisateur vers la page de paiement Stripe
        return $this->redirect($session->url, Response::HTTP_FOUND);
    }

    #[Route('/purchase/paySuccess', name: 'app_pay')]
    public function success(Security $security, ProductRepository $productRepository ,SessionInterface $session,UserRepository $userRepository, EntityManagerInterface $em, PurchaseRepository $purchaseRepository): Response
    {   $basket=$session->get("basket");
        $delevry=$session->get("adress");
        $total=$session->get("total");
       

        $longueur = 8; // Longueur du numéro de commande
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Caractères possibles pour le numéro de commande
        $numberCommand = '';

        for ($i = 0; $i < $longueur; $i++) {
        $numberCommand .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }

        


        $commandDateTime= new DateTimeImmutable();

        $user=$security->getUser();
        $firstName=$delevry->getFirstName();
        $name=$delevry->getName();
        $adress=$delevry->getAdress();
        $postalCode=$delevry->getPostalCode();
        $city=$delevry->getCity();



        $purchase= new Purchase();
        $purchase->setUser($user);
        $purchase->setFirstName($firstName);
        $purchase->setName($name);
        $purchase->setAdress($adress);
        $purchase->setPostalCode($postalCode);
        $purchase->setCity($city);
        $purchase->setTotal($total);
        $purchase->setNoOrder($numberCommand);
        $purchase->setPurchaseDate($commandDateTime);

        $em->persist($purchase);
        $em->flush();

        

 
        foreach($basket as $id=>$quantity)
        {
            $dataBaseBasket=new Basket();
            $product=$productRepository->find($id);

            $dataBaseBasket->setPurchases($purchase);
            $dataBaseBasket->setProducts($product);
            $dataBaseBasket->setQuantity($quantity);

            $em->persist($dataBaseBasket);
            $em->flush();
            

        }

        $session->remove('basket');
        $session->remove('adress');
        $session->remove('total');
        
        $invoice= $purchaseRepository->findOneBy([] ,["id" => "DESC"]);
    
         return $this->redirectToRoute('app_invoice');




       


    }


}
