<?php
/////////////////////
//author:zhangwei  //
//time:2013-7-19   //
//mysql.class.php  //
/////////////////////
//Exception handling:  Failed to connect database error, my_error_handle catch errors thrown
class mysqlException extends Exception{}

function my_exception_handle($exception){
//exception handler
//@param Exception object
//@return Tips
	echo $exception->getMessage();
}

function my_error_handle($errno, $errstr, $errfile, $errline)
{
//error handling functions
//@param  error level、error infomation、error file、error line
//@return throw exception
    throw new Exception($errstr, $errno);
}

set_exception_handler('my_exception_handle');

set_error_handler('my_error_handle');

class mysql{
	private $addr;
	private $user;
	private $password;
	private $conn;
	function __construct($addr="localhost", $user="root", $password="cloudSoft@Skybility"){
	//@param address、user、password
		$this->addr = $addr;
		$this->user = $user;
		$this->password = $password;
	}
	function connect($db){
	//@param database
		try{
			$this->conn = mysqli_connect($this->addr,$this->user,$this->password, $db);
			// mysql_select_db($db,$this->conn);
			mysqli_query($this->conn, "set names utf-8");
			if($this->conn){
				echo "connect success!\r\n";
			}else{
				echo "connect faild\r\n";
			}
		}catch(Exception $e){
			throw new mysqlException($e->getMessage());

			// "mysql connect faild, please try again!"
		}
	}
	function query($sql){
	//execute a query
		try{
			$result = array();
			$arr = mysqli_query($this->conn, $sql);
			while($temp = mysqli_fetch_array($arr)){
				$result[] = $temp; 
			}
			return $result;
		}catch(Exception $e){
			throw new mysqlException("select data faild!");
		}
	}
	function exec($sql){
	//execute delete、update、insert query
		try{
			if(!mysqli_query($this->conn,$sql)){
				echo $sql;
				throw new Exception("update data faild", 1);	
			}else{
				return true;
			}
		}catch(Exception $e){
			throw new mysqlException($e->getMessage());
		}
	}
	function close(){
		$this->conn->close();
	}
	function __destruct(){
		unset($this);
	}
	public function __toString(){
		return "mysql object";
	}
}
/*$mq = new mysql("125.221.225.210","root","88888888");
$mq->connect("sns");
$sql  = "update tb_records set title='record1' where title='asdf'";
echo $sql;
$mq->exec($sql);*/
?>