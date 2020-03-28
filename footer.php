<?php include 'theme-variables.php'; ?>



<?php if(is_page( 'Menu' )) {} else { ?>
<div id="insta-feed" class="insta-feed">
  <?php if ( is_active_sidebar( 'instagram-sidebar' ) ) : ?>
      <?php dynamic_sidebar( 'instagram-sidebar' ); ?>
  <?php endif; ?>
</div><!-- #main-content -->
<?php }; ?>


<?php if(is_page('Menu')) {} else { ?>
<!-- Footer -->
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-2 col-md-2">
        <img src="<?php echo $images_url; ?>bottom-logo.svg" width="100%" />
      </div>
      <div class="col-lg-1 col-md-1"></div>
      <?php if ( is_active_sidebar( 'footer-sidebar' ) ) : ?>
          <?php dynamic_sidebar( 'footer-sidebar' ); ?>
      <?php endif; ?>

      <div class="col-lg-3 col-md-12 text-right">
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
          <div id="sub-widget" class="textwidget">
            <p>Enter your email below to stay up to date.</p>
            <?php echo do_shortcode('[contact-form-7 id="912" title="Footer Subscribe"]'); ?>
          </div>

      </div>
    </div>
  </div>

</footer>

<div class="copyright">
  <p>&copy; <?php echo date("Y"); ?> - <?php echo get_bloginfo( 'name' ); ?></p>
</div>

<?php }; ?>

<?php wp_footer(); ?>
</div>
</body>
</html>
