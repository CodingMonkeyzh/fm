$(function(e){
var currentAngle = 0;
var tempMarginTop = 0;
var temp;
var count;
var isSimilar = 0;	//是否在听相似的歌曲
var currentSimilarMusicID;

	var rotation = function(){
			$("#coverImage").rotate({
			duration: 8000,
			angle: currentAngle%360,
			animateTo:360+currentAngle%360,
			callback: rotation,
			easing: function(x,t,b,c,d){
				// t: current time, b: begInnIng value, c: change In value, d: duration
				return c*(t/d) + b;
			}
		});
		
	};
	function extractDelta(e) {
	    if (e.wheelDelta) {
	        return e.wheelDelta;
	    }

	    if (e.originalEvent.detail) {
	        return e.originalEvent.detail * -40;
	    }

	    if (e.originalEvent && e.originalEvent.wheelDelta) {
	        return e.originalEvent.wheelDelta;
	    }
	}
	function Scroll(event, delta) {


	    var liHeight = $('.list').find('ul').children('li').first().height();
	    var count = $('.list ul').find('li').length;
	    var temp = -(liHeight + 20);
	    var d = extractDelta(event);
	    var listHeight = $(".list").height();
	    
	    if (d / 120 > 0) {
	        if (tempMarginTop >= 0) {
	            tempMarginTop = 0;
	        }
	        else {          
	            tempMarginTop = tempMarginTop - temp;
	        }       
	    }
	    else {
	        tempMarginTop = tempMarginTop + temp;
	    }
	    // 是否到达底部
	   
	    if (-tempMarginTop >= -(temp * (count + 1) + listHeight)) {
	        tempMarginTop = tempMarginTop - temp;
	    }
	    else {
	        $('.list').find('ul').css('margin-top', tempMarginTop + 'px');
	    }
	    return false;
	}

	var rollList = function(){
		var liHeight = $('.list').find('ul').children('li').first().height();
        count = $('.list ul').find('li').length / 2;
        temp = -(liHeight + 20);
       
        var margintop = $('.list').find('ul').css('margin-top');
        
        tempMarginTop = parseInt(margintop.toString().substring(0, margintop.length - 2));
        
		 $('.list').bind("mousewheel DOMMouseScroll", function (event, delta) {
            return Scroll(event, delta);
        });
	}
	
	
		
	var initPlayer = function(){
		
		// 初始类别
		var currentMusicClass = 1;
		var player = $("audio")[0];
		var coverImage, musicSinger, musicAlbum, musicName, musicSrc, love=0;


		function setSongInfo(coverImage, musicSinger, musicAlbum, musicName, musicSrc, love, musicID){
			$("#coverImage").fadeOut(400, function() {
				$("#coverImage").attr("src", coverImage);
				$(this).fadeIn(400);
			});
			$("#musicID").val(musicID);
            $("#musicSinger").text(musicSinger);
            $("#musicAlbum").text("《" + musicAlbum + "》");
            $("#musicName").html('<i class="icon-music"></i> ' + musicName);
            player.setSrc(musicSrc);
            document.title = musicName + " - 科大FM";

            // 设置“喜欢”按钮的状态
            if(love===1){
            	$("#like").css("color","#e74c3c");
            }
            else{
            	$("#like").css("color","#fff");
            }
            
		}

		// 获取歌曲信息
		function getSongInfo(){
			var musicUrl = "/hnust2/Public/Songs/";
			var coverUrl = "/hnust2/Public/Covers/";
			var musicID = isSimilar?currentSimilarMusicID:$("#musicID").val();
			$.getJSON(
				'/hnust2/index.php/Music/next',
				{musicClass: currentMusicClass, isSimilar: isSimilar, musicID: musicID}

			)
			.done(function(json){
				// 获取成功执行以下操作
					musicID = json.musicID;
					musicSinger = json.musicSinger;
					musicAlbum = json.musicAlbum;
					musicName = json.musicName;
					coverImage = coverUrl + json.musicID + ".jpg";
					musicSrc = musicUrl + json.musicID + ".mp3";
					love = json.musicLove;
					// alert("love form get:" + love);
					setSongInfo(coverImage, musicSinger, musicAlbum, musicName, musicSrc, love, musicID);
					player.play();
					play = 1;

					$("#similarMusicInfo .keyword").html(json.keyword);
					$("#similarMusicInfo .emotionLevel").html(function() {
						var content = '';
						for(var prop in json.emotionInfo) {
							if(prop != 'musicID') {
								if(json.emotionInfo[prop] > 0) {
							 		content += '<span style="background:#e74c3c; border-radius:3px; color:#FFFFFF">' + prop + ' : ' + json.emotionInfo[prop] + '</span>';
								}else{
							 		content += '<span>' + prop + ' : ' + json.emotionInfo[prop] + '</span>';
								}
							}
						}
						return content;
					});
					console.log(json);
				})
			.fail(function(textStatus, error){
				// 获取失败
				var err = textStatus + ", " + error;
				alert("╮(╯_╰)╭非常抱歉！数据获取失败: " + err);
			});
			
		}



		$('#audio-player').mediaelementplayer({
            alwaysShowControls: true,
            features: ['playpause','volume','progress'],
            audioVolume: 'horizontal',
            audioWidth: 360,
            audioHeight: 250,
        });

		var Mplayer = new MediaElement('audio-player', {
			success:function(player){
				getSongInfo();
				
				rotation();
				// 播放完一首歌曲
				player.addEventListener('ended', function() {
             		currentAngle = 0;
             		$("#coverImage").stopRotate();
             		getSongInfo();
             		
             		rotation();
       			}, false);
			}
		});

		// 点击播放/暂停
		$(".mejs-play button").bind("click",function(){
			if(play==0){
				rotation();
				play = 1;
			}
			else{
				$("#coverImage").stopRotate();
				currentAngle = $("#coverImage").getRotateAngle();
				play = 0;
			}
			
		});

		// 敲击空格,播放/暂停
		$(document).bind('keydown', function(e) {
			if(e.keyCode == 32) {
				if(play==0){
					rotation();
					play = 1;
				}
				else{
					$("#coverImage").stopRotate();
					currentAngle = $("#coverImage").getRotateAngle();
					play = 0;
				}
			}
		});

		// 点击“下一首”
		$("#nextSong").bind("click", function(){

			getSongInfo();
			currentAngle = 0;
         	$("#coverImage").stopRotate();
        	rotation();
        	play = 1;
			
		});

		// 点击分类列表
		$(".list ul li").bind("click", function(){
			// 判断是否在听相似歌曲
			if(isSimilar == 1) {
				isSimilar = 0;
				$(".similarMusicWrap").html("相似歌曲");
			}
			$(this).siblings().removeClass("active");
			$(this).addClass("active");
			currentMusicClass = $(this).attr("id");
			changeWrap(currentMusicClass);
			getSongInfo();
			currentAngle = 0;
         	$("#coverImage").stopRotate();
        	rotation();
        	play = 1;
		});

		// 点击喜欢
		$("#like").click(function(){
			var id = $("#musicID").val();
			var isLike = $("#like").css("color");
			$.getJSON("/hnust2/index.php/Music/ilike",{id:id},function(data){
				if(data.msg == "1"){
					//$("#like").css("color","#e74c3c");
					// var count = $(".userInfo p span").html();
					$(".userInfo p span").html(data.count);

					if(isLike=='rgb(231, 76, 60)'){
						$("#like").css("color","#fff");
					}else{
						$("#like").css("color","#e74c3c");
					}
				}else{
					alert("操作失败!");
				}
			});
		});

		// 点击移入垃圾桶
		$("#hate").click(function() {
			var id = $("#musicID").val();
			$.getJSON("/hnust2/index.php/Music/ihate", {id:id}, function(data) {
				if(data.msg == "1") {
					$("#nextSong").trigger("click");
				}
			})
		});

		// 点击"相似歌曲"
		$(".similarMusicWrap").click(function(e) {
			var that = this;
			if(isSimilar == 0) {
				isSimilar = 1;
				isFirst = 1;
				currentSimilarMusicID = parseInt($("#musicID").val());
				$("#nextSong").trigger('click');
				$(that).html('正在播放相似歌曲');
			}
			return false;
		});

		// 显示相似歌曲信息
		var similarMusicTimer;
		$(".similarMusicWrap").bind('mouseover', function(e) {
			if(isSimilar) {
				$("#similarMusicInfo").css({
					left : $(this).offset().left - 200,
					top  : $(this).offset().top + 40,
				});
				$("#similarMusicInfo").fadeIn();
			}
		});
		$("#similarMusicInfo").bind('click', function(e) {
			e.stopPropagation();
		});
		$(document).click(function() {
			$("#similarMusicInfo").fadeOut();
		});
	}

	// 初始化侧边拉出块
	var initSidebar = function(){
		$("#sidebarCtl .ctlBtn").toggle(
			function(){
				$("#sidebar").animate({"left": "+=48%"}, "slow");
				$(".ctlBtn span i").removeClass("icon-chevron-right").addClass("icon-chevron-left");
			},
			function(){
				$("#sidebar").animate({"left": "-=48%"}, "slow");
				$(".ctlBtn span i").removeClass("icon-chevron-left").addClass("icon-chevron-right");
			}
		);

		/*click setting button*/
		$('#userSetting').click(function(e) {
			if(!$('#sidebar').is(':animated')){
				$('.ctlBtn').trigger('click');
			}
			e.preventDefault();
		});

	};
	// 初始化聊天室
	var initChatRoom = function(){
		var chatRoomRcvHeight = $(window).height()-180;
		var chatRoomHeight = chatRoomRcvHeight + 130;
		var currentBottom = 1;
		$("#chatroom").height(chatRoomHeight).css("bottom", -chatRoomHeight+70 + "px");
		$(".chatRoomRcv").height(chatRoomRcvHeight);

		$(".chatRoomHeader span").toggle(
			function(){
				if($("#similarMusicInfo").is(':visible')) {
					$("#similarMusicInfo").fadeOut();
				}
				chatRoomRcvHeight = $(window).height()-180;
				chatRoomHeight = chatRoomRcvHeight + 130;
				$(".chatRoomRcv").height(chatRoomRcvHeight);
				$("#chatroom").height(chatRoomHeight);
				$("#chatroom").animate({"bottom":"0px"}, "slow", function(){
					$("#msgContent").focus();
				});
				currentBottom = 0;
				$(".chatRoomHeader span i").removeClass("icon-angle-up").addClass("icon-angle-down");
			},
			function(){
				if($("#similarMusicInfo").is(':visible')) {
					$("#similarMusicInfo").fadeOut();
				}
				$("#chatroom").animate({"bottom":-chatRoomHeight+70 + "px"}, "slow");
				currentBottom = 1;
				$(".chatRoomHeader span i").removeClass("icon-angle-down").addClass("icon-angle-up");
			}
		);
		$(window).resize(function(){
				chatRoomRcvHeight = $(window).height()-180;
				chatRoomHeight = chatRoomRcvHeight + 130;
				if(currentBottom == 0){
					console.log("bottom 0");
					$(".chatRoomRcv").height(chatRoomRcvHeight);
					$("#chatroom").height(chatRoomHeight);
				}
		});
	}

	// 连接到聊天室
	var connectChatRoom = function(){
		// var name = prompt("起一个响亮的名头吧！");
		var Num = $("#userNo").val();
		var Name = $("div.chatRoomHeader h3").html();
		if(Num){
			window.onload = function(){
				url = "ws://127.0.0.1:8888/hnust2/Home/Tpl/Music/server.php";	//服务器地址,ws://为ws协议
				try{
					sock = new WebSocket(url);

					sock.onopen = function(){
						var join = {};
						join = {"type":"join","user":Num,"userName":Name};
						join = JSON.stringify(join);
						sock.send(join);
					}
					sock.onmessage  = function(e){
						var info = JSON.parse(e.data);
						console.log(info);
						switch(info.type){
							case "join":
								$('<p class="alertMsg">欢迎 ' + info.userName + ' 加入聊天室</p>').appendTo('.chatRoomRcv');
								$(".chatRoomRcv").get(0).scrollTop = $(".chatRoomRcv").get(0).scrollHeight;
								break;
							case "msg":
								var date = new Date(info.content.time);
								var time = date.getHours() + ':' + date.getMinutes();
								if(info.content.user !== Num){
									$('<div class="receiveMsg"><img src="/hnust2/Public/Photos/'+info.photo+'.jpg" alt="userImage" width="50px" height="50px" /><span class="borderAngle-left"></span><p>'+info.content.message+'<span>'+time+'</span></p><span class="msgPubTime">'+info.content.userName+'</span></div>').appendTo(".chatRoomRcv");
								}
								else{
									$('<div class="sendMsg"><img src="/hnust2/Public/Photos/'+info.photo+'.jpg" alt="userImage" width="50px" height="50px" /><span class="borderAngle-right"></span><p>'+info.content.message+'<span>'+time+'</span></p><span class="msgPubTime">'+info.content.userName+'</span></div>').appendTo(".chatRoomRcv");
								}
								$(".chatRoomRcv").get(0).scrollTop = $(".chatRoomRcv").get(0).scrollHeight;
								break;
							case "exit":
								$('<p class="alertMsg"> ' + info.userName + ' 退出了聊天室</p>').appendTo('.chatRoomRcv');
								$(".chatRoomRcv").get(0).scrollTop = $(".chatRoomRcv").get(0).scrollHeight;
								break;
							default :
								$('<p class="alertMsg">' + info.message + '</p>').appendTo('.chatRoomRcv');
								$(".chatRoomRcv").get(0).scrollTop = $(".chatRoomRcv").get(0).scrollHeight;
						}
					}
					sock.onclose = function(e){
						$.get("/hnust2/admin.php/Chatroom/exitRoom",{user:Num},function(data){
						if(data == "true"){
							alert("与服务器断开连接");
						}
				});
					//	document.getElementById("show").innerHTML += "<br />关闭连接 " + this.readyState + "<br />";
					}
				}catch(ex){
					$(".alertMsg").html(ex);
				}

				// 响应“发送消息”按钮的点击事件
				$("#sendMsg").click(function(){
						var msg = $("#msgContent").val();
						var user = sent = {};
						user = {"user":Num, "userName":Name, "message":msg, "time":(Date.parse(new Date))};
						sent = {"type":"msg", "content":user};
						var post = JSON.stringify(sent);
						sock.send(post);
						
						$("#msgContent").val("").focus();
				});
				// 监听回车
				$("body").keyup(function(event){
					if (event.keyCode == 13&&$("#msgContent").val()!='' ){
                		$('#sendMsg').click();
             		}
				});
			} //window.onload
		}//
	}

	// 更改背景图片
	function changeWrap(index){
		$("#wrap").fadeOut(400, function(){
			$(this).css('background-image','url("/hnust2/Public/Images/bg_'+index +'.jpg")');
		}).fadeIn(400);
	};



	/*TODO:  init function*/
	initPlayer();
	initSidebar();
	initChatRoom();
	rollList();
	connectChatRoom();

});
	


	
