<?php
// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        // Redirige automatiquement vers la page de login
        if ($this->getUser()) {
            return $this->render('dashboard.html.twig', [
                // Tu peux passer des variables si besoin
            ]);
        }

        return $this->redirectToRoute('app_login');
    }
}