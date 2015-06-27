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
            $nowpage=$result[2];
            $totalpage=$result[3];
            $count=$result[4];
            $this->assign('nowpage',$nowpage);
            $this->assign('totalpage',$totalpage);
            $this->assign('count',$count);
            $this->assign('page',$page);
			$this->assign('yuludan',$list);
    		$this->display('Index/index');
    	}else{
    		$this->display('User/login');
    	}
    	
    }
}