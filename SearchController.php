<?php
require_once 'Utilities/autoload.php';
//filter parameter
$keyword = isset($_GET['keyword']) ? filter_var($_GET['keyword'],FILTER_SANITIZE_STRING) :'';
$page = isset($_GET['page']) ? (int) $_GET['page'] :'1';
//set the template name
$template_name = 'search';
//check if the keyword sent
if(trim($keyword) != '' ){
	
	//instantiate config object and pass it to RestRequest with  cache object 
	$config = new \flickr\config\Config();
	$request = new \flickr\utilities\RestRequest($config, new \flickr\cache\APC());
	//instantiate parser object depends on the output format from the config.ini
	$parser_factory =  new \flickr\utilities\ParserFactory();
	$parser = $parser_factory->build($config->offsetGet('output_format'));	
	//get how many item per page from config.ini
	$per_page = $config->offsetGet('per_page');
	//instantiate search service object 
	$search  = new \flickr\services\Search($request,$parser);
	//run the rest url with param
 	$results =  $search->run(array('text'=>$keyword,'page'=>$page,'per_page'=>$per_page ),$parser);
	//generate pagination
	$url =  "/flickr/SearchController.php?keyword=$keyword&page=";
	$pagination = new \flickr\utilities\Pagination();
	$paging_links  = $pagination->render($per_page,$page ,$url ,$results['photos']['total']);

}
//load the main template 
require_once 'templates/main.php'
?>

