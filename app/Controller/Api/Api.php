<?php
    namespace App\Controller\Api;

    class Api{
        public static function getDetails($request){
            return[
                'name'      => 'secureChat - API',
                'versao'    => 'v1.0.0',
                'autor'     => 'cleverdeveloper',
            ];
        }

        protected static function getPagination($request, $obPagination){
            $query_params = $request->getQueryParams();
            
            $pages = $obPagination->getPages();

            return [
                'paginaActual'       => isset($query_params['page']) ?  (int)$query_params['page'] : 1,
                'quantidadePaginas'  => !empty($pages) ? count($pages) : 1
            ];
        }
    }
?>