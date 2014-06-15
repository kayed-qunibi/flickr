<?php
/**
 * class responsible for parsing from json to php array
 * */
namespace flickr\utilities;

class JsonParser implements \flickr\utilities\ParserInterface{
	
	/**
	 * 
	 * */
	public function parse($json){
		$data = str_replace( 'jsonFlickrApi(', '', $json );
		$data = substr( $data, 0, strlen( $data ) - 1 ); //strip out last paren
		$data = json_decode( $data ,true); // stdClass object		
		$data['photo']['title']['value'] = (isset($data['photo']['title']['_content'])) ? $data['photo']['title']['_content'] :'';
		return $data;
	}
	
}