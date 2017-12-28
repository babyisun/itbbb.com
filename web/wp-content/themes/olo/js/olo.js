jQuery(document).ready(function($){
$body=(window.opera)?(document.compatMode=="CSS1Compat"?$('html'):$('body')):$('html,body');
$('#oloUp').click(function(){
		$body.animate({scrollTop:0},400);
});
}); 
jQuery(document).ready(function($){
setTimeout(function(){
var h1 = $(".oloPosts").height();
	var h2 = $("#oloContainer #oloWidget").height();

	if(h2 < h1){
		$("#oloContainer #oloWidget").height(h1);
		}else {
		$(".oloPosts").height(h2);
		}
},1000);
}); 