<?php get_header(); ?>

	<main>
        <section>
               <div class="wrapper">
                    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <h1 class="entry-title author"><?php printf( __( 'Author Archives: %s', 'twentyten' ), "<span class='vcard'><a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "' rel='me'>" . get_the_author() . "</a></span>" ); ?></h1>

                        <?php
                        // If a user has filled out their description, show a bio on their entries.
                        if ( get_the_author_meta( 'description' ) ) : ?>
                            <div id="entry-author-info">
                                <div id="author-avatar">
                                    <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyten_author_bio_avatar_size', 60 ) ); ?>
                                </div><!-- #author-avatar -->
                                <div id="author-description">
                                    <h2><?php printf( __( 'About %s', 'twentyten' ), get_the_author() ); ?></h2>
                                    <?php the_author_meta( 'description' ); ?>
                                </div><!-- #author-description	-->
                            </div><!-- #entry-author-info -->
                        <?php endif; ?>

                        <?php
                        rewind_posts();

                        get_template_part( 'loop', 'author' );
                        ?>
                    </div><!-- #post-## -->

                    <?php endwhile; ?>
               </div>
        </section>
	</main><!-- main -->

<?php get_footer(); ?>
