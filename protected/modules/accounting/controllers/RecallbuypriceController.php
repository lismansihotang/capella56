<?php
class RecallbuypriceController extends Controller {
	public $menuname = 'recallbuyprice';
	public function actionIndex() {
		$this->renderPartial('index',array());
	}
	public function actionRun() {
		header('Content-Type: application/json');
		$begindate = date_parse_from_format('d-m-Y',$_POST['startdate']);  //date(Yii::app()->params['datetodb'], strtotime(isset ($_POST['startdate']) ? $_POST['startdate'] : 'now'));
		$month = $begindate['month'];
		$year = $begindate['year'];
		$companyid = $_POST['companyid'];
		$connection=Yii::app()->db;
		$transaction = $connection->beginTransaction();
		$status = '';$buyprice=0;$averageprice = 0;$currencyid=40;$currencyrate=1;$vid=0;
		try {
			/*$sql = "call recallbuyprice(".$companyid.",".$month.",".$year.")";*/
			$sql = "update productstockdet z set buyprice = 0, averageprice =0, currencyid = 40, currencyrate = 1
			where month(z.transdate) = ".$month."
				and year(z.transdate) = ".$year."
				and z.slocid in (select za.slocid from sloc za join plant zb on zb.plantid = za.plantid where zb.companyid = ".$companyid.")
				order by z.transdate";
			$connection->createCommand($sql)->execute();
			$sql = "SELECT z.productstockdetid,z.referenceno,z.productid,z.slocid,z.transdate,z.qty,z.productoutputfgid,z.uomid
				FROM productstockdet z
				where month(z.transdate) = ".$month."
				and year(z.transdate) = ".$year."
				and z.slocid in (select za.slocid from sloc za join plant zb on zb.plantid = za.plantid where zb.companyid = ".$companyid.")
				order by z.transdate";
			$datas = $connection->createCommand($sql)->queryAll();
			foreach ($datas as $data) {
				if (strpos($data['referenceno'],'LPB') === 0) {
					$sql = "select distinct 
						(select c.price from podetail c where c.productid = b.productid and c.poheaderid = a.poheaderid limit 1) * b.qty as buyprice,
						(select c.price from podetail c where c.productid = b.productid and c.poheaderid = a.poheaderid limit 1) as price,
						GetKurs(a.grdate, (select d.currencyid from poheader d where d.poheaderid = a.poheaderid limit 1)) as curr,
						(select d.currencyid from poheader d where d.poheaderid = a.poheaderid limit 1) as currid
						from grheader a
						join grdetail b on b.grheaderid = a.grheaderid 
						where a.grno = '".$data['referenceno']."' and b.productid = ".$data['productid']." and b.slocid = ".$data['slocid'];
					$datalpb = $connection->createCommand($sql)->queryRow();
					$buyprice = $datalpb['buyprice'];
					$averageprice = $datalpb['price'];
					$currencyid = $datalpb['currid'];
					$currencyrate = $datalpb['curr'];
				} else 
				if (strpos($data['referenceno'],'TSO') === 0) {
					$sql = "select distinct 
						b.buyprice * b.qty as buyprice,
						b.buyprice as price,
						GetKurs(a.bsdate, b.currencyid) as curr,
						b.currencyid as currid
						from bsheader a
						join bsdetail b on b.bsheaderid = a.bsheaderid 
						where a.bsheaderno = '".$data['referenceno']."' and b.productid = ".$data['productid']." and a.slocid = ".$data['slocid'];
					$datalpb = $connection->createCommand($sql)->queryRow();
					$buyprice = $datalpb['buyprice'];
					$averageprice = $datalpb['price'];
					$currencyid = $datalpb['currid'];
					$currencyrate = $datalpb['curr'];
				} else 
				if (strpos($data['referenceno'],'TFS') === 0) {
					$sql = "select averageprice*".$data['qty']." as buyprice,averageprice as price,40 as currid,1 as curr
						from productstockdet 
						where transdate < '".$data['transdate']."'
							and productid = ".$data['productid']." 
							and slocid = ".$data['slocid']."  
						order by transdate desc 
						limit 1";
					$datalpb = $connection->createCommand($sql)->queryRow();
					$buyprice = $datalpb['buyprice'];
					$averageprice = $datalpb['price'];
					$currencyid = $datalpb['currid'];
					$currencyrate = $datalpb['curr'];				
				} else 
				if (strpos($data['referenceno'],'OP') === 0) {
					if ($data['qty'] < 0) {
						$sql = "select averageprice*".$data['qty']." as buyprice,averageprice as price,40 as currid,1 as curr
							from productstockdet 
							where transdate < '".$data['transdate']."'
							and productid = ".$data['productid']." 
							and slocid = ".$data['slocid']." 
							order by transdate desc 
							limit 1";
						$datalpb = $connection->createCommand($sql)->queryRow();
						$buyprice = $datalpb['buyprice'];
						$averageprice = $datalpb['price'];
						$currencyid = $datalpb['currid'];
						$currencyrate = $datalpb['curr'];				
					} else {
						if ($data['productoutputfgid'] != null) {
							$sql = "select ifnull(sum(averageprice*qty*currencyrate),0) as total,ifnull(sum(ifnull(qty,0)),0) as qtys
								from productstockdet a
								where a.referenceno = '".$data['referenceno']."' and a.productid = ".$data['productid']." and a.productoutputfgid = ".$data['productoutputfgid'];
						} else {
							$sql = "select ifnull(sum(averageprice*qty*currencyrate),0) as total,ifnull(sum(ifnull(qty,0)),0) as qtys
								from productstockdet a
								where a.referenceno = '".$data['referenceno']."' and a.productid = ".$data['productid'];
						}
						$dataop = $connection->createCommand($sql)->queryRow();
						$buyprice = (float) $dataop['total'];
						$qty = (float) $data['qty'];
						$sql = "select ifnull(sum(b.qty / a.qty),0) as qtys
							from billofmaterial a 
							join bomdetail b on b.bomid = a.bomid 
							join product c on c.productid = b.productid 
							where a.productid = ".$data['productid']." and c.isstock = 0";
						$fohul = (float) $connection->createCommand($sql)->queryScalar();
						if ($fohul < 0) {
							$sql = "select ifnull(sum(a.valued),0) as qtys
							from stdfohul a 
							where plantid = (select plantid from sloc z where z.slocid = ".$data['slocid']." limit 1)
							and uomcid = ".$data['uomid'];
							$fohul = $connection->createCommand($sql)->queryScalar();
						}
						$buyprice = $buyprice + ($fohul * $qty);
						if ($qty > 0) {
							$averageprice = $buyprice / $qty;
						}
						$currencyid = 40;
						$currencyrate = 1;
					}
				} else 
				if (strpos($data['referenceno'],'TFF') === 0) {
					$sql = "select b.productoutputno 
						from transstock a 
						join productoutput b on b.productoutputid = a.productoutputid 
						where transstockno = '".$data['referenceno']."'";
					$no = $connection->createCommand($sql)->queryScalar();
					
					$sql = "select averageprice*".$data['qty']." as buyprice,averageprice as price,40 as currid,1 as curr
						from productstockdet 
						where transdate < '".$data['transdate']."'
						and referenceno = '".$no."' 
						and productid = ".$data['productid']."
						and slocid = ".$data['slocid']."
						and qty > 0
						order by transdate desc 
						limit 1";
					$datalpb = $connection->createCommand($sql)->queryRow();
					$buyprice = $datalpb['buyprice'];
					$averageprice = $datalpb['price'];
					$currencyid = $datalpb['currid'];
					$currencyrate = $datalpb['curr'];				
				} else 
				if (strpos($data['referenceno'],'RB') === 0) {
					$sql = "SELECT b.grno
						FROM grretur a 
						join grreturdetail c on c.grreturid = a.grreturid 
						join grheader b on b.grheaderid = c.grheaderid
						join grdetail d on d.grdetailid = c.grdetailid 
						where a.grreturno = '".$data['referenceno']."'";
					$no = $connection->createCommand($sql)->queryScalar();
					
					$sql = "select averageprice*".$data['qty']." as buyprice,averageprice as price,40 as currid,1 as curr
						from productstockdet 
						where transdate < '".$data['transdate']."'
						and referenceno = '".$no."' 
						and productid = ".$data['productid']."
						and slocid = ".$data['slocid']."
						and qty > 0
						order by transdate desc 
						limit 1";
					$datalpb = $connection->createCommand($sql)->queryRow();
					$buyprice = $datalpb['buyprice'];
					$averageprice = $datalpb['price'];
					$currencyid = $datalpb['currid'];
					$currencyrate = $datalpb['curr'];	
				} else 
				if (strpos($data['referenceno'],'SJ') === 0) {
					$sql = "
						select buyprice,averageprice,currencyid,currencyrate
						from productstockdet 
						where referenceno like 'TFF%'
						and transdate < '".$data['transdate']."' 
						and productid = ".$data['productid']."
						and slocid = ".$data['slocid']."
						order by transdate desc 
						limit 1
					";
					$datatff = $connection->createCommand($sql)->queryRow();
					$buyprice = $datalpb['buyprice'];
					$averageprice = $datalpb['price'];
					$currencyid = $datalpb['currid'];
					$currencyrate = $datalpb['curr'];	
				} else 
				if (strpos($data['referenceno'],'RJ') === 0) {
					$sql = "select gino 
						from giretur a
						join giheader b on b.giheaderid = a.giheaderid 
						join gireturdetail c on c.gireturid = a.gireturid 
						where c.productid = ".$data['productid']." and a.gireturno = '".$data['referenceno']."' and c.slocid = ".$data['slocid'];
					$no = $connection->createCommand($sql)->queryScalar();
					
					$sql = "
						select buyprice,averageprice,currencyid,currencyrate
						from productstockdet 
						where referenceno like '".$no."'
						and transdate < '".$data['transdate']."' 
						and productid = ".$data['productid']."
						and slocid = ".$data['slocid']."
						order by transdate desc 
						limit 1
					";
					$datatff = $connection->createCommand($sql)->queryRow();
					$buyprice = $datalpb['buyprice'];
					$averageprice = $datalpb['price'];
					$currencyid = $datalpb['currid'];
					$currencyrate = $datalpb['curr'];	
				} else 
				if (strpos($data['referenceno'],'TRS') === 0) {
					$sql = "select c.transstockno 
						from transstock a 
						join formrequest b on b.formrequestid = a.formrequestid 
						join transstock c on c.formrequestid = b.formrequestid 
						where a.transstockno = '".$data['referenceno']."' and c.transstockno like 'TF%' order by a.transstockdate desc limit 1";
					$no = $connection->createCommand($sql)->queryScalar();
					
					$sql = "select averageprice*".$data['qty']." as buyprice,averageprice as price,40 as currid,1 as curr
						from productstockdet 
						where transdate < '".$data['transdate']."'
						and referenceno = '".$no."' 
						and productid = ".$data['productid']."
						and slocid = ".$data['slocid']."
						and qty > 0
						order by transdate desc 
						limit 1";
					$datalpb = $connection->createCommand($sql)->queryRow();
					$buyprice = $datalpb['buyprice'];
					$averageprice = $datalpb['price'];
					$currencyid = $datalpb['currid'];
					$currencyrate = $datalpb['curr'];				
				}
				$vid = $data['productstockdetid'];
				$sql = "update productstockdet z
					set z.buyprice = '".(is_null($buyprice)?0:$buyprice).
					"', z.averageprice = '".(is_null($averageprice)?0:$averageprice).
					"', z.currencyrate = '".(is_null($currencyrate)?1:$currencyrate).
					"',z.currencyid = '".(is_null($currencyid)?40:$currencyid)."'
					where productstockdetid = ".$vid; 
				$connection->createCommand($sql)->execute();				
			}
			$transaction->commit();
			getmessage(false,'Harga Beli sudah di generate');
		}
		catch (Exception $e) {
			$transaction->rollback();
			getmessage(true,'Status: '.$status.' Error: '.$e->getMessage());
		}
	}
}