<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
	public function _initialize(){
        session_start();
        if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
            $this->redirect("Manage/index");
        }
    }
    public function index(){
    	$this->redirect("Manage/index");
    }
}