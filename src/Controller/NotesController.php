<?php

namespace App\Controller;
use App\Form\FormSearchType;

use App\Entity\User;
use App\Entity\Notes;
use App\Form\NotesType;
use App\Repository\NotesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/notes')]
class NotesController extends AbstractController
{
    #[Route('/', name: 'app_notes_index', methods: ['GET', 'POST'])]
    public function index(Request $request, NotesRepository $notesRepository, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        // $searchForm = $this->createForm(FormSearchType::class);
        // $searchForm->handleRequest($request);
        $notes = [];

        // if ($searchForm->isSubmitted() && $searchForm->isValid()) {
        //     $searchTerm = $searchForm->get('searchTerm')->getData();
        //     $notes = $notesRepository->searchNotes($searchTerm);
        //                 $searchPerformed = true; // Ajoutez cette variable pour indiquer qu'une recherche a été effectuée

        // } else {
        //     // Peut-être voulez-vous afficher toutes les notes ou seulement celles de l'utilisateur
        //     $notes = $notesRepository->findBy(['user' => $this->getUser()]);
        //     $searchPerformed = false; // La recherche n'a pas été effectuée

        // }
        $notes = $notesRepository->findBy(['user' => $user]);
        $showUrgency = false;


        return $this->render('notes/index.html.twig', [
            'notes' => $notes,
            'user' => $user,
            'showUrgency' => $showUrgency,
            // 'searchForm' => $searchForm->createView(),
            // 'searchPerformed' => $searchPerformed, // Passez cette nouvelle variable à Twig


        ]);
    }


    #[Route('/new', name: 'app_notes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NotesRepository $notesRepository, EntityManagerInterface $entityManager): Response
    {
        $note = new Notes();
        //$note->setUser($this->getUser()); // Obtient l'utilisateur actuellement connecté et l'attribue à la note

        $form = $this->createForm(NotesType::class, $note);
        $form->handleRequest($request);
        $searchForm = $this->createForm(FormSearchType::class);
        $searchForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($note);
            $entityManager->flush();

            return $this->redirectToRoute('app_notes_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchTerm = $searchForm->get('searchTerm')->getData();
            $notes = $notesRepository->searchNotes($searchTerm);
            $searchPerformed = true; // Ajoutez cette variable pour indiquer qu'une recherche a été effectuée
        } else {
            $notes = [];
            $searchPerformed = false; // La recherche n'a pas été effectuée
        }
        return $this->render('notes/new.html.twig', [
            'note' => $note,
            'form' => $form,
            'searchForm' => $searchForm->createView(),

        ]);
    }

    #[Route('/{id}', name: 'app_notes_show', methods: ['GET'])]
    public function show(Request $request, NotesRepository $notesRepository,Notes $note): Response
    {
        $searchForm = $this->createForm(FormSearchType::class);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchTerm = $searchForm->get('searchTerm')->getData();
            $notes = $notesRepository->searchNotes($searchTerm);
            $searchPerformed = true; // Ajoutez cette variable pour indiquer qu'une recherche a été effectuée
        } else {
            $notes = [];
            $searchPerformed = false; // La recherche n'a pas été effectuée
        }
        return $this->render('notes/show.html.twig', [
            'note' => $note,
            'searchForm' => $searchForm->createView(),
            'searchPerformed' => $searchPerformed, // Passez cette nouvelle variable à Twig


        ]);
    }

    #[Route('/{id}/edit', name: 'app_notes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Notes $note, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        $form = $this->createForm(NotesType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_notes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('notes/edit.html.twig', [
            'note' => $note,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_notes_delete', methods: ['POST'])]
    public function delete(Request $request, Notes $note, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();

        if ($note->getUser() !== $user) {
            return $this->render('error/restrictionDelete.html.twig', [
                'user' => $user
            ]);
        };
        if ($this->isCsrfTokenValid('delete' . $note->getId(), $request->request->get('_token'))) {
            $entityManager->remove($note);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_notes_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/all', name: 'app_notes_delete_all', methods: ['POST'])]
    public function deleteAll(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user) {
            // Trouvez toutes les notes appartenant à l'utilisateur
            $notes = $entityManager->getRepository(Notes::class)->findBy(['user' => $user]);

            foreach ($notes as $note) {
                $entityManager->remove($note);
            }

            $entityManager->flush();
        }

        return $this->redirectToRoute('app_notes_index');
    }
}
