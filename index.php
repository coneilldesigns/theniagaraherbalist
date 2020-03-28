<?php
include 'theme-variables.php'; ?>
<?php get_header(); ?>


<section class="main-slideshow">

  <div class="main-slideshow-holder">
    <div class="cycle-slideshow"
    data-cycle-fx="scrollHorz"
    data-cycle-timeout="7000"
    data-cycle-slides="> div"
    >

    <?php if( have_rows('main_slider_slides', 'option') ): ?>
      <?php while( have_rows('main_slider_slides', 'option') ): the_row();

      // vars
      $title = get_sub_field('title');
      $image = get_sub_field('background_image');
      $content = get_sub_field('button_text');
      $link = get_sub_field('button_link');

      ?>
      <div class="masthead text-white" style="background-image:url(<?php echo $image; ?>);">
        <div class="overlay"></div>
        <div class="container">
          <div class="row">
            <div class="col-lg-6 col-md-8 col-sm-12">
              <img class="leaf" src="<?php echo $images_url; ?>leaf.svg" />
              <h1 class="mb-4"><?php echo $title; ?></h1>
              <a class="btn btn-primary" href="<?php echo $link; ?>"><?php echo $content; ?></a>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
    <?php endif; ?>
    </div>
  </div>

  <!-- Info Area -->
  <div class="info-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-8 pr-0 pl-0 d-none d-sm-block"></div>
        <div class="col-lg-2 col-md-3 col-sm-3 col-12 pr-0 pl-0 upper">
          <?php if ( is_active_sidebar( 'info-sidebar' ) ) : ?>
              <div id="secondary" class="widget-area" role="complementary">
              <?php dynamic_sidebar( 'info-sidebar' ); ?>
              </div>
          <?php endif; ?>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-12 pr-0 pl-0 upper">
          <?php if( have_rows('social-icons', 'option') ): ?>
            <ul class="main-social">
            <?php while( have_rows('social-icons', 'option') ): the_row();
            // vars
            $icon = get_sub_field('icon');
            $link = get_sub_field('social_url');
            ?>
              <li><a target="_blank" href="<?php echo $link; ?>"><?php echo $icon; ?></a></li>
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
      <?php if ( is_active_sidebar( 'bottom-header-sidebar' ) ) : ?>
          <?php dynamic_sidebar( 'bottom-header-sidebar' ); ?>
      <?php endif; ?>
      <div class="col-3"></div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
