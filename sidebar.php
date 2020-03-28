<div id="sidebar">
	<?php if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : ?>
	<?php endif; // end primary widget area ?>
	
	<?php if ( is_active_sidebar( 'secondary-widget-area' ) ) : ?>
		<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
	<?php endif; ?>
</div>
