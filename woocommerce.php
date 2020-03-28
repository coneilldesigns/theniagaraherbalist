<?php include 'theme-variables.php'; ?>
<?php get_header();
$home = home_url();
?>

<section class="page-title" style="background-image:url(<?php echo $images_url . 'bg-masthead.jpg'; ?>);">
  <?php if(is_shop()) { ?>
    <h1>Menu</h1>
  <?php } else { ?>
    <h1><?php echo single_term_title(); ?></h1>
  <?php }; ?>

  <div class="overlay"></div>
</section>

<div class="container woocommerce">

<?php if(is_shop()) { ?>
  <div class="row woo-topnav">
    <div class="col-lg-3 col-md-3 first">
      <a href="<?php echo home_url(); ?>/product-category/hybrid/">
        <div class="img-holder"><img width="100%" src="<?php echo home_url(); ?>/wp-content/uploads/2019/06/cwa0m67cxxqwva1xono9-300x300.jpg"></div>
        <h3>Dried Flower</h3>
      </a>
    </div>
    <div class="col-lg-3 col-md-3">
      <a href="<?php echo home_url(); ?>/product-category/pre-roll/">
        <div class="img-holder"><img width="100%" src="<?php echo home_url(); ?>/wp-content/uploads/2019/06/1b4b6fc9-4623-41d2-bfe7-9d8e2d2265e9-300x300.png"></div>
        <h3>Pre-Rolled</h3>
      </a>
    </div>
    <div class="col-lg-3 col-md-3">
      <a href="<?php echo home_url(); ?>/product-category/oils/">
        <div class="img-holder"><img width="100%" src="<?php echo home_url(); ?>/wp-content/uploads/2019/06/terjwu9p9pd6umqr5ymq-300x300.png"></div>
        <h3>Oils & Sprays</h3>
      </a>
    </div>
    <div class="col-lg-3 col-md-3 last">
      <a href="<?php echo home_url(); ?>/product-category/capsules/">
        <div class="img-holder"><img width="100%" src="<?php echo home_url(); ?>/wp-content/uploads/2019/06/95b7bc9e-aab2-4e35-b598-1f0ec5caa7db-300x300.png"></div>
        <h3>Capsules</h3>
      </a>
    </div>
  </div>
<?php }; ?>

  <div class="row">
    <div class="col-lg-3 col-sm-12 woo-sidebar">
      <div class="page">
        <?php if ( is_active_sidebar( 'woocommerce-sidebar' ) ) : ?>
            <?php dynamic_sidebar( 'woocommerce-sidebar' ); ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-lg-9 col-sm-12">
      <div class="page">
        <div class="woocommerce">
          <?php woocommerce_breadcrumb(); ?>
          <?php woocommerce_content(); ?>
        </div>
      </div>
    </div>
  </div>

</div>


<?php get_footer(); ?>
