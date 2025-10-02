<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/welcome')]
class WelcomeController extends AbstractController
{
    #[Route('/', name: 'app_welcome_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('welcome/index.html.twig');
    }

//    #[Route('/{name}', name: 'app_welcome_index_custom', methods: ['GET'])]
//    public function indexCustom(String $name): Response
//    {
//        return $this->render('welcome/index_custom.html.twig', [
//            'name' => $name,
//        ]);
//    }

    #[Route('/{name}/{sex}', name: 'app_welcome_index_custom', requirements: ['sex' => 'h|f'], methods: ['POST'])]
    public function indexCustom(String $name, String $sex): Response
    {
        $civility = $sex == 'f' ? 'Madame' : 'Monsieur';

        return $this->render('welcome/index_custom.html.twig', [
            'name' => $name,
            'civility' => $civility,
        ]);
    }
}