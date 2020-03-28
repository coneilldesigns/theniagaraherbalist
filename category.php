<?php include 'theme-variables.php'; ?>
<?php get_header(); ?>

<div class="container">
    <?php include 'site-header.php'; ?>
    <?php include 'site-footer.php'; ?>
</div>

<div class="overlay overlay-contentpush">
    <button type="button" class="overlay-close">Close</button>
    <div class="wrapper">
        <div class="verticle">
            <?php

			$defaults = array(
				'theme_location'  => 'Main Menu',
				'menu'            => 'Main Menu',
				'container'       => 'nav',
				'container_class' => false,
				'container_id'    => false,
				'menu_class'      => false,
				'menu_id'         => 'header-menu',
				'echo'            => true,
				'fallback_cb'     => 'wp_page_menu',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s<div class="float-divider"></div></ul>',
				'depth'           => 0,
				'walker'          => ''
			);

			wp_nav_menu( $defaults );

		?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
