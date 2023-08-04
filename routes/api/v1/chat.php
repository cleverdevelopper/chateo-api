<?php
    use App\Http\Response;
    use App\Controller\Api;

    $objRouter->get('/api/v1/chat', [
        'middlewares' => [
            'api'
        ],
        function ($request){
            return new Response(200, Api\Chat::getChat($request), 'application/json');
        }
    ]);

     //CADASTRO DE USERS USANDO API
     $objRouter->post('/api/v1/chat', [
        'middlewares' => [
            'api',
            //'user-basic-auth'
        ],
        function ($request){
            return new Response(201, Api\Chat::setNewChat($request), 'application/json');
        }
    ]);

?>