$(document).ready(function(){
	$(".edit-privilege").click(function(){
		$(this).parent().siblings().find("a.ad").hide();
		$(this).parent().siblings().find(".change_privilege").show();
	});
	$(".change_cancle").click(function(){
		$(this).parent().hide();
		$(this).parent().siblings().show();
	});
	$("#privilege_item").change(function(){
		var adminName = $("#adname").val();
		var item = $("#privilege_item").val();

		$.get("/hnust2/admin.php/User/change_privilege",{role:item,adminname:adminName},
			function(data){
			if(data){
				alert("修改成功!");
				var new_role = item == 0 ? "管理员" : "超级管理员";
				$("#privilege_item").parent().siblings().html(new_role);
				$("#privilege_item").parent().hide();
				$("#privilege_item").parent().siblings().show();
			}else{
				alert("修改失败!");
			}
		});
	});
});