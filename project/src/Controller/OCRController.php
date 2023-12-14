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
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
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
     * @Route("/ocr/process-file", name="process_file", methods={"POST"})
     */
    public function processFile(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        dump('Before processing file'); // Ajoutez cette ligne

        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            return $this->redirectToRoute('ocr_upload');
        }

        $processedData = $this->processUploadedFile($uploadedFile);

        $facture = new Facture();


        // Assuming the keys 'contrat' and 'client' exist in $processedData
        $contrat = $processedData['contrat'];
        $client = $processedData['client'];

        if ($contrat && $client) {
            // Set the values to the Facture entity
            $facture->setContrat($contrat);
            $facture->setClient($client);
        }

        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

            // Save to the database
        $entityManager = $doctrine->getManager();
        $entityManager->persist($facture);
        $entityManager->flush();

        $savedFacture = $entityManager->getRepository(Facture::class)->find($facture->getId());

    return $this->render('ocr/show.html.twig', [
        'processed_data' => $processedData,
        'saved_facture' => $savedFacture,
        'form' => $form->createView(),
        ]);
    }




    private function processUploadedFile(UploadedFile $file): array
    {
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

        // Ajoutez des options Ghostscript selon vos besoins
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

    private function runPythonScript(string $tesseractOutput): array
    {
        $scriptPath = $this->getParameter('kernel.project_dir') . '/scripts/process_data.py';
        $pythonExecutable = $this->getParameter('kernel.project_dir') . '/venv/bin/python';

        $command = [$pythonExecutable, $scriptPath, $tesseractOutput];
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
