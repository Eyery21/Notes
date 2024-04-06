<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    #[Route(path: "/welcome", name: "app_home")]
    public function index(AdminRepository $adminRepository): Response
    {
        // Récupérez le repository de l'entité Admin par son email
        $admin = $adminRepository->findOneBy(['email' => 'example@gmail.com']);

        //utiliser la méthode getUser pour récuper le repository de user
        $user = $this->getUser();


        // Rendu de la vue avec l'instance de l'entité Admin
        return $this->render(
            "welcome.html.twig",
            [
                'admin' => $admin,
                'user' => $user

            ]
        );
    }
}
