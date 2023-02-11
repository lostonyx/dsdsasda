<?php

$post = $data['post'];
$get = $data['get'];

$User = new User();

if(!$User->hasLogin()) {

    if(!empty($post['name']) && !empty($post['surname']) && !empty($post['email']) && !empty($post['password'])) {

        if((strlen($post['name']) > 32) || (strlen($post['surname']) > 32) || (strlen($post['email']) > 32) || (strlen($post['password']) > 32)) {

            $reply->status = 'error';
            $reply->message = 'Algum dos campos é muito comprido.';

        } elseif(!preg_match("/^[a-zA-Z-' ]*$/", $post['name'])) {

            $reply->status = 'error';
            $reply->message = 'Nome inválido, utilize apenas letras.';

        } elseif(!preg_match("/^[a-zA-Z-' ]*$/", $post['surname'])) {

            $reply->status = 'error';
            $reply->message = 'Sobrenome inválido, utilize apenas letras.';

        } elseif(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {

            $reply->status = 'error';
            $reply->message = 'Email inválido.';

        } else {

            if($User->createUser($post['email'], $post['password'], $post['name'], $post['surname'])) {

                $reply->status = 'success';
                $reply->message = 'Cadastro realizado com sucesso.';
                $reply->reload = true;

                if(!$User->setLogin($post['email'], $post['password'])) {

                    $reply->status = 'error';
                    $reply->message = 'Cadastro realizado com sucesso, mas erro no login.';
                    unset($reply->reload);

                }

            } else {

                $reply->status = 'error';
                $reply->message = 'Já existe um cadastro no email informado.';

            }

        }

    } else {

        $reply->status = 'error';
        $reply->message = 'Faltando dados.';

    }

} else {

    $reply->status = 'error';
    $reply->message = 'Já está logado.';

}