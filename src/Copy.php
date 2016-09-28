<?php
    class Copy
    {
        private $checked_out;
        private $due_date;
        private $book_id;
        private $id;

        function __construct($checked_out, $due_date = null, $book_id, $id=null)
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

        function checkOutBook($patron_id)
        {
          $GLOBALS['DB']->exec("INSERT INTO checkouts (patron_id, copy_id) VALUES ({$patron_id}, {$this->getId()});");
          $GLOBALS['DB']->exec("UPDATE copies SET checked_out = 1 WHERE id = {$this->getId()};");
          $this->checked_out = 1;
        }

        function checkInBook()
        {
          $GLOBALS['DB']->exec("UPDATE copies SET checked_out = 0 WHERE id = {$this->getId()};");
          $this->checked_out = 0;
        }

        static function find($search_id)
        {
            $found_copy = null;
            $copies = Copy::getAll();
            foreach($copies as $copy) {
                $copy_id = $copy->getId();
                if ($copy_id == $search_id){
                    $found_copy = $copy;
                }
                return $found_copy;
            }
        }

        static function getAll()
        {
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
            $copies = array();
            foreach($returned_copies as $copy) {
                $checked_out = $copy['checked_out'];
                $due_date = $copy['due_date'];
                $book_id = $copy['book_id'];
                $id = $copy['id'];
                $new_copy = new Copy($checked_out, $due_date, $book_id, $id);
                array_push($copies, $new_copy);
            }
            return $copies;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies;");
        }
    }
?>
