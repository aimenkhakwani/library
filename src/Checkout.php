<?php
    class Checkout
    {
        private $patron_id;
        private $copy_id;
        private $due_date;
        private $id;

        function __construct($patron_id, $copy_id, $due_date, $id = null)
        {
            $this->title = $title;
            $this->genre = $genre;
            $this->description = $description;
            $this->id = $id;
        }

        function getCopyId()
        {
            return $this->genre;
        }

        function getPatronId()
        {
            return $this->description;
        }

        function getId()
        {
            return $this->id;
        }

        function getDueDate()
        {
            return $this->id;
        }

        function setDueDate()
        {
          
        }

        function save()
        {
          $GLOBALS['DB']->exec("INSERT INTO books (title, genre, description) VALUES ('{$this->getTitle()}', '{$this->getGenre()}', '{$this->getDescription()}');");
          $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM books_authors WHERE book_id = {$this->getId()};");
        }

        static function find($search_id)
        {
            $found_book = null;
            $books = Book::getAll();
            foreach($books as $book) {
                $book_id = $book->getId();
                if ($book_id == $search_id){
                    $found_book = $book;
                }
                return $found_book;
            }
        }

        static function getAll()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
            $books = array();
            foreach($returned_books as $book) {
                $title = $book['title'];
                $genre = $book['genre'];
                $description = $book['description'];
                $id = $book['id'];
                $new_book = new Book($title, $genre, $description, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM books;");
        }
    }
?>
