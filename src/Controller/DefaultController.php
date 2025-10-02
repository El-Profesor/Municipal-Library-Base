<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book')]
final class DefaultController extends AbstractController
{
    #[Route('/default', name: 'app_default')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $book->setIsbn('9782070752447');
        $book->setTitle('Villa vortex');
        $book->setSummary('11 septembre 2001, un nouveau monde commence...');
        $book->setPublicationYear(2003);
        $book->setCreatedAt(new \Datetime());
        $book->setUpdatedAt(new \Datetime());
        $entityManager->persist($book);
        $entityManager->flush();
        return new Response('Identifiant du livre ajouté : ' . $book->getId());
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        dd($book);
        return new Response('Livre consulté : ' . $book->getTitle());
    }

    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Book $book, EntityManagerInterface $entityManager): Response
    {
        $book->setSummary('Attention ! Ouvrir un roman de Dantec c\'est comme entrer en
religion...');
        $entityManager->flush();
        return new Response('Livre modifié : ' . $book->getTitle());
    }

    #[Route('/{id}/delete', name: 'app_book_delete', methods: ['GET'])]
    public function delete(Book $book, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($book);
        $entityManager->flush();
        return new Response('Identifiant du livre supprimé : ' . $book->getId());
    }
}
