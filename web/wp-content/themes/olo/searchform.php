<form method="get" id="searchform" action="<?php echo home_url(); ?>/" >
  <input type="text" class="field" id="s" name="s" value="<?php the_search_query(); ?>" placeholder="<?php _e( 'Search...', 'olo'); ?>" required="required" />
  <input type="submit" class="submit" name="submit" id="searchsubmit" value="Search" />
</form>