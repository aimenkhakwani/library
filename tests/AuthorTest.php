<?php
        /**
        * @backupGlobals disabled
        * @backupStaticAttributes disabled
        */

        require_once "src/Author.php";
        require_once "src/Book.php";

        $server = 'mysql:host=localhost;dbname=library_test';
        $username = 'root';
        $password = 'root';
        $DB = new PDO($server, $username, $password);

        class CourseTest extends PHPUnit_Framework_TestCase
        {
            protected function tearDown()
            {
                Author::deleteAll();
                Book::deleteAll();
            }
            function test_get_name()
            {
                //Arrange
                $name = "Seth";
                $test_author = new Author($name);

                //Act
                $result = $test_author->getName();

                //Assert
                $this->assertEquals($name, $result);
            }

            function test_get_id()
            {
                //Arrange
                $name = "Seth";
                $id = 15;
                $test_author = new Author($name, $id);

                //Act
                $result = $test_author->getId();

                //Assert
                $this->assertEquals(15, $result);
            }

            function test_set_name()
            {
                //Arrange
                $name = "Seth";
                $id = 15;
                $test_author = new Author($name, $id);

                //Act
                $new_name = "Peter";
                $result = $test_author->setName($new_name);

                //Assert
                $this->assertEquals("Peter", $test_author->getName());
            }

            function test_save()
            {
                //Arrange
                $name = "Seth";
                $test_author = new Author($name);

                //Act
                $test_author->save();
                $result = Author::getAll();

                //Assert
                $this->assertEquals($test_author, $result[0]);
            }

            function test_get_all()
            {
                //Arrange
                $name = "Seth";
                $test_author = new Author($name);
                $name2 = "Peter";
                $test_author2 = new Author($name2);

                //Act
                $test_author->save();
                $test_author2->save();
                $result = Author::getAll();

                //Assert
                $this->assertEquals([$test_author, $test_author2], $result);
            }

            function test_delete_all()
            {
              //Arrange
              $name = "Seth";
              $test_author = new Author($name);
              $name2 = "Peter";
              $test_author2 = new Author($name2);

              //Act
              $test_author->save();
              $test_author2->save();
              Author::deleteAll();
              $result = Author::getAll();

              //Assert
              $this->assertEquals([],$result);
            }

            function test_find()
            {
              //Arrange
              $name = "Seth";
              $test_author = new Author($name);
              $test_author->save();

              //Act
              $id = $test_author->getId();
              $result = Author::find($id);

              //Assert
              $this->assertEquals($test_author, $result);
            }

            function test_delete()
            {
              //Arrange
              $name = "Seth";
              $test_author = new Author($name);
              $test_author->save();
              $name2 = "Peter";
              $test_author2 = new Author($name2);
              $test_author2->save();

              //Act
              $test_author->delete();
              $result = Author::getAll();

              //Assert
              $this->assertEquals([$test_author2], $result);
            }

            function test_update()
            {
              //Arrange
              $name = "Seth";
              $test_author = new Author($name);
              $test_author->save();
              $new_name = "Peter";

              //Act
              $test_author->update($new_name);
              $result = $test_author->getName();

              //Assert
              $this->assertEquals($new_name, $result);
            }

            function test_add_book()
            {
              //Arrange
              $name = "Seth";
              $test_author = new Author($name);
              $test_author->save();

              $title = "Harry Potter";
              $genre = "Fantasy";
              $description = "A great book";
              $test_book = new Book($title, $genre, $description);
              $test_book->save();
              //Act
              $test_author->addBook($test_book);
              $result = $test_author->getBooks();
              //Assert
              $this->assertEquals([$test_book], $result);
            }

            function test_get_books()
            {
                //Arrange
                $name = "Seth";
                $test_author = new Author($name);
                $test_author->save();

                $title = "Harry Potter";
                $genre = "Fantasy";
                $description = "A great book";
                $test_book = new Book($title, $genre, $description);
                $test_book->save();

                $title2 = "Harry Potter: And the Chamber of Secrets";
                $genre2 = "Fantasy2";
                $description2 = "A great book2";
                $test_book2 = new Book($title2, $genre2, $description2);
                $test_book2->save();

                //Act
                $test_author->addBook($test_book);
                $test_author->addBook($test_book2);
                $result = $test_author->getBooks();

                //Assert
                $this->assertEquals([$test_book, $test_book2], $result);
            }
        }
?>
