<?php
    namespace App\Http;

    class Request{
        private $httpMethod;
        private $uri;
        private $queryParams = [];
        private $postVars = [];
        private $headers = [];
        private $router;
        private $file;

        public function __construct($router)
        {
            $this->router          = $router;
            $this->httpMethod      = $_SERVER['REQUEST_METHOD'] ?? '';
            $this->queryParams     = $_GET ?? [];
            $this->setUri();
            $this->setPostVars();
            $this->headers         = getallheaders();
        }

        private function setPostVars(){
            if($this->httpMethod == 'GET') return false;

            //POST PADRAO
            $this->postVars   = $_POST ?? [];
            
            //POST JSON
            $inputRaw = file_get_contents('php://input');

            if(isset($_FILES['imagem'])){
                $img_name = $_FILES['imagem']['name'];
                $tmp_name = $_FILES['imagem']['tmp_name'];

                $time = time();
                $new_image_name = $time.$img_name;
                move_uploaded_file($tmp_name, "ProfilePictures/".$new_image_name);

                $this->file = $new_image_name;
                $this->postVars        = $_POST ?? [];

                $response = [
                    'success' => true,
                    'message' => 'Image uploaded successfully.'
                ];
                json_encode($response);

            }else{
                $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
            }


        } 


        /*private function setPostVars() {
            // Verifique se a requisição é POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Verifique se existe um arquivo enviado com o nome 'imagem'
                if (isset($_FILES['imagem'])) {
                    // Diretório onde você deseja armazenar as imagens criptografadas
                    $diretorioDestino = 'ProfilePictures/';

                    // Crie um nome único para a imagem usando um carimbo de data/hora
                    $time = time();
                    $img_name = $_FILES['imagem']['name'];
                    $new_image_name = $time . $img_name;

                    // Caminho completo para o arquivo de destino
                    $caminhoCompleto = $diretorioDestino . $new_image_name;

                    // Mova o arquivo enviado para o diretório de destino
                    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoCompleto)) {
                        // O arquivo foi armazenado com sucesso
                        $response = [
                            'success' => true,
                            'message' => 'Imagem enviada e armazenada com sucesso.',
                            'file_path' => $caminhoCompleto // Caminho completo da imagem no servidor
                        ];
                        echo json_encode($response);
                    } else {
                        // Ocorreu um erro ao mover o arquivo
                        $response = [
                            'success' => false,
                            'message' => 'Erro ao mover o arquivo para o servidor.'
                        ];
                        echo json_encode($response);
                    }
                } else {
                    // Nenhum arquivo 'imagem' foi enviado na requisição
                    $response = [
                        'success' => false,
                        'message' => 'Nenhuma imagem foi enviada na requisição.'
                    ];
                    echo json_encode($response);
                }
            } else {
                // A requisição não é POST
                $response = [
                    'success' => false,
                    'message' => 'Apenas requisições POST são permitidas.'
                ];
                echo json_encode($response);
            }
        }*/
        
        private function setUri(){
            $this->uri = $_SERVER['REQUEST_URI'] ?? '';
            $xURI = explode('?', $this->uri);
            $this->uri = $xURI[0];
        }

        public function getHttpMethod(){
            return $this->httpMethod;
        }

        public function getUri(){
            return $this->uri;
        }

        public function getQueryParams(){
            return $this->queryParams;
        }

        public function getPostVars(){
            return $this->postVars;
        }

        public function getHeaders(){
            return $this->headers;
        }

        public function getRouter(){
            return $this->router;
        }

        public function getFile(){
            return $this->file;
        }
    }

?>