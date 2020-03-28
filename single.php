<?php include 'theme-variables.php'; ?>
<?php get_header();
$image = get_field( "header_image" );
?>

<?php  while ( have_posts() ) : the_post(); ?>
<section class="page-title" style="background-image:url(<?php echo the_post_thumbnail_url(); ?>);">
  <h1><?php the_title(); ?></h1>
  <div class="overlay"></div>
</section>
<?php endwhile; ?>

<div class="container mb-5 mt-5">
  <section id="content">
      <div class="wrap-content blog-single">

      <?php  while ( have_posts() ) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
              <div class="entry-content"><?php the_content(); ?></div>
          </article>
      <?php endwhile; ?>

      </div>

  </section>
</div>
<?php get_footer(); ?>
