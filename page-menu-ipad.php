<?php include 'theme-variables.php'; ?>
<?php
get_header();
$image = get_field( "header_image" );
?>

<div id="menu-holder-ipad">

  <div class="top-area">
    <div class="container">
      <div class="row pt-4 pb-4">
        <div class="col-10 mb-3">
          <h3>Live Menu</h3>
          <h4>The Niagara Herbalist</h4>
        </div>
        <div class="col-2 text-right">
          <img class="leaf" src="<?php echo $images_url; ?>leaf.svg" />
        </div>
        <div class="col-12 nav-circles">
          <ul class="nav nav-tabs" id="cat-tabs" role="tablist"></ul>
        </div>
      </div>
    </div>
  </div>

  <div class="menu-area">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="container pl-0 pr-0" >
            <div class="row header-row">
              <div class="col-10 name">
                <p>Name</p>
              </div>
              <div class="col-2">
                <p>Price</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="container pl-0 pr-0 mb-5" id="products-body">

            <div class="row pt-3 pb-3 item-row">
              <div class="col-10 name">
                <h6 class="vendor_name"></h6>
                <h3 class="product_name"></h3>
                <p>THC <span class="product_thc"></span> | CBD <span class="product_cbd"></span></p>
              </div>
              <div class="col-2">
                <p class="product_price"></p>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>

</div>

<?php get_footer(); ?>
