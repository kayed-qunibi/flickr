<?php
/**
 * encapsulate cache functionality 
 * 
 * */
/**
 * responsible for caching
 * */
namespace flickr\cache;

class APC{

	private  $expire_time = 3600;
	
	/**
	 * setup variables
	 * */
	public function __construct($expire_time = null){
		
		if(isset($expire_time)){
			$this->expire_time = $expire_time;
		}
	}
	/**
	 * return the cache response from the cache folder if exits
	 * after hashing the url using md5
	 * */
	public function getCache($url){
		$hash = md5($url);

		return apc_fetch($hash);
	}
	/**
	 * set the cache response in the cache folder 
	 * after hashing the url using md5
	 * */
	public function setCache($url,$data){
		$hash = md5($url);
		return apc_add($hash, $data, $this->expire_time);;
	}
		
}