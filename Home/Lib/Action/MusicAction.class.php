<?php
// 本类由系统自动生成，仅供测试用途
class MusicAction extends Action {
	public function _initialize(){
        session_start();
        if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
            $this->redirect("Manage/index");
        }
    }
    public function fm(){
    	$user = $_SESSION["user"];
    	$us = M("user");
    	$this->userPhoto = $us->where("userNumber=".$user)->getField("userPhoto");
        $this->userExplanation = $us->where("userNumber=".$user)->getField("userExplanation");

    	$mc = M('musicclass');
    	$this->classList = $mc->select();
        $ik = M("ilike");
        $ilikeInfo = $ik->where("userNumber=".$user)->find();
        $this->collection = count(json_decode($ilikeInfo["collection"]));

        $data["userNumber"] = $_SESSION["user"];

        $us = M("user");
        $userInfo = $us->where($data)->getField("userNumber,userSex,userEmail,userBirthday,userExplanation");
        $this->userInfo = $userInfo[$data["userNumber"]];

    	$this->display();
    }
    public function next(){
        header('Access-Control-Allow-Origin: *'); 

        $class = $_GET['musicClass'];
        $isSimilar = $_GET['isSimilar'];
        $musicID = $_GET['musicID'];
        $result = array();

        if($isSimilar) {
            $nextSimilar = $this->getSimilarMusic($musicID);
            if($nextSimilar) {
                $em = M("emotionmusic");
                $emotionLevel = $em->where("musicID=".$nextSimilar['musicID']);
                echo json_encode($nextSimilar);
                return ;
            }
        }

        if($class != 0){
            $hasHateList = implode(",", $this->getHateList());
            $hateList = '';
            if(!empty($hasHateList)){
                $hateList = " and musicID not in (".$hasHateList.")";
            }
            $ms = M("music");
            $result = $ms->where("musicClass=".$class.$hateList)->select();

        }else{ 
            $lovelist = $this->getLoveList();
            $result = array();
            $ms = M("music");
            foreach ($lovelist as $key => $value) {
                array_push($result, $ms->where("musicID=".$value)->find());
            }
        }

        $list = $this->getLoveList();
        $rand_num = mt_rand(0,count($result)-1);
        $return = $result[$rand_num];
        $return["musicLove"] = in_array($return["musicID"], $list) ? 1 : 0;
        $return["isSimilar"] = $isSimilar;
       
        echo json_encode($return);
    }

    public function ilike(){
        $musicID = $_GET['id'];

        $user = $_SESSION["user"];
        $ik = M("ilike");
        $type = 0;  //已有收藏0,第一次收藏1
        $clist = $ik->where("userNumber='".$user."'")->getField("collection");
        if(isset($clist) && !empty($clist)){
            $nlist = json_decode($clist);
            //Has been collected,remove it from list
            if(in_array($musicID, $nlist)){
                array_splice($nlist, array_search($musicID, $nlist), 1);
            }else{
                $nlist[] = $musicID;
            }
        }else{
            $nlist = array($musicID);
            $type = 1;
        }
        if(count($nlist) > 0){
            $clist = json_encode($nlist);
            $likeSum = count($nlist);
        }else{
            $clist = '';
        }
        

        $data["userNumber"] = $user;
        $data["collection"] = $clist;

        $sql = '';
        $sql_where = '';
        if($type == 0){
            $sql = 'UPDATE ilike ';
            $sql_where = " WHERE userNumber='".$user."'";
        }else{
            $sql = 'INSERT INTO ilike ';
        }
        
        $sql .= " SET collection='".$clist."', userNumber='".$user."' ".$sql_where;
        if($ik->execute($sql)){
            $res['msg'] = 1;
            $res['count'] = $likeSum;
            echo json_encode($res);
        }else{
            $res['msg'] = 0;
            $res['count'] = $likeSum;
            echo json_encode($res);
        }
    }

    // 歌曲黑名单
    public function ihate(){
        $musicID = $_GET['id'];
        $user = $_SESSION["user"];
        $ih = M("ihate");
        $type = 0;  //已有收藏0,第一次收藏1
        $clist = $ih->where("userNumber='".$user."'")->getField("hateList");
        if(isset($clist) && !empty($clist)){
            $nlist = json_decode($clist);
            $nlist[] = $musicID;
        }else{
            $nlist = array($musicID);
            $type = 1;
        }
        if(count($nlist) > 0){
            $clist = json_encode($nlist);
        }else{
            $clist = '';
        }
        
        $data["userNumber"] = $user;
        $data["hateList"] = $clist;

        $sql = '';
        $sql_where = '';
        if($type == 0){
            $sql = 'UPDATE ihate ';
            $sql_where = " WHERE userNumber='".$user."'";
        }else{
            $sql = 'INSERT INTO ihate ';
        }
        
        $sql .= " SET hateList='".$clist."', userNumber='".$user."' ".$sql_where;
        if($ih->execute($sql)){
            $res['msg'] = 1;
            echo json_encode($res);
        }else{
            $res['msg'] = 0;
            echo json_encode($res);
        }
    }

    private function getLoveList(){
        $user = $_SESSION["user"];
        $ik = M("ilike");
        $arr = $ik->where("userNumber=".$user)->getField("collection");
        $list = json_decode($arr);
        return $list;
    }

    private function getHateList() {
        $user = $_SESSION["user"];
        $ih = M("ihate");
        $arr = $ih->where("userNumber=".$user)->getField("hateList");
        $list = json_decode($arr);
        return $list;
    }

    //获取相似音乐
    public function getSimilarMusic($musicID) {
        $em = M('emotionmusic');  //database object
        $ms = M('music');
        $currentMusic = $em->where('musicID = '.$musicID)->find();
        arsort($currentMusic);
        foreach ($currentMusic as $key => $value) {
            if($currentMusic[$key]!= '0' && $key != 'musicID') {
                $sql = 'select musicID from emotionmusic where '.$key.' != 0 and musicID != '.$musicID;
                $temp = $em->query($sql);

                if($temp) {
                    $idListStr = '';
                    for($i = 0; $i < count($temp); $i++) {
                        if($i == 0) {
                            $idListStr .= "'".$temp[$i]['musicID']."'";
                        }else{
                            $idListStr .= ",'".$temp[$i]['musicID']."'"; 
                        }
                    }
                    $recommendList = $ms->field('musicName, musicSinger')->where("musicID in (".$idListStr.")")->limit(3)->select();
                    $rand_num = mt_rand(0,count($temp)-1);
                    $nextMusicID = $temp[$rand_num]['musicID'];
                    $resultSql = 'select * from music as t1, emotionmusic as t2 where t1.musicID = t2.musicID and t2.musicID = '.$nextMusicID;
                    // $result = M() ->table(array('music'=>'t1','emotionmusic'=>'t2'))->where('t1.musicID='.$nextMusicID.' and t2.musicID='.$nextMusicID)->field('*');
                    $result = $ms->where("musicID=".$nextMusicID)->find();
                    $result["emotionInfo"] = $em->where("musicID=".$nextMusicID)->find();
                    $result["recommendList"] = $recommendList;
                    // $res = $ms->query($resultSql);
                    $list = $this->getLoveList();
                    $result["musicLove"] = in_array($result["musicID"], $list) ? 1 : 0;
                    return $result;
                }
            }
        }
    }

}