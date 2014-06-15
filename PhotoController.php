<?php
require_once 'Utilities/autoload.php';
//filter the photo id and set it into $photo_id 
$photo_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
//check if the photo id valid if not redirec to searchController
if(!$photo_id){
	header('Location: /flickr/SearchController.php');
}
//set template to templates/photo.php
$template_name = 'photo';

list($keyword,$page) = explode(',', $_GET['cb']);
$keyword = isset($keyword) ? filter_var($keyword,FILTER_SANITIZE_STRING) :''; 
$page = (int) $page;
	
	//load the config and cache and pass it RestRquest 
	$config = new \flickr\config\Config();
	$request = new \flickr\utilities\RestRequest($config, new \flickr\cache\APC());
	//instantiate parser object depends on the output format from the config.ini
	$parser_factory =  new \flickr\utilities\ParserFactory();
	$parser = $parser_factory->build($config->offsetGet('output_format'));
	//instantiate photo service object 
	$photo  = new \flickr\services\Photo($request, $parser);
 	$photo_info =  $photo->run(array('photo_id'=>$photo_id), $parser);

//load the main template 
require_once 'templates/main.php'
?>

