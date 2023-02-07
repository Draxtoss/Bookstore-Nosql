<?php
namespace App\Form;
namespace App\Controller;

use App\Document\Author;
use App\Document\Books;
use App\Form\BooksType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Count;
use Doctrine\ODM\MongoDB\Types\Type;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use MongoDB\BSON\Regex;

class BooksController extends AbstractController
{

    /**
     * @Route ("/books/CreateTester",name="AddBooksTester", methods={"GET", "POST"})
     */
    public function createAction(DocumentManager $dm)
    {

        $book = new Books();
        $book->setTitle('No Longer Human 22');
        $book->setPages(336);
        $book->setPublicationDate(new \DateTime('1948-09-01'));
        $book->setGenre('Novel');
        $author = new Author();
        $author->setName('Osamu Dazai 55');
        $author->setSexe('Male');
        $author->setBirthDate(new \DateTime('1909-09-19'));
        $author->setNationality('Japanese');
        $book->setAuthor($author);
        var_dump($book);
        $dm->persist($book);
        $dm->flush();
        return new JsonResponse(array('Status' => 'OK'));

    }

    /**
     * @Route("/books/add", name="books_add")
     */
    public function add(DocumentManager $documentManager, Request $request, ValidatorInterface $validator)
    {
        $book = new Books();
        $form = $this->createForm(BooksType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentManager->persist($book);
            $documentManager->flush();

            return $this->redirectToRoute('books_list');
        }

        return $this->render('add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/books/affiche", name="books_list")
     */
    public function affiche(DocumentManager $documentManager)
    {
        $Books = $documentManager->getRepository(Books::class)->findAll();
        return $this->render("affiche.html.twig", ["books" => $Books,'error_message'=>'']);
    }
    /**
     * @Route("/Author/affiche", name="Author_list")
     */
    public function afficheAuthor(DocumentManager $documentManager)
    {
        $data = [];
        //$Books = $documentManager->getRepository(Books::class)->findAll();
        $uniqueAuthors = $documentManager->createQueryBuilder(Books::class)
            ->distinct('Author.Name')
            ->sort('Author.Name', 'asc')
            ->getQuery()
            ->execute();
        foreach ($uniqueAuthors as $author) {
            $books = $documentManager->getRepository(Books::class)->findBy(['Author.Name' => $author]);
            foreach ($books as $book) {
                $data[] = [
                    'Name' => $book->getAuthor()->getName(),
                    'Sexe' => $book->getAuthor()->getSexe(),
                    'BirthDate' => $book->getAuthor()->getBirthDate()->format('d-m-Y'),
                    'Nationality' => $book->getAuthor()->getNationality()
                ];
            }
        }
        $unique = array_filter($data, function ($item) {
            static $authors = [];
            if (in_array($item['Name'], $authors)) {
                return false;
            }
            $authors[] = $item['Name'];
            return true;
        });
        //var_dump($unique);
        //return new JsonResponse(array('Status' => 'OK'));
        return $this->render('afficheAuthor.html.twig', ['data' => $unique]);
        //return $this->render("afficheAuthor.html.twig",["books"=>$authors]);
    }
    /**
     * @Route("/books/{id}/edit", name="edit_book")
     */
    public function editBookAction(string $id, Request $request, DocumentManager $documentManager)
    {
        $book = $documentManager->getRepository(Books::class)->find($id);
        $form = $this->createForm(BooksType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $documentManager->persist($book);
            $documentManager->flush();
            return $this->redirectToRoute('books_list');
        }
        return $this->render('book_edit_form.html.twig', [
            'form' => $form->createView(),
        ]);

    }
    /**
     * @Route("/books/{id}/delete", name="delete_book")
     */
    public function DeleteBookAction(string $id, Request $request, DocumentManager $documentManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        if (!$authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('redirect.html.twig', [
                'url' => $this->generateUrl('Index'),
                'time' => 5,
            ]);
        }
        $book = $documentManager->getRepository(Books::class)->find($id);
        $documentManager->remove($book);
        $documentManager->flush();
        return $this->redirectToRoute('books_list');
    }
    /**
     * @Route("/Index", name="Index")
     */
    public function Index()
    {
        return $this->render('Index.html.twig');
    }
    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request, DocumentManager $documentManager)
    {
        $searchQuery = $request->query->get('search');
        $books = $documentManager->getRepository(Books::class)->findBy(['Title' => $searchQuery]);
        //$searchQuery = "longer";
        //$regex = new \MongoRegex("/.*$searchQuery*./i");
        //$books = $documentManager->getRepository(Books::class)->findBy(['Title' => $regex]);
        return $this->render('search_results.html.twig', ['books' => $books,'searchQuery' => $searchQuery]);
    }
    /**
     * @Route("/Author/{authorName}/books", name="AuthorsBooks")
     */
    public function AuthorsBooks(string $authorName, Request $request, DocumentManager $documentManager)
    {
        $books = $documentManager->getRepository(Books::class)->findBy(['Author.Name' => $authorName]);
        return $this->render('authors.html.twig', ['books' => $books]);
    }
    /**
     * @Route("/", name="index")
     */
    public function Indexx()
    {
        return $this->render('Index.html.twig');
    }
   // /**
   // * @Route("/author/{name}/edit", name="editAuthor")
   // */
   //public function EditAuthor(String $name,Request $request, DocumentManager $documentManager,AuthorizationCheckerInterface $authorizationChecker){
   //     if (!$authorizationChecker->isGranted('ROLE_ADMIN')) {
   //         return $this->render('redirect.html.twig', [
   //             'url' => $this->generateUrl('Index'),
   //             'time' => 5,
   //         ]);
   //     }
   //     $author =$documentManager->getRepository(Author::class)->findBy(['Author.Name' => $name]);
//
   //    $form = $this->createForm(AuthorType::class, $author);
   //    $form->handleRequest($request);
   //    if ($form->isSubmitted() && $form->isValid()) {
   //     $books = $documentManager->getRepository(Book::class)->findBy(['Author.Name' => $name]);
   //     foreach ($books as $book) 
   //     {
   //         $book->setAuthor($author);
   //         $documentManager->persist($book);
   //         $documentManager->flush();
   //     }
   //        return $this->redirectToRoute('Author_list');
   //    }
   //    return $this->render('updateAuthor.html.twig', [
   //        'form' => $form->createView(),
   //    ]);
   //}
    /**
     * @Route("/books/{id}/rent", name="books_rent")
     */
   public function rentBook(string $id, DocumentManager $documentManager, Request $request)
   {
       $book = $documentManager->getRepository(Books::class)->find($id);
       $Books = $documentManager->getRepository(Books::class)->findAll();

       $UserIdentifier = $this->getUser()->getUserIdentifier();

       $userId = $request->request->get('user_id');
       if(!$UserIdentifier){return $this->render("affiche.html.twig", ["books" => $Books,"error_message"=>'user log in is required']);
       }

       // check if the book is already rented
       if($book->getRented()){return $this->render("affiche.html.twig", ["books" => $Books,"error_message"=>'Book already rented']);
       }

       // update the book and set it as rented

       $book->setRented(true);
       $book->setRentedBy($UserIdentifier);
       $documentManager->flush();
       return $this->render("affiche.html.twig", ["books" => $Books,"error_message"=>'']);
   }
       /**
     * @Route("/books/{id}/return", name="books_return")
     */
   public function returnBook(string $id, DocumentManager $documentManager, Request $request)
   {
       $book = $documentManager->getRepository(Books::class)->find($id);
       $Books = $documentManager->getRepository(Books::class)->findAll();
       $UserIdentifier = $this->getUser()->getUserIdentifier();
       if(!$UserIdentifier){return $this->render("affiche.html.twig", ["books" => $Books,"error_message"=>'You have to be logged in']);

       }

       // check if the book is rented
       if(!$book->getRented()){return $this->render("affiche.html.twig", ["books" => $Books,"error_message"=>'This book is not rented']);
       }

       // check if the book is rented by the current user
       if($book->getRentedBy() != $UserIdentifier){return $this->render("affiche.html.twig", ["books" => $Books,"error_message"=>'You are not the user that rented this book!!!!!']);
       }

       // update the book and set it as not rented
       $book->setRented(false);
       $book->setRentedBy(null);
       $documentManager->flush();
       
       return $this->render("affiche.html.twig", ["books" => $Books,"error_message"=>'']);
   }
          /**
     * @Route("/books/rented", name="books_rented")
     */
   public function getRentedBooks(DocumentManager $documentManager, Request $request)
   {
    $UserIdentifier = $this->getUser()->getUserIdentifier();
    if(!$UserIdentifier){return $this->render('redirect.html.twig', [
        'url' => $this->generateUrl('Index'),
        'time' => 5,
    ]);

    }

       $books = $documentManager->getRepository(Books::class)->findBy(['RentedBy' => $UserIdentifier]);
       return $this->render('affiche.html.twig', ['books' => $books,"error_message"=>'']);
   }


}
