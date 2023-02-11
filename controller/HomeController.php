<?php

class HomeController extends Controller 
{

    public function index() 
    {

        $data = [];

        $User = new User();

        if(!$User->hasLogin()) $this->redirect(SITE_URL.'user/login');

        $data['page'] = 'home';

        $data['name'] = $User->getUser($_COOKIE['authI'])['name'];

        $Shop = new Shop();

        $data['shops'] = $Shop->getShopsByUser($_COOKIE['authI']);

        $this->loadView('pages/home', $data);
        
    }

}