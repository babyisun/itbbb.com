<?php
/**
 * The template for displaying 404 pages (Not Found).
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

    <h1>Page Not Found</h1>
    <p>We're very sorry, but the page you requested has not been found! It may have been moved or deleted.</p>
    <p>I'm not blaming you, but have you checked your address bar? There might be a typo in the URL.</p>
    <p>If there isn't, you could try searching my website for the content you were looking for:</p>
    <?php get_search_form(); ?>
    
    <p>Alternatively, you can go to  or </p>
    <p>One last thing, if you're feeling so kind, please tell me</a> about this error, so that I can fix it. Thanks!</p>

    <?php endif; ?>
  </div>

   <?php get_sidebar(); ?>
</section><br clear="all" />

<?php get_footer(); ?>