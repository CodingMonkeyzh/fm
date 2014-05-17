<?php
class PublicAction extends Action {
	public function _initialize(){
        session_start();
        if(!isset($_SESSION['admin']) || empty($_SESSION['admin'])){
            $this->redirect("__APP__/Manage/login");
        }
    }
	public function play(){
		$this->display();
	}
}

?>