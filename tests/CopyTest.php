<?php
        /**
        * @backupGlobals disabled
        * @backupStaticAttributes disabled
        */

        require_once "src/Copy.php";
        require_once "src/Book.php";

        $server = 'mysql:host=localhost;dbname=library_test';
        $username = 'root';
        $password = 'root';
        $DB = new PDO($server, $username, $password);

        class CopyTest extends PHPUnit_Framework_TestCase
        {
            protected function tearDown()
            {
                Copy::deleteAll();
                Book::deleteAll();
            }
            function test_get_checked_out_status()
            {
                //Arrange
                $checked_out = 0;
                $due_date = "2016-09-28";
                $book_id = null;
                $test_copy = new Copy($checked_out, $due_date, $book_id);

                //Act
                $result = $test_copy->getCheckedOutStatus();

                //Assert
                $this->assertEquals($checked_out, $result);
            }

            function test_get_id()
            {
                //Arrange
                $checked_out = 0;
                $due_date = "2016-09-28";
                $book_id = null;
                $id = 1;
                $test_copy = new Copy($checked_out, $due_date, $book_id, $id);

                //Act
                $result = $test_copy->getId();

                //Assert
                $this->assertEquals(1, $result);
            }

            function test_get_due_date()
            {
              //Arrange
              $checked_out = 0;
              $due_date = "2016-09-28";
              $book_id = null;
              $id = 1;
              $test_copy = new Copy($checked_out, $due_date, $book_id, $id);

              //Act
              $result = $test_copy->getDueDate();

              //Assert
              $this->assertEquals($due_date, $result);
          }

            function test_set_genre()
            {
              //Arrange
              $checked_out = 0;
              $due_date = "2016-09-28";
              $book_id = 1;
              $id = 1;
              $test_copy = new Copy($checked_out, $due_date, $book_id, $id);

              //Act
              $result = $test_copy->getBookId();

              //Assert
              $this->assertEquals($book_id, $result);
            }

            function test_save()
            {
                //Arrange
                $checked_out = 0;
                $due_date = "2016-09-28";
                $book_id = 1;
                $id = 1;
                $test_copy = new Copy($checked_out, $due_date, $book_id, $id);

                //Act
                $test_copy->save();
                $result = Copy::getAll();

                //Assert
                $this->assertEquals($test_copy, $result[0]);
            }

            function test_get_all()
            {
              //Arrange
              $checked_out = 0;
              $due_date = "2016-09-28";
              $book_id = 1;
              $id = 1;
              $test_copy = new Copy($checked_out, $due_date, $book_id, $id);

              $checked_out2 = 0;
              $due_date2 = "2016-09-28";
              $book_id2 = 1;
              $id2 = 1;
              $test_copy2 = new Copy($checked_out2, $due_date2, $book_id2, $id2);

              //Act
              $test_copy->save();
              $test_copy2->save();
              $result = Copy::getAll();

              //Assert
              $this->assertEquals([$test_copy, $test_copy2], $result);
            }

            function test_delete_all()
            {
              //Arrange
              $checked_out = 0;
              $due_date = "2016-09-28";
              $book_id = 1;
              $id = 1;
              $test_copy = new Copy($checked_out, $due_date, $book_id, $id);

              $checked_out2 = 0;
              $due_date2 = "2016-09-28";
              $book_id2 = 1;
              $id2 = 1;
              $test_copy2 = new Copy($checked_out2, $due_date2, $book_id2, $id2);

              //Act
              $test_copy->save();
              $test_copy2->save();
              Copy::deleteAll();
              $result = Copy::getAll();

              //Assert
              $this->assertEquals([], $result);
            }

            function test_find()
            {
              //Arrange
              $checked_out = 0;
              $due_date = "2016-09-28";
              $book_id = 1;
              $test_copy = new Copy($checked_out, $due_date, $book_id);

              $checked_out2 = 0;
              $due_date2 = "2016-09-28";
              $book_id2 = 1;
              $test_copy2 = new Copy($checked_out2, $due_date2, $book_id2);

              //Act
              $test_copy->save();
              $test_copy2->save();
              $id = $test_copy->getId();
              $result = Copy::find($id);

              //Assert
              $this->assertEquals($test_copy, $result);
            }

            function test_delete()
            {
                //Arrange
                $checked_out = 0;
                $due_date = "2016-09-28";
                $book_id = 1;
                $test_copy = new Copy($checked_out, $due_date, $book_id);

                $checked_out2 = 0;
                $due_date2 = "2016-09-28";
                $book_id2 = 1;
                $test_copy2 = new Copy($checked_out2, $due_date2, $book_id2);

                //Act
                $test_copy->save();
                $test_copy2->save();
                $test_copy->delete();
                $result = Copy::getAll();

                //Assert
                $this->assertEquals([$test_copy2], $result);
            }

            function test_check_out_book()
            {
                //Arrange
                $title = "Harry Potter";
                $genre = "Fantasy";
                $description = "A great book";
                $test_book = new Book($title, $genre, $description);
                $test_book->save();

                $patron_id = 3;

                $checked_out = 0;
                $due_date = "2016-09-28";
                $book_id = $test_book->getId();
                $test_copy = new Copy($checked_out, $due_date, $book_id);
                $test_copy->save();

                //Act
                $test_copy->checkOutBook($patron_id);
                $result = $test_copy->getCheckedOutStatus();

                $this->assertEquals(1, $result);
            }

            function test_check_in_book()
            {
                //Arrange
                $title = "Harry Potter";
                $genre = "Fantasy";
                $description = "A great book";
                $test_book = new Book($title, $genre, $description);
                $test_book->save();

                $patron_id = 3;

                $checked_out = 0;
                $due_date = "2016-09-28";
                $book_id = $test_book->getId();
                $test_copy = new Copy($checked_out, $due_date, $book_id);
                $test_copy->save();

                //Act
                $test_copy->checkInBook($patron_id);
                $result = $test_copy->getCheckedOutStatus();

                $this->assertEquals(0, $result);
            }
        }
?>
