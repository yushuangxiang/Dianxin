<?php
namespace Home\Controller;
use Think\Controller;
class ManageController extends Controller{
  //投诉事项列表
    public function complaints(){
      $uid=is_login();
      if($uid>0){
        $orderdata=M('dbsx');
        $map['DBSX_LB']=array('like',array('%待办事项%','%投诉%'));
        $count=$orderdata->where($map)->count();
        $Page=new \Think\Page($count,15);
        $show=$Page->show();    
        $list=$orderdata->where($map)->field(array('DBSX_id,po_id,ZT,DBSX_LB,body,form_who,CJ_time,BJ_time,BJ_body'))->limit(30)->order('PO_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $nowpage=$Page->nowPage;
        $totalpage=$Page->totalPages;
        $this->assign('totalpage',$totalpage);
        $this->assign('nowpage',$nowpage);
        $this->assign('count',$count);
        $this->assign('page',$show);
        $this->assign('complaints',$list);
        $this->display('Manage/complaints');
      }else{
        $this->display('User/login');
      }
    }
    //投诉处理详情页
    public function compdetail(){
      $orderdata=M('dbsx');
      $uid=is_login();
      if($uid>0){
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
      }else{
        $this->dispaly('User/login');
      }
      
      
    }
   //门店物流列表 
    public function mendianwuliu(){
      $pagenum=$_GET['page'];

      $wuliudata=M('wuliu');
      $mendian['LB']=array('like',array('%门店%'));
      $count=$wuliudata->where($mendian)->count();
      $Page=new \Think\Page($count,15);
      $show=$Page->show();
      $list=$wuliudata->where($mendian)->field(array('ID,LB,czuser,shoujianr,shoujianr_add,shoujianr_tell,zxd,CJtime,WLZT'))->order('ID desc')->limit($Page->firstRow.','.$Page->listRows)->select();
      $nowpage=$Page->nowPage;
      $totalpage=$Page->totalPages;
      $this->assign('totalpage',$totalpage);
      $this->assign('nowpage',$nowpage);
      $this->assign('pagenum',$pagenum);
      $this->assign('count',$count);
      $this->assign('list',$list);
      $this->assign('page',$show);
      $this->display('Manage/mendianwuliu');
    }
    //订单物流列表
    public function dingdanwuliu(){
      $pagenum=$_GET['page'];
      $wuliudata=M('wuliu');
      $dingdan['LB']=array('like',array('%订单%'));
      $count=$wuliudata->where($dingdan)->count();
      $Page=new \Think\Page($count,15);
      $show=$Page->show();
      $list=$wuliudata->where($dingdan)->field(array('ID,LB,czuser,shoujianr,shoujianr_add,shoujianr_tell,zxd,CJtime,WLZT'))->order('ID desc')->limit($Page->firstRow.','.$Page->listRows)->select();
      $nowpage=$Page->nowPage;
      $totalpage=$Page->totalPages;
      $this->assign('totalpage',$totalpage);
      $this->assign('nowpage',$nowpage);
      $this->assign('count',$count);
      $this->assign('data',$list);
      $this->assign('page',$show);
      $this->display('Manage/dingdanwuliu');
    }
    //物流发货详情
    public function fahuodetail(){
      $wuliudata=M('wuliu');
      $wid['ID']=$_GET['id'];
      $type=$_GET['type'];
      if($type=='1201'){
        $ordertype="门店物流";
        $wuliulist="mendianwuliu";

      }elseif($type=='1203'){
        $ordertype="订单物流";
        $wuliulist="dingdanwuliu";
      }elseif($type==''){
        $wuliulist="mendianwuliu";
      }
        $this->assign('wuliulist',$wuliulist);
        $this->assign('ordertype',$ordertype);
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
        if($_GET['cid']!==null){
          $cid['czuser']=$_GET['cid'];
          $wuliudetail=$wuliudata->where($cid)->select();
        }else{
          $wuliudetail=$wuliudata->where($wid)->select();
        }
        $this->assign('data',$wuliudetail[0]);
        $this->display('Manage/fahuodetail');
      }
    }
    //代理门店列表
    public function agentstore(){
      $agentdata=M('czuser');
      $map['UPuserID']=$_GET['id'];
      if($type=="1"){
        $limit['active_ZZ']="是";
      }elseif($type=="2"){
        $limit['active_ZZ']="否";
      }else{
        $limit['active_ZZ']>='';
      }
      $count=$agentdata->where($map)->count();
      $Page=new \Think\Page($count,15);
      $show=$Page->show();
      $nowpage=$Page->nowPage;
      $totalpage=$Page->totalPages;
      $this->assign('totalpage',$totalpage);
      $this->assign('nowpage',$nowpage);
      $this->assign('count',$count);
      $this->assign('page',$show);
      $list=$agentdata->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
      $this->assign('agentstore',$list);
      $this->display('Manage/agentstore');
    }
    //业务员列表
    public function agent(){
      $agentdata=M('czuser');
      $type=$_GET['type'];
      $count=$agentdata->where("KEYY>''")->count();
      $Page=new \Think\Page($count,15);
      $show=$Page->show();
      $nowpage=$Page->nowPage;
      $totalpage=$Page->totalPages;
      $this->assign('totalpage',$totalpage);
      $this->assign('nowpage',$nowpage);
      $this->assign('count',$count);
      $this->assign('page',$show);
      $agentstore=$agentdata->where("KEYY>''")->order('Open_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
      $this->assign('agent',$agentstore);
      $this->display('Manage/agent');
    }
}