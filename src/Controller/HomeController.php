<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        $user = $this->getUser();

        return $this->render('home/profile.html.twig', [
            'user' => $user,
        ]);
    }
}
