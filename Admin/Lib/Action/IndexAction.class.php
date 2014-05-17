<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
	public function _initialize(){
        session_start();
        if(!isset($_SESSION['admin']) || empty($_SESSION['admin'])){
            $this->redirect("__APP__/Manage/login");
        }
    }
    public function index(){
    	$this->redirect("Fm/songs");
    }
}