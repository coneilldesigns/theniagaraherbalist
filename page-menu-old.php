<?php include 'theme-variables.php'; ?>
<?php
get_header();
$image = get_field( "header_image" );
?>

<div id="menu-holder">


  <div id="cart">
    TEST
  </div>



    <div id="menu" class="container-fluid h-100">
      <div class="row h-100">

        <div id="cat-scroll" class="col-lg-2 pl-0 pr-0 tab-col h-100 d-table">

          <h3>Categories</h3>

          <div class="menu-content" id="menu-content"> <!-- d-table-cell align-middle -->
            <div class="menu-content-inside">
              <ul class="nav nav-tabs" id="cat-tabs" role="tablist"></ul>
            </div>
            <div id="last-updated">
              <?php
              date_default_timezone_set('America/Toronto');
              $filename = get_template_directory() . '/menu_json.json';
              if (file_exists($filename)) {
                  echo "<b>Updated:</b> " . date ("F jS Y h:i A", filemtime($filename));
              }
              ?>
            </div>
          </div>

        </div>
        <div id="menu-scroll" class="col-lg-10 h-100 outer">
          <div class="product-holder">
            <table id="product-table" class="table">
              <thead>
                <tr>
                  <th scope="col">Product Name</th>
                  <th scope="col">Category</th>
                  <th scope="col">THC</th>
                  <th scope="col">CBD</th>
                  <th scope="col">Price</th>
                  <th scope="col">Description</th>
                </tr>
              </thead>
              <tbody id="products-body"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>

<?php get_footer(); ?>
