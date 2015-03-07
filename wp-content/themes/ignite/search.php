<?php get_header(); ?>

<div class="container">

    <div class="entry-header search-end top">
        <h1 class='entry-title'>Результаты поиска: "<?php echo $s ?>"</h1>
        <?php get_search_form(); ?>
    </div>

    <?php
    // The loop
    if ( have_posts() ) :
        while (have_posts() ) :
            the_post();
            get_template_part( 'content-search' );
        endwhile;
    endif;
    ?>

    <?php if ( current_theme_supports( 'loop-pagination' ) ) loop_pagination(); ?>

    <div class="search-end bottom">
        <p>Попробуйте воспользоваться поиском еще раз:</p>
        <?php get_search_form(); ?>
    </div>

</div>
<?php get_footer(); ?>