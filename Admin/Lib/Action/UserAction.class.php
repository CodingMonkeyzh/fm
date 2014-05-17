<?php
	class UserAction extends Action{
		public function _initialize(){
	        session_start();
	        if(!isset($_SESSION['admin']) || empty($_SESSION['admin'])){
	            $this->redirect("__APP__/Manage/login");
	        }
	    }
		public function information(){
			$this->user = "selected";
			$this->information = "selected";

			$adminName = $_GET["admin"];

			$ad = M("administrator");
			$admin = $ad->where("adminName='".$adminName."'")->find();
		//	var_dump($admin);
			$this->admin = $admin;
			$this->display();
		}
		public function adminUpdate(){
			$data["adminName"] = $_POST["adminname"];
			$data["name"] = $_POST["name"];
			$data["nikeName"] = $_POST["nikename"];
			$data["email"] = $_POST["email"];
			if(isset($_POST["passwd"]) && !empty($_POST["passwd"])){
				$data["password"] = md5($_POST["passwd"]);
			}

		//	var_dump($data);
			$ad = M("administrator");
			$admin = $ad->where($data)->find();
			if($admin){
				$this->success("资料更新成功!","__APP__/Fm/songs");
			}else{
				$count = $ad->save($data);
				if($count > 0){
					session_start();
					$_SESSION["nikename"] = $_POST["nikename"];
					$this->success("资料更新成功!","__APP__/Fm/songs");
				}else{
					$this->error("资料更新失败!");
				}
			}
		}
		public function adminAdd(){
			$data["adminName"] = $_POST["adminname"];
			$data["name"] = $_POST["name"];
			$data["nikeName"] = $_POST["nikename"];
			$data["email"] = $_POST["email"];
			$data["password"] = md5($_POST["passwd"]);
			$data["privilege"] = $_POST["role"];

			$ad = M("administrator");
			$count = $ad->add($data);
			if($count > 0){
				$this->success("管理员添加成功!","administrator");
			}else{
				$this->error("管理员添加失败!");
			}
		}
		public function users(){
			$this->user = "selected";
			$this->users = "selected";

			$us = M("user");
			$this->userList = $us->select();
			$this->display();
		}
		public function administrator(){
			$this->user = "selected";
			$this->administrator = "selected";

			$ad = M("administrator");
			$lg_user = $_SESSION['admin'];
			$user = $ad->where("adminName='".$lg_user."'")->find();
			$this->privilege = $user['privilege'];	//获取当前用户的权限

			$role = $_GET['role'];
			if(isset($role) && $role != "all"){
				$adminList = $ad->where("privilege=".$role)->select();
			}else{
				$adminList = $ad->select();
			}
			
			$this->adminList = $adminList;
			$this->display();
		}
		public function adminSearch(){
			$key = $_GET["key"];

			$ad = M("administrator");
			$lg_user = $_SESSION['user'];
			$user = $ad->where("adminName='".$lg_user."'")->find();
			$this->privilege = $user['privilege'];	//获取当前用户的权限

			$ad = M("administrator");
			$data["adminName"] = array("like","%$key%");
			$data["nikeName"] = array("like","%$key%");
			$data["email"] = array("like","%$key%");
			$data["_logic"] = "or";

			$this->adminList = $ad->where($data)->select();
		//	var_dump($adminList);
			$this->display("administrator");
		}
		public function adminDelete(){
			$admin = $_GET["admin"];

			$ad = M("administrator");
			$count = $ad->where("adminName='".$admin."'")->delete();
			if($count>0){
				$this->success("管理员删除成功!","administrator");
			}else{
				$this->error("管理员删除失败!");
			}
		}
		public function change_privilege(){
			$data["privilege"] = $_GET["role"];
			$data["adminName"] = $_GET["adminname"];

			$ad = M("administrator");
			$count = $ad->save($data);

			if($count > 0){
				echo true;
			}else{
				echo false;
			}
		}
	}

?>