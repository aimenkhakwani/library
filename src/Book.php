<?php
    class Book
    {
        private $title;
        private $genre;
        private $description;
        private $id;

        function __construct($title, $genre, $description, $id = null)
        {
            $this->title = $title;
            $this->genre = $genre;
            $this->description = $description;
            $this->id = $id;
        }

        function setTitle($new_title)
        {
            $this->title = (string) $new_title;
        }

        function getTitle()
        {
            return $this->title;
        }

        function setGenre($new_genre)
        {
            $this->genre = (string) $new_genre;
        }

        function getGenre()
        {
            return $this->genre;
        }

        function setDescription($new_description)
        {
            $this->description = (string) $new_description;
        }

        function getDescription()
        {
            return $this->description;
        }

        function getId()
        {
            return $this->id;
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

        function update($new_title, $new_genre, $new_description)
        {
            $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("UPDATE books SET genre = '{$new_genre}' WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("UPDATE books SET description = '{$new_description}' WHERE id = {$this->getId()};");
            $this->setTitle($new_title);
            $this->setGenre($new_genre);
            $this->setDescription($new_description);
        }

        function createCopies($number, $book)
        {
          //how many copies on book create
        }

        function numberOfCopies()
        {
          //how many copies on book create
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
