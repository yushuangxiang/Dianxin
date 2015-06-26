<?php
namespace Home\Controller;
use Think\Controller;
class CenterController extends Controller {
	//已完工总表
    public function order_done(){
            $uid=is_login();
      if($uid>0){
        $orderdata=M('po');
        $map['PO_zt']=array('like','%完工%');
        $count=$orderdata->where($map)->count();
        $Page=new \Think\Page($count,15);
        $show=$Page->show();
        $list=$orderdata->where($map)->field(array('PO_id,PO_zt,PO_Client_name,PO_time,PO_Client_visa,PO_Client_visa_Nber,PO_name'))->order('PO_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $nowpage=$Page->nowPage;
        $totalpage=$Page->totalPages;
        $this->assign('totalpage',$totalpage);
        $this->assign('nowpage',$nowpage);
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('order_done',$list);
        $this->display('Center/order_done');
      }else{
        $this->display('User/login');
      }
    }
    //订单详情
    public function orderdetail(){
      $PO_id=$_GET['wid'];
      $type=$_GET['type'];
      if($type=='1322'){
        $orderlist="chedan";
        $ordertype='撤单';
      }if($type=='1325'){
        $orderlist='order_done';
        $ordertype='已完工';
      }if($type=='1315'){
        $orderlist='overarea';
        $ordertype='区域限制';
      }if($type=='1312'){
        $orderlist='noresource';
        $ordertype='无资源';
      }if($type=='1314'){
        $orderlist='allorder';
        $ordertype='总业绩表';
      }
      $this->assign('ordertype',$ordertype);
      $this->assign('orderlist',$orderlist);
       $pid['PO_id']=$PO_id;
       $orderdata=M('po');
       $result=$orderdata->where($pid)->field('PO_id,PO_PordacetID,PO_name,PO_Client_name,PO_Client_bank,PO_Client_bank_id,PO_Client_visa_Nber,PO_Client_addres,PO_Client_phone,FKFS,PO_zt,PO_seles,PO_seles_id,BZ')->select();
       //订单备注信息查询
        $beizhu_data=M('history');
        $hid['UP_PO_id']=$result[0]['PO_id'];
        $beizhu=$beizhu_data->where($hid)->select();
       //套餐资费查询
       $product=M('adsl_tc');
       $tc_id['TC_id']=$result[0]['PO_PordacetID'];
       $price=$product->where($tc_id)->field('TC_KDZF')->select();
       //订单日志查询
       $logdata=M('history');
       $lid['UP_PO_id']=$PO_id;
       $logs=$logdata->where($lid)->field('ID,LB,body,TJ_time,user')->order('TJ_time desc')->select();
       $this->assign('log',$logs);
       $this->assign('id',$PO_id);
       $this->assign('price',$price[0]);
       $this->assign('type',$type);
       $this->assign('orderdetail',$result[0]);
       $this->display('Center/orderdetail');
    }



    //订单信息保存
    public function order_save(){
      $logdata=M('history');
      $type=$_GET['type'];
      if($type=='1322'){
        $orderlist="chedan";
        $ordertype='撤单';
      }if($type=='1325'){
        $orderlist='order_done';
        $ordertype='已完工';
      }if($type=='1315'){
        $orderlist='overarea';
        $ordertype='区域限制';
      }if($type=='1312'){
        $orderlist='noresource';
        $ordertype='无资源';
      }if($type=='1314'){
        $orderlist='allorder';
        $ordertype='总业绩表';
      }
      $content=$_POST['content'];
      $log['LB']="Change State";
      $log['UP_PO_id']=$_GET['id'];
      $log['body']=$_POST['content'];
      $log['TJ_time']=date('Y-m-d H:i:s',time());
      $log['user']=get_username();
      $re_log=$logdata->add($log);

      $yuludandata=M('po');
      $pid['PO_id']=$_GET['id'];
      $zt['PO_zt']=$_POST['PO_zt'];
      $re_zt=$yuludandata->where($pid)->save($zt);
      $this->redirect('Center/orderdetail?wid='.$_GET['id']);
    }





    //撤单
    public function chedan(){
    	      $uid=is_login();
      if($uid>0){
        $status="%撤单%";
        $result=orderlist('',$status);
        $page=$result[0];
        $list=$result[1];
        $nowpage=$result[2];
        $totalpage=$result[3];
        $count=$result[4];

        $this->assign('nowpage',$nowpage);
        $this->assign('totalpage',$totalpage);
        $this->assign('count',$count);
        $this->assign('page',$page);
        // $this->assign('dankuanlist',$list);
        $this->assign('chedan',$list);

        $this->display('Center/chedan');
      }else{
        $this->display('User/login');
      }
    }
    //区域限制
    public function overarea(){
      $uid=is_login();
      if($uid>0){
        $orderdata=M('po');
        $map['PO_zt']=array('like',array('%区域限制%','限制'));
        $count=$orderdata->where($map)->count();
        $Page=new \Think\Page($count,15);
        $show=$Page->show();
        $list=$orderdata->where($map)->field(array('PO_id,PO_zt,PO_Client_name,PO_time,PO_Client_visa,PO_Client_visa_Nber,PO_name'))->order('PO_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $nowpage=$Page->nowPage;
        $totalpage=$Page->totalPages;
        $this->assign('totalpage',$totalpage);
        $this->assign('nowpage',$nowpage);
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('overarea',$list);
        $this->display('Center/overarea');
      }else{
        $this->display('User/login');
      }
    }
    //无资源
    public function noresource(){
      $uid=is_login();
      if($uid>0){
        $orderdata=M('po');
        $map['PO_zt']=array('like',array('%无资源%','限制'));
        $count=$orderdata->where($map)->count();
        $Page=new \Think\Page($count,15);
        $show=$Page->show();
        $list=$orderdata->where($map)->field(array('PO_id,PO_zt,PO_Client_name,PO_time,PO_Client_visa,PO_Client_visa_Nber,PO_name'))->order('PO_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $nowpage=$Page->nowPage;
        $totalpage=$Page->totalPages;
        $this->assign('totalpage',$totalpage);
        $this->assign('nowpage',$nowpage);
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('noresource',$list);
        $this->display('Center/noresource');
      }else{
        $this->display('User/login');
      }
    }
    public function allorder(){
      $uid=is_login();
      if($uid>0){
        $orderdata=M('po');
        $count=$orderdata->count();
        $Page=new \Think\Page($count,25);
        $show=$Page->show();
    $list=$orderdata->field(array('PO_id,PO_zt,PO_Client_name,PO_time,PO_Client_visa,PO_Client_visa_Nber,PO_name'))->order('PO_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $nowpage=$Page->nowPage;
        $totalpage=$Page->totalPages;
        $this->assign('totalpage',$totalpage);
        $this->assign('nowpage',$nowpage);
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('allorder',$list);
        $this->display('Center/allorder');
      }else{
        $this->display('User/login');
      }
    }

}