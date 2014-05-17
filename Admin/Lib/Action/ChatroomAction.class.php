<?php
	class ChatroomAction extends Action{
		public function _initialize(){
	        session_start();
	        if(!isset($_SESSION['admin']) || empty($_SESSION['admin'])){
	            $this->redirect("__APP__/Manage/login");
	        }
	    }
		public function useronline(){
			$this->chat = "selected";
			$this->useronline = "selected";

			$us = M("user");
			$this->onlinelist = $us->where("status=1")->select();
			$this->display();
		}
		public function records(){
			$this->chat = "selected";
			$this->records = "selected";

			$who = $_GET["user"];

			$cr = M("chatrecords");
			$data["speaker"] =  $who;
			$this->recordlist = $cr->where($data)->order("time desc")->select();

			if(!isset($who) || empty($who)){
				$this->recordlist = $cr->order("time desc")->select();
			}
			$this->display();
		}
		public function userSearch(){
			$this->chat = "selected";
			$this->useronline = "selected";

			$key = $_GET["key"];
			$us = M("user");

			$data["userName"] = array("like","%{$key}%");
			$data["status"] = 1;
			$this->onlinelist = $us->where($data)->select();
			$this->display("useronline");
		}
		public function recordSearch(){
			$this->chat = "selected";
			$this->records = "selected";

			$key = $_GET["key"];
			$cr = M("chatrecords");

			$data["content"] = array("like","%{$key}%");
			$this->recordlist = $cr->where($data)->order("time desc")->select();
			$this->display("records");
		}
		public function exitRoom(){
			$user = $_GET["user"];

			$us = M("user");
			$data["userName"] = $user;
			$data["status"] = 0;
			$count = $us->save($data); 
			if($count > 0){
				echo "true";
			}else{
				echo "false";
			}
		}
		public function Words(){
			$user = $_GET["user"];
			$opt = $_GET["opt"];

			$us = M("user");
			$data["userNumber"] = $user;
			$data["ban"] = $opt;
			$count = $us->save($data); 
			if($count > 0){
				echo "true";
			}else{
				echo "false";
			}
		}
		public function deleteRecord(){
			$data["id"] = $_GET["id"];

			$cr = M('chatrecords');
			$count = $cr->where($data)->delete();
			if($count > 0){
				$this->success("删除成功!");
			}else{
				$this->error("删除失败!");
			}
		}
	}