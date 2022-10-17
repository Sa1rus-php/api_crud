<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private $bookRepository;

    /**
     * @param BookRepository $bookRepository
     */
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/book/add', name: 'add_books', methods: 'POST')]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $title = $data['title'] ?? 'Without title';
        $publisher = $data['publisher'] ?? 'Without publisher';
        $author = $data['author'] ?? 'Without author';
        $genre = $data['genre'] ?? 'Without genre';
        $date_publication = $data['date_publication'] ?? null;
        $amount_words = $data['amount_words'] ?? 0;
        $price = $data['price'] ?? 0;

        $this->bookRepository->saveBook($title, $publisher, $author, $genre, $date_publication, $price, $amount_words);

        return new JsonResponse(['status' => 'Book created!'], Response::HTTP_CREATED);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    #[Route('/book/get/{id}', name: 'get_books', methods: 'POST')]
    public function get($id): JsonResponse
    {
        $book = $this->bookRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'publisher' => $book->getPublisher(),
            'author' => $book->getAuthor(),
            'genre' => $book->getGenre(),
            'date_publication' => $book->getDatePublication(),
            'amount_words' => $book->getAmountWords(),
            'price' => $book->getPrice(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    #[Route('/book/get', name: 'get_all_books', methods: 'POST')]
    public function getAll(): JsonResponse
    {
        $books = $this->bookRepository->findAll();
        $data = [];

        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'publisher' => $book->getPublisher(),
                'author' => $book->getAuthor(),
                'genre' => $book->getGenre(),
                'date_publication' => $book->getDatePublication(),
                'amount_words' => $book->getAmountWords(),
                'price' => $book->getPrice(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/book/update/{id}', name: 'update_books', methods: 'PUT')]
    public function update($id, Request $request): JsonResponse
    {
        $book = $this->bookRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['title']) ? true : $book->setTitle($data['title']);
        empty($data['publisher']) ? true : $book->setPublisher($data['publisher']);
        empty($data['author']) ? true : $book->setAuthor($data['author']);
        empty($data['genre']) ? true : $book->setGenre($data['genre']);
        empty($data['date_publication']) ? true : $book->setDatePublication($data['date_publication']);
        empty($data['amount_words']) ? true : $book->setAmountWords($data['amount_words']);
        empty($data['price']) ? true : $book->setPrice($data['price']);

        $updatedBook = $this->bookRepository->updateBook($book);

        return new JsonResponse([
            'status' => 'Book updated!',
            'id' => $updatedBook->getId()
        ],
            Response::HTTP_OK);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    #[Route('/book/delete/{id}', name: 'delete_books', methods: 'DELETE')]
    public function delete($id): JsonResponse
    {
        $book = $this->bookRepository->findOneBy(['id' => $id]);

        $this->bookRepository->removeBook($book);

        return new JsonResponse(['status' => 'Book deleted'], Response::HTTP_OK);
    }
}
