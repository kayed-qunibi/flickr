<?php
/**
 * abstract class
 * */
namespace flickr\services;

abstract class FlickrService{
	/***
	 * set the method for the rest api
	 * */
	public function setMethod($method=null){
		
		if(isset($method)){
			$this->method = $method;
		}
	}
	/**
	 * run the request
	 * */
	abstract public function run($params);
}