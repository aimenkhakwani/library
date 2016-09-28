<?php
        /**
        * @backupGlobals disabled
        * @backupStaticAttributes disabled
        */

        require_once "src/Patron.php";

        $server = 'mysql:host=localhost;dbname=library_test';
        $username = 'root';
        $password = 'root';
        $DB = new PDO($server, $username, $password);

        class PatronTest extends PHPUnit_Framework_TestCase
        {
            protected function tearDown()
            {
                Patron::deleteAll();
            }

            function test_set_name()
            {
                //Arrange
                $name = "Seth";
                $test_patron = new Patron($name);

                //Act
                $new_name = "Peter";
                $test_patron->setName($new_name);
                $result = $test_patron->getName();

                //Assert
                $this->assertEquals($new_name, $result);
            }

            function test_get_name()
            {
                //Arrange
                $name = "Seth";
                $test_patron = new Patron($name);

                //Act
                $result = $test_patron->getName();

                //Assert
                $this->assertEquals($name, $result);
            }

            function test_get_id()
            {
                //Arrange
                $name = "Seth";
                $id = 15;
                $test_patron = new Patron($name, $id);

                //Act
                $result = $test_patron->getId();

                //Assert
                $this->assertEquals(15, $result);
            }

            function test_save()
            {
                //Arrange
                $name = "Seth";
                $test_patron = new Patron($name);

                //Act
                $test_patron->save();
                $result = Patron::getAll();

                //Assert
                $this->assertEquals($test_patron, $result[0]);
            }

            function test_get_all()
            {
                //Arrange
                $name = "Seth";
                $test_patron = new Patron($name);
                $name2 = "Peter";
                $test_patron2 = new Patron($name2);

                //Act
                $test_patron->save();
                $test_patron2->save();
                $result = Patron::getAll();

                //Assert
                $this->assertEquals([$test_patron, $test_patron2], $result);
            }

            function test_delete_all()
            {
              //Arrange
              $name = "Seth";
              $test_patron = new Patron($name);
              $name2 = "Peter";
              $test_patron2 = new Patron($name2);

              //Act
              $test_patron->save();
              $test_patron2->save();
              Patron::deleteAll();
              $result = Patron::getAll();

              //Assert
              $this->assertEquals([],$result);
            }

            function test_find()
            {
              //Arrange
              $name = "Seth";
              $test_patron = new Patron($name);
              $test_patron->save();

              //Act
              $id = $test_patron->getId();
              $result = Patron::find($id);

              //Assert
              $this->assertEquals($test_patron, $result);
            }

            function test_delete()
            {
              //Arrange
              $name = "Seth";
              $test_patron = new Patron($name);
              $test_patron->save();
              $name2 = "Peter";
              $test_patron2 = new Patron($name2);
              $test_patron2->save();

              //Act
              $test_patron->delete();
              $result = Patron::getAll();

              //Assert
              $this->assertEquals([$test_patron2], $result);
            }

            function test_update()
            {
              //Arrange
              $name = "Seth";
              $test_patron = new Patron($name);
              $test_patron->save();
              $new_name = "Peter";

              //Act
              $test_patron->update($new_name);
              $result = $test_patron->getName();

              //Assert
              $this->assertEquals($new_name, $result);
            }
        }
?>
