<?php

namespace App\Controller;

use DateTime;
use Stripe\Stripe;

use PayPal\Api\Payer;
use App\Entity\Basket;
use DateTimeImmutable;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use App\Entity\Purchase;
use Stripe\StripeClient;
use App\Form\AddAdressType;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Api\RedirectUrls;
use Stripe\Checkout\Session;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use App\Repository\ProductRepository;
use PayPal\Auth\OAuthTokenCredential;
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

    #[Route('/purchase/paypal', name: 'app_paypal')]
    public function paypal(SessionInterface $session): Response
    {   
        // Récupérer le montant total de la session
        $total = $session->get("total");
      

        // Configuration de l'API PayPal
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                'AVyXSqJ1aSmvGtSG-5LEhYgukmY1lGhoOvnP9BR7U4unZlkxB6y6-VSApffmyeRNVgelAsKRK7USgXZ0',     // Remplacer par votre client ID
                'EBgucnR-884XWJ0RC2VluTrIO6BFObB43Onkjhv5kn_kVpgEvEb55W-iaKFXFwtlvbeMAi-OYqqvPG2N'  // Remplacer par votre client secret
            )
        );
        
        // Configurer le mode (sandbox ou live)
        $apiContext->setConfig(['mode' => 'sandbox']); // Changez pour 'live' en production

        // Création d'un paiement PayPal
        $payment = new Payment();
        $payment->setIntent('sale');

        // Configuration du payeur
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $payment->setPayer($payer);

        // Configuration du montant
        $amount = new Amount();
        $amount->setTotal( $total);
        $amount->setCurrency('EUR');
        
        // Configuration de la transaction
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $payment->setTransactions([$transaction]);

        // Configuration des URLs de redirection
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($this->generateUrl('app_success', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $redirectUrls->setCancelUrl($this->generateUrl('app_home', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $payment->setRedirectUrls($redirectUrls);

        // Création du paiement
        $payment->create($apiContext);

        // Redirection vers PayPal pour compléter le paiement
        return $this->redirect($payment->getApprovalLink());
    }


        #[Route('/purchase/pay', name: 'app_pay')]
    public function pay(Security $security, ProductRepository $productRepository ,SessionInterface $session, ): Response
    {   
        $total=$session->get("total");
        $totalStripe= $total*100;

        Stripe::setApiKey("sk_test_51OosdzCmZ8F0ibT38qCeNaBVbmiDqMuPF6yuwXce8A3oOIs9ki2w9CgllEUve9SoBLG8BkLnoQndmNBHfzjowYhY00ePXbUauc");

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


    #[Route('/purchase/paySuccess', name: 'app_success')]
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

    #[Route('/purchase/sendingmail', name: 'app_sendingEmail')]
    public function sendingEmail(Security $security, MailerInterface $mailer): Response
    {   //trouver le nom du fichier de facture correspondant 
        $user=$security->getUser();
        foreach($user->getPurchases() as $purchase ){
            $lastPurchasNoOrder = $purchase->getNoOrder();
        }

        //on indique le chemin du fichier ainsi que le nom du fichier
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/pdf';
        
        
       
        $email = 
        (new TemplatedEmail())
                    ->from(new Address('no-reply@apolloCloud.com', 'AppolloCloud'))
                    ->to($user->getEmail())
                    ->subject('confirmation commande')
                    ->htmlTemplate('purchase/email.html.twig')
                    //on attache le fichier
                    ->attachFromPath( $publicDirectory .'/'. $lastPurchasNoOrder.'.pdf');
       
        $mailer->send($email);

        return $this->render("purchase/success.html.twig");
;
    }

}
