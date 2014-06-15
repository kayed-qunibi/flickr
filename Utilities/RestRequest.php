<?php
/**
 * 
 * 
 * */
namespace flickr\utilities;


class RestRequest{
	
	/**
	 * 
	 * */
	public function __construct(\flickr\config\Config $config, \flickr\cache\APC $cache){
		
		$this->cache  = $cache;
	 	$this->rest_url = $config->offsetGet('rest_url');
		$this->api_key = $config->offsetGet('api_key');
		$this->secret_key = $config->offsetGet('secret_key');
		$this->output_format = $config->offsetGet('output_format');
		
		$this->url  =  $this->rest_url . '?api_key='. $this->api_key .'&secret='. $this->secret_key .'&format=' . $this->output_format;
	}
	/**
	 * 
	 * */
	public function buildUrl($method,$params = array()){
		
		$params['method'] = $method;
		$param_url = '';
		foreach($params as $var => $val) {
			$var = urlencode($var);
			$val = urlencode($val);
			$param_url .= "&$var=$val";
		}
		return 	$this->url.$param_url ;
	}
	/**
	 * 
	 * */
	public function execute($method, $params){
	
	    $url  = $this->buildUrl($method,$params);
		return  $this->fetch($url);
	}
	/**
	 * 
	 * */
	public function fetch($url = null){
		if(!isset($url)){
			throw new InvalidArgumentException('Url not passed to fetch');
		} else {
			$response = $this->cache->getCache($url);
			if($response){
				return $response;
			}
			try {
				$response = $this->request($url);
				$this->cache->setCache($url,$response);
				return $response;
			} catch( Exception $error){
				// handle the error
			}
		}	
	}
	/**
	 * 
	 * */
	function request($url)
	{
		if(function_exists("curl_init")){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$content = curl_exec($ch);
			curl_close($ch);
			return $content;
		} else {
			return file_get_contents($url);
		}
	}
}