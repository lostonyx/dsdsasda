<?php

class ServiceController extends Controller 
{

    public function index() 
    {

        $data = [];

        $this->redirect(SITE_URL.'home');
        
    }

    public function shop($id = false, $action = false, $action2 = false) 
    {

        $data = [];

        if(!$id) $this->redirect(SITE_URL.'home');

        $User = new User();

        if(!$User->hasLogin()) $this->redirect(SITE_URL.'user/login');

        $Shop = new Shop();

        $data['shop'] = $Shop->get($id, $_COOKIE['authI']);

        if($data['shop']) {
            if(!$action) {
                $data['page'] = 'home';
                $view = 'pages/shop.home';
            } else {
                if($action == 'theme') {
                    $data['page'] = 'theme';
                    if(!$action2) {
                        $data['subpage'] = 'theme.home';
                        $view = 'pages/shop.theme.home';
                    } else {
                        if($action2 == 'info') {
                            $data['subpage'] = 'theme.info';
                            $view = 'pages/shop.theme.info';
                        } else {
                            $view = 'layouts/404';
                        }
                    }
                } else {
                    $view = 'layouts/404';
                }
            }
        } else {
            $view = 'layouts/404';
        }
        
        $this->loadView($view, $data);
        
    }

}