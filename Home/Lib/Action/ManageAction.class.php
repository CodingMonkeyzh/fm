<?php
// 本类由系统自动生成，仅供测试用途
class ManageAction extends Action {
    public function index(){
    	$this->Note = "
<p>迎风笑</p>
<p>一曲独舞乐天舒</p>
<p>轻歌漫步我自由</p>
<p>人生有义</p>
<p>好事就来</p>
<p>迎风笑</p>
<p>笑尽天下得意人</p>
<p>悦心未必有真情</p>
<p>吾自独行</p>
<p>不负此生</p>";
    	$this->display();
    }
    public function login(){
        $this->Note = "
<p>迎风笑</p>
<p>一曲独舞乐天舒</p>
<p>轻歌漫步我自由</p>
<p>人生有义</p>
<p>好事就来</p>
<p>迎风笑</p>
<p>笑尽天下得意人</p>
<p>悦心未必有真情</p>
<p>吾自独行</p>
<p>不负此生</p>";
        $return = array();
    	$userNumber = $_POST["userNomber"];
    	$userPaswd = md5($_POST["userPaswd"]);

    	$us = M("user");
    	$data["userNumber"] = $userNumber;
    	$data["userPaswd"] = $userPaswd;

    	$user = $us->where($data)->find();

    	if(isset($user) && !empty($user)){
    		session_start();
    		$_SESSION["user"] = $userNumber;
    		$_SESSION["username"] = $user["userName"];
            $result = 1;
    		echo $result;
    	}else{
            $result = 0;
            echo $result;
    	}
    }
    public function regist(){
    	$userNumber = $_POST["userNomber"];
    	$userPaswd = md5($_POST["userPaswd"]);

        $us = M("user");
        $exit = 0;
        $exit = $us->where("userNumber=".$userNumber)->count();
        $exit == 0 ? "" : die("该学号已经注册!");

    	$userinfo = $this->getInfo($userNumber);

        if(isset($userinfo) && !empty($userinfo)){
            $data["userNumber"] = $userNumber;
            $data["userName"] = iconv("GB2312", "UTF-8", $userinfo[1]);
            $data["userPaswd"] = $userPaswd;
            
            $count = $us->add($data);
            echo $count;
        }else{
            echo "学号不存在！";
        }
    }
    private function getInfo($sid){
        $fp = fsockopen("cwc.hnust.cn",80,$errno,$errstr,5);

        $out =<<<EOT
POST http://cwc.hnust.cn/CwcxV4/SF40/AxhFind.asp HTTP/1.1
Host: cwc.hnust.cn
User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:24.0) Gecko/20100101 Firefox/24.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3
Accept-Encoding: gzip, deflate
Referer: http://cwc.hnust.cn/CwcxV4/SF40/AxhFind.asp
Cookie: lzstat_uv=38387290432132767367|2722660; ASPSESSIONIDSCCACDBC=BCPDJLIABFCFLJOJMAOAMNIN
Connection: close
Content-Type: application/x-www-form-urlencoded
Content-Length: 59

TXTXH={$sid}&TXTXM=&TXTZH=&CmdFind=%B2%E9+%D1%AF%28Q%29
EOT;

        $str = "";
        fwrite($fp, $out);
        while(!feof($fp)) {
            $str .= fgets($fp, 1280);
        }
        $pattern = "/<td class=\"center\">(.*)<\/td>/";
        if(preg_match_all($pattern, $str, $matches)){
            return $matches[1];
        }
        fclose($fp);
	}
    public function updateInfo(){
        $data["userNumber"] = $_POST['userNumber'];
        $data["userSex"] = $_POST["sex"];
        $data["userEmail"] = $_POST["email"];
        $data["userBirthday"] = $_POST["birthday"];
        $data["userExplanation"] = $_POST["explanation"];
        //var_dump($_POST);
        ////////////
        //上传图片//
        if(isset($_FILES['photo']["name"]) && !empty($_FILES['photo']["name"])){
            import('ORG.Net.UploadFile');
            $config['savePath'] = "./Public/Photos/";
            $config['allowExts'] = array("jpg");
            $config['saveRule'] = $data["userNumber"];
            $config['uploadReplace'] = true;
            $upload = new UploadFile($config);          // 实例化上传类并传入参数
            if(!$upload->uploadOne($_FILES['photo'])) {                    // 上传错误提示错误信息
                $this->error($upload->getErrorMsg());
                echo $upload->getErrorMsg();
            }
            $data["userPhoto"] = $data["userNumber"];
        }
        $us = M("user");
        $aa = $us->where($data)->find(); //是否更改
        if($aa){    //内容未变
            //$this->success("资料更新成功!","userDetail");
            echo "1";
        }else{     //内容变动则更新数据库
            $count = $us->save($data);
            if($count > 0){
                //$this->success("资料更新成功!","userDetail");
                echo "1";
            }else{
                //$this->error("资料更新失败!");
                echo "0";
            }
        }
    }
    public function userDetail(){
        $data["userNumber"] = $_SESSION["user"];

        $us = M("user");
        $userInfo = $us->where($data)->getField("userNumber,userSex,userEmail,userBirthday,userExplanation");
        $this->userInfo = $userInfo[$data["userNumber"]];   
        // var_dump($this->userInfo);
        $this->display();
    }
    public function logout(){
        $_SESSION = array();
        if(isset($_COOKIE[session_name()])){
            setcookie(session_name(),"",time()-1,"/");
        }
        session_destroy();
        $this->redirect("index");
    }
    public function changePaswd(){
        $c_paswd = md5($_POST["c_paswd"]);
        // var_dump($c_paswd);
        $n_paswd = md5($_POST["n_paswd"]);

        $data["userNumber"] = $_SESSION["user"];

        $us = M("user");
        $paswd = $us->where("userNumber=".$data["userNumber"])->getField("userPaswd");
        if($c_paswd != $paswd){
            echo "密码不正确，请重新输入!";
        }else{
            $data["userPaswd"] = $n_paswd;
            $count = $us->save($data);
            if($count > 0){
                //echo "密码修改成功!";
                echo "1";
            }else{
                echo "密码修改失败!";
            }
        }
    }
}