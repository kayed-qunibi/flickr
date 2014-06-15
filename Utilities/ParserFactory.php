<?php
/**
 * factory class to return which parser should we use
 * 
 * */
namespace flickr\utilities;

class ParserFactory{
	
	protected static $types = array('json','rest');
	
	public function build($type){
		
		if(!in_array($type,self::$types)){
			throw new InvalidArgumentException('the type you are trying to use is not valid!');
		}
	 	$class_name  = '\flickr\utilities\\'.ucfirst($type).'Parser';
		
		return new $class_name();
	}
}