<?php
    namespace App\Controller\Api;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\Message as EntityMessage;

    class Message extends Api{

        private static function getMessageItens($request, &$objPagination){
            $itens = [];
            $quantidadeTotal = EntityMessage::getMessages(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $queryParams = $request->getQueryParams();
            $paginaActual = $queryParams['page'] ?? 1;

            $outgoing =  $queryParams['outgoing_id'];
            $incoming =  $queryParams['incoming_id'];
            $whereClouser = " outgoing_id = $outgoing AND incoming_id = $incoming OR outgoing_id = $incoming AND incoming_id = $outgoing ";

            $objPagination = new Pagination($quantidadeTotal, $paginaActual, $quantidadeTotal);


            $results = EntityMessage::getMessages($whereClouser, 'id_message', $objPagination->getLimit());

            While ($objMessage = $results->fetchObject(EntityMessage::class)){
                $itens[] = [
                    'outgoing_id'               =>  $objMessage->outgoing_id,
                    'incoming_id'               =>  $objMessage->incoming_id,
                    'message'                   =>  $objMessage->message,
                    'created_at'                =>  $objMessage->created_at,
                    'updated_at'                =>  $objMessage->updated_at,
                    'deleted_at'                =>  $objMessage->deleted_at,
                ];
            }
            return $itens;
        }


        //Metodo responsavel por armazenar uma conversa nova
        public static function setNewMessage($request){
            $postVars = $request->getPostVars();
            
            //Validacao dos campos obrigatorios
            if(!isset($postVars['message'])){
                throw new \Exception("O campo 'Mensagem' é obrigatorios.", 400);
            }

            //Cadastrando o user na bd
            $objMessage = new EntityMessage;
            $objMessage->outgoing_id         = $postVars['sender'];
            $objMessage->incoming_id         = $postVars['receiver'];
            $objMessage->message             = $postVars['message'];
            $objMessage->created_at          = date('Y-m-d H:i:s');
            $objMessage->updated_at          = date('Y-m-d H:i:s');
            $objMessage->deleted_at          = NULL;

            $objMessage->cadastrar();
            return [
                'success'       => 'Mensagem enviada'
            ];
        }

        public static function getMessages($request){
            return [
                'messages' => self::getMessageItens($request, $objPagination)
            ];  
        }

        //metodo responsavel por editar o user
       /* public static function setEditUser($request, $id){
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


        //metodo responsavel pela exclusao o user
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
                'telefone'      => $objUtilizador->telefone
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
        }*/
    }
?>