<?php 

class Core 
{

	public function start() 
	{
		/*$url = explode('index.php', $_SERVER['PHP_SELF']);
		$access = explode('/', $url[1]);
		array_shift($access);
*/
        if(empty($_GET['url'])) {
            $url = '/';
        } else {
            $url = $_GET['url'];
        }
        $access = explode('/', $url);

		if(isset($access[0]) && $access[0] != null) {

			$char1 = substr(strtoupper($access[0]), 0, 1);
			$access[0] = $char1.substr($access[0], 1);
			
			if(class_exists($access[0].'Controller')) {

				$access[0] = $access[0];
				$controller = $access[0].'Controller';
				array_shift($access);

				if(empty($access[0]) or $access[0] == '') {
					$action = 'index';
				} else {
					$action = $access[0];
					if(!method_exists(new $controller, $access[0])) {
						$controller = 'ErrorController';
						$action = 'index';
					}
					array_shift($access);
				}

			} else {
				$controller = 'ErrorController';
				$action = 'index';
			}

		} else {
			$controller = 'HomeController';
			$action = 'index';
		}
		
		$params = array();

		if(count($access) > 0){
			$params = $access;
		}
		
		$obj = new $controller();
		call_user_func_array(array($obj, $action), $params);
	}
	
}