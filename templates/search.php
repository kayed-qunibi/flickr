<div class="search-form">
	<form action="SearchController.php" method="get">
		<div class="form-row">
		<label for="keyword">Search</label>
			<input id="keyword" name="keyword" type="text" placeholder="Enter keyword" value="<?php echo $keyword?>"/>
		</div>
		<div class="form-row">
			<input type="submit" value="Search">
		</div>
	</form>
</div>
<?php
 if(isset($results)  && ! empty($results)){
	 include_once('templates/results.php');
}?>