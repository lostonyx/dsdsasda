<?php

class FormController extends Controller 
{

    public function index() 
    {

        $reply = new stdClass();

        $User = new User();

        if(isset($_POST['function'])) {

            /******************************************/
            // CHECAGEM DE LOGIN PARA OUTRAS FUNÇÕES
            /******************************************/
            if(($_POST['function'] !== 'login') && ($_POST['function'] !== 'register') && (!$User->hasLogin())) {

                $reply->status = 'error';
                $reply->message = 'This functions need to be loged.';

            /******************************************/
            // CONTINUA
            /******************************************/
            } else {

                $data['post'] = $_POST;
                $data['get'] = $_GET;

                $function = str_replace('_', '.', $_POST['function']);

                $reply = $this->loadAction($function, $data);

            }

        } else {

            $reply->status = 'error';
            $reply->message = 'Function not informed.';

        }

        echo json_encode($reply);
        
    }

}