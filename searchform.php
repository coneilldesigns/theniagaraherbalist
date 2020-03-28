<?php
	// We can fill in the search term in case the user has typed something
	$search_term = ( isset($_GET['s']) ) ? $_GET['s'] : null;

	// include important file that will give us the ability to use theme variables
	include 'theme-variables.php';

	// direct the form action to the homepage
	$search_location = $home_url;
?>
<div id="search-box">
	<form action="<?php echo $search_location; ?>" method="GET">
		<input id="search-submit" type="submit" value="search" />
		<input id="search-text" type="text" name="s" value="<?php echo $search_term; ?>" default="Enter Keyword..." />
		<div class="float-divider"></div>
	</form>
</div>