<?php
newspace Home\Model;
use Think\Model;
class PoModel extends Model{
	protected $trueTableName = 'po'; 
	public function polist($id=''){
		$PO_id=$id;
		$pid['PO_id']=$PO_id;
		$orderdata=M('po');
		$result=$orderdata->where($pid)->field('PO_id,PO_PordacetID,PO_name,PO_Client_name,PO_Client_bank,PO_Client_bank_id,PO_Client_visa_Nber,PO_Client_addres,PO_Client_phone,FKFS,PO_zt,PO_seles,PO_seles_id,BZ')->select();
		return $result;
	}
	
}