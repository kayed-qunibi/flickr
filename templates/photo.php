

<div class="photo-view">
<?php 
if($photo_info['stat'] != 'fail'){?>

	<a class="back" href="/flickr/SearchController?keyword=<?php echo $keyword; ?>&page=<?php echo $page; ?>" onclick="window.history.back(); return false;">Back to results</a>
	<img src="http://farm<?php echo $photo_info['photo']['farm']; ?>.staticflickr.com/<?php echo $photo_info['photo']['server']; ?>/<?php echo $photo_info['photo']['id'] ;?>_<?php echo $photo_info['photo']['originalsecret']; ?>_o.<?php echo $photo_info['photo']['originalformat']; ?>" />

	<ul class="photo-information">
		<li><span>Title:</span> <?php echo $photo_info['photo']['title']['value'];?></li>
		<li><span>photo by:</span> <a href="http://www.flickr.com/people/<?php echo $photo_info['photo']['owner']['nsid'];?>/"><?php echo $photo_info['photo']['owner']['username'];?></a></li>
		<li><span>taken on:</span>  <?php echo $photo_info['photo']['dates']['taken'];?></li>
	</ul>

<?php }else{ ?>
No info
<?php }?>
</div>