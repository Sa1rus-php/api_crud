<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @param $title
     * @param $publisher
     * @param $author
     * @param $genre
     * @param $date_publication
     * @param $price
     * @param $amount_words
     * @return void
     */
    public function saveBook($title, $publisher, $author, $genre, $date_publication, $price, $amount_words)
    {
        $newBook = new Book();

        $newBook
            ->setTitle($title)
            ->setPublisher($publisher)
            ->setAuthor($author)
            ->setGenre($genre)
            ->setDatePublication($date_publication)
            ->setPrice($price)
            ->setAmountWords($amount_words);

        $this->getEntityManager()->persist($newBook);

        $this->getEntityManager()->flush();
    }

    /**
     * @param Book $book
     * @return Book
     */
    public function updateBook(Book $book): Book
    {
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();

        return $book;
    }

    /**
     * @param Book $book
     * @return Book
     */
    public function removeBook(Book $book): Book
    {
        $this->getEntityManager()->remove($book);
        $this->getEntityManager()->flush();

        return $book;
    }
}
