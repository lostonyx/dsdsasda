<?php

$post = $data['post'];
$get = $data['get'];

if(!empty($post['theme'])) {

    if(($post['theme'] == 'light') || ($post['theme'] == 'dark')) {

        if((!isset($_COOKIE['theme'])) || ($_COOKIE['theme'] !== $post['theme'])) {

            setcookie('theme', $post['theme']);

            $reply->status = 'success';
            $reply->message = 'Tema atualizado com sucesso.';
            $reply->reload = true;

        } else {

            $reply->status = 'error';
            $reply->message = 'Tema igual o antigo.';

        }

    } else {

        $reply->status = 'error';
        $reply->message = 'Tema diferente de light/dark.';

    }

} else {

    $reply->status = 'error';
    $reply->message = 'Tema não informado.';

}