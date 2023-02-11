<?php

$post = $data['post'];
$get = $data['get'];

$User = new User();

if(!$User->hasLogin()) {

    if(!empty($post['email']) && !empty($post['password'])) {
                        
        if($User->setLogin($post['email'], $post['password'])) {

            $reply->status = 'success';
            $reply->message = 'Login realizado com sucesso.';
            $reply->reload = true;

        } else {

            $reply->status = 'error';
            $reply->message = 'Dados de login incorretos.';

        }

    } else {

        $reply->status = 'error';
        $reply->message = 'Faltando dados.';

    }

} else {

    $reply->status = 'error';
    $reply->message = 'Já está logado.';

}