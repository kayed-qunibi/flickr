<div class="results">
	<?php
	if(isset($results['photos']['photo'])){
	?>
	<ul class="result-grid">
<?php
	foreach($results['photos']['photo'] as $photo){?>
		<li>
			<a href="/flickr/PhotoController.php?id=<?php echo $photo['id'] ?>&cb=<?php echo $keyword.','.$page?>">
				<img src="<?php echo $photo['url_t'];?>" title="<?php echo $photo['title'];?>" />
			</a>
		</li>
<?php }?>
	</ul>
	<?php }else{?>
	no results	
<?php	}?>
</div>
<?php
echo $paging_links;
?>