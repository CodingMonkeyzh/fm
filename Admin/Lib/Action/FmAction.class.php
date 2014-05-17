<?php
class FmAction extends Action {
    public function _initialize(){
        session_start();
        if(!isset($_SESSION['admin']) || empty($_SESSION['admin'])){
            $this->redirect("__APP__/Manage/login");
        }
    }

    public function play(){
        $this->musicName =  $_GET["name"];
        $this->musicID = $_GET["id"];
        $this->display("Public:play");
    }


    /*
     *  FM电台首页
     *  歌曲列表，用于展现歌曲搜索结果、全部歌曲、关键词搜索等
    */
    public function songs(){
    	$this->fm = "selected";            //
    	$this->songs = "selected";         //选中侧边菜单栏

        $ad = M("administrator");
        $lg_user = $_SESSION['user'];
        $user = $ad->where("adminName='".$lg_user."'")->find();
        $this->privilege = $user['privilege'];  //获取当前用户的权限
        
        $mc = M("musicclass");
        $this->classList = $mc->select();       //获取歌曲分类目录

        $ms = M("music");
        $musicList = $ms->select();

        for($i=0 ; $i<count($musicList) ; $i++){                    //将分类目录ID转换为中文
            $classID = $musicList[$i]["musicClass"];
            $className = $mc->where("classID=".$classID)->getField("className");
            $musicList[$i]["className"] = $className;
        }

        $this->musicList = $musicList;
    	$this->display();
    }


    /*
     *  歌曲编辑
     *  @param  id => 歌曲ID
     *  更新歌曲信息
    */
    public function edit(){
        $this->fm = "selected";
        $id = $_GET['id'];
        if(!isset($id) || empty($id)){
            $this->error("查找失败，该歌曲不存在...","songs");     //无ID访问页面报错
        }
        $ms = M('music');
        $music = $ms->where("musicID=".$id)->find();
        if(!$music){
            $this->error("查找失败，该歌曲不存在...");         //无该首歌曲访问页面报错
        }
        $mc = M("musicclass");
        $this->classList = $mc->select();
        $this->music = $music;
        $this->display();
    }


    /*
     *  搜索
     *  @param key  => 搜索的关键词
     *  @param class => 分类目录ID
     *  展现搜索结果
    */
    public function musicSearch(){
        $this->fm = "selected";
        $data = array();
        $key = $_GET['key'];                    //关键词搜索
        $class =$_GET['class'];                 //分类目录搜索

        if($class == "all"){
            $this->redirect("songs");
        }
        $mc = M("musicclass");
        $this->classList = $mc->select();       //获取歌曲分类目录

        $ms = M("music");
        $mc = M("musicclass");
        if($key){                  //关键词查询条件
            $data["musicName"] = array("like","%$key%");
            $data["musicSinger"] = array("like","%$key%");
            $data['_logic'] = "or";             //改变逻辑关系为or
            //sql::SELECT * FROM `music` WHERE ( `musicName` LIKE '%一%' ) OR ( `musicSinger` LIKE '%一%' )
        }
        if($class){               //分类查询条件
            $data['musicClass'] = $class;
        }

        $musicList = $ms->where($data)->select();

        for($i=0 ; $i<count($musicList) ; $i++){                    //将分类目录ID转换为中文
            $classID = $musicList[$i]["musicClass"];
            $className = $mc->where("classID=".$classID)->getField("className");
            $musicList[$i]["className"] = $className;
        }
        $this->musicList = $musicList;
        $this->display("songs");
    }


    public function getKeyword($title){
        require('./Public/fenci/pscws4.class.php');
        $pscws = new PSCWS4();
        $pscws->set_dict('./Public/fenci/scws/dict.utf8.xdb');
        $pscws->set_rule('./Public/fenci/scws/rules.utf8.ini');
        $pscws->set_ignore(true);
        $pscws->send_text($title);
        $words = $pscws->get_tops(10);
        $tags = array();
        foreach ($words as $val) {
            $tags[] = $val['word'];
        }
        $pscws->close();
        return $tags;
    }


    /*
     *  更新
     *  @param  歌曲表单
     *  更新歌曲信息
    */
    public function uploadmusic(){
        $musicName = $_POST['musicname'];
        $musicClass = $_POST['musicclass'];
        $musicSinger = $_POST['musicsinger'];
        $musicAlbum = $_POST['musicalbum'];
        $musicPub = $_POST['musicpub'];
        $musicURL = $_POST['musicurl'];
        $musicLyric = $_POST['lyric'];         //获取表单信息

        $ms = M("music");
        $id = $ms->max("musicID")+1;

        import('ORG.Net.UploadFile');
        ////////////
        //上传音乐//
        $config['savePath'] = ".".$musicURL;
        $config['allowExts'] = array("mp3");
        $config['saveRule'] = $id;
        // $config['maxSize']  = 3145728 ;
        $upload = new UploadFile($config);          // 实例化上传类并传入参数
        if(!$upload->uploadOne($_FILES['music'])) {                    // 上传错误提示错误信息
            $this->error($upload->getErrorMsg());
        }
        ////////////
        //上传图片//
        $config['savePath'] = "./Public/Covers/";
        $config['allowExts'] = array("jpg");
        $config['saveRule'] = $id;
        $upload = new UploadFile($config);          // 实例化上传类并传入参数
        if(!$upload->uploadOne($_FILES['image'])) {                    // 上传错误提示错误信息
            $name = $musicName ? $musicName : $_FILES['music']["name"];
            if(file_exists(".\Public\Songs\\".$name)){
                unlink(".\Public\Songs\\".$name);       //上传失败时删除上传成功的MP3文件
            }
            $this->error($upload->getErrorMsg());
        }

        $data["musicID"] = $id;             //将歌曲信息添加至数据库
        $data["musicName"] = $musicName;
        $data["musicSinger"] = $musicSinger;
        $data['musicAlbum'] = $musicAlbum;
        $data['musicClass'] = $musicClass;
        $data['musicPub'] = $musicPub;
        $data['musicCover'] = $id.".jpg";
        $data['URL'] = $musicURL;
        $data['lyric'] = $musicLyric;

        
        //获取歌词关键字
        $result = $this->getKeyword($musicLyric);
        $sql = 'select emotionclass,level from emotion where word in ("'.implode('","', $result).'")';
        $emotionclass = $ms->query($sql);

        $temp = array();
        foreach ($emotionclass as $key => $value) {
            $temp[$value['emotionclass']] += $value['level'];
        }
        
        $sql = 'INSERT INTO emotionmusic SET musicID="'.$id.'" ';
        foreach ($temp as $key => $value) {
           $sql .= ','.$key.'="'.$value.'"';
        }
        $emotionclass = $ms->execute($sql);
        
        $data['keyword'] = implode(',', $result);
        $count = $ms->add($data);
        if($count > 0){
            $this->success('上传成功!',"songs");
        }
        else{
            unlink(".\Public\Songs\\".$id.".mp3");          //上传失败，删除MP3文件和封面图片
            unlink(".\Public\Covers\\".$id.".jpg");
            $this->error("上传失败!","songs");
        } 
    }

    public function musicDelete(){
        $musicID = $_GET["id"];

        $ms = M("music");
        $data["musicID"] = $musicID;
        $count = $ms->where($data)->delete();
     
        if($count > 0){
            unlink(".\Public\Songs\\".$id.".mp3");
            unlink(".\Public\Covers\\".$id.".jpg");
            $this->success("删除成功!");
        }else{
            $this->error("删除失败!");
        }
    }

    public function classification(){
    	$this->fm = "selected";
    	$this->classification = "selected";

        $color = array("#4714AD","#D0052B","#62049E","#023294",
                       "#028594","#029447","#369402","#A17A03",
                       "#A11D03","#C4B00B","#9BC40B","#A80BC4",
                       "#580F0F","#4A860F","#865B0F","#1E3DAE",);
        $mc = M("musicclass");
        $classList = $mc->select();   
        $this->classList =  $classList;  //获取歌曲分类目录

        $ms = M("music");
        $sum = $ms->count();
        $countList = array();
        $percentList = array();

        for($i=0 ; $i<count($classList) ; $i++){
            $countList[] = $ms->where("musicClass=".$classList[$i]["classID"])->count();
            $percentList[] = ($ms->where("musicClass=".$classList[$i]["classID"])->count() * 100)/$sum . "%";
        }
        $this->colorList = $color;
        $this->countList = $countList;
        $this->percentList = $percentList;
        $this->sum = $sum;
//        var_dump($classList);
    	$this->display();
    }

    public function addClass(){
        $data["className"] = $_POST["classname"];

        $mc = M("musicclass");
        $count = $mc->add($data);
        if($count > 0){
            $this->success("添加成功!");
        }else{
            $this->error("添加失败!");
        }
    }

    public function modifyClass(){
        $className = $_POST['newClassName'];
        $classID = $_POST['classID'];

        $data["classID"] = $classID;
        $data["className"] = $className;
     //   var_dump($data);
        $mc = M("musicclass");
        $count = $mc->save($data);
        if($count > 0){
            $this->success("修改成功!");
        }else{
            $this->error("修改失败!");
        }
    }

    public function deleteClass(){
        $classID = $_GET["id"];
        
        $mc = M("musicclass");
        $count = $mc->where("classID=".$classID)->delete();     //删除该分类
        if($count > 0){         //删除成功则修改该分类下的歌曲分为未分类
            $ms = M("music");
            $count = $ms->where("musicClass=".$classID)->setField("musicClass","9");
            if($count > 0){
                $this->success("删除成功!");
            }else{
                $this->error("修改分类目录下歌曲失败!");
            }
        }else{
            $this->error("删除分类目录失败!");
        }
    }

    public function musicUpdate(){
        $data['musicID'] = $_POST["musicid"];
        $data['musicName'] = $_POST["musicname"];
        $data['musicClass'] = $_POST["musicclass"];
        $data['musicSinger'] = $_POST["musicsinger"];
        $data['musicAlbum'] = $_POST["musicalbum"];
        $data['musicPub'] = $_POST["musicpub"];
        $data['URL'] = $_POST["musicurl"];
        $data['lyric'] = $_POST["lyric"];
        $errorMsg = "内容更新失败!";
        $successMsg = "内容更新成功!";
        //var_dump($_FILES);

        $ms = M("music");

        //获取歌词关键字
        $result = $this->getKeyword($_POST['lyric']);
        $sql = 'select emotionclass,level from emotion where word in ("'.implode('","', $result).'")';
        $emotionclass = $ms->query($sql);

        $temp = array();
        foreach ($emotionclass as $key => $value) {
            $temp[$value['emotionclass']] += $value['level'];
        }
        
        $sql = 'INSERT INTO emotionmusic SET musicID="'.$_POST["musicid"].'" ';
        foreach ($temp as $key => $value) {
           $sql .= ','.$key.'="'.$value.'"';
        }
        $emotionclass = $ms->execute($sql);
        
        $data['keyword'] = implode(',', $result);


        if(isset($_FILES['image']["name"]) && !empty($_FILES['image']["name"])){
            import('ORG.Net.UploadFile');
            ////////////
            //上传图片//
            $config['savePath'] = "./Public/Covers/";
            $config['allowExts'] = array("jpg");
            $config['saveRule'] = $_POST["musicid"];
            $config['uploadReplace'] = true;
            $upload = new UploadFile($config);          // 实例化上传类并传入参数
            if(!$upload->upload()) {                    // 上传错误提示错误信息
                $this->error($upload->getErrorMsg());
            }
            $errorMsg = "图片更新成功，内容更新失败!";
            $successMsg = "图片、内容更新成功!";
        }
        $aa = $ms->where($data)->find();
        if($aa){    //内容未变
            $this->success($successMsg,"songs");
        }else{  //内容变动则更新数据库
            $count = $ms->save($data);
            if($count > 0){
                $this->success($successMsg,"songs");
            }else{
                $this->error($errorMsg);
            }
        }
    }

    public function upload(){
        $this->fm = "selected";
        $this->upload = "selected";

        $mc = M("musicclass");
        $this->classList = $mc->select();
        $this->display();
    }
}