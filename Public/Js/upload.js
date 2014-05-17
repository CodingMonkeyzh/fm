$(document).ready(function(){
	$(".scan").mousedown(function(){
		$(this).css("background","linear-gradient(to bottom, #999, #fff) repeat scroll 0 0 #fff");});
	$(".scan").mouseup(function(){
		$(this).css("background","linear-gradient(to bottom, #FEFEFE, #F4F4F4) repeat scroll 0 0 #F3F3F3");
	});
	$("#bt-scan").click(function(){
		$("#file").click();
	});
	$("#file").change(function(){
		$("#scan-fileaddr").val($(this).val());
	});
	$(".edit").click(function(){
		$("#edit-input").attr("readonly",false);
		$("#edit-input").focus();
	});
	$("#edit-input").blur(function(){
		$(this).attr("readonly",true);
	});
	$("#img-scan").click(function(){
		$("#doc").click();
	});
});

function handleFileSelect(evt) 				
{
	var files = evt.target.files; // FileList object
	for (var i = 0, f; f = files[i]; i++) 
	{
	  var reader = new FileReader();
	  reader.onload = (function(theFile) 
	  {
	    return function(e) 
	    {
			$("div.co").html(['<img id="cover" src="'+e.target.result+'" title="'+escape(theFile.name)+'"/>'].join(''));
		//	$("#cover").attr("src",e.target.result);
	    };
	  })(f);
	  reader.readAsDataURL(f);
	}
}
  function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) { $('#img_prev').attr('src', e.target.result); };
        reader.readAsDataURL(input.files[0]);
    } else {
        //IE下，使用滤镜
        var docObj = document.getElementById('doc');
        docObj.select();
        //解决IE9下document.selection拒绝访问的错误
        docObj.blur();
        var imgSrc = document.selection.createRange().text;
        var localImagId = document.getElementById("localImag");
        $('#localImag').width(100).height(100); //必须设置初始大小
        //图片异常的捕捉，防止用户修改后缀来伪造图片
        try {
            localImagId.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
            localImagId.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;
        } catch (e) {
            alert("您上传的图片格式不正确，请重新选择!"); return false;
        }
        $('#img_prev').hide();
        document.selection.empty();
    }
}