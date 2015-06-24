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
        $result=$orderdata->where($map)->field(array('PO_id,PO_zt,PO_Client_name,PO_Client_visa,PO_Client_visa_Nber,PO_name'))->limit(10)->order('PO_id desc')->select();
        foreach($result as $i=>$val){
          $order_done[$i]["PO_id"]=$val["PO_id"];
          $order_done[$i]["PO_Client_name"]=$val["PO_Client_name"];
          $order_done[$i]["PO_Client_visa"]=$val["PO_Client_visa"];
          $order_done[$i]["PO_Client_visa_Nber"]=$val["PO_Client_visa_Nber"];
          $order_done[$i]["PO_name"]=$val["PO_name"];
          $order_done[$i]["PO_zt"]=$val["PO_zt"];

        }
        $this->assign('order_done',$order_done);
        $this->display('Center/order_done');
      }else{
        $this->display('User/login');
      }
    }
    //已完工详情
    public function orderdetail(){
      $PO_id=$_GET['qid'];
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
       $this->assign('yid',$PO_id);
       $this->assign('price',$price[0]);
       $this->assign('orderdetail',$result[0]);
       $this->display('Center/orderdetail');
    }
    //撤单
    public function chedan(){
    	      $uid=is_login();
      if($uid>0){
        $orderdata=M('po');
        $map['PO_zt']=array('like','%撤单%');
        $result=$orderdata->where($map)->field(array('PO_id,PO_zt,PO_Client_name,PO_Client_visa,PO_Client_visa_Nber,PO_name'))->limit(10)->order('PO_id desc')->select();
        foreach($result as $i=>$val){
          $chedan[$i]["PO_id"]=$val["PO_id"];
          $chedan[$i]["PO_Client_name"]=$val["PO_Client_name"];
          $chedan[$i]["PO_Client_visa"]=$val["PO_Client_visa"];
          $chedan[$i]["PO_Client_visa_Nber"]=$val["PO_Client_visa_Nber"];
          $chedan[$i]["PO_name"]=$val["PO_name"];
          $chedan[$i]["PO_zt"]=$val["PO_zt"];

        }
        $this->assign('chedan',$chedan);
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
        $result=$orderdata->where($map)->field(array('PO_id,PO_zt,PO_Client_name,PO_Client_visa,PO_Client_visa_Nber,PO_name'))->limit(10)->order('PO_id desc')->select();
        foreach($result as $i=>$val){
          $overarea[$i]["PO_id"]=$val["PO_id"];
          $overarea[$i]["PO_Client_name"]=$val["PO_Client_name"];
          $overarea[$i]["PO_Client_visa"]=$val["PO_Client_visa"];
          $overarea[$i]["PO_Client_visa_Nber"]=$val["PO_Client_visa_Nber"];
          $overarea[$i]["PO_name"]=$val["PO_name"];
          $overarea[$i]["PO_zt"]=$val["PO_zt"];

        }
        $this->assign('overarea',$overarea);
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
        $result=$orderdata->where($map)->field(array('PO_id,PO_zt,PO_Client_name,PO_Client_visa,PO_Client_visa_Nber,PO_name'))->limit(10)->order('PO_id desc')->select();
        foreach($result as $i=>$val){
          $noresource[$i]["PO_id"]=$val["PO_id"];
          $noresource[$i]["PO_Client_name"]=$val["PO_Client_name"];
          $noresource[$i]["PO_Client_visa"]=$val["PO_Client_visa"];
          $noresource[$i]["PO_Client_visa_Nber"]=$val["PO_Client_visa_Nber"];
          $noresource[$i]["PO_name"]=$val["PO_name"];
          $noresource[$i]["PO_zt"]=$val["PO_zt"];

        }
        $this->assign('noresource',$noresource);
        $this->display('Center/noresource');
      }else{
        $this->display('User/login');
      }
    }
    public function allorder(){
      $uid=is_login();
      if($uid>0){
        $orderdata=M('po');
        $result=$orderdata->field(array('PO_id,PO_zt,PO_Client_name,PO_Client_visa,PO_Client_visa_Nber,PO_name'))->limit(30)->order('PO_id desc')->select();
        foreach($result as $i=>$val){
          $allorder[$i]["PO_id"]=$val["PO_id"];
          $allorder[$i]["PO_Client_name"]=$val["PO_Client_name"];
          $allorder[$i]["PO_Client_visa"]=$val["PO_Client_visa"];
          $allorder[$i]["PO_Client_visa_Nber"]=$val["PO_Client_visa_Nber"];
          $allorder[$i]["PO_name"]=$val["PO_name"];
          $allorder[$i]["PO_zt"]=$val["PO_zt"];

        }
        $this->assign('allorder',$allorder);
        $this->display('Center/allorder');
      }else{
        $this->display('User/login');
      }
    }

}