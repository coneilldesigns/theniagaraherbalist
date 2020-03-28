<ul id="posts-list">
<?php
	while( have_posts() ) : the_post();

	// this is the list to the full post
	$link = get_permalink($post->ID);

	// title of the post
	$title = verifyLength($post->post_title,50);

	// excerpt of the post
	$excerpt = verifyLength($post->post_excerpt,100);

	// thumbnail
	$thumbnail = get_post_image($post->ID,50,50,false);

	echo '<li>';
		// this is the thumbnail
		echo '<div class="post-thumbnail"><a href="' . $link . '">' . $thumbnail . '</a></div>';

		// this is the description of the post
		echo '<div class="post-description">';
			echo '<h3><a href="' . $link . '">' . $title . '</h3>';
			echo '<p>' . $excerpt . '</p>';
			echo '<a href="' . $link . '" class="button read-more">Read More</a>';
		echo '</div>';

		echo '<div class="float-divider"></div>';

	echo '</li>';

	endwhile;
?>
</ul>