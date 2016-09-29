<?php
        date_default_timezone_set('America/Los_Angeles');
        require_once __DIR__."/../vendor/autoload.php";
        require_once __DIR__."/../src/Book.php";
        require_once __DIR__."/../src/Author.php";
        require_once __DIR__."/../src/Patron.php";
        require_once __DIR__."/../src/Copy.php";

        use Symfony\Component\Debug\Debug;
        Debug::enable();
        $app = new Silex\Application();
        $app['debug'] = true;

        $server = 'mysql:host=localhost;dbname=library';
        $username = 'root';
        $password = 'root';
        $DB = new PDO($server, $username, $password);

        use Symfony\Component\HttpFoundation\Request;
        Request::enableHttpMethodParameterOverride();

        $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
        ));

        $app->get("/", function() use ($app) {
          return $app['twig']->render("index.html.twig");
        });

        $app->get("/patron/{id}", function($id) use ($app) {
          $patron = Patron::find($id);
          return $app['twig']->render("patron.html.twig", array('books' => Book::getAll(), 'results' => array(), 'patron' =>$patron, 'checkouts' =>$patron->getCheckouts()));
        });

        $app->post("/patron", function() use ($app) {
          $patron = new Patron($_POST['name']);
          $patron->save();
          return $app['twig']->render("patron.html.twig", array('books' => Book::getAll(), 'results' => array(), 'patron' => $patron, 'checkouts' =>$patron->getCheckouts()));
        });

        $app->get("/admin/{patron_id}", function($patron_id) use ($app) {
          $patron = Patron::find($patron_id);
          return $app['twig']->render("admin.html.twig", array('books' => Book::getAll(), 'results' => array(), 'patron' =>$patron));;
        });

        $app->post("/search", function() use ($app) {
          $search_results = Book::searchBooks($_POST['search']);
          $patron_id = $_POST['patron'];
          $patron = Patron::find($patron_id);
          return $app['twig']->render("patron.html.twig", array('books' => Book::getAll(), 'results' => $search_results, 'patron' =>$patron, 'checkouts' =>$patron->getCheckouts()));
        });

        $app->post("/search_admin/{patron_id}", function($patron_id) use ($app) {
          $patron = Patron::find($patron_id);
          $search_results = Book::searchBooks($_POST['search']);
          return $app['twig']->render("admin.html.twig", array('books' => Book::getAll(), 'results' => $search_results, 'patron' => $patron));
        });

        $app->post("/new_book", function() use ($app) {
          $title = $_POST['title'];
          $genre = $_POST['genre'];
          $description = $_POST['description'];
          $new_book = new Book ($title, $genre, $description);
          $new_book->save();
          return $app['twig']->render("admin.html.twig", array('books' => Book::getAll(), 'results' => array()));
        });

        $app->get("/admin_book_page/{id}/{patron_id}", function($id, $patron_id) use ($app) {
          $book = Book::find($id);
          $patron = Patron::find($patron_id);
          return $app['twig']->render("adminBookPage.html.twig", array('book' => $book, 'authors' => $book->getAuthors(), 'patron' => $patron));
        });

        $app->post("/add_copies/{id}", function($id) use ($app) {
          $book = Book::find($id);
          $book->createCopies($_POST['number']);
          return $app['twig']->render("adminBookPage.html.twig", array('book' => $book, 'authors' => $book->getAuthors()));
        });

        $app->get("/book_page/{id}/{patron_id}", function($id, $patron_id) use ($app) {
          $book = Book::find($id);
          $patron = Patron::find($patron_id);
          return $app['twig']->render("bookPage.html.twig", array('book' => $book, 'authors' => $book->getAuthors(), 'patron' => $patron));
        });

        $app->post("/checkout/{id}/{patron_id}", function($id, $patron_id) use ($app) {
          $book = Book::find($id);
          $patron = Patron::find($patron_id);
          $copies = $book->getCopies();
          $copies[0]->checkOutBook($patron_id);
          return $app['twig']->render("patron.html.twig", array('results' => array(), 'books' => Book::getAll(), 'authors' => $book->getAuthors(), 'patron' => $patron, 'checkouts' =>$patron->getCheckouts()));
        });

        $app->patch("/update_book/{id}", function($id) use ($app) {
          $book = Book::find($id);
          $title = $_POST['title'];
          $genre = $_POST['genre'];
          $description = $_POST['description'];
          $book->update($title, $genre, $description);
          return $app['twig']->render("adminBookPage.html.twig", array('book' => $book, 'authors' => $book->getAuthors()));
        });


        $app->delete("/delete_book/{id}", function($id) use ($app) {
          $book = Book::find($id);
          $book->delete();
          return $app['twig']->render("admin.html.twig", array('books' => Book::getAll(), 'results' => array()));
        });

        $app->get("/delete_all_books", function() use ($app) {
          Book::deleteAll();
          return $app['twig']->render("admin.html.twig", array('books' => Book::getAll(), 'results' => array()));
        });

        $app->post("/new_author/{id}", function($id) use ($app) {
          $book = Book::find($id);
          $name = $_POST['name'];
          $author = new Author($name);
          $author->save();
          $book->addAuthor($author);
          return $app['twig']->render("adminBookPage.html.twig", array('book' => $book, 'authors' => $book->getAuthors()));
        });

    return $app;
?>
