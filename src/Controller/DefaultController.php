<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
//        $book = new Book();
//        $book->setIsbn('9782070752447');
//        $book->setTitle('Villa vortex');
//        $book->setSummary('11 septembre 2001, un nouveau monde commence...');
//        $book->setPublicationYear(2003);
//        $book->setCreatedAt(new \Datetime());
//        $book->setUpdatedAt(new \Datetime());

        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();
        }

        return $this->render('book/new.html.twig', [
            'form' => $form,
        ]);

//        $entityManager->persist($book);
//        $entityManager->flush();
//        return new Response('Identifiant du livre ajouté : ' . $book->getId());
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        //dd($book);
        $user = $book->getUser();
        if ($user === null) {
            $message = "[Disponible]";
        } else {
            $message = "[emprunté par " . $book->getUser()->getFirstname() . " le " . $book->getIssueDate()->format('d/m/Y') . "]";
        }
        $message = 'Livre "' . $book->getTitle() . '" ' .  $message;
        return new Response($message);
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

    #[Route('/user/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function createUser(EntityManagerInterface $entityManager, ValidatorInterface
    $validator): Response
    {
        $user = new User();
        $user->setEmail("john.doe@mailbox.com");
        $user->setPassword("password");
        $user->setRoles(["ROLE_USER"]);
        $user->setLastName("Doe");
        $user->setFirstName("John");
        $user->setAddress("Avenue du Maréchal Juin");
        $user->setZipCode("33000");
        $user->setBirthDate(new \DateTime());
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->render('user/validate.html.twig', array(
                'errors' => $errors,
            ));
        }
        $entityManager->persist($user);
        $entityManager->flush();
        return new Response('Identifiant du lecteur ajouté : ' . $user->getId());
    }

    #[Route('/{book}/{user}/loan', name: 'app_book_user_loan', methods: ['GET', 'POST'])]
    public function loan(Book $book, User $user, EntityManagerInterface $entityManager): Response
    {
        $book->setUser($user);
        $book->setIssueDate(new \DateTimeImmutable());
        $entityManager->flush();
        $message = 'Livre "' . $book->getTitle() . '" emprunté par ' . $user->getFirstName() . " le " . $book->getIssueDate()->format('d/m/Y');
        return new Response($message);
    }

}
