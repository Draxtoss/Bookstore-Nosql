<?php
namespace App\Form;
namespace App\Controller;

use App\Document\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\Books;
class ApiController extends AbstractController
{
    /**
     * @Route("/api/books", name="api_books", methods={"GET"})
     */
    public function getBooks(DocumentManager $documentManager)
    {
        $Books = $documentManager->getRepository(Books::class)->findAll();
        foreach($Books as $books){
            $res[] = ['Title'=>$books->getTitle(),
            'PublicationDate'=>$books->getPublicationDate(),
            'Genre'=>$books->getGenre(),
            'Pages'=>$books->getPages(),
            'Author'=> ['Name' => $books->getAuthor()->getName(), 'BirthDate' => $books->getAuthor()->getBirthDate()]];
        }
        return new JsonResponse(['books' => $res]);
    }


    /**
     * @Route("/api/books/{id}", name="api_books_get", methods={"GET"})
     */
    public function getBook($id, DocumentManager $documentManager)
    {
        $book = $documentManager->getRepository(Books::class)->find($id);
        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], 404);
        }
        $res[] = ['Title'=>$book->getTitle(),
        'PublicationDate'=>$book->getPublicationDate(),
        'Genre'=>$book->getGenre(),
        'Pages'=>$book->getPages(),
        'Author'=> ['Name' => $book->getAuthor()->getName(), 'BirthDate' => $book->getAuthor()->getBirthDate()]];
        return new JsonResponse($res);
    }

    /**
     * @Route("/api/books", name="api_books_create", methods={"POST"})
     */
    public function createBook(Request $request, DocumentManager $documentManager)
    {
        $data = json_decode($request->getContent(), true);
        $book = new Books();
        $book->setTitle($data['Title']);
        $book->setAuthor($data['Author']);
        $book->setPages($data['Pages']);
        $documentManager->persist($book);
        $documentManager->flush();
        return new JsonResponse($book, 201);
    }
    /**
     * @Route("/api/books/{id}", name="api_books_update", methods={"PUT"})
     */
    public function updateBook($id, Request $request, DocumentManager $documentManager)
    {
        $book = $documentManager->getRepository(Books::class)->find($id);
        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], 404);
        }
        $data = json_decode($request->getContent(), true);
        $book->setTitle($data['Title']);
        $book->setAuthor($data['Author']);
        $book->setPages($data['Pages']);
        $documentManager->flush();
        return new JsonResponse($book);
    }

    /**
     * @Route("/api/books/{id}", name="api_books_delete", methods={"DELETE"})
     */
    public function deleteBook($id, DocumentManager $documentManager)
    {
        $book = $documentManager->getRepository(Books::class)->find($id);
        if (!$book) {
            return new JsonResponse(['error' => 'Book not found'], 404);
        }
        $documentManager->remove($book);
        $documentManager->flush();
        return new JsonResponse(null, 204);
    }
}