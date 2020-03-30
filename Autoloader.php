<?php

class Autoloader{

	public function __construct(){
		spl_autoload_register(function($class){
			require 'Controller/'.$class.".php";
		});
	}

}

new Autoloader();