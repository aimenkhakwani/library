<?php
    class Patron
    {
        private $name;
        private $id;

        function __construct($name, $id=null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO patrons (name) VALUES ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM checkouts WHERE patron_id = {$this->getId()};");
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE patrons SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        function getCheckouts()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT books.* FROM patrons
                JOIN checkouts ON (checkouts.patron_id = patrons.id)
                JOIN copies ON (copies.id = checkouts.copy_id)
                JOIN books ON (books.id = copies.book_id)
                WHERE patrons.id = {$this->getId()};");
            $books = array();
            foreach ($returned_books as $book){
                $title = $book['title'];
                $genre = $book['genre'];
                $description = $book['description'];
                $id = $book['id'];
                $new_book = new Book($title, $genre, $description, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function find($search_id)
        {
            $found_patron = null;
            $patrons = Patron::getAll();
            foreach($patrons as $patron) {
                $patron_id = $patron->getId();
                if ($patron_id == $search_id){
                    $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        static function getAll()
        {
            $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons;");
            $patrons = array();
            foreach($returned_patrons as $patron) {
                $name = $patron['name'];
                $id = $patron['id'];
                $new_patron = new Patron($name, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons;");
        }

        static function lastId()
        {
          return $GLOBALS['DB']->lastInsertId();
        }
    }
?>
