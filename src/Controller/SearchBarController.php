<?php

namespace App\Controller;

use App\Form\FormSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\NotesRepository;
use Symfony\Component\Routing\Annotation\Route;

class SearchBarController extends AbstractController
{
    
    #[Route('/search', name: 'app_search_bar')]
    public function index(Request $request,FormSearchType $searchForm, NotesRepository $notesRepository): Response
    {
        $searchForm = $this->createForm(FormSearchType::class);
        $searchForm->handleRequest($request);

        $notes = [];
        $searchPerformed = false;

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchTerm = $searchForm->get('searchTerm')->getData();
            $notes = $notesRepository->searchNotes($searchTerm);
            $searchPerformed = true;
        }

        return $this->render('searchbar/_searchbar.html.twig', [
            'searchForm' => $searchForm->createView(),
            'searchPerformed' => $searchPerformed,
            'notes' => $notes,
        ]);
    }
}
