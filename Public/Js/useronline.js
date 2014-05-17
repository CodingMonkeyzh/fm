$(document).ready(function(){
	$("a.wordspeak").click(function(){
		var _this = $(this);
		var option = 0;
		if(_this.html() == "禁言"){
			var option = 1;
		}
		var num = $(this).parent().siblings(".userNumber").children(0).html();
		$.get("/hnust2/admin.php/Chatroom/Words",{user:num,opt:option},function(data){
			if(data == "true"){
				if(option == 0){
					_this.html("禁言");
					alert("该用户已经恢复言论!");
				}else{
					_this.html("恢复");
					alert("该用户已经被禁言!");
				}
			}else{
				alert("操作失败，请稍后重试!");
			}
		});
	});
});