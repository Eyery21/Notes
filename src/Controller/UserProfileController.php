<?php

namespace App\Controller;

use App\Entity\Notes;
use App\Form\NotesType;
use App\Repository\NotesRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    #[Route('/user/profile', name: 'app_user_profile')]
    public function index(NotesRepository $notesRepository): Response
    {
        // dans ton contrôleur
        $user = $this->getUser();
        $notes = $notesRepository->findBy(['user' => $user]);

        // Ajoute cette ligne pour déboguer
        // dump($notes);

        return $this->render('user_profile/index.html.twig', [
            'user' => $user,
            'notes' => $notes,
        ]);
    }
}
