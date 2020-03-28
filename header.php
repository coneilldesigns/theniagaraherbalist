<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<?php include 'theme-variables.php'; ?>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php bloginfo('name'); ?><?php wp_title( '|', true, 'left' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<link rel="icon" type="image/png" href="<?php echo $images_url; ?>favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
	
	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
<div id="full-site">
	<!-- Masthead -->
	<header>
		<!-- Map Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-body">
		        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2908.457433752133!2d-79.25620608368358!3d43.19989407913935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89d3512fa62f5b8b%3A0x7467b80a5b2b04de!2s33+Lakeshore+Rd+%2315%2C+St.+Catharines%2C+ON+L2N+7B3!5e0!3m2!1sen!2sca!4v1553133119963" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="top-bar">
			<div class="container">
				<ul>
					<li><a href="tel:<?php the_field('phone', 'option'); ?>"><i class="fa fa-phone"></i> <?php the_field('phone', 'option'); ?></a></li>
					<li><a href="#" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-map-marker-alt"></i> <?php the_field('address', 'option'); ?></a></li>
				</ul>
			</div>
		</div>

		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container">
				<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div class="logo"><img src="<?php echo $images_url . 'logo.svg'; ?>" class="img-responsive logo" /></div></a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bootstrap-nav-collapse" aria-controls="jd-bootstrap-nav-collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="navbar-collapse collapse justify-content-between" id="bootstrap-nav-collapse">
					<?php
					wp_nav_menu( array(
						'menu' => 'mega_menu',
						'depth' => 0,
						'container' => false,
						'menu_class' => 'navbar-nav ml-auto',
						'walker' => new BootstrapNavMenuWalker()
					));
					?>
				</div>
			</div>
		</nav>
	</header>
