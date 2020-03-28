<?php get_header(); ?>

<div id="inside-page">

	<h1 class="entry-title">
	<?php printf( __( 'Tag Archives: %s', 'twentyten' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?>
	</h1>
	
	<?php get_template_part( 'loop', 'tag' ); ?>

</div><!-- #inside-page -->

<?php get_footer(); ?>
