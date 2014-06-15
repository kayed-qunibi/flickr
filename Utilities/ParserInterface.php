<?php
/***
 * each parser should implemtnts parse method
 * */
namespace flickr\utilities;

interface ParserInterface{
	
	public function parse($response);
}