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
    {   $user1 = $security->getUser()->getEmail();
      
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
        $userId = $session->get('id');
      //  $oldEmail=$security->getUser()->getUsername();
        $actualUserId=$security->getUser()->getId();
        $idUrl=intval($id);
      
    

        if($idUrl  !==  $actualUserId ){

            $this->addFlash('danger', 'You dont have access to this page.');
            return $this->redirectToRoute('app_home');
        }
        
      
        if ($form->isSubmitted() && $form->isValid()) {
            $password=$form->get('plainPassword')->getData();
             $newEmail=$form->get('email')->getData();
            
        
        
          
          $security->getUser()->setEmail($newEmail);
           $entityManager->flush();
                
      

          



            if($password){
                $encodedPassword = $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                );    
            $user->setPassword($encodedPassword);
            $entityManager->flush();



            }


            $this->addFlash('success', 'Les informations ont a été modifié avec succès.');
            return $this->redirectToRoute('app_user');
         
        }
        return $this->render('user/editUser.html.twig', [
            'registrationForm' => $form
        ]);
    }

    #[Route('/orderHistory', name: 'app_orderHistory')]
    public function orderHistory(Security $security) : response
    {
        $orders = $security->getUser()->getPurchases();

        return $this->render('user/orderHistory.html.twig', [ 'orders' => $orders ]);


    }

    #[Route('/orderHistoryDetail/{noOrder}', name: 'app_orderHistoryDetail')]
    public function orderHistoryDetail($noOrder,Security $security,PurchaseRepository $purchaseRepository, BasketRepository $basketRepository) : response
    {
        $invoice= $purchaseRepository->findBy([ 'NoOrder' => $noOrder]);

        if ($invoice== null){
            return $this->redirectToRoute('app_user');
        } 


        return $this->render('user\showOrder.html.twig' , ['invoice' => $invoice[0] ]);
    }






    #[Route('/orderHistoryDetail/downloadInvoice/{noOrder}', name: 'app_downloadInvoice')]
    public function downloadInvoice($noOrder) : response
   {
   
    $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/pdf';

    $pdfFilepath =  $publicDirectory . '/'.$noOrder.'.pdf';



    $response = new BinaryFileResponse($pdfFilepath);

    return $this->file($pdfFilepath);
   
   
   }
}
