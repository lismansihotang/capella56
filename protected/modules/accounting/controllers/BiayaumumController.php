<?php
class BiayaumumController extends Controller {
  public $menuname = 'biayaumum';
  public function actionIndex() {
    parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
	public function actionIndexcounttype() {
    parent::actionIndex();
    if (isset($_GET['grid']))
      echo $this->searchcounttype();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $repbiayaumumid     = isset($_POST['repbiayaumumid']) ? $_POST['repbiayaumumid'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $accountcode       = isset($_POST['accountcode']) ? $_POST['accountcode'] : '';
    $nourut          = isset($_POST['nourut']) ? $_POST['nourut'] : '';
    $keterangan          = isset($_POST['keterangan']) ? $_POST['keterangan'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'repbiayaumumid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $com          = array();
       
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('repbiayaumum t')
			->join('company a', 'a.companyid=t.companyid')
			->join('counttype b', 'b.counttypeid=t.counttypeid')
			->where('((t.repbiayaumumid like :repbiayaumumid) or
                (a.companyname like :companyid) or
                (t.accountcode like :accountcode) or
                (t.nourut like :nourut)) and t.companyid in ('.getUserObjectValues('company').')', array(
      ':repbiayaumumid' => '%' . $repbiayaumumid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':accountcode' => '%' . $accountcode . '%',
      ':nourut' => '%' . $nourut . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.companyname,b.counttypename')
			->from('repbiayaumum t')
			->join('company a', 'a.companyid=t.companyid')
			->join('counttype b', 'b.counttypeid=t.counttypeid')
			->where('((t.repbiayaumumid like :repbiayaumumid) or
                (a.companyname like :companyid) or
                (t.accountcode like :accountcode) or
                (t.nourut like :nourut)) and t.companyid in ('.getUserObjectValues('company').')', array(
      ':repbiayaumumid' => '%' . $repbiayaumumid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':accountcode' => '%' . $accountcode . '%',
      ':nourut' => '%' . $nourut . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'repbiayaumumid' => $data['repbiayaumumid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'accountcode' => $data['accountcode'],
        'plantcode' => $data['plantcode'],
        'nourut' => $data['nourut'],
        'isbold' => $data['isbold'],
        'iscount' => $data['iscount'],
        'counttypeid' => $data['counttypeid'],
        'counttypename' => $data['counttypename'],
        'keterangan' => $data['keterangan']
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  private function ModifyData($connection,$arraydata) {
    $id = (isset($arraydata[0])?$arraydata[0]:'');
    if ($id == '') {
      $sql     = 'call Insertbiayaumum(:vcompanyid,:vaccountcode,:vplantcode,:vnourut,:visbold,:viscount,:vcounttype,:vketerangan,:vcreatedby)';
      $command = $connection->createCommand($sql);
    } else {
      $sql     = 'call Updatebiayaumum(:vid,:vcompanyid,:vaccountcode,:vplantcode,:vnourut,:visbold,:viscount,:vcounttype,:vketerangan,:vcreatedby)';
      $command = $connection->createCommand($sql);
      $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
      $this->DeleteLock($this->menuname, $arraydata[0]);
    }
    $command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
    $command->bindvalue(':vaccountcode', $arraydata[2], PDO::PARAM_STR);
    $command->bindvalue(':vplantcode', $arraydata[3], PDO::PARAM_STR);
    $command->bindvalue(':vnourut', $arraydata[4], PDO::PARAM_STR);
    $command->bindvalue(':visbold', $arraydata[5], PDO::PARAM_STR);
    $command->bindvalue(':viscount', $arraydata[6], PDO::PARAM_STR);
    $command->bindvalue(':vcounttype', $arraydata[7], PDO::PARAM_STR);
    $command->bindvalue(':vketerangan', $arraydata[8], PDO::PARAM_STR);
    $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
    $command->execute();
  }
  public function actionSave() {
    parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
			$this->ModifyData($connection,array((isset($_POST['repbiayaumumid'])?$_POST['repbiayaumumid']:''),
				$_POST['companyid'],
				$_POST['accountcode'],$_POST['plantcode'],$_POST['nourut'],
				$_POST['isbold'],$_POST['iscount'],$_POST['counttypeid'],$_POST['keterangan']));
      $transaction->commit();
      GetMessage(false,getcatalog('insertsuccess'));
		}
		catch (CDbException $e) {
			$transaction->rollBack();
			GetMessage(true,implode(" ",$e->errorInfo));
		}
  }
  public function actionPurge() {
    parent::actionPurge();
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgebiayaumum(:vid,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $id, PDO::PARAM_STR);
        $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
        $command->execute();
        $transaction->commit();
        GetMessage(false,getcatalog('insertsuccess'));
			}
			catch (CDbException $e) {
				$transaction->rollBack();
				GetMessage(true,implode(" ",$e->errorInfo));
			}
    } else {
      GetMessage(true, 'chooseone');
    }
  }
  public function actionGeneratebs() {
    parent::actionDownload();
		$companyid = $_POST['companyid'];
		$tgl = date(Yii::app()->params['datetodb'], strtotime($_POST['bsdate']));
		$sql = "delete from repbiayaumumlajur where companyid = ".$companyid."  
      and tahun = year('".$tgl."')
      and bulan = month('".$tgl."')";
		Yii::app()->db->createCommand($sql)->execute();
		$sql = "select * from repbiayaumum where companyid = " . $companyid;
		$datareader = Yii::app()->db->createCommand($sql)->queryAll();
		$plants;$accounts;
		foreach($datareader as $data) {
			$plants = explode(';',$data['plantcode']);
			$accounts = explode(';',$data['accountcode']);
			$i = 0;
			foreach($plants as $plant) {
				if ($data['counttypeid'] == 2) {
					$accvalue = 0;
					$nouruts = explode('+',$accounts[$i]);
					foreach ($nouruts as $nourut) {
						$sql3 = "select ifnull(sum(ifnull(plantvalue,0)),0) as totalplant 
							from repbiayaumumlajur 
							where companyid = ".$companyid."  
							and tahun = year('".$tgl."')
							and bulan >= 1 
							and bulan <= month('".$tgl."')
							and plantcode = '".$plant."' 
							and nourut = ".$nourut;
						$accvalue = $accvalue + Yii::app()->db->createCommand($sql3)->queryScalar();
					}
					$sql1 = "insert into repbiayaumumlajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
						values (".$companyid.",month('".$tgl."'),
							year('".$tgl."'),
							".$data['nourut'].",'".$data['keterangan']."','".$plant."',".$accvalue.",null,".$data['iscount'].",".$data['isbold'].")";
					Yii::app()->db->createCommand($sql1)->execute();
				} else 
				if ($data['counttypeid'] == 1) {
					$value = 0;
					$accountx = $accounts[$i];
					$accountd = explode('+',$accountx);
					foreach($accountd as $accounte) {
						$sql1 = "insert into repbiayaumumlajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
							values (".$companyid.",month('".$tgl."'),
								year('".$tgl."'),
								".$data['nourut'].",'".$data['keterangan']."','".$plant."'";
						$sql3 = "select case when b.isdebit = 1 then ifnull(sum(ifnull(debit,0))-sum(ifnull(credit,0)),0) else ifnull(sum(ifnull(credit,0))-sum(ifnull(debit,0)),0) end  as total 
										from genledger a  
										join account b on b.accountid = a.accountid  
							where month(a.journaldate) >= 1 and month(a.journaldate) <= month('".$tgl."')
							and year(a.journaldate) = year('".$tgl."')
							and a.accountcode = '".$accounte."'";
						$accvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
						$sql1 .= ",".$accvalue.",'".$accounte."',".$data['iscount'].",".$data['isbold'].")";
						Yii::app()->db->createCommand($sql1)->execute();
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
      from repbiayaumumlajur a 
      where a.companyid = " . $_GET['company'] . " 
      and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      order by cast(nourut as int)";
    $datareader = $this->connection->createCommand($sql)->queryAll();
    $this->pdf->AddPage('L');
    $this->pdf->companyid = $_GET['company'];
    $this->pdf->Cell(0, 0, GetCatalog('biayaumum'), 0, 0, 'C');
    $this->pdf->Cell(-277, 10, 'Per : ' . date("d F Y", strtotime($_GET['date'])), 0, 0, 'C');
    $i = 0;
		
		foreach ($datareader as $data) {
			$sql1 = "select distinct plantcode from repbiayaumum where companyid = ".$_GET['company']." limit 1";
			$datareader1 = $this->connection->createCommand($sql1)->queryScalar();
			$plants = explode(';',$datareader1);
			$colalign = array('C','C');
			$header = array('No Urut','Keterangan');
			$colwidth = array(20,70);
			$coldetailalign = array('L','L');
			foreach ($plants as $plant) {
				array_push($header,$plant);
				array_push($colwidth,40);
				array_push($coldetailalign,'R');
				array_push($colalign,'C');
			}			
		}
		array_push($colalign,'C');
		array_push($header,'Total');
		array_push($colwidth,45);
		array_push($coldetailalign,'R');
		$this->pdf->setFont('Arial', 'B', 10);
		$this->pdf->sety($this->pdf->gety() + 10);
		$this->pdf->colalign = $colalign;
		$this->pdf->colheader = $header;
		$this->pdf->setwidths($colwidth);
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = $coldetailalign;
		foreach ($datareader as $data) {
			$sql1 = "select distinct plantcode from repbiayaumum where companyid = ".$_GET['company']." limit 1";
			$datareader1 = $this->connection->createCommand($sql1)->queryScalar();
			$plants = explode(';',$datareader1);
			$total = 0;
			if ($data['nourut'] == 99) {
				$datadetail = array('',$data['keterangan']);
			} else {
			$datadetail = array($data['nourut'],$data['keterangan']);
			}
			foreach ($plants as $plant) {
				$sql2 = "
					select ifnull(sum(ifnull(plantvalue,0)),0) as plantvalue
					from repbiayaumumlajur a 
					where a.companyid = " . $_GET['company'] . " 
					and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
					and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
					and a.nourut = '".$data['nourut']."'
					and a.plantcode = '".$plant."' 
					and a.keterangan = '".$data['keterangan']."'
					order by cast(nourut as int) 
				";
				$datareader2 = $this->connection->createCommand($sql2)->queryAll();
				$value = 0;
				foreach ($datareader2 as $data2) {
					$value = $data2['plantvalue'];
				}
				$total = $total + $value;
				array_push($datadetail,Yii::app()->format->formatNumberWODecimal($value));
			}
			array_push($datadetail,Yii::app()->format->formatNumberWODecimal($total));
			if ($data['isbold'] == 1) {
				$this->pdf->setFont('Arial', 'B', 10);
			} else {
				$this->pdf->setFont('Arial', '', 10);
			}
			$this->pdf->row($datadetail);
		}
    $this->pdf->Output();
  }
}