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


        return $this->render('base.html.twig', [
            'controller_name' => 'SearchBarController',
            'searchForm' => $searchForm->createView(),
            'notes' => $notes,
            'searchPerformed' => $searchPerformed, 

        ]);
    }
}
