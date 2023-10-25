<?php
    namespace App\Model\Entity;
    use App\DatabaseManager\Database;

    class Message{
        public $id_message;
        public $outgoing_id;
        public $incoming_id;
        public $message;
        public $image;
        public $audio;
        public $created_at;
        public $updated_at;
        public $deleted_at;

         
        public  function cadastrar(){
            $this->id_message = (new Database('messages'))->insert([
                'outgoing_id'               =>  $this->outgoing_id,
                'incoming_id'               =>  $this->incoming_id,
                'message'                   =>  $this->message,
                'image'                     =>  $this->image,
                'audio'                     =>  $this->audio,
                'created_at'                =>  $this->created_at,
                'updated_at'                =>  $this->updated_at,
                'deleted_at'                =>  $this->deleted_at,
            ]);
            return true;
        }
    
        public static function getMessages($where = null, $order = null, $limit = null, $fields = "*"){
            return (new Database('messages'))->select($where, $order, $limit, $fields);
        }

        public static function getMessageById($id){
            return self::getMessages('id_message = '.$id)->fetchObject(self::class);
        }

        public  function actualizar(){
            return (new Database('messages'))->update('id_message = '.$this->id_message, [
                'outgoing_id'               =>  $this->outgoing_id,
                'incoming_id'               =>  $this->incoming_id,
                'message'                   =>  $this->message,
                'image'                     =>  $this->image,
                'audio'                     =>  $this->audio,
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