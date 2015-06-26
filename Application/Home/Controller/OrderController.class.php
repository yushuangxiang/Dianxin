<?php
namespace Home\Controller;
use Think\Controller;
class OrderController extends Controller {
  /*预处理程序*/
    //预处理订单详情
    public function acceptance(){

      $id=$_GET['yid'];
      $logs=logs($id);
      $result=orderdetail($id);
      $pid=$result['PO_PordacetID'];
      $price=price($pid);


      $this->assign('log',$logs);
      $this->assign('yid',$id);
      $this->assign('price',$price[0]);
      $this->assign('yushouli',$result);
      $this->display('Order/acceptance');
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
      $logs=logs($id);
      $result=orderdetail($id);
      $pid=$result['PO_PordacetID'];
      $price=price($pid);
      $this->assign('log',$logs);
      $this->assign('yid',$id);
      $this->assign('price',$price[0]);
      $this->assign('data',$result);
       $this->display('Order/orderdetail');
    }







    //预处理信息保存
    public function acceptance_save(){
      $logdata=M('history');
      $content=$_POST['content'];
      $log['LB']="Change State";
      $log['UP_PO_id']=$_GET['yid'];
      $log['body']=$_POST['content'];
      $log['TJ_time']=date('Y-m-d H:i:s',time());
      $log['user']=get_username();
      $re_log=$logdata->add($log);

      $yuludandata=M('po');
      $yid['PO_id']=$_GET['yid'];
      $zt['PO_zt']=$_POST['PO_zt'];
      $re_zt=$yuludandata->where($yid)->save($zt);
      if($re_zt){
        $this->display('Index/index');
      }else{
        $this->error('更新失败',U('Index/index'));
      }
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
      $this->assign('page',$page);
      $this->assign('rongheoverlist',$list);
      $this->display('Order/rongheoverlist'); 
    }

    /*待录订单详情*/
    public function dailu(){
      $PO_id=$_GET['did'];
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
       $this->assign('did',$PO_id);
       $this->assign('price',$price[0]);
       $this->assign('dailu',$result[0]);
       $this->display('Order/dailu');
    }
    /*待录单信息保存逻辑处理*/
    public function dailu_save(){
      $logdata=M('history');
      $content=$_POST['content'];
      $log['LB']="Change State";
      $log['UP_PO_id']=$_GET['did'];
      $log['body']=$_POST['content'];
      $log['TJ_time']=date('Y-m-d H:i:s',time());
      $log['user']=get_username();
      $re_log=$logdata->add($log);

      $yuludandata=M('po');
      $yid['PO_id']=$_GET['did'];
      $zt['PO_zt']=$_POST['PO_zt'];
      $re_zt=$yuludandata->where($yid)->save($zt);
      if($re_zt=='0'){
        $this->redirect('Order/dailu?did='.$_GET['did']);
      }else{
        $this->redirect('Order/dailulist');
      }

    }
    /*待开卡业务逻辑*/

    /*待开卡订单详情*/
    public function kaika(){
        $PO_id=$_GET['kid'];
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
       $this->assign('kid',$PO_id);
       $this->assign('price',$price[0]);
       $this->assign('kaika',$result[0]);
       $this->display('Order/kaika');
    }
    /*待开卡信息保存*/
    public function kaika_save(){
      $logdata=M('history');
      $content=$_POST['content'];
      $log['LB']="Change State";
      $log['UP_PO_id']=$_GET['kid'];
      $log['body']=$_POST['content'];
      $log['TJ_time']=date('Y-m-d H:i:s',time());
      $log['user']=get_username();
      $re_log=$logdata->add($log);

      $yuludandata=M('po');
      $yid['PO_id']=$_GET['kid'];
      $zt['PO_zt']=$_POST['PO_zt'];
      $re_zt=$yuludandata->where($yid)->save($zt);
      if($re_zt=='0'){
        $this->redirect('Order/kaika?kid='.$_GET['kid']);
      }else{
        $this->redirect('Order/kaikalist');
      }
    }
    /*待缴费业务逻辑处理*/

    /*待缴费订单详情*/
    public function nopay(){
       $PO_id=$_GET['nid'];
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
       $this->assign('nid',$PO_id);
       $this->assign('price',$price[0]);
       $this->assign('nopay',$result[0]);
      $this->display('Order/nopay');
    }
    /*待缴费信息保存*/
    public function nopay_save(){
      $logdata=M('history');
      $content=$_POST['content'];
      $log['LB']="Change State";
      $log['UP_PO_id']=$_GET['nid'];
      $log['body']=$_POST['content'];
      $log['TJ_time']=date('Y-m-d H:i:s',time());
      $log['user']=get_username();
      $re_log=$logdata->add($log);
      $yuludandata=M('po');
      $nid['PO_id']=$_GET['nid'];
      $zt['PO_zt']=$_POST['PO_zt'];
      $re_zt=$yuludandata->where($nid)->save($zt);
      if($re_zt=='0'){
        $this->redirect('Order/nopay?nid='.$_GET['nid']);
      }else{
        $this->redirect('Order/paylist');
      }
    }
    /*待施工业务逻辑处理*/


    /**宽带待施工订单详情*/
    public function install(){
      $PO_id=$_GET['sid'];
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
      $this->assign('sid',$PO_id);
      $this->assign('price',$price[0]);
      $this->assign('install',$result[0]);
      $this->display('Order/install');
    }
    /*待施工信息保存**/
    public function install_save(){
       $logdata=M('history');
      $content=$_POST['content'];
      $log['LB']="Change State";
      $log['UP_PO_id']=$_GET['sid'];
      $log['body']=$_POST['content'];
      $log['TJ_time']=date('Y-m-d H:i:s',time());
      $log['user']=get_username();
      $re_log=$logdata->add($log);
      $yuludandata=M('po');
      $nid['PO_id']=$_GET['sid'];
      $zt['PO_zt']=$_POST['PO_zt'];
      $re_zt=$yuludandata->where($nid)->save($zt);
      if($re_zt=='0'){
        $this->redirect('Order/install?sid='.$_GET['sid']);
      }else{
        $this->redirect('Order/installlist');
      }
      
    }


    // 单宽已完工订单详情表
    public function dankuan(){
      $PO_id=$_GET['fid'];
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
      $this->assign('fid',$PO_id);
      $this->assign('price',$price[0]);
      $this->assign('dankuan',$result[0]);
      $this->display('Order/dankuan');
    }
    /*单宽已完工信息保存*/
    public function dankuan_save(){
       $logdata=M('history');
      $content=$_POST['content'];
      $log['LB']="Change State";
      $log['UP_PO_id']=$_GET['fid'];
      $log['body']=$_POST['content'];
      $log['TJ_time']=date('Y-m-d H:i:s',time());
      $log['user']=get_username();
      $re_log=$logdata->add($log);
      $yuludandata=M('po');
      $nid['PO_id']=$_GET['fid'];
      $zt['PO_zt']=$_POST['PO_zt'];
      $re_zt=$yuludandata->where($nid)->save($zt);
      if($re_zt=='0'){
        $this->redirect('Order/dankuan?fid='.$_GET['fid']);
      }else{
        $this->redirect('Order/dankuanlist');
      }
      
    }

    public function ronghepay(){
      $PO_id=$_GET['rid'];
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
      $this->assign('rid',$PO_id);
      $this->assign('price',$price[0]);
      $this->assign('ronghepay',$result[0]);
      $this->display('Order/ronghepay');
    }
    public function ronghepay_save(){
      $logdata=M('history');
      $content=$_POST['content'];
      $log['LB']="Change State";
      $log['UP_PO_id']=$_GET['rid'];
      $log['body']=$_POST['content'];
      $log['TJ_time']=date('Y-m-d H:i:s',time());
      $log['user']=get_username();
      $re_log=$logdata->add($log);
      $yuludandata=M('po');
      $nid['PO_id']=$_GET['rid'];
      $zt['PO_zt']=$_POST['PO_zt'];
      $re_zt=$yuludandata->where($nid)->save($zt);
      if($re_zt=='0'){
        $this->redirect('Order/ronghepay?rid='.$_GET['rid']);
      }else{
        $this->redirect('Order/ronghepaylist');
      }
    }

    public function rongheover(){
      $PO_id=$_GET['gid'];
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
      $this->assign('gid',$PO_id);
      $this->assign('price',$price[0]);
      $this->assign('rongheover',$result[0]);
      $this->display('Order/rongheover');
    }
    public function rongheover_save(){
      $logdata=M('history');
      $content=$_POST['content'];
      $log['LB']="Change State";
      $log['UP_PO_id']=$_GET['gid'];
      $log['body']=$_POST['content'];
      $log['TJ_time']=date('Y-m-d H:i:s',time());
      $log['user']=get_username();
      $re_log=$logdata->add($log);
      $yuludandata=M('po');
      $nid['PO_id']=$_GET['gid'];
      $zt['PO_zt']=$_POST['PO_zt'];
      $re_zt=$yuludandata->where($nid)->save($zt);
      if($re_zt=='0'){
        $this->redirect('Order/rongheover?gid='.$_GET['gid']);
      }else{
        $this->redirect('Order/rongheoverlist');
      }
    }

}