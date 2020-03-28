<?php include 'theme-variables.php'; ?>
<?php
get_header();
$image = get_field( "header_image" );
?>

<div id="menu-holder">

  <div class="center-center text-center">
    <img width="60" class="leaf mb-2" src="<?php echo $images_url; ?>leaf.svg" />
    <p>Live Menu Loading...</p>
  </div>

  <script async="" id="dutchie--embed__script" src="https://dutchie.com/api/v2/embedded-menu/5e41cc73451ecb007bd05cae.js"></script>
</div>

<?php get_footer(); ?>
