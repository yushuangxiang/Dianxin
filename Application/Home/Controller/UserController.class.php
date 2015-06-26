<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    public function login(){
    	if(IS_POST){
        session_start();
    		$arr['Masteruser']=strtolower($_POST['username']);
    		$arr['passs']=$_POST['password'];
            $userdata=M('masteruser');
  		    $result=$userdata->where($arr)->find();
	    	if(!is_null($result)){
    				$_SESSION['uid']=$result['ID'];
                    $_SESSION['username']=$result['Masteruser'];
    				$this->redirect('Index/index');
	    	}else{
	    		$msg ="对不起!您输入的账号或密码有误，请核对后重新登录！";
	    		$this->assign('msg',$msg);
	    		$this->display('User/login');
	    	}

    	}else{
    		$this->display('User/login');
    	}
    	
    }
    public function logout(){
    	session_unset();
    	if(is_login()>0){
    		$this->display('Index/index');
    	}else{
    		$this->display('User/login');
    	}
    }
    public function personaldata(){
        




        
        $this->display('User/personaldata');
    }

}