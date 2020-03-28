<?php include 'theme-variables.php'; ?>
<?php get_header();
$image = get_field( "header_image" );
?>

<section class="page-title" style="background-image:url(<?php echo $image; ?>);">
  <h1><?php echo the_title(); ?></h1>
  <div class="overlay"></div>
</section>

<?php
if (have_posts()) :
 while (have_posts()) :
    the_post();

    ?>
<div class="container">
  <div class="page-content">
    <?php echo the_content(); ?>
  </div>
</div>
<?php
endwhile;
endif;
?>

<?php get_footer(); ?>
