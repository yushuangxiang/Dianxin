<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$uid=is_login();
    	if($uid>0){
			$type="dailu";
			$status='%预定%';
			$result=orderlist($type,$status);
            $page=$result[0];
            $list=$result[1];
            $this->assign('page',$page);
			$this->assign('yuludan',$list);
    		$this->display('Index/index');
    	}else{
    		$this->display('User/login');
    	}
    	
    }
}