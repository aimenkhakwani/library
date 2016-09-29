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

        function addAuthor($new_author)
        {
            $GLOBALS['DB']->exec("INSERT INTO books_authors (book_id, author_id) VALUES ({$this->getId()}, {$new_author->getId()});");

        }

        function getAuthors()
        {
            $returned_authors = $GLOBALS['DB']->query("SELECT authors.* FROM books
                JOIN books_authors ON (books_authors.book_id = books.id)
                JOIN authors ON (authors.id = books_authors.author_id)
                WHERE books.id = {$this->getId()};");
            $authors = array();
            foreach ($returned_authors as $author){
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
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

        function createCopies($number)
        {
            for ($i=1; $i <= $number; $i++) {
                $checked_out = 0;
                $book_id = $this->getId();
                $new_copy = new Copy($checked_out, $due_date=null, $book_id);
                $new_copy->save();
            }
        }

        function getCopiesNumber()
        {
            $copies = array();
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies WHERE book_id = {$this->getId()} AND checked_out = 0;");
            foreach($returned_copies as $copy){
                $checked_out = $copy['checked_out'];
                $due_date = $copy['due_date'];
                $book_id = $copy['book_id'];
                $id = $copy['id'];
                $new_copy = new Copy($checked_out, $due_date, $book_id, $id);
                array_push($copies, $new_copy);
            }
            $return_value = count($copies);
            return $return_value;
        }

        function getCopies()
        {
            $copies = array();
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies WHERE book_id = {$this->getId()} AND checked_out = 0;");
            foreach($returned_copies as $copy){
                $checked_out = $copy['checked_out'];
                $due_date = $copy['due_date'];
                $book_id = $copy['book_id'];
                $id = $copy['id'];
                $new_copy = new Copy($checked_out, $due_date, $book_id, $id);
                array_push($copies, $new_copy);
            }
            return $copies;
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
            }
            return $found_book;
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

        static function searchBooks($new_search)
        {
            $search_results = array();
            $returned_books = Book::getAll();
            foreach($returned_books as $book){
                if ($new_search == $book->getTitle()) {
                  array_push($search_results, $book);
                } else {
                  $returned_authors = $book->getAuthors();
                  foreach($returned_authors as $author){
                      if ($new_search == $author->getName()) {
                        array_push($search_results, $book);
                      }
                  }
                }
            }
            if (empty($search_results)) {
              $search_results = "none";
            }
            return $search_results;
        }
    }
?>
