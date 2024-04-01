<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/product3dit/{id}/edit', name:'edit_product')]
    #[Route('/productN3w/new', name: 'add_product')]
    public function add(Product $product=NULL , EntityManagerInterface $entityManager,  Request $request): Response
    {
         if(!$product){// pour l'edition, si aucun objet n'est trouver
            $product = new Product();// creé un nouveau produit
       }

       $form=$this->createForm(ProductType::class,$product);// creation du formulaire

       $form->handleRequest($request);// 

       if ($form->isSubmitted() && $form->isValid()) {// si le formulaire est envoyé et est valide
        $aroma=$form->get('Aroma')->getData();
      
       

        $pictures = $form->get('picture')->getData();
         //on recupere les données differentes entrée
       
         $product-> setAroma($aroma);
       
         $capacity=$form->get('capacity')->getData();
         $product->setCapacity($capacity);

          
        foreach($product as $products ){
          
         $nicotine=$form->get('Nicotines')->getData();
       // dd($nicotine);
            
           
            
        }
        // et on les definits dans le nouveau produit
        
        $entityManager->persist($product);// on prepare le produit pour la base de donnée

        $entityManager->flush();// et on l'envoie dans la base de donnée

                if ($pictures) {//
                    
                    foreach($pictures as $picture){// on fait une boucle pour faire les image une par une
                        // On génère un nouveau nom de fichier
                        $name = md5(uniqid()).'.'.$picture->guessExtension();
                        
                        // On copie le fichier dans le dossier uploads
                        $picture->move(
                            $this->getParameter('pictures_directory'),// recuperation du parametre present dans service.yalm
                            $name
                        );
                        // On crée l'image dans la base de données
                        $img = new Picture();
                        $img->setName( $name);// on ajoute un nom
                        $img->setProducts($product);// on associe le produit a l'image
                        $product->addPicture($img);//on ajoute l'image au produit

                            $entityManager->persist($img); //prepare les donner
                            $entityManager->flush();//et envoie dans la base de donnée

                    }
                }


            return  $this->redirectToRoute('app_admin');//redirection vers home
            }


       return $this->render('product\addProduct.html.twig',[ // affichage de la page avec le formulaire
        'form' => $form
       ]);

       }



       //pour afficher un produit
       #[Route('/product/{id}/Show', name: 'show_product')]//on recupere l'id du produit
       public function show(Product $product): Response
       {    
          
           return $this->render('product/show.html.twig',
       [ 'product' => $product]);
       }




       
       // pour supprimer un produit
       #[Route('/product/{id}/r3mov3', name: 'delete_product')]// on recupere l'id du prduit
       public function delete(Product $product,EntityManagerInterface $entityManager,ProductRepository $productRepo ): Response
    {         
        
        $pathImg= $this->getParameter('pictures_directory');//recuperation du chemin des images
       $idProduct=$product->getId();// recuperation de l'id du produit a suprimer
       $nameimgDeleteQuery= $productRepo->nameImgDelete($idProduct);// lancement de la fonction pour trouver toute les images avec l'id du produit
       $nameimgDelete=array_column($nameimgDeleteQuery, 'name');// recuperation des noms des fichier image a supprimer
        
       foreach($nameimgDelete as $img){// pour supprimer le ou les fichier image
          
           $imgPath=$pathImg.'/'.$img;// definition du chemin de l'image avec le nom du fichier a supprimer
           if (file_exists($imgPath)) {
               unlink($imgPath);// suppression du fichier image
           } 
          
      }
      foreach($product as $products){// boucle pour suppression des associations dans la base de donnée
      $imgBdd=$products->getPicture();// prend l'image associer a l'objet
      
      $imgBdd->removeProducts();// supprime le produit dans l'entité image
      $product->removePicture( $imgBdd);// supprime l'objet image dans l'entité  produit
      
      }
           $entityManager->remove($product);//enleve produit dans la base de donnée
           $entityManager->flush();// execute la requete
    
           return $this->redirectToRoute('app_admin');
       }
       
   }




