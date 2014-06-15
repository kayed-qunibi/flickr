<?php
function my_autoloader($namespace) {
	$namespace_array = explode('\\', $namespace);
	$class_name = end($namespace_array);
	$folder_name =  ucfirst($namespace_array[count($namespace_array)-2]);
	$class_path = $folder_name.'/'.$class_name;
	include_once $class_path.'.php';
}

spl_autoload_register('my_autoloader');