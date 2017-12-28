	<aside id="oloWidget" class="sky_side">
		<ul>
			<?php if (is_home()) { ?>
				<?php if ( !dynamic_sidebar('home') ) : ?>
					<li id="archives" class="widget">
						<h3 class="widget-title"><?php _e( 'Archives', 'olo' ); ?></h3>
						<ul>
							<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
						</ul>
					</li>

					<li id="meta" class="widget">
						<h3 class="widget-title"><?php _e( 'Meta', 'olo' ); ?></h3>
						<ul>
							<?php wp_register(); ?>
							<li><?php wp_loginout(); ?></li>
							<?php wp_meta(); ?>
						</ul>
					</li>
				<?php endif; ?>
			<?php } elseif( is_single() ) { ?>
				<?php dynamic_sidebar( 'single' ); ?>
			<?php } else { ?>
				<?php dynamic_sidebar( 'other' ); ?>
			<?php } ?>
		</ul>
	</aside><!-- #oloWidget-->