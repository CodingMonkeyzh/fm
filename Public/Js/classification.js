$(document).ready(function(){
	$(".bt-submit").mousedown(function(){
		$(this).css("background","linear-gradient(to bottom, #999, #fff) repeat scroll 0 0 #fff");});
	$(".bt-submit").mouseup(function(){
		$(this).css("background","linear-gradient(to bottom, #FEFEFE, #F4F4F4) repeat scroll 0 0 #F3F3F3");
	});
	$(".modify").click(function(){
		if($(this).siblings().val()){
			$("form[name='modifyform']").submit();
		}else{
			alert("分类目录名不能为空!");
		}
	});
});