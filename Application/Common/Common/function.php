<?php
/**隐藏头部和底部**/
/**
 * 检测用户是否登录
 *
 * @return integer 0-未登录，大于0-当前登录用户ID
 */
function is_login() {
	return $_SESSION['uid']?$_SESSION['uid']:0;
}
/**
 * 查询用户登录名
 *
 * @return string false-未登录，不为空-当前登录用户名
 */
function get_username() {
	$uid=is_login();
	$rid['ID']=$uid;
	$userdata=M('masteruser');
	$realname=$userdata->where($rid)->field('Unamename')->select();
	return $realname[0]['Unamename'];
}

/**
*
*用户数据操作
*
* @return 用户数据操作结果
**/
// function mysql_action($table,$action,$array,$where=0){
// 	$table="'".$table."'";
// 	$tabledata=M($table);
// 	switch ($action) {
// 		case 'add':
// 			$tabledata->$add($array);
// 			break;
// 		case 'del':
			
// 			break;
// 		case 'save':
// 			break;
// 		case 'select':
// 			break;
// 		default:
// 			return "alert('非法操作！')";
// 			break;
// 	}
// }
/**
*
*用户数据操作
*
* @return 用户订单列表
**/
    function orderlist($type="",$status=""){
		$listname=($type.'list');
		$listurl=('Order/'.$listname);
		$orderdata=M('po');
		$map['PO_zt']=array('like',$status);
        $count=$orderdata->where($map)->count();
        $Page=new \Think\Page($count,15);
        $show=$Page->show();
        $list=$orderdata->where($map)->field(array('PO_id,PO_zt,PO_Client_name,PO_time,PO_Client_visa,PO_Client_visa_Nber,PO_name'))->order('PO_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $nowpage=$Page->nowPage;
        $totalpage=$Page->totalPages;
		$listarr[0]=$show;
		$listarr[1]=$list;
		$listarr[2]=$nowpage;
		$listarr[3]=$totalpage;
		$listarr[4]=$count;
		return $listarr;
    }

/**
*
*用户数据操作
*
* @return 用户订单详情
**/
function orderdetail($id=0){

	$pid['PO_id']=$id;
	$orderdata=M('po');
	$result=$orderdata->where($pid)->field('PO_id,PO_PordacetID,PO_name,PO_Client_name,PO_Client_bank,PO_Client_bank_id,PO_Client_visa_Nber,PO_Client_addres,PO_Client_phone,FKFS,PO_zt,PO_seles,PO_seles_id,BZ')->select();
	return $result[0];
}

/**
*
*用户数据操作
*
* @return 用户订单备注信息
**/

function  beizhu($id=0){
	//订单备注信息查询
	 $beizhu_data=M('po');
	 $hid['PO_id']=$id;
	 $beizhu=$beizhu_data->where($hid)->field('BZ')->select();
	 return $beizhu['BZ'];
}
		

/**
*
*用户数据操作
*
* @return 用户订单价格
**/

function price($tc_id=0){
	//套餐资费查询
	$product=M('adsl_tc');
	$tc_id['TC_id']=$tc_id;
	$price=$product->where($tc_id)->field('TC_KDZF')->select();
	return $price[0]['TC_KDZF'];
}


/**
*
*用户数据操作
*
* @return 用户订单日志
**/

function logs($id=0){
	//订单日志查询
	$logdata=M('history');
	$lid['UP_PO_id']=$id;
	$logs=$logdata->where($lid)->field('ID,LB,body,TJ_time,user')->order('TJ_time desc')->select();
	return $logs;
}

