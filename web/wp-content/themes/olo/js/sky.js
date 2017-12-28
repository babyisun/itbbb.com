// author: baby  
// from:   itbbb.com
;$(function(){
	var $win=$(window);
	var Init=function(){
	var _width=$win.width()||$(document).width(),left = $("#oloContainer .oloPosts"),right = $("#oloContainer .sky_side");
	if(_width<767){left.removeAttr("style");right.removeAttr("style");return;}
	var h1=left.height(),h2=right.height();
	if(h2 < h1){right.height(h1);
	}else {left.height(h2);}
	}
	//GoTop
	$("#sky_gotop").click(function(){
		$('html,body').animate({scrollTop: 0}, 300);
	});
	//resize
	 $win.resize(function(){ Init();});
	 
	 setTimeout(function(){Init();},500);

         hljs.configure({"tabReplace":"    "});
         $('pre code').each(function(i, block) {
            hljs.highlightBlock(block);
         });
           
});