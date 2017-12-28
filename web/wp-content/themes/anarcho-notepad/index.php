<?php
/**
 * The main template file.
 *
 * @package	Anarcho Notepad
 * @since	2.3
 * @author	Arthur (Berserkr) Gareginyan <arthurgareginyan@gmail.com>
 * @copyright 	Copyright (c) 2013-2014, Arthur Gareginyan
 * @link      	http://mycyberuniverse.tk/anarcho-notepad.html
 * @license   	http://www.gnu.org/licenses/gpl-3.0.html
 */
?>

<?php get_header(); ?>

<section id="content" role="main">
  <div class="col01">
  <?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

<?php anarcho_breadcrumbs(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <h3><?php the_title(); ?></h3>
      <div class="post-inner">
        <div class="date-tab"><span class="month"><?php the_time('F') ?></span><span class="day"><?php the_time('j') ?></span></div>
        
		<?php the_post_thumbnail(); ?>

		<?php the_content( __( 'Continue reading', 'anarcho-notepad' ) ); ?>
      </div>
      <div class="meta">
	<?php _e('Category: ', 'anarcho-notepad'); ?><?php the_category(', ') ?> |  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
      </div>

      <?php anarcho_post_nav(); ?>

    </article>
    <?php endwhile; ?>

    <?php anarcho_page_nav(); ?>

    <?php else : ?>

	<div class="no-results">
		<h3>Not Found</h3>
		<p>Sorry, but you are looking for something that isn't here.</p>
	</div>

    <?php endif; ?>
  </div>

   <?php get_sidebar(); ?>
</section><br clear="all" />

<?php get_footer(); ?>