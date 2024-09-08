<?php
/*
===============================================
Fonte: Route.php
Descrição: Responsável por alinhar as rotas para a aplicação SectoTeca
Data: 07/09/2024
Programador(a): Ighor Drummond
===============================================
*/
    /*
    Namespace: Controls
    Descrição: Agrupa classes do Controller
    Data: 07/09/2024
    Programador(a): Ighor Drummond.
    */
    namespace App\Controls{
        session_start();

        use FM\Controller\Action;//Aciona a classe Action para devidas ações do controller
        use App\Models\Playlist;//Aciona a classe para cadastrar, editar e deletar playlist e seus conteúdos

        /*
         * Classe: IndexController
         * Descrição: Responsável por realizar operações do Controller
         * Data: 30/08/2024
         * Extends: Não há.
         * Programador(a): Ighor Drummond
         */
        class IndexController extends Action{
            /*
            * Método: index()
            * Descrição: renderiza a view do Index
            * Data: 07/09/2024
            * Programador(a): Ighor Drummond
            */
            public function index(){
                $this->render('index');
            }
        }
    }

?>