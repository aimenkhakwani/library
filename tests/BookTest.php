<?php
        /**
        * @backupGlobals disabled
        * @backupStaticAttributes disabled
        */

        require_once "src/Book.php";
        require_once "src/Author.php";
        require_once "src/Copy.php";

        $server = 'mysql:host=localhost;dbname=library_test';
        $username = 'root';
        $password = 'root';
        $DB = new PDO($server, $username, $password);

        class BookTest extends PHPUnit_Framework_TestCase
        {
            protected function tearDown()
            {
                Book::deleteAll();
                Author::deleteAll();
            }
            function test_get_title()
            {
                //Arrange
                $title = "Harry Potter";
                $genre = "Fantasy";
                $description = "A great book";
                $test_book = new Book($title, $genre, $description);

                //Act
                $result = $test_book->getTitle();

                //Assert
                $this->assertEquals($title, $result);
            }

            function test_get_id()
            {
                //Arrange
                $title = "Harry Potter";
                $genre = "Fantasy";
                $description = "A great book";
                $id =  2;
                $test_book = new Book($title, $genre, $description, $id);

                //Act
                $result = $test_book->getId();

                //Assert
                $this->assertEquals(2, $result);
            }

            function test_set_title()
            {
              //Arrange
              $title = "Harry Potter";
              $genre = "Fantasy";
              $description = "A great book";
              $id =  2;
              $test_book = new Book($title, $genre, $description, $id);

              //Act
              $new_title = "Also Harry Potter";
              $test_book->setTitle($new_title);

              //Assert
              $this->assertEquals($new_title, $test_book->getTitle());
            }

            function test_set_genre()
            {
              //Arrange
              $title = "Harry Potter";
              $genre = "Fantasy";
              $description = "A great book";
              $id =  2;
              $test_book = new Book($title, $genre, $description, $id);

              //Act
              $new_genre = "Kid's fiction";
              $test_book->setGenre($new_genre);

              //Assert
              $this->assertEquals($new_genre, $test_book->getGenre());
            }

            function test_set_description()
            {
              //Arrange
              $title = "Harry Potter";
              $genre = "Fantasy";
              $description = "A great book";
              $id =  2;
              $test_book = new Book($title, $genre, $description, $id);

              //Act
              $new_description = "A classic";
              $test_book->setDescription($new_description);

              //Assert
              $this->assertEquals($new_description, $test_book->getDescription());

            }

            function test_save()
            {
                //Arrange
                $title = "Harry Potter";
                $genre = "Fantasy";
                $description = "A great book";
                $id =  2;
                $test_book = new Book($title, $genre, $description, $id);

                //Act
                $test_book->save();
                $result = Book::getAll();

                //Assert
                $this->assertEquals($test_book, $result[0]);
            }

            function test_get_all()
            {
                //Arrange
                $title = "Harry Potter";
                $genre = "Fantasy";
                $description = "A great book";
                $id =  2;
                $test_book = new Book($title, $genre, $description, $id);
                $title2 = "Harry Potter: And the Chamber of Secrets";
                $genre2 = "Fantasy2";
                $description2 = "A great book2";
                $test_book2 = new Book($title2, $genre2, $description2, $id);

                //Act
                $test_book->save();
                $test_book2->save();
                $result = Book::getAll();

                //Assert
                $this->assertEquals([$test_book, $test_book2], $result);
            }

            function test_delete_all()
            {
              //Arrange
              $title = "Harry Potter";
              $genre = "Fantasy";
              $description = "A great book";
              $id =  2;
              $test_book = new Book($title, $genre, $description, $id);
              $title2 = "Harry Potter2";
              $genre2 = "Fantasy2";
              $description2 = "A great book2";
              $test_book2 = new Book($title2, $genre2, $description2, $id);

              //Act
              $test_book->save();
              $test_book2->save();
              Book::deleteAll();
              $result = Book::getAll();

              //Assert
              $this->assertEquals([],$result);
            }

            function test_find()
            {
              //Arrange
              $title = "Harry Potter";
              $genre = "Fantasy";
              $description = "A great book";
              $test_book = new Book($title, $genre, $description);
              $test_book->save();

              $title2 = "Harry Potter2";
              $genre2 = "Fantasy2";
              $description2 = "A great book2";
              $test_book2 = new Book($title2, $genre2, $description2);
              $test_book2->save();

              //Act
              $id = $test_book->getId();
              $result = Book::find($id);

              //Assert
              $this->assertEquals($test_book, $result);
            }

            function test_delete()
            {
              //Arrange
              $title = "Harry Potter";
              $genre = "Fantasy";
              $description = "A great book";
              $test_book = new Book($title, $genre, $description);
              $test_book->save();

              $title2 = "Harry Potter2";
              $genre2 = "Fantasy2";
              $description2 = "A great book2";
              $test_book2 = new Book($title2, $genre2, $description2);
              $test_book2->save();

              //Act
              $test_book->delete();
              $result = Book::getAll();

              //Assert
              $this->assertEquals($test_book2, $result[0]);
            }

            function test_update()
            {
              //Arrange
              $title = "Harry Potter";
              $genre = "Fantasy";
              $description = "A great book";
              $id =  2;
              $test_book = new Book($title, $genre, $description, $id);

              //Act
              $new_title = "Nancy Drew";
              $new_genre = "Mystery";
              $new_description = "An oldie but a goodie";
              $test_book->update($new_title, $new_genre, $new_description);
              $rtitle = $test_book->getTitle();
              $rgenre = $test_book->getGenre();
              $rdescription = $test_book->getDescription();
              $result = array($rtitle, $rgenre, $rdescription);


              //Assert
              $this->assertEquals([$new_title, $new_genre, $new_description], $result);
            }

            function test_get_copies()
            {
                //Arrange
                $title = "Harry Potter";
                $genre = "Fantasy";
                $description = "A great book";
                $test_book = new Book($title, $genre, $description);
                $test_book->save();

                $checked_out = 0;
                $due_date = "2016-09-28";
                $book_id = $test_book->getId();
                $test_copy = new Copy($checked_out, $due_date, $book_id);
                $test_copy->save();

                $checked_out2 = 0;
                $due_date2 = "2016-09-28";
                $book_id2 = $test_book->getId();
                $id2 = 1;
                $test_copy2 = new Copy($checked_out2, $due_date2, $book_id2);
                $test_copy2->save();

                //Act
                $result = $test_book->getCopies();

                //Assert
                $this->assertEquals([$test_copy, $test_copy2], $result);
            }
        }
?>
