<?php
class ProfitlossfiskalController extends Controller {
  public $menuname = 'profitlossfiskal';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $repprofitlossfiskalid = isset($_POST['repprofitlossfiskalid']) ? $_POST['repprofitlossfiskalid'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $accountcode       = isset($_POST['accountcode']) ? $_POST['accountcode'] : '';
    $isdebet         = isset($_POST['isdebet']) ? $_POST['isdebet'] : '';
    $nourut          = isset($_POST['nourut']) ? $_POST['nourut'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'repprofitlossfiskalid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('repprofitlossfiskal t')
			->join('company a', 'a.companyid=t.companyid')
			->leftjoin('counttype b', 'b.counttypeid=t.counttypeid')
			->where('((t.repprofitlossfiskalid like :repprofitlossfiskalid) or
								(a.companyname like :companyid) or
								(t.accountcode like :accountcode) or
								(t.nourut like :nourut)) and t.companyid in ('.getUserObjectValues('company').')', array(
      ':repprofitlossfiskalid' => '%' . $repprofitlossfiskalid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':accountcode' => '%' . $accountcode . '%',
      ':nourut' => '%' . $nourut . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.companyid,a.companyname,b.counttypename')
			->from('repprofitlossfiskal t')
			->join('company a', 'a.companyid=t.companyid')
			->leftjoin('counttype b', 'b.counttypeid=t.counttypeid')
			->where('((t.repprofitlossfiskalid like :repprofitlossfiskalid) or
								(a.companyname like :companyid) or
								(t.accountcode like :accountcode) or
								(t.nourut like :nourut)) and t.companyid in ('.getUserObjectValues('company').')', array(
      ':repprofitlossfiskalid' => '%' . $repprofitlossfiskalid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':accountcode' => '%' . $accountcode . '%',
      ':nourut' => '%' . $nourut . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'repprofitlossfiskalid' => $data['repprofitlossfiskalid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'accountcode' => $data['accountcode'],
        'nourut' => $data['nourut'],
        'iscount' => $data['iscount'],
        'isbold' => $data['isbold'],
        'counttypeid' => $data['counttypeid'],
				'counttypename' => $data['counttypename'],
        'keterangan' => $data['keterangan'],
        'sourcemenu' => $data['sourcemenu']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
	private function ModifyData($arraydata) {
		$connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $id = (isset($arraydata[0])?$arraydata[0]:'');
			if ($id == '') {
        $sql     = 'call Insertprofitlossfiskal(:vcompanyid,:vnourut,:vaccountcode,:viscount,:vcounttype,:vsourcemenu,:visbold,:vketerangan,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateprofitlossfiskal(:vid,:vcompanyid,:vnourut,:vaccountcode,:viscount,:vcounttype,:vsourcemenu,:visbold,:vketerangan,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $arraydata[0]);
      }
      $command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
      $command->bindvalue(':vnourut', $arraydata[2], PDO::PARAM_STR);
      $command->bindvalue(':vaccountcode', $arraydata[3], PDO::PARAM_STR);
      $command->bindvalue(':viscount', $arraydata[4], PDO::PARAM_STR);
      $command->bindvalue(':vcounttype', $arraydata[5], PDO::PARAM_STR);
      $command->bindvalue(':vsourcemenu', $arraydata[6], PDO::PARAM_STR);
      $command->bindvalue(':visbold', $arraydata[7], PDO::PARAM_STR);
      $command->bindvalue(':vketerangan', $arraydata[8], PDO::PARAM_STR);
      $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
      $command->execute();
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
	}
  public function actionSave() {
    header('Content-Type: application/json');
    if (!Yii::app()->request->isPostRequest)
      throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
		$this->ModifyData(array((isset($_POST['repprofitlossid'])?$_POST['repprofitlossid']:''),
			$_POST['companyid'],$_POST['nourut'],
			$_POST['accountcode'],$_POST['iscount'],$_POST['counttypeid'],$_POST['sourcemenu'],$_POST['isbold'],$_POST['keterangan']));
  }
  public function actionPurge() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeprofitlossfiskal(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false, getcatalog('insertsuccess'));
      }
      catch (Exception $e) {
        $transaction->rollback();
        GetMessage(true, $e->getMessage());
      }
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionGeneratepl() {
    parent::actionDownload();
		$companyid = $_POST['companyid'];
		$tgl = date(Yii::app()->params['datetodb'], strtotime($_POST['bsdate']));
		$sql = "delete from repprofitlossfiskallajur where companyid = ".$companyid."  
      and tahun = year('".$tgl."')
      and bulan = month('".$tgl."')";
		Yii::app()->db->createCommand($sql)->execute();
		$sql = "select * from repprofitlossfiskal where companyid = " . $companyid." order by nourut";
		$datareader = Yii::app()->db->createCommand($sql)->queryAll();
		$accounts;
		foreach($datareader as $data) {
			$accounts = explode(';',$data['accountcode']);
			$i = 0; 
			if ($data['iscount'] == 0) {
				$sql1 = "insert into repprofitlossfiskallajur (companyid,bulan,tahun,nourut,keterangan,plantvalue,accountcode,iscount,isbold) 
					values (".$companyid.",month('".$tgl."'),
						year('".$tgl."'),
						".$data['nourut'].",'".$data['keterangan']."',0,null,".$data['iscount'].",".$data['isbold'].")";
				Yii::app()->db->createCommand($sql1)->execute();
			} else {
				if ($data['counttypeid'] == 6) {
					$sql1 = "insert into repprofitlossfiskallajur (companyid,bulan,tahun,nourut,keterangan,plantvalue,accountcode,iscount,isbold) 
						values (".$companyid.",month('".$tgl."'),
							year('".$tgl."'),
							".$data['nourut'].",'".$data['keterangan']."',".$data['accountcode'].",null,".$data['iscount'].",".$data['isbold'].")";
					Yii::app()->db->createCommand($sql1)->execute();
				} else
				if ($data['counttypeid'] == 5) {
					$accvalue = 0;
					$nouruts = explode('+',$accounts[$i]);
					foreach ($nouruts as $nourut) {
						$sql3 = "select ifnull(sum(ifnull(plantvalue,0)),0) as totalplant 
							from repprofitlossfiskallajur 
							where companyid = ".$companyid."  
							and tahun = year('".$tgl."')
							and bulan = month('".$tgl."')
							and nourut = ".$nourut;
						$accvalue = $accvalue + Yii::app()->db->createCommand($sql3)->queryScalar();
					}
					$sql1 = "insert into repprofitlossfiskallajur (companyid,bulan,tahun,nourut,keterangan,plantvalue,accountcode,iscount,isbold) 
						values (".$companyid.",month('".$tgl."'),
							year('".$tgl."'),
							".$data['nourut'].",'".$data['keterangan']."',".$accvalue.",null,".$data['iscount'].",".$data['isbold'].")";
					Yii::app()->db->createCommand($sql1)->execute();
				} else 
				if ($data['counttypeid'] == 4) {
					$accvalue = 0;
					$nouruts = explode('-',$accounts[$i]);
					foreach ($nouruts as $nourut) {
						$sql3 = "select ifnull(sum(ifnull(plantvalue,0)),0) as totalplant 
							from repprofitlossfiskallajur 
							where companyid = ".$companyid."  
							and tahun = year('".$tgl."')
							and bulan = month('".$tgl."')
							and nourut = ".$nourut;
						if ($accvalue == 0) {
							$accvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
						} else {
						$accvalue = $accvalue - Yii::app()->db->createCommand($sql3)->queryScalar();
						}
					}
					$sql1 = "insert into repprofitlossfiskallajur (companyid,bulan,tahun,nourut,keterangan,plantvalue,accountcode,iscount,isbold) 
						values (".$companyid.",month('".$tgl."'),
							year('".$tgl."'),
							".$data['nourut'].",'".$data['keterangan']."',".$accvalue.",null,".$data['iscount'].",".$data['isbold'].")";
					Yii::app()->db->createCommand($sql1)->execute();
				} else 
				if ($data['counttypeid'] == 3) {
					$sql3 = "select plantvalue  
						from ".$data['sourcemenu']." 
						where companyid = ".$companyid."  
							and tahun = year('".$tgl."')
							and bulan = month('".$tgl."') 
							and nourut = ".$data['accountcode'];
					$accvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
					if (($data['sourcemenu'] == 'repbiayaumumlajur') && ($i == 0)) {
						$sql3 = "select plantvalue  
							from ".$data['sourcemenu']." 
							where companyid = ".$companyid."  
								and tahun = year('".$tgl."')
								and bulan = month('".$tgl."')
								and nourut = ".$data['accountcode'];
						$accvalue = $accvalue + Yii::app()->db->createCommand($sql3)->queryScalar();
					}
					if ($accvalue < 0) {
						$accvalue = $accvalue * -1;
					}
					$sql1 = "insert into repprofitlossfiskallajur (companyid,bulan,tahun,nourut,keterangan,plantvalue,accountcode,iscount,isbold) 
						values (".$companyid.",month('".$tgl."'),
							year('".$tgl."'),
							".$data['nourut'].",'".$data['keterangan']."',".$accvalue.",null,".$data['iscount'].",".$data['isbold'].")";
					Yii::app()->db->createCommand($sql1)->execute();
				} else 
				if ($data['counttypeid'] == 2) {
					$accvalue = 0;
					$nouruts = explode('+',$accounts[$i]);
					foreach ($nouruts as $nourut) {
						$sql3 = "select ifnull(sum(ifnull(plantvalue,0)),0) as totalplant 
							from repprofitlossfiskallajur 
							where companyid = ".$companyid."  
							and tahun = year('".$tgl."')
							and bulan = month('".$tgl."') 
							and nourut = ".$nourut;
						$accvalue = $accvalue + Yii::app()->db->createCommand($sql3)->queryScalar();
					}
					$sql1 = "insert into repprofitlossfiskallajur (companyid,bulan,tahun,nourut,keterangan,plantvalue,accountcode,iscount,isbold) 
						values (".$companyid.",month('".$tgl."'),
							year('".$tgl."'),
							".$data['nourut'].",'".$data['keterangan']."',".$accvalue.",null,".$data['iscount'].",".$data['isbold'].")";
					Yii::app()->db->createCommand($sql1)->execute();
				} else {
					if ($data['iscount'] == 1) {
						$accountx = $accounts[$i];
						$accountd = explode('+',$accountx);
						$accvalue = 0;
						foreach($accountd as $accounte) {
							if ($data['counttypeid'] == 0) {
								$sql3 = "select case when b.isdebit = 1 then ifnull(sum(ifnull(debit,0))-sum(ifnull(credit,0)),0) else ifnull(sum(ifnull(credit,0))-sum(ifnull(debit,0)),0) end  as total 
									from genledger a  
									join account b on b.accountid = a.accountid 
									where a.journaldate < concat(year('".$tgl."'),'-',month('01'),'-01')
									and a.accountcode = '".$accounte."'";
								$accvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
							} else 
							if ($data['counttypeid'] == 1) {
								$sql3 = "select case when b.isdebit = 1 then ifnull(sum(ifnull(debit,0))-sum(ifnull(credit,0)),0) else ifnull(sum(ifnull(credit,0))-sum(ifnull(debit,0)),0) end  as total 
									from genledger a 
									join account b on b.accountid = a.accountid 
									where month(a.journaldate) <= month('".$tgl."')
									and year(a.journaldate) = year('".$tgl."')
									and a.accountcode = '".$accounte."'";
								$accvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
							} 
							if (strpos($data['keterangan'],"Retur") === false) {
							} else {
								$accvalue = $accvalue * -1;
							}
							$sql1 = "insert into repprofitlossfiskallajur (companyid,bulan,tahun,nourut,keterangan,plantvalue,accountcode,iscount,isbold) 
								values (".$companyid.",month('".$tgl."'),
									year('".$tgl."'),
									".$data['nourut'].",'".$data['keterangan']."',".$accvalue.",'".$accounte."',".$data['iscount'].",".$data['isbold'].")";
							Yii::app()->db->createCommand($sql1)->execute();
						}
					}
				}
				$i++;
			}
		}
		GetMessage('success', 'Data Generated');
  }
	public function actionDownPDF() {
    parent::actionDownload();
    $connection = Yii::app()->db;
    $sql        = "select distinct a.nourut,a.keterangan,a.iscount,a.isbold
      from repprofitlossfiskallajur a 
      where a.companyid = " . $_GET['company'] . " 
      and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      order by cast(nourut as int)";
    $datareader = $this->connection->createCommand($sql)->queryAll();
    $this->pdf->AddPage('P','A4');
    $this->pdf->companyid = $_GET['company'];
    $this->pdf->Cell(0, 0, GetCatalog('profitlossfiskal'), 0, 0, 'C');
    $this->pdf->Cell(-190, 10, 'Per : ' . date("d F Y", strtotime($_GET['date'])), 0, 0, 'C');
    $i = 0;
		
		foreach ($datareader as $data) {
			$colalign = array('C','C');
			$header = array('Keterangan');
			$colwidth = array(110);
			$coldetailalign = array('L');		
		}
		array_push($colalign,'C');
		array_push($header,'Total');
		array_push($colwidth,70);
		array_push($coldetailalign,'R');
		$this->pdf->setFont('Arial', 'B', 10);
		$this->pdf->sety($this->pdf->gety() + 10);
		$this->pdf->colalign = $colalign;
		$this->pdf->colheader = $header;
		$this->pdf->setwidths($colwidth);
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = $coldetailalign;
		foreach ($datareader as $data) {
			$total = 0;
			$datadetail = array($data['keterangan']);
			$sql2 = "
				select sum(ifnull(plantvalue,0)) as plantvalue
				from repprofitlossfiskallajur a 
				where a.companyid = " . $_GET['company'] . " 
				and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
				and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
				and a.nourut = '".$data['nourut']."'
				and a.keterangan = '".$data['keterangan']."'
				order by cast(nourut as int) 
			";
			$datareader2 = $this->connection->createCommand($sql2)->queryAll();
			$value = 0;
			foreach ($datareader2 as $data2) {
				$value = $data2['plantvalue'];
			}
			if ($data['iscount'] != 0) {
				$total = $total + $value;
				array_push($datadetail,Yii::app()->format->formatNumberWODecimal($value));
			}
			if ($data['isbold'] == 0) {
				$this->pdf->setFont('Arial', '', 10);
			} else {
				$this->pdf->setFont('Arial', 'B', 10);
			}
			$this->pdf->row($datadetail);
		}
    $this->pdf->Output();
  }
}