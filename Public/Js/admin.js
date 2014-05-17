$(document).ready(function(){
	$(".selected").siblings().attr("style","display:block");
	$("ul.menulist li h2").click(function(){
		var children = $("ul.menulist li").find(".sub-menu");
		for(var i=0 ; i<children.length ; i++){
			if(children[i] != $(this).siblings()[0]){
				$(children[i]).slideUp();
			}else{
				if($(children[i]).css("display") == "block"){
					return ;
				}
			}
		}
		$(this).parent().find(".sub-menu").slideToggle();
	});
	$(".ad-account").mouseover(function(){
		$(".welcome").css({background:"#fff",color:"#222"});
		$("#admin-user-action").css("display","block");
	});
	$(".ad-account").mouseout(function(){
		$(".welcome").css({background:"#464646",color:"#CCCCCC"});
		$("#admin-user-action").css("display","none");
	});
	$("._submit").mousedown(function(){
		$(this).css("background","linear-gradient(to bottom, #999, #fff) repeat scroll 0 0 #fff");});
	$("._submit").mouseup(function(){
		$(this).css("background","linear-gradient(to bottom, #FEFEFE, #F4F4F4) repeat scroll 0 0 #F3F3F3");
	});
});
