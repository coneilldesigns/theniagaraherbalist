<?php
include 'theme-variables.php'; ?>
<?php get_header(); ?>
<section class="main-slideshow">
  <div class="cycle-slideshow"
  data-cycle-fx="scrollHorz"
  data-cycle-timeout="7000"
  data-cycle-slides="> div"
  >

  <?php if( have_rows('main_slider_slides', 'option') ): ?>
    <?php while( have_rows('main_slider_slides', 'option') ): the_row();

    // vars
    $title = get_sub_field('title');
    $tagline = get_sub_field('tagline');
    $image = get_sub_field('background_image');
    $content = get_sub_field('button_text');
    $link = get_sub_field('button_link');

    ?>
    <div class="masthead text-white" style="background-image:url(<?php echo $image; ?>);">
      <div class="overlay"></div>
      <div class="container h-100">
        <div class="row h-100">
          <div class="col-lg-6 col-md-8 col-sm-12 my-auto">
            <div class="w-100">
              <img class="leaf" src="<?php echo $images_url; ?>leaf.svg" />
              <h1><?php echo $title; ?></h1>
              <h3><?php echo $tagline; ?></h3>
              <a class="btn btn-primary" href="<?php echo $link; ?>"><?php echo $content; ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
  <?php endif; ?>
  </div>

  <!-- Info Area -->
  <div class="info-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-8 pr-0 pl-0 d-none d-sm-block"></div>
        <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 pr-0 pl-0 upper">
          <?php if ( is_active_sidebar( 'info-sidebar' ) ) : ?>
              <div id="secondary" class="widget-area" role="complementary">
              <?php dynamic_sidebar( 'info-sidebar' ); ?>
              </div>
          <?php endif; ?>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 pr-0 pl-0 upper">
          <?php if( have_rows('social-icons', 'option') ): ?>
            <ul class="main-social">
            <?php while( have_rows('social-icons', 'option') ): the_row();
            // vars
            $icon = get_sub_field('icon');
            $link = get_sub_field('social_url');
            ?>
              <li><a href="<?php echo $link; ?>"><?php echo $icon; ?></a></li>
          <?php endwhile; ?>
          </ul>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <!-- Info Area End -->

</section>
<!-- Icons Grid -->
<section class="features-icons bg-light">
  <div class="container">
    <div class="row">
      <?php
      $args = array(
          'post_type' => 'post'
      );

      $post_query = new WP_Query($args);
      if($post_query->have_posts() ) {
        $x = 0;
        while($post_query->have_posts() ) {
        $post_query->the_post();
        if($x === 3) {break;}
        ?>
          <aside class="col-lg-3 col-md-4">
            <div class="row">
              <div class="col-5">
                <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'front-thumb' ); ?>
                <a href="<?php the_permalink(); ?>"><img style="width:100%" src="<?php echo $image[0]; ?>" /></a>
              </div>
              <div class="col-7 pl-0">
                <h3 class="widget-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div class="textwidget front-widget">
                  <a href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a>
                </div>
              </div>
            </div>
          </aside>
        <?php
        $x++;
        }
      }
      ?>
      <div class="col-3"></div>
    </div>
  </div>
</section>

<div id="main-content" class="main-content">
  <div class="container">
    <?php
    if (have_posts()) :
     while (have_posts()) :
        the_post();
           the_content();
     endwhile;
    endif;
    ?>
  </div>
</div><!-- #main-content -->

<?php get_footer(); ?>
