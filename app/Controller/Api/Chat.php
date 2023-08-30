<?php
    namespace App\Controller\Api;
    use App\Utils\ViewManager;
    use App\DatabaseManager\Pagination;
    use App\Model\Entity\Chat as EntityChat;
    use App\Model\Entity\Utilizador as EntityUtilizador;

    class Chat extends Api{
        private static function getChatItens($request, &$objPagination){
            $itens = [];
            $quantidadeTotal = EntityChat::getChats(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            $queryParams = $request->getQueryParams();
            $paginaActual = $queryParams['page'] ?? 1;

            $my_id =  $queryParams['myID'];
            $whereClouser = " outgoing_pk = $my_id OR incoming_pk = $my_id";

            $objPagination = new Pagination($quantidadeTotal, $paginaActual, $quantidadeTotal);

            $results = EntityChat::getChats($whereClouser, 'id_chat desc', $objPagination->getLimit());

            While ($objChat = $results->fetchObject(EntityChat::class)){
                $incoming = EntityUtilizador::getUtilizadorById($objChat->incoming_pk);
                $outgoing = EntityUtilizador::getUtilizadorById($objChat->outgoing_pk);
                if($objChat->incoming_pk == $objChat->sender){
                    $itens[] = [
                        'id'                       => $objChat->id_chat,
                        'id_incoming'              => $objChat->incoming_pk,
                        'id_outgoing'              => $objChat->outgoing_pk,
                        'nome_incoming'            => $incoming->nome,
                        'nome_outgoing'            => $outgoing->nome,
                        'sender'                   => $objChat->sender,
                        'receiver'                 => $objChat->receiver,
                        'public_key_incoming'      => $incoming->public_key,
                        'public_key_outgoing'      => $outgoing->public_key,
                        'message'                  => $objChat->message
                    ];
                }else{
                    $itens[] = [
                        'id'                       => $objChat->id_chat,
                        'id_incoming'              => $objChat->incoming_pk,
                        'id_outgoing'              => $objChat->outgoing_pk,
                        'nome_incoming'            => $incoming->nome,
                        'nome_outgoing'            => $outgoing->nome,
                        'sender'                   => $objChat->sender,
                        'receiver'                 => $objChat->receiver,
                        'public_key_incoming'      => $outgoing->public_key,
                        'public_key_outgoing'      => $incoming->public_key,
                        'message'                  => $objChat->message
                    ];
                }
                
            }
            return $itens;
        }

        public static function getChat($request){
            return [
                'users' => self::getChatItens($request, $objPagination)
            ];
        }
    }
?>