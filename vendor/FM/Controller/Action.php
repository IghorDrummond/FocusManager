<?php
    /*
    Namespace: Controller
    Descrição: Agrupa classes para ações de renderização das Views
    Data: 07/09/2024
    Programador(a): Ighor Drummond.
    */
    namespace FM\Controller{
        /*
         * Classe: Action
         * Descrição: Responsável por ações diretas do Controller
         * Data: 31/08/2024
         * Extends: Não há.
         * Programador(a): Ighor Drummond
         */
        abstract class Action{
            //Atributos
            //Array
            protected array $json;
            //Objetos
            protected object $view;

            //Construtor
            public function __construct(){
                $this->view = new \stdClass();
                //Constroi Json
                $this->json[0]['error'] = false;
                $this->json[1]['mensagem'] = '';
            }

            //Métodos
            /*
            * Método: render(diretório da View)
            * Descrição: carrega a View desejada
            * Data: 07/09/2024
            * Programador(a): Ighor Drummond
            */
            public function render($view){
                $this->view->page = $view;//Guarda view requisitante
                require_once('../App/View/layout.phtml');//Chama Layout padrão
            }

            public function renderHtml($view){
                $this->view->page = $view;//Guarda view requisitante
                return $this->content();
            }
            /*
            * Método: render(diretório da View)
            * Descrição: carrega a View desejada
            * Data: 07/09/2024
            * Programador(a): Ighor Drummond
            */
            protected function content(){
                require_once("../App/View/". $this->view->page . "/". $this->view->page  .".phtml");
            }
        }
    }
?>