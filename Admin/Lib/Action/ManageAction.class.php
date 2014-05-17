<?php
	class ManageAction extends Action{
		public function login(){
			$this->display();
		}
		public function verify(){
			$data = array();
			$m = M("administrator");
			$data['adminName'] = $_POST['username'];
			$data['password'] = md5($_POST['password']);
			$admin = $m->where($data)->find();
			if(isset($admin) && !empty($admin)){
				session_start();
				$_SESSION['admin'] = $_POST['username'];
			//	var_dump($admin);
				$_SESSION['nikename'] = $admin["nikeName"];
				$this->redirect("__APP__/Index/index");
			}else{
				$this->error("用户名或密码错误!");
			}
		}
		public function logout(){
			$_SESSION = array();
			if(isset($_COOKIE[session_name()])){
				setcookie(session_name(),"",time()-1,"/");
			}
			session_destroy();
			$this->redirect("login");
		}
	}

?>