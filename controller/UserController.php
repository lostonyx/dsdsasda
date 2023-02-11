<?php

class UserController extends Controller 
{

    public function index()
    {

        $data = [];

        $User = new User();

        if($User->hasLogin()) $this->redirect(SITE_URL.'home');

        $this->redirect(SITE_URL.'user/login');

    }

    public function profile($action = false)
    {

        $data = [];

        $User = new User();

        if(!$User->hasLogin()) $this->redirect(SITE_URL.'user/login');

        $data['user_info'] = $User->getUser($_COOKIE['authI']);

        if(!$action) {
            $data['page'] = 'profile.home';
            $view = 'pages/profile.home';
        } else {
            if($action == 'password') {
                $data['page'] = 'profile.password';
                $view = 'pages/profile.password';
            } elseif($action == 'api') {
                $data['page'] = 'profile.api';
                $view = 'pages/profile.api';
            } else {
                $view = 'layouts/404';
            }
        }
        
        $this->loadView($view, $data);

    }

    public function login() 
    {

        $data = [];

        $User = new User();

        if($User->hasLogin()) $this->redirect(SITE_URL.'home');
        
        $this->loadView('pages/login', $data);
        
    }

    public function register() 
    {

        $data = [];

        $User = new User();

        if($User->hasLogin()) $this->redirect(SITE_URL.'home');
        
        $this->loadView('pages/register', $data);
        
    }

    public function logout()
    {

        setcookie('authC', '', time() - 7200, '/');
        setcookie('authI', '', time() - 7200, '/');
        setcookie('authT', '', time() - 7200, '/');
        $this->redirect(SITE_URL.'user/login');

    }

}