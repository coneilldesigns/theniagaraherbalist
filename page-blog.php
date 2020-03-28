<?php include 'theme-variables.php'; ?>
<?php get_header();
$image = get_field( "header_image" );
?>

<section class="page-title" style="background-image:url(<?php echo $image; ?>);">
  <h1><?php echo the_title(); ?></h1>
  <div class="overlay"></div>
</section>

<section class="showcase">
<div class="container-fluid p-0">
<?php
$args = array(
    'post_type' => 'post'
);

$post_query = new WP_Query($args);
if($post_query->have_posts() ) {
  $x = 0;
  while($post_query->have_posts() ) {
  $post_query->the_post();
  ?>
    <div class="row no-gutters">
      <?php if ($x % 2 == 0) { ?>
        <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
        <div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image: url('<?php echo $image[0]; ?>');"></div>
        <div class="col-lg-6 order-lg-1 my-auto showcase-text">
        <div class="post-details pr-5 pl-5">
      <?php } else { ?>
        <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
        <div class="col-lg-6 text-white showcase-img" style="background-image: url('<?php echo $image[0]; ?>');"></div>
        <div class="col-lg-6 my-auto showcase-text">
        <div class="post-details pl-5 pr-5">
      <?php }; ?>


          <h2 class="mb-3"><?php the_title(); ?></h2>

          <div class="author-info">
            <ul class="list-inline">
              <?php
                $posttags = get_the_tags();
                $count=0;
                if ($posttags) {
                  foreach($posttags as $tag) {
                    $count++;
                    if (1 == $count) {
                      echo '<li class="list-inline-item blog-tag">' . $tag->name . '</li>';
                    }
                  }
                }

                $author = get_the_author();

                if ($author === 'admin') {
                  $authorReal = 'Herbalist';
                } else {
                  $authorReal = $author;
                };

                ?>
                <li class="list-inline-item"><b><?php echo $authorReal; ?></b></li>
                <li class="list-inline-item">Published: <?php echo get_the_date('F j, Y'); ?></li>
              </ul>
          </div>
          <p class="lead mb-0"><?php echo get_excerpt($post->ID, 600); ?></p>
          <a href="<?php the_permalink(); ?>" class="btn btn-primary">Read More</a>
        </div>
      </div>
    </div>

  <?php
  $x++;
  }
}
?>
  </div>

</section>

<!-- Testimonials
<section class="testimonials text-center bg-light">
  <div class="container">
    <h2 class="mb-5">What people are saying...</h2>
    <div class="row">
      <div class="col-lg-4">
        <div class="testimonial-item mx-auto mb-5 mb-lg-0">
          <img class="img-fluid rounded-circle mb-3" src="<?php echo $images_url; ?>testimonials-1.jpg" alt="">
          <h5>Margaret E.</h5>
          <p class="font-weight-light mb-0">"This is fantastic! Thanks so much guys!"</p>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="testimonial-item mx-auto mb-5 mb-lg-0">
          <img class="img-fluid rounded-circle mb-3" src="<?php echo $images_url; ?>testimonials-2.jpg" alt="">
          <h5>Fred S.</h5>
          <p class="font-weight-light mb-0">"Bootstrap is amazing. I've been using it to create lots of super nice landing pages."</p>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="testimonial-item mx-auto mb-5 mb-lg-0">
          <img class="img-fluid rounded-circle mb-3" src="<?php echo $images_url; ?>testimonials-3.jpg" alt="">
          <h5>Sarah W.</h5>
          <p class="font-weight-light mb-0">"Thanks so much for making these free resources available to us!"</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="call-to-action text-white text-center">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-xl-9 mx-auto">
        <h2 class="mb-4">Ready to get started? Sign up now!</h2>
      </div>
      <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
        <form>
          <div class="form-row">
            <div class="col-12 col-md-9 mb-2 mb-md-0">
              <input type="email" class="form-control form-control-lg" placeholder="Enter your email...">
            </div>
            <div class="col-12 col-md-3">
              <button type="submit" class="btn btn-block btn-lg btn-primary">Sign up!</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>-->



<?php get_footer(); ?>
