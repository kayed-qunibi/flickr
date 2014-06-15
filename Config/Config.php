<?php
/**
* configuration class for flickr apis loads the configuration from ini file config.ini
* where you can set your API_KEY and The API_SECRET 
* for you application
*
**/
namespace flickr\config;

Class Config implements \ArrayAccess{
	
	private $config_ini = 'config/config.ini';
	
	private $config;
	
	public function __construct($file=''){
		if(empty($file)){
			$file = $this->config_ini;
		}
		$this->config = parse_ini_file ( $file);
		if(!$this->config){
			throw new Exception ( 'Unable to load config file');
		}
	}
	
	/**
	 * return value by it key
	 * @param string $key
	 * @see ArrayAccess::offsetGet
	 */
	
	public function offsetGet($key) {
		
		if (isset ( $this->config [$key] )) {
			return $this->config [$key];
		}
		return false;
	}
	
	/**
	 * this method will raise error exception when call 
	 * @param string $key
	 * @param string $val
	 * @see ArrayAccess::offsetSet
	 * 
	 */
	
	public function offsetSet($key, $val) {
		throw new BadMethodCallException ( "Array access of class " . get_class ( $this ) . " is read-only!" );
	}
	/**
	 * check if key exists or not
	 * @param string $key
	 * @see ArrayAccess::offsetExists
	 */
	
	public function offsetExists($key) {
		
		return isset ( $this->config[$key] );
	}
	
	/**
	 * this method will raise  error exception when call
	 * @param string $key
	 * @see ArrayAccess::offsetUnset
	 */
	public function offsetUnset($key) {
		throw new BadMethodCallException ( "Array access of class " . get_class ( $this ) . " is read-only!" );
	}
}