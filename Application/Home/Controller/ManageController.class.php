<?php
namespace Home\Controller;
use Think\Controller;
class ManageController extends Controller{
    public function complaints(){
      $uid=is_login();
      if($uid>0){
        $orderdata=M('dbsx');
        $map['DBSX_LB']=array('like',array('%待办事项%','%投诉%'));
        $result=$orderdata->field(array('DBSX_id,po_id,ZT,DBSX_LB,body,form_who,CJ_time,BJ_time,BJ_body'))->limit(30)->order('PO_id desc')->select();
        foreach($result as $i=>$val){
          $complaints[$i]["DBSX_id"]=$val["DBSX_id"];
          $complaints[$i]["po_id"]=$val["po_id"];
          $complaints[$i]["ZT"]=$val["ZT"];
          $complaints[$i]["DBSX_LB"]=$val["DBSX_LB"];
          $complaints[$i]["body"]=$val["body"];
          $complaints[$i]["form_who"]=$val["form_who"];
          $complaints[$i]["CJ_time"]=$val["CJ_time"];
          $complaints[$i]["BJ_time"]=$val["BJ_time"];
          $complaints[$i]["BJ_body"]=$val["BJ_body"];
        }
        $this->assign('complaints',$complaints);
        $this->display('Manage/complaints');
      }
    }
}