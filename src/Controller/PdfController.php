<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Repository\PurchaseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfController extends AbstractController
{
    #[Route('/pdf', name: 'app_pdf')]
    public function index(): Response
    {
        return $this->render('pdf/index.html.twig', [
            'controller_name' => 'PdfController',
        ]);
    }


    #[Route('/pdf/invoice', name: 'app_invoice')]
    public function invoice(PurchaseRepository $purchaseRepository): Response
    {   
        $invoice= $purchaseRepository->findOneBy([] ,["id" => "DESC"]);// on recupere la facture de la base de donnée
      
        $numberCommand=$invoice->getNoOrder();// on prend le numero de commande
       
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('pdf/invoice.html.twig', [
            'invoice' => $invoice
        ]);

    //    return new Response($html);die();
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        
        // dd($dompdf->render());
        // Render the HTML as PDF
         $dompdf->render();
         ob_get_clean();

        //  $this->domPdf->stream('invoice.pdf', [
        //     'Attachment' => false
        // ]);
        // Store PDF Binary Data
        $output = $dompdf->output();
        
        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/pdf';
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/'.$numberCommand.'.pdf';
        
        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);

        return $this->redirectToRoute('app_sendingEmail');
       
    }
}
