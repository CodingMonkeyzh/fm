$(document).ready(function(){

	$("#submit").click(function(){
		var adminName = $("input[name='adminname']").val();
		var nikeName = $("input[name='nikename']").val();
		var email = $("input[name='email']").val();
		var passwd = $("input[name='passwd']").val();
		var repasswd = $("input[name='repasswd']").val();

		if(adminName.length == 0){
			alert("您还未登录!");
			window.location = "/hnust2/admin.php/Manage/login";
			return false;
		}
		if(nikeName.length == 0){
			alert("昵称不能为空!");
			return false;
		}
		if(email.length == 0){
			alert("邮箱不能为空!");
			return false;
		}
		if(passwd != repasswd){
			alert("密码不一致!");
			return false;
		}

		$("#infoform").submit();
	});

});