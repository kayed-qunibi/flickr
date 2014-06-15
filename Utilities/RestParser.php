<?php
/**
 * parsing from xml to php array
 * */

namespace flickr\utilities;

class RestParser implements \flickr\utilities\ParserInterface{
		
	public function parseOld($xml_string){
		
		$xmlObj = simplexml_load_string($xml_string);

		$data = array();
		foreach($xmlObj as $key=>$value) {
			if(sizeof($value->attributes())> 0 ){
				foreach($value->attributes() as $attrib_key=>$attrib_value){
					$data[$key][$attrib_key] = (string)$attrib_value; 
				}
			} 
			foreach($value->children() as $key_child=> $child) {
					$child_attrib = array();
					foreach($child->attributes() as $c_attrib_key=>$c_attrib_value){
						$child_attrib[$c_attrib_key] = (string)$c_attrib_value; 
					}
				$data[$key][$key_child][] = $child_attrib;
			}
		}
		print_R($data);die;
//		$data['photo']['title']['_content'] = 
		return $data;
	}
	
	function parse($contents, $get_attributes=1, $priority = '1') { 
	    if(!$contents) return array(); 

	    if(!function_exists('xml_parser_create')) { 
	        //print "'xml_parser_create()' function not found!"; 
	        return array(); 
	    } 

	    //Get the XML parser of PHP - PHP must have this module for the parser to work 
	    $parser = xml_parser_create(''); 
	    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss 
	    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0); 
	    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
	    xml_parse_into_struct($parser, trim($contents), $xml_values); 
	    xml_parser_free($parser); 

	    if(!$xml_values) return;//Hmm... 

	    //Initializations 
	    $xml_array = array(); 
	    $parents = array(); 
	    $opened_tags = array(); 
	    $arr = array(); 

	    $current = &$xml_array; //Refference 

	    //Go through the tags. 
	    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array 
	    foreach($xml_values as $data) { 

	        unset($attributes,$value);//Remove existing values, or there will be trouble 

	        //This command will extract these variables into the foreach scope 
	        // tag(string), type(string), level(int), attributes(array). 
	        extract($data);//We could use the array by itself, but this cooler. 

	        $result = array(); 
	        $attributes_data = array(); 

	        if(isset($value)) { 
	            if($priority == 'tag') $result = $value; 
	            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode 
	        } 

	        //Set the attributes too. 
	        if(isset($attributes) and $get_attributes) { 
	            foreach($attributes as $attr => $val) { 
	                if($priority == 'tag') $attributes_data[$attr] = $val; 
	                else $result[$attr] = $val; //Set all the attributes in a array called 'attr' 
	            } 
	        } 

	        //See tag status and do the needed. 
	        if($type == "open") {//The starting of the tag '<tag>' 
	            $parent[$level-1] = &$current; 
	            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag 
	                $current[$tag] = $result; 
	                if($attributes_data) $current[$tag. '_attr'] = $attributes_data; 
	                $repeated_tag_index[$tag.'_'.$level] = 1; 

	                $current = &$current[$tag]; 

	            } else { //There was another element with the same tag name 

	                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array 
	                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result; 
	                    $repeated_tag_index[$tag.'_'.$level]++; 
	                } else {//This section will make the value an array if multiple tags with the same name appear together
	                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
	                    $repeated_tag_index[$tag.'_'.$level] = 2; 

	                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well 
	                        $current[$tag]['0_attr'] = $current[$tag.'_attr']; 
	                        unset($current[$tag.'_attr']); 
	                    } 

	                } 
	                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1; 
	                $current = &$current[$tag][$last_item_index]; 
	            } 

	        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />' 
	            //See if the key is already taken. 
	            if(!isset($current[$tag])) { //New Key 
	                $current[$tag] = $result; 
	                $repeated_tag_index[$tag.'_'.$level] = 1; 
	                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data; 

	            } else { //If taken, put all things inside a list(array) 
	                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array... 

	                    // ...push the new element into that array. 
	                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result; 

	                    if($priority == 'tag' and $get_attributes and $attributes_data) { 
	                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data; 
	                    } 
	                    $repeated_tag_index[$tag.'_'.$level]++; 

	                } else { //If it is not an array... 
	                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
	                    $repeated_tag_index[$tag.'_'.$level] = 1; 
	                    if($priority == 'tag' and $get_attributes) { 
	                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

	                            $current[$tag]['0_attr'] = $current[$tag.'_attr']; 
	                            unset($current[$tag.'_attr']); 
	                        } 

	                        if($attributes_data) { 
	                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data; 
	                        } 
	                    } 
	                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken 
	                } 
	            } 

	        } elseif($type == 'close') { //End of tag '</tag>' 
	            $current = &$parent[$level-1]; 
	        } 
	    } 
	$xml_array = $xml_array['rsp'];
	if(isset($xml_array['photos']['photo']) && !isset($xml_array['photos']['photo'][0])  ){
		$temp = $xml_array['photos']['photo'];
		unset($xml_array['photos']['photo']);
		$xml_array['photos']['photo'][] = $temp;
	}

	    return (array)$xml_array; 
	}
	
}
