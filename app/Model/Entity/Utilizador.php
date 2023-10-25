<?php
    namespace App\Model\Entity;
    use App\DatabaseManager\Database;

    class Utilizador{
        public $id_user;
        public $nome;
        public $otp;
        public $telefone;
        public $imagem;
        public $public_key;
        public $user_token;
        public $user_connection_id;
        public $created_at;
        public $updated_at;
        public $deleted_at;

         
        public  function cadastrar(){
            $this->id_user = (new Database('users'))->insert([
                'nome'                      =>  $this->nome,
                'otp'                       =>  $this->otp,
                'telefone'                  =>  $this->telefone,
                'imagem'                    =>  $this->imagem,
                'public_key'                =>  $this->public_key,
                'created_at'                =>  $this->created_at,
                'updated_at'                =>  $this->updated_at,
                'deleted_at'                =>  $this->deleted_at,
            ]);
            return true;
        }
    
        public static function getUtilizadores($where = null, $order = null, $limit = null, $fields = "*"){
            return (new Database('users'))->select($where, $order, $limit, $fields);
        }

        public static function getUtilizadorById($id){
            return self::getUtilizadores('id_user = '.$id)->fetchObject(self::class);
        }

        public static function getUtilizadorByPhone($telefone){
            return self::getUtilizadores('telefone = '.$telefone)->fetchObject(self::class);
        }

        public  function actualizar(){
            return (new Database('users'))->update('id_user = '.$this->id_user, [
                'nome'                      =>  $this->nome,
                'otp'                       =>  $this->otp,
                'telefone'                  =>  $this->telefone,
                'imagem'                    =>  $this->imagem,
                'user_token'                =>  $this->user_token,
                'user_connection_id'        =>  $this->user_connection_id,
                'public_key'                =>  $this->public_key,
                'created_at'                =>  $this->created_at,
                'updated_at'                =>  $this->updated_at,
                'deleted_at'                =>  $this->deleted_at,
            ]);
            return true;
        }

        public function excluir(){
            return (new Database('users'))->delete('id_user = '.$this->id_user);
        }
    
    }

?>