<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/book')]
final class BookController extends AbstractController
{
    #[Route(name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var UploadedFile|null $photoFile */
                $photoFile = $form->get('photo')->getData();

                if ($photoFile) {
                    $newFilename = uniqid() . '.' . $photoFile->guessExtension();
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                    $book->setPhoto($newFilename);
                }

                $entityManager->persist($book);
                $entityManager->flush();
                $this->addFlash('success', 'Opération réussie!');
                return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
            }
            foreach ($form->getErrors(true) as $error) {
                $fieldName = $error->getOrigin()->getName();
                $this->addFlash('error', $fieldName . ' : ' . $error->getMessage());
            }
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                /** @var UploadedFile|null $photoFile */
                $photoFile = $form->get('photo')->getData();

                if ($photoFile) {
                    $newFilename = uniqid() . '.' . $photoFile->guessExtension();
                    $photoFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                    $book->setPhoto($newFilename);
                }

                $entityManager->flush();
                $this->addFlash('success', 'Opération réussie!');
                return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
            }

            foreach ($form->getErrors(true) as $error) {
                $fieldName = $error->getOrigin()->getName();
                $this->addFlash('error', $fieldName . ' : ' . $error->getMessage());
            }
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $activeReservations = $book->getReservations()->filter(fn($reservation) => $reservation->isActive());

        if (!$activeReservations->isEmpty()) {
            $this->addFlash('error', 'Impossible de supprimer ce livre : il est lié à une réservation active.');
            return $this->redirectToRoute('app_book_index');
        }

        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Opération réussie!');
        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }
}
