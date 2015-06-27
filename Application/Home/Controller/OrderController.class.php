<?php
namespace Home\Controller;
use Think\Controller;
class OrderController extends Controller {
  /*预处理程序*/
  public function index(){
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
    $this->display('Order/index');
  }


//订单详情
    public function orderdetail(){
      $id=$_GET['id'];
      $type=$_GET['type'];
      if($type=='1301'){
        $orderlist="index";
        $ordertype='预受理';
      }if($type=='1302'){
        $orderlist='kaikalist';
        $ordertype='待开卡';
      }if($type=='1303'){
        $orderlist='dailulist';
        $ordertype='待录单';
      }if($type=='1304'){
        $orderlist='paylist';
        $ordertype='宽带待缴费';
      }if($type=='1305'){
        $orderlist='installlist';
        $ordertype='宽带待施工';
      }if($type=='1306'){
        $orderlist='dankuanlist';
        $ordertype='单宽已完工';
      }if($type=='1307'){
        $orderlist='ronghepaylist';
        $ordertype='融合待缴费';
      }if($type=='1308'){
        $orderlist='rongheoverlist';
        $ordertype='融合完工';
      }
      $this->assign('ordertype',$ordertype);
      $this->assign('orderlist',$orderlist);
      $this->assign('type',$type);      
      $logs=logs($id);
      $result=orderdetail($id);
      $pid=$result['PO_PordacetID'];
      $prices=price($pid);
      $this->assign('log',$logs);
      $this->assign('id',$id);
      $this->assign('price',$prices);
      $this->assign('data',$result);
      $wuliudata=M('wuliu');
      $wuliuid['czuser']=$_GET['id'];
      $wuliuinfo=$wuliudata->where($wuliuid)->field('shoujianr,shoujianr_add,shoujianr_tell,CJtime,CJuser,czuser,zxd,WLZT')->select();
      $this->assign('wuliuinfo',$wuliuinfo[0]);
      $this->display('Order/orderdetail');
    }
  //订单信息保存
    public function order_save(){
      $logdata=M('history');
      $type=$_GET['type'];
      $content=$_POST['content'];
      $log['LB']="Change State";
      $log['UP_PO_id']=$_GET['id'];
      $log['body']=$_POST['content'];
      $log['TJ_time']=date('Y-m-d H:i:s',time());
      $log['user']=get_username();
      $re_log=$logdata->add($log);
      //订单状态更新
      $yuludandata=M('po');
      $pid['PO_id']=$_GET['id'];
      $zt['PO_zt']=$_POST['PO_zt'];
      $re_zt=$yuludandata->where($pid)->save($zt);
      $this->redirect('Order/orderdetail?id='.$_GET['id'].'&type='.$type);
    }

    public function wuliu_save(){
      $data["shoujianr"]=$_POST['shoujianr'];
      $data["shoujianr_add"]=$_POST['shoujianr_add'];
      $data['shoujianr_tell']=$_POST['shoujianr_tell'];
      $data['CJtime']=date('Y-m-d H:i:s',time());
      $data['CJuser']=get_usernum();
      $data['czuser']=$_GET['id'];
      $data['zxd']=$_POST['zxd'];
      $data['LB']="订单配送";
      $data['WLZT']="待发货";
      $wuliudata=M('wuliu');
      $result=$wuliudata->add($data);
      $this->redirect('Order/orderdetail?id='.$_GET['id'].'&type='.$_GET['type']);
    }
    /*待开卡列表*/
    public function kaikalist(){
      $uid=is_login();
      if($uid>0){
        $type="dailu";
        $status='%开卡%';
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
        $this->assign('dailulist',$list);
        $this->display('Order/dailulist');
      }else{
          $this->display('User/login');
      }
    }


    /*待录单业务处理逻辑*/
      /*待录订单列表*/
    public function dailulist(){
      $type="dailu";
      $status='%录%';
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
      $this->assign('dailulist',$list);
      $this->display('Order/dailulist');
    }

    /*待缴费列表*/
    public function paylist(){
      $type="pay";
      $status="%宽带待缴费%";
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
      $this->assign('paylist',$list);
      $this->display('Order/paylist');
    }
    /*宽带待施工订单列表*/
    public function installlist(){
      $type="install";
      $status="%待施工%";
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
      $this->assign('install',$list);
      $this->display('Order/installlist'); 
    }

    //单宽已完工订单列表
    public function dankuanlist(){
      $type="dankuan";
      $status="%单宽%";
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
      $this->assign('dankuanlist',$list);
      $this->display('Order/dankuanlist'); 
    }
    //融合待缴费
    public function ronghepaylist(){
      $type="ronghepay";
      $status="%融合待缴费%";
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
      $this->assign('ronghepaylist',$list);
      $this->display('Order/ronghepaylist'); 
    }
    //融合完工
    public function rongheoverlist(){
      $type="rongheover";
      $status="%融合完工%";
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
      $this->assign('rongheoverlist',$list);
      $this->display('Order/rongheoverlist'); 
    }
}