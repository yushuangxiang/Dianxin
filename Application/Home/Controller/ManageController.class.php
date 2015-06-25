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
      }else{
        $this->display('User/login');
      }
    }
    public function compdetail(){
      $orderdata=M('dbsx');
      $tid['DBSX_ID']=$_GET['id'];
      if($_POST){
          $arr['BJ_body']=$_POST['content'];
          $arr['BJ_time']=date('Y-m-d H:i:s',time());
          $arr['ZT']="已回复";
          $savedata=$orderdata->where($tid)->save($arr);
          $this->redirect('Manage/compdetail?id='.$_GET['id']);
      }else{
        
        $result=$orderdata->where($tid)->field(array('DBSX_id,po_id,ZT,DBSX_LB,body,form_who,to_who,CJ_time,BJ_time,BJ_body'))->limit(15)->order('PO_id desc')->select();
        $this->assign('data',$result[0]);
        $this->display('Manage/compdetail');
      }
      
    }
    public function mendianwuliu(){
      $wuliudata=M('wuliu');
      $mendian['LB']=array('like',array('%门店%'));
      $count=$wuliudata->where($mendian)->count();
      $Page=new \Think\Page($count,15);
      $show=$Page->show();
      $list=$wuliudata->where($mendian)->field(array('ID,LB,czuser,shoujianr,shoujianr_add,shoujianr_tell,zxd,CJtime,WLZT'))->order('ID desc')->limit($Page->firstRow.','.$Page->listRows)->select();
      $this->assign('list',$list);
      $this->assign('page',$show);
      $this->display('Manage/mendianwuliu');
    }
    public function fahuodetail(){
      $wuliudata=M('wuliu');
      $wid['ID']=$_GET['id'];
      if($_POST){
        if($_POST['WLZT']!==null){
          $status['WLZT']=$_POST['WLZT'];
          $savedata=$wuliudata->where($wid)->save($status);
          $this->redirect('Manage/fahuodetail?id='.$_GET['id']);
        }
        if($_POST['fahuor']!==null){
          $wuliu['fahuor']=$_POST['fahuor'];
          $wuliu['fahuor_add']=$_POST['fahuor_add'];
          $wuliu['fahuor_tel']=$_POST['fahuor_tel'];
        }

        $wuliu['wuliu_comp']=$_POST['wuliu_comp'];
        $wuliu['wuliu_id']=$_POST['wuliu_id'];
        $wuliu['FHtime']=date('Y-m-d H:i:s',time());
        $wuliu['WLZT']="已发货";
        $savedata=$wuliudata->where($wid)->save($wuliu);
        $this->redirect('Manage/fahuodetail?id='.$_GET['id']);
      }else{
        $wuliudetail=$wuliudata->where($wid)->select();
        $this->assign('data',$wuliudetail[0]);
        $this->display('Manage/fahuodetail');
      }
    }
    public function dingdanwuliu(){
      $wuliudata=M('wuliu');
      $mendian['LB']=array('like',array('%订单%'));
      $count=$wuliudata->where($mendian)->count();
      $Page=new \Think\Page($count,15);
      $show=$Page->show();
      $list=$wuliudata->where($mendian)->field(array('ID,LB,czuser,shoujianr,shoujianr_add,shoujianr_tell,zxd,CJtime,WLZT'))->order('ID desc')->limit($Page->firstRow.','.$Page->listRows)->select();
      $this->assign('list',$list);
      $this->assign('page',$show);
      $this->display('Manage/mendianwuliu');
    }
}