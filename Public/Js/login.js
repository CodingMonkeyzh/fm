function submit(){
	var username = $("#username").val();
	var password = $("#password").val();

	if(username == "" || password ==""){
		alert("登录信息不能为空!");
	}else{
		document.userInfo.submit();
	}
}
var ie =navigator.appName=="Microsoft Internet Explorer"?true:false;
   
function keyDown(e)
{
	var keyNum = ie ? event.keyCode : e.which;
	if(keyNum == 13){
		submit();
	}
}
document.onkeydown = keyDown;