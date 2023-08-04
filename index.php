<?php
    require __DIR__.'/includes/app.php';
    use App\Http\Router;

    $objRouter = new Router(URL);
        //inclusao das rotas de api
        include __DIR__.'/routes/api.php';

    $objRouter->run()
              ->sendResponse();
?>