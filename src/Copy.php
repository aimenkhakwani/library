<?php
    class Copy
    {
        private $checked_out;
        private $due_date;
        private $book_id;
        private $id;

        function __construct($checked_out, $due_date, $book_id, $id=null)
        {
            $this->checked_out = $checked_out;
            $this->due_date = date_create($due_date);
            $this->book_id = $book_id;
            $this->id = $id;
        }

        function getCheckedOutStatus()
        {
            return $this->checked_out;
        }

        function getId()
        {
            return $this->id;
        }

        function getDueDate()
        {
            return date_format($this->due_date, 'Y-m-d');
        }

        function getBookId()
        {
            return $this->book_id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO copies (checked_out, due_date, book_id) VALUES ({$this->getCheckedOutStatus()}, '{$this->getDueDate()}',{$this->getBookId()});");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM checkouts WHERE copy_id = {$this->getId()};");
        }

        // Maybe come back for book renewal
        // function update($new_name)
        // {
        //     $GLOBALS['DB']->exec("UPDATE patrons SET name = '{$new_name}' WHERE id = {$this->getId()};");
        //     $this->setName($new_name);
        // }

        function checkInCopy()
        {
            //allows librarian to check in a returned copy of one book
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
                return $found_patron;
            }
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
    }
?>
