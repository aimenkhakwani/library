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
          return $app['twig']->render("index.html.twig", array('books' => Book::getAll(), 'results' => array()));
        });

        $app->get("/admin", function() use ($app) {
          return $app['twig']->render("admin.html.twig", array('books' => Book::getAll(), 'results' => array()));;
        });

        $app->post("/search", function() use ($app) {
          $search_results = Book::searchBooks($_POST['search']);
          return $app['twig']->render("index.html.twig", array('books' => Book::getAll(), 'results' => $search_results));
        });

        $app->post("/search_admin", function() use ($app) {
          $search_results = Book::searchBooks($_POST['search']);
          return $app['twig']->render("admin.html.twig", array('books' => Book::getAll(), 'results' => $search_results));
        });

        $app->post("/new_book", function() use ($app) {
          $title = $_POST['title'];
          $genre = $_POST['genre'];
          $description = $_POST['description'];
          $new_book = new Book ($title, $genre, $description);
          $new_book->save();
          return $app['twig']->render("admin.html.twig", array('books' => Book::getAll(), 'results' => array()));
        });

        $app->get("/admin_book_page/{id}", function($id) use ($app) {
          $book = Book::find($id);
          // var_dump($book);
          return $app['twig']->render("adminBookPage.html.twig", array('book' => $book, 'authors' => $book->getAuthors()));
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
