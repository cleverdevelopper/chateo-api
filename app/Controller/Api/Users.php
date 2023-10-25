<?php
    namespace App\Controller\Api;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\Utilizador as EntityUtilizador;

    class Users extends Api{

        private static function getUtilizadorItens($request, &$objPagination){
            $itens = [];
            $quantidadeTotal = EntityUtilizador::getUtilizadores(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $queryParams = $request->getQueryParams();
            $paginaActual = $queryParams['page'] ?? 1;

            $objPagination = new Pagination($quantidadeTotal, $paginaActual, $quantidadeTotal);

            $results = EntityUtilizador::getUtilizadores(null, 'id_user', $objPagination->getLimit());

            While ($objUtilizador = $results->fetchObject(EntityUtilizador::class)){
                $itens[] = [
                    'id'            => $objUtilizador->id_user,
                    'public_key'    => $objUtilizador->public_key,
                    'name'          => $objUtilizador->nome,
                    'telefone'      => $objUtilizador->telefone,
                    'imagem'        => $objUtilizador->imagem,
                    'user_token'    => $objUtilizador->user_token,
                    'connectionId'  => $objUtilizador->user_connection_id,
                ];
            }
            return $itens;
        }

        //Metodo responsavel por cirar um novo utilizador
        public static function getProfileFoto($request){
            $imageFilename = $_GET['filename'];

            // Define the directory path where image files are stored
            $imageDirectory = 'ProfilePictures/';

            $imagePath = $imageDirectory . $imageFilename;

            // Check if the image file exists
            if (file_exists($imagePath)) {
                $pathInfo = pathinfo($imagePath);
                $imageExtension = $pathInfo['extension'];

                header('Content-Type: image/'.$imageExtension);

                readfile($imagePath);
            } else {
                // Return a placeholder image or an error message
                header('Content-Type: image/png'); // Placeholder image format
                readfile('path/to/placeholder/image.png'); // Replace with your placeholder image
            }
        }


        //Metodo responsavel por cirar um novo utilizador
        public static function setNewUser($request){
            $postVars = $request->getPostVars();
            
            //Validacao dos campos obrigatorios
            if(!isset($postVars['telefone'])){
                throw new \Exception("Os campos 'Telefone' é obrigatorios.", 400 );
            }

            //Cadastrando o user na bd
            $objUtilizador = new EntityUtilizador;
            $objUtilizador->otp                 = mt_rand(10000, 99999);
            $objUtilizador->nome                = $postVars['nome'];
            $objUtilizador->telefone            = $postVars['telefone'];
            $objUtilizador->public_key          = $postVars['public_key'];
            $objUtilizador->imagem              = 'avatar.png';
            $objUtilizador->created_at          = date('Y-m-d H:i:s');
            $objUtilizador->updated_at          = date('Y-m-d H:i:s');
            $objUtilizador->deleted_at          = NULL;


            $objUtilizador->cadastrar();
            return [
                'id_user'       => (int)$objUtilizador->id_user,
                'telefone'      => $objUtilizador->telefone
            ];
        }

        //metodo responsavel por editar o user
        public static function setEditUser($request, $id){
            $postVars = $request->getPostVars();
            
            //Validacao dos campos obrigatorios
            if(!isset($postVars['nome'])){
                throw new \Exception("O campo 'nome' é obrigatorio.", 400 );
            }

            //Buscar a existencia de um utilizdor
            $objUtilizador = EntityUtilizador::getUtilizadorById($id);

            if(!$objUtilizador instanceof EntityUtilizador){
                throw new \Exception('O utilizador '.$id.' não foi encontrado!', 404 );
            }

            //actualizaco do user na bd
            $objUtilizador->nome                = $postVars['nome'];
            $objUtilizador->public_key          = $postVars['public_key'];
            $objUtilizador->updated_at          = date('Y-m-d H:i:s');
            $objUtilizador->deleted_at          = NULL;

            $objUtilizador->actualizar();
            return [
                'id'            => $objUtilizador->id_user,
            ];
        }


        public static function setEditProfile($request, $id){
            $postVars = $request->getPostVars();
            
            //Validacao dos campos obrigatorios
            if(!isset($postVars['nome'])){
                throw new \Exception("O campo 'nome' é obrigatorio.", 400 );
            }

            //Buscar a existencia de um utilizdor
            $objUtilizador = EntityUtilizador::getUtilizadorById($id);

            if(!$objUtilizador instanceof EntityUtilizador){
                throw new \Exception('O utilizador '.$id.' não foi encontrado!', 404 );
            }

            //actualizaco do user na bd
            $objUtilizador->nome                = $postVars['nome'];
            $objUtilizador->updated_at          = date('Y-m-d H:i:s');
            $objUtilizador->deleted_at          = NULL;

            $objUtilizador->actualizar();
            return [
                'id'            => $objUtilizador->id_user,
            ];
        }


        public static function setProfilePicture ($request, $id){
            $objUtilizador = EntityUtilizador::getUtilizadorById($id);
            $file = $request->getFile();

            if(!$objUtilizador instanceof EntityUtilizador){
                throw new \Exception("Este utilizador nao existe", 400 );
            }

            $postVars = $request->getPostVars();
            $objUtilizador->imagem = $file ?? $objUtilizador->imagem;
            $objUtilizador->actualizar();

            return [
                'success'       => true
            ];
        }

        public static function setDeleteUser($request, $id){
            //Buscar a existencia de um utilizdor
            $objUtilizador = EntityUtilizador::getUtilizadorById($id);

            if(!$objUtilizador instanceof EntityUtilizador){
                throw new \Exception('O utilizador '.$id.' não foi encontrado!', 404 );
            }
            //elimina do user na bd
            $objUtilizador->excluir();
            
            return [
                'success'       => true
            ];
        }

        public static function getUsers($request){
            return [
                'users' => self::getUtilizadorItens($request, $objPagination)
            ];
        }

        public static function getUser($request, $id){
            if(!is_numeric($id)){
                throw new \Exception("O id '".$id."' não é valido!", 400 );
            }

            $objUtilizador = EntityUtilizador::getUtilizadorById($id);

            if(!$objUtilizador instanceof EntityUtilizador){
                throw new \Exception('O utilizador '.$id.' não foi encontrado!', 404 );
            }
            return [
                'id'            => $objUtilizador->id_user,
                'otp'           => $objUtilizador->otp,
                'name'          => $objUtilizador->nome,
                'telefone'      => $objUtilizador->telefone,
                'imagem'        => $objUtilizador->imagem,
            ];
        }

        public static function getUserByPhone($request, $telefone){
            
            $objUtilizador = EntityUtilizador::getUtilizadorByPhone($telefone);

            if(!$objUtilizador instanceof EntityUtilizador){
                throw new \Exception('O telefone '.$telefone.' não foi encontrado!', 404 );
            }
            return [
                'id'            => $objUtilizador->id_user,
                'otp'           => $objUtilizador->otp,
                'name'          => $objUtilizador->nome,
                'telefone'      => $objUtilizador->telefone
            ];
        }


        public static function updateToken($request, $id){
            $postVars = $request->getPostVars();

            $objUtilizador = EntityUtilizador::getUtilizadorById($id);
            
            //actualizaco do user na bd
            $objUtilizador->user_token              = $postVars['user_token'];
            $objUtilizador->user_connection_id      = $postVars['user_connection_id'];
            $objUtilizador->updated_at              = date('Y-m-d H:i:s');
            $objUtilizador->deleted_at               = NULL;

            $objUtilizador->actualizar();
        }
    }
?>