<?php
/**
 * The template for displaying Search Results pages.
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
  <?php anarcho_breadcrumbs(); ?>

<div id="center">

<h1><?php printf( 'Search Results for: %s', get_search_query() ); ?></h1>

  <?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h3>
      <div class="post-inner">

        <a href="<?php the_permalink(); ?>"><div class="date-tab"><span class="month"><?php the_time('F') ?></span><span class="day"><?php the_time('j') ?></span></div></a>

		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>

		<?php the_content( __( 'Continue reading', 'anarcho-notepad' ) ); ?>
      </div>
      <div class="meta"><?php _e('Category: ', 'anarcho-notepad'); ?><?php the_category(', ') ?></div>
                </article>
   <?php endwhile; ?>

   <?php anarcho_page_nav(); ?>

   <?php else :
            echo _e('Sorry for your result nothing found', 'anarcho-notepad');
         endif; ?>
    </div>

  </div>

   <?php get_sidebar(); ?>
</section><br clear="all" />

<?php get_footer(); ?>