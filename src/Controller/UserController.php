<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use App\Repository\BasketRepository;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(Session $session, Security $security): Response
    {$flashes = $session->getFlashBag();
    
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
  

    #[Route('/delete/{id}', name: 'delete_user')]
    public function delete($id,EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher,UserRepository $userRepository, TokenStorageInterface $tokenStorage): Response
    {



        //instanciate the user
        $user = $userRepository->findOneBy(['id' => $id]);



        //the user is equal to the user in seesion
        if($user == $this->getUser()){



            // $user->setPseudo('Profile supprimé');



            $newPassword = bin2hex(random_bytes(8));
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);



            $uniqueValue = 'deleted_user_' . uniqid();
            $user->setUsername($uniqueValue);



            $user->setIsVerified(false);



            $em->persist($user);
            $em->flush();



            $tokenStorage->setToken(null);
            //let him see his profile
            $this->addFlash('success', 'Your profile was succesfully deleted.');
            return $this->redirectToRoute('app_home');
        }



        //if the user was different from the current user in seesion send back to home and add flash message 
        $this->addFlash('danger', 'You dont have access to this page.');
        return $this->redirectToRoute('app_home');
    }


    #[Route('/editUser/{id}', name: 'app_editUser')]
    public function editUser($id, User $user ,Request $request, SessionInterface $session, UserPasswordHasherInterface $passwordHasher ,UserRepository $userRepository, EntityManagerInterface $entityManager, Security $security) : Response
    {  
        
        $user1 = $security->getUser()->getEmail();
      
        $form = $this->createForm(UserEditType::class, $user);// creation du formulaire pour editer le mot de passe et email
        $form->handleRequest($request);
        $userId = $session->get('id');
        $actualUserId=$security->getUser()->getId();
        $idUrl=intval($id);// convertit la chaine de caractère en nombre entier
      
    

        if($idUrl  !==  $actualUserId ){// securité: si l'utilisateur en ligne modifie l'url  ,il ne peux pas acceder a la page  de modification d'un autre 

            $this->addFlash('danger', 'You dont have access to this page.');
            return $this->redirectToRoute('app_home');
        }
        
      
        if ($form->isSubmitted() && $form->isValid()) {
            $password=$form->get('plainPassword')->getData();// recuperation des donée du formulaire
             $newEmail=$form->get('email')->getData();
            
        
        
          
          $security->getUser()->setEmail($newEmail);// mise en place du nouvelle email pour l'utilisateur en ligne
           $entityManager->flush();// envoie dans la base de donnée
                
      

          



            if($password){// si le $password n'est pas null
                $encodedPassword = $passwordHasher->hashPassword(// achage du mot de passe 
                    $user,
                    $form->get('plainPassword')->getData()// recuperation du nouveau mot de passe dans le formulaire
                );    
            $user->setPassword($encodedPassword);// implementation du nouveau mot de passe
            $entityManager->flush();



            }


            $this->addFlash('success', 'Les informations ont a été modifié avec succès.');
            return $this->redirectToRoute('app_user');
         
        }
        return $this->render('user/editUser.html.twig', [
            'registrationForm' => $form
        ]);
    }



// menu affichant tout les commandes qui on été passer
    #[Route('/orderHistory', name: 'app_orderHistory')]
    public function orderHistory(Security $security) : response
    {
        $orders = $security->getUser()->getPurchases();//recupere tous les achats de l'utilisateur connecter 

        return $this->render('user/orderHistory.html.twig', [ 'orders' => $orders ]);


    }

// voir le detail de la commande
    #[Route('/orderHistoryDetail/{noOrder}', name: 'app_orderHistoryDetail')]
    public function orderHistoryDetail($noOrder,Security $security,PurchaseRepository $purchaseRepository, BasketRepository $basketRepository) : response
    {   
       

        $invoice= $purchaseRepository->findBy([ 'NoOrder' => $noOrder]);// trouve les details de la commande en cherchant par le numero de commande
     
        if ($invoice== null){// si la facture n'existe pas
            return $this->redirectToRoute('app_orderHistory');// on  redirige vers l historique des facture
        } 
        else{
         $ordersUser= $security->getUser()->getId();// on recupere l'id de l'utilisateur en ligne
        $userOfInvoice = $invoice[0]->getUser()->getId();// on recupere l'id de l'utilisateur liée a la facture
      
     
            if ( $ordersUser !==  $userOfInvoice    ){// securité : si l'id de l'utilisateur de la facture et l'id de l'utilisateur qui est connecter n'est pas egale
            return $this->redirectToRoute('app_orderHistory');// je  renvoie vers le menu 
            }
            else{
      
             return $this->render('user\showOrder.html.twig' , ['invoice' => $invoice[0] ]);// affichage du premier resultat 

        }}
    }





// telechargement de facture 
    #[Route('/orderHistoryDetail/downloadInvoice/{noOrder}', name: 'app_downloadInvoice')]
    public function downloadInvoice($noOrder) : response
   {
   
    $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/pdf';// chemin vers le dossier du pdf

    $pdfFilepath =  $publicDirectory . '/'.$noOrder.'.pdf';// precise le nom du fichier avec le chemin du dossier du pdf



    $response = new BinaryFileResponse($pdfFilepath);

    return $this->file($pdfFilepath);// telechargement du fichier
   
   
   }
}
