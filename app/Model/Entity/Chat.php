<?php
    namespace App\Model\Entity;
    use App\DatabaseManager\Database;

    class Chat{
        public $id_chat;
        public $outgoing_pk;
        public $incoming_pk;
        public $outgoing_id;
        public $incoming_id;
        public $created_at;
        public $updated_at;
        public $deleted_at;

         
        public  function cadastrar(){
            $this->id_chat = (new Database('chats'))->insert([
                'outgoing_pk'               =>  $this->outgoing_pk,
                'incoming_pk'               =>  $this->incoming_pk,
                'outgoing_id'               =>  $this->outgoing_id,
                'incoming_id'               =>  $this->incoming_id,
                'created_at'                =>  $this->created_at,
                'updated_at'                =>  $this->updated_at,
                'deleted_at'                =>  $this->deleted_at,
            ]);
            return true;
        }
    
        public static function getChats($where = null, $order = null, $limit = null, $fields = "*"){
            return (new Database('chats'))->select($where, $order, $limit, $fields);
        }

        public static function getChatById($id){
            return self::getChats('id_chat = '.$id)->fetchObject(self::class);
        }

        public  function actualizar(){
            return (new Database('chats'))->update('id_chat = '.$this->id_chat, [
                'outgoing_pk'               =>  $this->outgoing_pk,
                'incoming_pk'               =>  $this->incoming_pk,
                'outgoing_id'               =>  $this->outgoing_id,
                'incoming_id'               =>  $this->incoming_id,
                'created_at'                =>  $this->created_at,
                'updated_at'                =>  $this->updated_at,
                'deleted_at'                =>  $this->deleted_at,
            ]);
            return true;
        }

        public function excluir(){
            return (new Database('messages'))->delete('id_message = '.$this->id_message);
        }

    }

?>