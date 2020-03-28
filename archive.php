<?php include 'theme-variables.php'; ?>
<?php get_header(); ?>

<div class="container">
    <?php include 'site-header.php'; ?>

    <div class="site-content">
        <div class="wrapper">
            <h1>UPCOMING EVENTS</h1>
            <!--<div id="event-sorting-page">
                    <ul>
                        <li><a class="filter" data-filter="all">All Events</a></li>
                        <li><a class="filter" data-filter=".friday">Fridays</a></li>
                        <li><a class="filter" data-filter=".saturday">Saturdays</a></li>
                        <li><a class="filter" data-filter=".sunday">Sundays</a></li>
                    </ul>
                    <div class="float-divider"></div>
                </div>-->


                <div id="all-events-page">
                        <?php
                        $eventFeed = file_get_contents('https://integrations.nightpro.co/v1/venue/8a5cec08d0dcb541/event?key=7a44a25f41326089972666c1d4e7ee15');
                        $eventFeed = json_decode($eventFeed, true);
                        ?>



<?php foreach ($eventFeed as $valueFeed) { ?>

<?php $date = $valueFeed['date'];
$newDate = date("m/d/Y", strtotime($date));
$fullDate = date("l F dS Y", strtotime($date));
$month = date("M", strtotime($date));
$day = date("d", strtotime($date));
$dayString = date("l", strtotime($date));
$dayString = strtolower($dayString);
$dayFull = date("D", strtotime($date));
?>



<div class="mix <?php echo $dayString; ?>">
     <div class="mix-overlay">
        <div class="event-button-holder">
           <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
               <?php if (isset($valueFeed['externalTicketLink'])) { ?>
                <div class="btn-event">
                    <a href="<?php echo $valueFeed['externalTicketLink']; ?>">BUY TICKETS</a>
                </div>
                <div class="btn-event">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>/bottleservice?event_name=<?php echo $valueFeed['name']; ?>&amp;event_date=<?php echo $newDate; ?>" alt="<?php echo $valueFeed['name']; ?>">BOTTLE SERVICE</a>
                </div>
                <?php } else { ?>
                <div class="btn-event">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>/guestlist?event_name=<?php echo $valueFeed['name']; ?>&amp;event_date=<?php echo $newDate; ?>" alt="<?php echo $valueFeed['name']; ?>">GUESTLIST</a>
                </div>
                <div class="btn-event">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>/bottleservice?event_name=<?php echo $valueFeed['name']; ?>&amp;event_date=<?php echo $newDate; ?>" alt="<?php echo $valueFeed['name']; ?>">BOTTLE SERVICE</a>
                </div>
                <?php }; ?>

            </div>
        </div>
    </div>
    <div class="mix-inside">
    <img width="100%" src="<?php echo $valueFeed['poster']['small']; ?>" />
    </div>
    <div class="float-divider"></div>
</div>

<?php }; ?>
                        <div class="float-divider"></div>
                    </div>
        </div>
    </div>

    <?php include 'site-footer.php'; ?>
</div>

<div class="overlay overlay-contentpush">
    <button type="button" class="overlay-close">Close</button>
    <div class="wrapper">
        <div class="verticle">
            <?php

			$defaults = array(
				'theme_location'  => 'Main Menu',
				'menu'            => 'Main Menu',
				'container'       => 'nav',
				'container_class' => false,
				'container_id'    => false,
				'menu_class'      => false,
				'menu_id'         => 'header-menu',
				'echo'            => true,
				'fallback_cb'     => 'wp_page_menu',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s<div class="float-divider"></div></ul>',
				'depth'           => 0,
				'walker'          => ''
			);

			wp_nav_menu( $defaults );

		?>
        </div>
    </div>
</div>

<?php get_footer(); ?>


