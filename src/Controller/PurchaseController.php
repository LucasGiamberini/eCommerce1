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
use Symfony\Component\Mime\Address;
use App\Repository\ProductRepository;
use App\Repository\NicotineRepository;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PurchaseController extends AbstractController
{   

    public function __construct(private ParameterBagInterface $param)
    {

    }


    #[Route('/purchase/edit', name: 'edit_adress')]
    #[Route('/purchase', name: 'app_purchase')]
    public function index(Security $security, SessionInterface $session, Request $request): Response
    {
        $userOnline=$security->getUser();// recupere l'utilisateur qui est connecter
       

        if($userOnline == NULL){// si il n'y pas d'utilisateur connecter
            $this->addFlash('success', 'Vous devez etre connecter pour passer commande');
            return $this->redirectToRoute('app_login');// redirige vers la page de connection
        }

        else{// si un utilisateur est connecter

            $form = $this->createForm(AddAdressType::class);// crée le formulaire pour l'adresse 
           $form->handleRequest($request);//handleRequest est une fonction qui retourne un booléen( True ou False) qui permet de determiner si le formulaire est envoyer ou non. 
          //$request est le contenant du formulaire
            if ($form->isSubmitted() && $form->isValid()) {// si le formulaire est envoyer et valide
              
                $adress = $form->getData();// recupere les infos du formulaire
             
                $session->set("adress", $adress);// sauvegarde dans la session l'adresse
                return $this->redirectToRoute('app_recap');
            }



            return $this->render('purchase/index.html.twig', [
                'AddAdressform' => $form
            ]);
        }

    }

// recapitulatif de commande avant le paiement
    #[Route('/purchase/recap', name: 'app_recap')]
    public function recap(Security $security, SessionInterface $session, ProductRepository $productRepository, NicotineRepository $nicotineRepository): Response
    {   
        // recupere toute les donnée enregistrer en session
        $basket=$session->get("basket");
        $adress=$session->get("adress");
        $total=$session->get("total");
       
  
         // voir basket controller ligne 23
            $dataBasket= [];
            
            $totalQuantity= 0;
    
            foreach ($basket as $id => $data) {
                $product = $productRepository->find($id);
                $nicotine = $nicotineRepository->find($data['nicotine_id']);
                $quantity= $data['quantity'];
    
                $dataBasket[] = [
                    "product" => $product,
                    "quantity" => $quantity,
                    "nicotine" => $nicotine
                ];
           
            $totalQuantity += $quantity;// on ajoute a la quantité total la quantité du produit
            }
        

          
        return $this->render('purchase/recap.html.twig', 
        [ 'address' => $adress,
        'baskets' => $dataBasket,
         'total' => $total ,
        
    ]);
    }

   





// payement par stripe
        #[Route('/purchase/pay', name: 'app_pay')]
    public function pay(Security $security, ProductRepository $productRepository ,SessionInterface $session,Request $request, ParameterBagInterface $param ): Response
    {    
        if (!$this->isCsrfTokenValid('pay', $request->request->get('_csrf_token'))) {// on recupere le jeton csrf et on verifie si elle est valide
            return $this->redirectToRoute('app_home');// si le jeton n'est pas valide , alors la page renvoyé est la page d'accueille
        }
        else{

        $total=$session->get("total");// recuperation du montant present en session
        $totalStripe= $total*100;// multiplication par 100 pour avoir le montant en centime
            $stripeApiKey= $this->param->get('STRIPE_SECRET_KEY');

        Stripe::setApiKey($stripeApiKey);

        $session = Session::create([
            'payment_method_types' => ['card'],// payement par carte
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',// choix de la monnaie
                        'unit_amount' =>  $totalStripe , // Montant total en centimes (par exemple, 50 EUR)
                        'product_data' => [
                            'name' => 'Commande', // Nom du produit ou service
                            
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_success', [], UrlGeneratorInterface::ABSOLUTE_URL), // Redirection vers app_success après un paiement réussi
            'cancel_url' => $this->generateUrl('app_home', [], UrlGeneratorInterface::ABSOLUTE_URL),// redirection après anuller payement
           
        ]);


      // Rediriger l'utilisateur vers la page de paiement Stripe
      return $this->redirect($session->url, Response::HTTP_FOUND);
    }
    }



// si le paiement est reussi
    #[Route('/purchase/paySuccess', name: 'app_success')]
    public function success(Security $security, ProductRepository $productRepository ,SessionInterface $session,UserRepository $userRepository, EntityManagerInterface $em, PurchaseRepository $purchaseRepository, NicotineRepository $nicotineRepository ): Response
    {   
        
        // recuperation des données presente en session
        $basket=$session->get("basket");
        $delevry=$session->get("adress");
        $total=$session->get("total");
       
        //creation du numero de commande
        $longueur = 8; // Longueur du numéro de commande
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Caractères possibles pour le numéro de commande
        $numberCommand = '';

        for ($i = 0; $i < $longueur; $i++) {//$i < $longueur: la boucle s'execute tant que le numero de commande est inferieur au nombre de caractère
            //$i++ a chaque itération de la boucle, $i est incrementé de 1
        $numberCommand .= $caracteres[rand(0, strlen($caracteres) - 1)];
       // a chaque itération , $numberCommand a un nouveau carractère
        // rand()  est une fonction php qui randomise  une valeur
        //  strlen permet de transformer des chaines de caractère en nombre entié
        }

        


        $commandDateTime= new DateTimeImmutable();// creation de la date de commande en immutable, empechant la modification future 

        $user=$security->getUser();// recuperation de l'entité user connecter
        $firstName=$delevry->getFirstName();// le reste est la recuperation des donnée de livraison sauvegarder en session
        $name=$delevry->getName();
        $adress=$delevry->getAdress();
        $postalCode=$delevry->getPostalCode();
        $city=$delevry->getCity();



        $purchase= new Purchase();// instanciation de la classe purchase
        $purchase->setUser($user);// le reste est  l'association des different information au nouvelle objet 
        $purchase->setFirstName($firstName);
        $purchase->setName($name);
        $purchase->setAdress($adress);
        $purchase->setPostalCode($postalCode);
        $purchase->setCity($city);
        $purchase->setTotal($total);
        $purchase->setNoOrder($numberCommand);
        $purchase->setPurchaseDate($commandDateTime);

        $em->persist($purchase);//  preparation du nouvelle objet
        $em->flush();//et injection dans la base de donnée

    

        foreach ($basket as $item) {
            $dataBaseBasket = new Basket();
            $product = $productRepository->find($item['product_id']);
            $nicotine = $nicotineRepository->find($item['nicotine_id'])->getProportioning();


        
            $dataBaseBasket->setPurchases($purchase);
            $dataBaseBasket->setProducts($product);
            $dataBaseBasket->setNicotine($nicotine);
            $dataBaseBasket->setQuantity($item['quantity']);
        
            $em->persist($dataBaseBasket);
            $em->flush();
        }

        $session->remove('basket');// suppression du panier,de l'adresse present en session
        $session->remove('adress');
        $session->remove('total');
        
        
        return $this->redirectToRoute('app_invoice');// renvoie vers le pdf controller


    }


    #[Route('/purchase/sendingmail', name: 'app_sendingEmail')]
    public function sendingEmail(Security $security, MailerInterface $mailer): Response
    {   
        //trouver le nom du fichier de facture correspondant 
        $user=$security->getUser();// recuperation de l'utilisateur connecter
        foreach($user->getPurchases() as $purchase ){// recuperation du numero de la derniere commande 
            $lastPurchasNoOrder = $purchase->getNoOrder();
        }

        //on indique le chemin du fichier ainsi que le nom du fichier
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/pdf';
        
        
       
        $email = 
        (new TemplatedEmail())// creation d'un nouveau mail au format twig
                    ->from(new Address('no-reply@apolloCloud.com', 'AppolloCloud'))// on indique de quelle expediteur en instanciant un nouvelle objet Adress de mailer
                    ->to($user->getEmail())// on precise a qui ont l'envoie, ici en l'occurance l'utilisateur qui est  connecter
                    ->subject('confirmation commande')// on precise le sujet
                    ->htmlTemplate('purchase/email.html.twig')// chemin vers le template du modèle de mail que l'on veux envoyer
                    //on attache le fichier de la facture associer a la commande
                    ->attachFromPath( $publicDirectory .'/'. $lastPurchasNoOrder.'.pdf');// 
       
        $mailer->send($email);// on envoie le mail avec mailer

        return $this->render("purchase/success.html.twig");// redirection vers la page de succcès
;
    }

}
