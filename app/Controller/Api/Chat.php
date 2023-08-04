<?php
    namespace App\Controller\Api;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\Chat as EntityChat;

    class Chat extends Api{

        private static function getChatItens($request, &$objPagination){
            $itens = [];
            $quantidadeTotal = EntityChat::getChats(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $queryParams = $request->getQueryParams();
            $paginaActual = $queryParams['page'] ?? 1;

            $outgoing =  $queryParams['outgoing_id'];
            $incoming =  $queryParams['incoming_id'];
            $whereClouser = " outgoing_id = $outgoing AND incoming_id = $incoming OR outgoing_id = $incoming AND incoming_id = $outgoing ";

            $objPagination = new Pagination($quantidadeTotal, $paginaActual, $quantidadeTotal);


            $results = EntityChat::getChats($whereClouser, 'id_chat', $objPagination->getLimit());

            While ($objChat = $results->fetchObject(EntityChat::class)){
                $itens[] = [
                    'outgoing_pk'               =>  $objChat->outgoing_pk,
                    'incoming_pk'               =>  $objChat->incoming_pk,
                    'outgoing_id'               =>  $objChat->outgoing_id,
                    'incoming_id'               =>  $objChat->incoming_id,
                    'created_at'                =>  $objChat->created_at,
                    'updated_at'                =>  $objChat->updated_at,
                    'deleted_at'                =>  $objChat->deleted_at,
                ];
            }
            return $itens;
        }


        //Metodo responsavel por armazenar uma conversa nova
        public static function setNewChat($request){
            $postVars = $request->getPostVars();
            
            //Validacao dos campos obrigatorios
            if(!isset($postVars['outgoing_id']) && !isset($postVars['incoming_id'])){
                throw new \Exception("Os campos remetente e receptor sao obrigatorios.", 400);
            }

            //Cadastrando o user na bd
            $objChat = new EntityMessage;
            $objChat->outgoing_pk         = $postVars['outgoing_pk'];
            $objChat->incoming_pk         = $postVars['incoming_pk'];
            $objChat->outgoing_id         = $postVars['outgoing_id'];
            $objChat->incoming_id         = $postVars['incoming_id'];
            $objChat->created_at          = date('Y-m-d H:i:s');
            $objChat->updated_at          = date('Y-m-d H:i:s');
            $objChat->deleted_at          = NULL;

            $objChat->cadastrar();
            return [
                'success'       => 'Chat comecado com sucesso'
            ];
        }

        public static function getChat($request){
            return [
                'chat' => self::getChatItens($request, $objPagination)
            ];
        }
    }
?>