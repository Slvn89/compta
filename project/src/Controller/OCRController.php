<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

class OCRController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/ocr/upload", name="ocr_upload")
     */
    public function uploadForm()
    {
        return $this->render('ocr/upload.html.twig');
    }

    /**
     * @Route("/ocr/process-upload", name="process_upload", methods={"POST"})
     */
    public function processFile(Request $request): Response
    {
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            return $this->redirectToRoute('ocr_upload');
        }

        // Process the uploaded file and get the data
        $processedData = $this->processUploadedFile($uploadedFile);

        // Create a new Facture entity
        $facture = new Facture();

        // Assuming the keys 'contrat' and 'client' exist in $processedData
        $contrat = $processedData['contrat'] ?? null;
        $client = $processedData['client'] ?? null;

        // Set the values to the Facture entity
        if ($contrat && $client) {
            $facture->setContrat($contrat);
            $facture->setClient($client);
        }

        // Store the Facture in the session
        $request->getSession()->set('facture_to_save', $facture);

        // Create the form and handle the request
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        return $this->render('ocr/show.html.twig', [
            'processed_data' => $processedData,
            'form' => $form->createView(),
        ]);
        
    }

    /**
     * @Route("/ocr/save-facture", name="save_facture", methods={"POST"})
     */
    public function saveFacture(Request $request, ManagerRegistry $doctrine): Response
    {
        // Récupérer la Facture depuis la session
        $facture = $request->getSession()->get('facture_to_save');

        if (!$facture) {
            return $this->redirectToRoute('ocr_upload');
        }

        // Créer le formulaire et gérer la requête
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        // Variable pour stocker la confirmation
        $confirmed = false;

        // Si le formulaire est soumis et valide, sauvegarder en base de données
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($facture);
            $entityManager->flush();

            // Supprimer la Facture de la session après la sauvegarde
            $request->getSession()->remove('facture_to_save');

            // Marquer comme confirmé
            $confirmed = true;
        }
        
        // Afficher la vue avec les données sauvegardées
        return $this->render('ocr/show.html.twig', [
            'saved_facture' => $facture,
            'confirmed' => $confirmed,
        ]);
    }
    
    

    private function processUploadedFile(UploadedFile $file): array
    {
        $extension = $file->getClientOriginalExtension();

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
            case 'tiff':
            case 'png':
                return $this->processImage($file);
            case 'txt':
            case 'doc':
            case 'rtf':
                return $this->processTextFile($file);
            case 'pdf':
                return $this->processPdfFile($file);
            default:
                throw new \InvalidArgumentException("Unsupported file type: $extension");
        }
    }

    private function processImage(UploadedFile $file): array
    {
        // Handle image processing with Tesseract
        $imageFilePath = $file->getPathname();
        $textOutput = $this->runTesseract($imageFilePath);

        // Run Python script with Tesseract output
        return $this->runPythonScript($textOutput);
    }



    private function processTextFile(UploadedFile $file): array
    {
        // Handle text file directly
        $text = file_get_contents($file->getPathname());
        return $this->runPythonScript($text);
    }

    private function processPdfFile(UploadedFile $file): array
    {
        // Handle PDF processing with Ghostscript
        $pdfFilePath = $file->getPathname();
        $tiffFilePath = $this->convertPdfToTiff($pdfFilePath);
        $text = $this->runTesseract($tiffFilePath);
        unlink($tiffFilePath);
        return $this->runPythonScript($text);
    }

    private function convertPdfToTiff(string $pdfFilePath): string
    {
        $tempDir = sys_get_temp_dir();
        $tiffFilePath = $tempDir . '/' . uniqid('converted_', true) . '.tiff';

        $gsOptions = [
            '-sDEVICE=tiff24nc',
            '-r300',
            '-o',
            $tiffFilePath,
            '-dNOPAUSE',
            '-dBATCH',
            '-dSAFER',
            '-dQUIET',
            '-dTextAlphaBits=4',
            '-dGraphicsAlphaBits=4',
        ];

        $process = new Process(['gs', ...$gsOptions, $pdfFilePath]);
        $process->mustRun();

        return $tiffFilePath;
    }

    private function runTesseract(string $imageFilePath): string
    {
        $tesseractOptions = [
            $imageFilePath,
            '-',
            '1', // Use LSTM OCR Engine
            '--hocr',
        ];

        $process = new Process(['tesseract', ...$tesseractOptions]);
        $process->setTimeout(120);
        
        try {
            $process->mustRun();
            return $process->getOutput();
        } catch (ProcessFailedException $exception) {
            throw new \RuntimeException("Erreur lors de l'exécution de Tesseract. " . $exception->getMessage());
        }
    }

    private function runPythonScript(string $text): array 
    {
        $scriptPath = $this->getParameter('kernel.project_dir') . '/scripts/process_data.py';
        $pythonExecutable = $this->getParameter('kernel.project_dir') . '/venv/bin/python';

        $command = [$pythonExecutable, $scriptPath, $text,];
        $process = new Process($command);

        try {
            $process->mustRun();
            $decodedOutput = json_decode($process->getOutput(), true);
            return $decodedOutput;
            
        } catch (ProcessFailedException $exception) {
            throw new \RuntimeException("Erreur lors de l'exécution du script Python. " . $exception->getMessage());
        }
    }
}
