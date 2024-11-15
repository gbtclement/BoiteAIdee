<?php

namespace Controllers;

abstract class Controller
{
	const VIEW_FOLDER = "abstract";
	const NAME = "abstract";
	abstract static function Run(): void;

	function Redirect(Controller $controller, int|string $action, array $params = []): void {
		$route = "?controller=".$controller::NAME."&action=$action";
		if ($params != []) {
			foreach ($params as $key => $param) {
				if (!is_string($key)) {
					// if the param can't be pass throught url pass to the next one
					continue;
				}
			
				$route .= "&$key=$param";
			}
		}
		
		header("/index.php$route");
	}

	function OpenView(string $view_name): void {
		require_once "/views/$view_name";
	}
}



