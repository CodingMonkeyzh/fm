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

function invalidPaswd(event){
    // alert("text");
    var c_paswd = $("input[name='c_paswd']").val();
    var n_paswd = $("input[name='n_paswd']").val();
    var ren_paswd = $("input[name='ren_paswd']").val();
   
    // console.log(c_paswd +','+ n_paswd +','+ ren_paswd);
    if((c_paswd+n_paswd+ren_paswd).length < 3){
        $(".error").html("<p>密码为空!</p>").fadeIn();
        event.preventDefault();
    }
    else if(n_paswd!=ren_paswd){
         $(".error").html("<p>两次输入的新密码不一致!</p>").fadeIn();
         event.preventDefault();
        
    }
    
}

$(document).ready(function(){
    $("form tr:even").css('backgroundColor', '#eee');
    
    $(".sideNav ul li").click(function(event) {
        /* Act on the event */
        var index = $(this).index();
        $(".contain>div").css('display', 'none');
        $(this).parent().children().removeClass('active');
        $(this).addClass('active');
        $($(".contain>div")[index]).fadeIn('slow', function() {
            
        });
    });
    $("#toSubmitChange").bind('click', function(event) {
        /* Act on the event */
        invalidPaswd(event);
    });

    $('#updateUserInfo').on('submit', function(e) {
        e.preventDefault();

        var that = this;
        $(that).ajaxSubmit(function(data) {
            console.log(data);
            if(data == "1") {
                alert('资料更新成功!');
            }else {
                alert('资料更新失败!');
            }
        });
    });

    $('#updateUserPass').on('submit', function(e) {
        e.preventDefault();

        var that = this;
        var formValues = {};
        $.each($(that).serializeArray(), function(index, value) {
            formValues[value.name] = value.value;
        });

        $.post("/hnust2/index.php/Manage/changePaswd", formValues, function(data) {
            if(data == "1") {
                alert('密码修改成功!');
                $(".changePswd input").each(function(){
                    $(this).val("");
                });
            }else {
                alert(data);
            }
        });

    });
    

});