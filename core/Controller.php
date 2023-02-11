<?php 

class Controller 
{
	
	public function loadView($view, $info = []) 
	{

		//$Config = new Config();
		$loader = new Twig_Loader_Filesystem('../views/');
		$twig = new Twig_Environment($loader, array(
			//'cache' => 'cache/',
		));

		$User = new User();

		if(isset($_COOKIE['authI'])) {
			$info['admin'] = ($_COOKIE['authI']) ? $User->getAdmin($_COOKIE['authI']) : false;
			$info['user'] = ($_COOKIE['authI']) ? $User->getUser($_COOKIE['authI']) : false;
		}

		if(isset($_COOKIE['theme'])) {
			$info['theme'] = $_COOKIE['theme'];
		}

		//$info['config'] = $Config->getConfigs();
		$info['SITE_URL'] = SITE_URL;
		$info['constants'] = get_defined_constants();
		
		echo $twig->render($view.'.twig', $info);
		
	}
	
	public function error404() 
	{
		$this->loadHeader();
		$this->loadView('template/404');
		$this->loadFooter(); 
		exit;
	}
	
	public function loadAdmin($view, $info = []) 
	{
		$this->loadView('admin/'.$view, $info);
	}
	
	public function loadHeader($title = false) 
	{

		$data = [];
		$data['title'] = $title;

        //$Admin = new Admin();

        //$data['admin'] = $Admin->getAdmin($_COOKIE['authI']);


		$this->loadView('template/header', $data);

	}
	
	public function loadFooter() 
	{
		$this->loadView('template/footer');
	}
	
	public function loadAdminHeader() 
	{
		
		$Admin = new Admin();
		
		$data = [
			'permissions' => $Admin->getPermissions(),
			'name' => $Admin->getName(),
			'email' => $Admin->getEmail()
		];
		
		$this->loadAdmin('header', $data);
		
	}
	
	public function loadAdminFooter() 
	{
		$this->loadAdmin('footer');
	}
	
	public function redirect($url) 
	{
		header('Location: '.$url);
		exit;
	}
	
	
	public function withoutPermission() 
	{
		
		$this->loadAdminHeader();
		$this->loadAdmin('withoutPermission');
		$this->loadAdminFooter();
		exit;
		
	}
	
	public function loadAction($action, $data = []) 
	{

		$reply = new StdClass();

		if(file_exists('../action/'.$action.'.php')) {

			require_once '../action/'.$action.'.php';

		} else {

			$reply->status = 'error';
			$reply->message = 'Action "'.$action.'" not found.';

		}

		return $reply;
		
	}
	
}