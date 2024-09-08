<?php
    /*
    Namespace: Init
    Descrição: Agrupa classes para Realizações de Rotas
    Data: 30/08/2024
    Programador(a): Ighor Drummond.
    */
	namespace FM\Init{
        /*
         * Classe: Route
         * Descrição: Responsável por preparar, enviar e acionar rotas
         * Data: 30/08/2024
         * Extends: Não há.
         * Programador(a): Ighor Drummond
         */
		abstract class Bootstrap {
            //Atributos
            //Array
            private array $routes;

            //Define métodos abstrados
           	abstract protected function initRoutes();

            //Construtor
            public function __construct(){
                //Inicia o Método initRoutes para iniciar as rotas existentes
                $this->initRoutes();
                //Executa rota desejada
                foreach ($this->routes as $key => $route) {
                    if($this->getUrl() === $route['route']){
                        //Define o controler a ser executado
                        $class = "App\\Controls\\". ucfirst($route['controller']);
                        //adiciona esse controller para execução
                        $controller = new $class;
                        //Recupera a ação
                        $action = $route['action']; 
                        //Executa a ação no controller
                        $controller->$action();
                    }
                }
            }

            //Getters
            /*
            * Método: getUrl()
            * Descrição: retorna a URL requisitada pelo Client
            * Data: 30/08/2024
            * Programador(a): Ighor Drummond
            */
            protected function getUrl(){
                return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            }
            /*
            * Método: getRoutes()
            * Descrição: retorna as rotas existentes
            * Data: 30/08/2024
            * Programador(a): Ighor Drummond
            */
            public function getRoutes(){
                return $this->routes;
            }

            //Setters
            /*
            * Método: setRoutes()
            * Descrição: guarda novas rotas para a variavel routes
            * Data: 30/08/2024
            * Programador(a): Ighor Drummond
            */
            public function setRoutes(array $routes){
                $this->routes = $routes;
            }
		}
	}
?>