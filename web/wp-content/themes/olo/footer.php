	<div class="clear"></div>
	<footer>
		<div class="copyright">
			<p><?php _e('CopyRight', 'olo'); ?>&nbsp;&copy;&nbsp;<?php echo date("Y"); ?>&nbsp;<a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a>, <a href="http://hjyl.org/" target="_blank" title="Designed by hjyl.org">olo Theme</a>, <a href="http://t.qq.com/igeyan" target="_blank"> Upgrade by Baby</a> <a target="_blank" href="http://itbbb.com/sitemap.html">网站地图</a> <a target="_blank" href="http://itbbb.com/index.php/about-me/">关于我</a> <?php global $olo_theme_options; if($olo_theme_options['is_olo_icp']== 1) { ?> <?php $olo_icp = esc_attr($olo_theme_options['olo_icp']); echo $olo_icp; ?><?php } ?></p>
		</div>

		<div class="mauto">
		  <div id="sky_gotop" class="sico backtop"></div>
		</div>	
	</footer>
<?php wp_footer(); ?>
<script src="http://www.itbbb.com/jsfunction/highlight/highlight.pack.js"></script>
<!-- JiaThis Button BEGIN -->
<script type="text/javascript">
var jiathis_config = {data_track_clickback:'true'};
</script>
<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jiathis_r.js?move=0&amp;btn=r5.gif&amp;uid=1590067" charset="utf-8"></script>
<!-- JiaThis Button END -->
<div style="display:none;">
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F665ed821ce0c947e5ccb95d1dbed69a8' type='text/javascript'%3E%3C/script%3E"));
</script>
<script src="http://s23.cnzz.com/stat.php?id=5816290&web_id=5816290" language="JavaScript"></script>
</div>
<!-- UJian Button BEGIN
<script type="text/javascript" src="http://v1.ujian.cc/code/ujian.js?type=slide&uid=1590067"></script>
<a href="http://www.ujian.cc" style="border:0;"><img src="http://img.ujian.cc/pixel.png" alt="友荐云推荐" style="border:0;padding:0;margin:0;" /></a>
-->
</body>
</html>