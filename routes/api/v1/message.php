<?php
    use App\Http\Response;
    use App\Controller\Api;

    $objRouter->get('/api/v1/messages', [
        'middlewares' => [
            'api'
        ],
        function ($request){
            return new Response(200, Api\Message::getMessages($request), 'application/json');
        }
    ]);

     //CADASTRO DE USERS USANDO API
     $objRouter->post('/api/v1/messages', [
        'middlewares' => [
            'api',
            //'user-basic-auth'
        ],
        function ($request){
            return new Response(201, Api\Message::setNewMessage($request), 'application/json');
        }
    ]);

    /*$objRouter->get('/api/v1/users', [
        'middlewares' => [
            'api'
        ],
        function ($request){
            return new Response(200, Api\Users::getUsers($request), 'application/json');
        }
    ]);

    $objRouter->get('/api/v1/users/{id}', [
        'middlewares' => [
            'api'
        ],
        function ($request, $id){
            return new Response(200, Api\Users::getUser($request, $id), 'application/json');
        }
    ]);

    //Buusca utilizador pelo telefone
    $objRouter->get('/api/v1/phone/{telefone}', [
        'middlewares' => [
            'api'
        ],
        function ($request, $telefone){
            return new Response(200, Api\Users::getUserByPhone($request, $telefone), 'application/json');
        }
    ]);

    //CADASTRO DE USERS USANDO API
    $objRouter->post('/api/v1/users', [
        'middlewares' => [
            'api',
            //'user-basic-auth'
        ],
        function ($request){
            return new Response(201, Api\Users::setNewUser($request), 'application/json');
        }
    ]);

    //EDICAO DE USERS USANDO API
    $objRouter->put('/api/v1/users/{id}', [
        'middlewares' => [
            'api',
            //'user-basic-auth'
        ],
        function ($request, $id){
            return new Response(201, Api\Users::setEditUser($request, $id), 'application/json');
        }
    ]);
     //Exclusao DE USERS USANDO API
     $objRouter->delete('/api/v1/users/{id}', [
        'middlewares' => [
            'api',
            //'user-basic-auth'
        ],
        function ($request, $id){
            return new Response(201, Api\Users::setDeleteUser($request, $id), 'application/json');
        }
    ]);*/
?>