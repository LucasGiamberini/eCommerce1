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

    #[Route('/product/{id}/edit', name:'edit_product')]
    #[Route('/product/new', name: 'add_product')]
    public function add(Product $product=NULL , EntityManagerInterface $entityManager,  Request $request): Response
    {
         if(!$product){
            $product = new Product();
       }

       $form=$this->createForm(ProductType::class,$product);

       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
        $aroma=$form->get('Aroma')->getData();
        $nicotine=$form->get('Nicotine')->getData();

        $pictures = $form->get('picture')->getData();
        
        $product-> setAroma($aroma);
        $product->setNicotine($nicotine);
      

        foreach($product as $products ){
            $capacity=$form->get('capacity')->getData();
            $products->addCapacity($capacity);
            
        }

        
        $entityManager->persist($product);

        $entityManager->flush();

                if ($pictures) {
                    
                    foreach($pictures as $picture){
                        // On génère un nouveau nom de fichier
                        $name = md5(uniqid()).'.'.$picture->guessExtension();
                        
                        // On copie le fichier dans le dossier uploads
                        $picture->move(
                            $this->getParameter('pictures_directory'),
                            $name
                        );
                        // On crée l'image dans la base de données
                        $img = new Picture();
                        $img->setName( $name);
                        $img->setProducts($product);
                        $product->addPicture($img);

                            $entityManager->persist($img); //prepare les donner
                            $entityManager->flush();

                    }
                }


            return  $this->redirectToRoute('app_home');
            }


       return $this->render('product\addProduct.html.twig',[ 
        'form' => $form
       ]);

       }

       #[Route('/product/{id}/Show', name: 'show_product')]
       public function show(Product $product): Response
       {    
          
           return $this->render('product/show.html.twig',
       [ 'product' => $product]);
       }


       #[Route('/product/{id}/delete', name: 'delete_product')]
       public function delete(Product $product,EntityManagerInterface $entityManager,ProductRepository $productRepo ): Response
    {           $pathImg= $this->getParameter('pictures_directory');
       $idProduct=$product->getId();
       $nameimgDeleteQuery= $productRepo->nameImgDelete($idProduct);
       $nameimgDelete=array_column($nameimgDeleteQuery, 'name');
         foreach($nameimgDelete as $img){
         
        // dd($nameimgDelete);
           $imgPath=$pathImg.'/'.$img;
           if (file_exists($imgPath)) {
               unlink($imgPath);
           } 
          // unlink($imgPath ) ;
         
          
      }
      foreach($product as $products){
      $imgBdd=$products->getPicture();
      $consumable=$product->getConsumable();
      $imgBdd->removeProducts();
      $product->removePicture( $imgBdd);
      
      }
           $entityManager->remove($product);
           $entityManager->flush();
    
           return $this->redirectToRoute('app_home');
       }
       
   }




