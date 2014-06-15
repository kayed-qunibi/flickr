<?php
/***
 * responsible for searching photos functionality 
 * */
namespace flickr\services;

class Search extends \flickr\services\FlickrService{
	/**
	 *  method for searching
	 * */
	private $method = 'flickr.photos.search';	
	/**
	 * setup the request and the parse and setup the method
	 * */
	public function __construct(\flickr\utilities\RestRequest $request,\flickr\utilities\ParserInterface $parser){
		$this->request =  $request;
		$this->parser =  $parser;
		$this->setMethod();
	}
	/**
	 * call request to execute the method and parse it in general format
	 * */
 	public function run($params){

		if(!isset($this->method)){
			throw new InvalidArgumentException('search method not set');
		} else {
			$params['extras'] = 'url_t';
			$response =  $this->request->execute($this->method,$params);
			return $this->parser->parse($response);
		}
	}
}