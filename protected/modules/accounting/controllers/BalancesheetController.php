<?php
class BalancesheetController extends Controller {
  public $menuname = 'balancesheet';
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
    $repneracaid     = isset($_POST['repneracaid']) ? $_POST['repneracaid'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $accountcoded       = isset($_POST['accountcoded']) ? $_POST['accountcoded'] : '';
    $nourut          = isset($_POST['nourut']) ? $_POST['nourut'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'repneracaid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
		->from('repneraca t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->leftjoin('counttype b','b.counttypeid=t.counttyped')
    ->leftjoin('counttype c','c.counttypeid=t.counttypek')
		->where('((t.repneracaid like :repneracaid) or
                (a.companyname like :companyid) or
                (t.accountcoded like :accountcoded) or
                (t.nourut like :nourut)) and t.companyid in ('.getUserObjectValues('company').')', array(
      ':repneracaid' => '%' . $repneracaid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':accountcoded' => '%' . $accountcoded . '%',
      ':nourut' => '%' . $nourut . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.companyid,a.companyname,b.counttypename as counttypedname,c.counttypename as counttypekname')
		->from('repneraca t')
    ->leftjoin('company a', 'a.companyid=t.companyid')
    ->leftjoin('counttype b','b.counttypeid=t.counttyped')
    ->leftjoin('counttype c','c.counttypeid=t.counttypek')
		->where('((t.repneracaid like :repneracaid) or
                (a.companyname like :companyid) or
                (t.accountcoded like :accountcoded) or
                (t.nourut like :nourut)) and t.companyid in ('.getUserObjectValues('company').')', array(
      ':repneracaid' => '%' . $repneracaid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':accountcoded' => '%' . $accountcoded . '%',
      ':nourut' => '%' . $nourut . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'repneracaid' => $data['repneracaid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'nourut' => $data['nourut'],
        'accountcoded' => $data['accountcoded'],
        'keterangand' => $data['keterangand'],
        'iscountd' => $data['iscountd'],
        'isboldd' => $data['isboldd'],
        'counttyped' => $data['counttyped'],
        'counttypenamed' => $data['counttypedname'],
        'accountcodek' => $data['accountcodek'],
        'keterangank' => $data['keterangank'],
        'iscountk' => $data['iscountk'],
        'isboldk' => $data['isboldk'],
        'counttypek' => $data['counttypek'],
        'counttypenamek' => $data['counttypekname'],
      );
    }
    $result = array_merge($result, array(
      'rows' => $row
    ));
    return CJSON::encode($result);
  }
  public function searchcounttype() {
    header('Content-Type: application/json');
    $nourut          = isset($_POST['nourut']) ? $_POST['nourut'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'counttypeid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
      ->from('counttype t')
      ->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*')
		  ->from('counttype t')
      ->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'counttypeid' => $data['counttypeid'],
        'counttypename' => $data['counttypename'],
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
      $sql     = 'call Insertbalancesheet(:vcompanyid,:vnourut,:vaccountcoded,:vketerangand,:viscountd,:visboldd,:vcounttyped,:vaccountcodek,:vketerangank,:viscountk,:visboldk,:vcounttypek,:vcreatedby)';
      $command = $connection->createCommand($sql);
    } else {
      $sql     = 'call Updatebalancesheet(:vid,:vcompanyid,:vnourut,:vaccountcoded,:vketerangand,:viscountd,:visboldd,:vcounttyped,:vaccountcodek,:vketerangank,:viscountk,:visboldk,:vcounttypek,:vcreatedby)';
      $command = $connection->createCommand($sql);
      $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
      $this->DeleteLock($this->menuname, $arraydata[0]);
    }
    $command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
    $command->bindvalue(':vnourut', $arraydata[2], PDO::PARAM_STR);
    $command->bindvalue(':vaccountcoded', $arraydata[3], PDO::PARAM_STR);
    $command->bindvalue(':vketerangand', $arraydata[4], PDO::PARAM_STR);
    $command->bindvalue(':viscountd', $arraydata[5], PDO::PARAM_STR);
    $command->bindvalue(':visboldd', $arraydata[6], PDO::PARAM_STR);
    $command->bindvalue(':vcounttyped', $arraydata[7], PDO::PARAM_STR);
    $command->bindvalue(':vaccountcodek', $arraydata[8], PDO::PARAM_STR);
    $command->bindvalue(':vketerangank', $arraydata[9], PDO::PARAM_STR);
    $command->bindvalue(':viscountk', $arraydata[10], PDO::PARAM_STR);
    $command->bindvalue(':visboldk', $arraydata[11], PDO::PARAM_STR);
    $command->bindvalue(':vcounttypek', $arraydata[12], PDO::PARAM_STR);
    $command->bindvalue(':vcreatedby', Yii::app()->user->name, PDO::PARAM_STR);
    $command->execute();
  }
  public function actionSave() {
    parent::actionWrite();
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      $this->ModifyData($connection,array((isset($_POST['repneracaid'])?$_POST['repneracaid']:''),
        $_POST['companyid'],$_POST['nourut'],$_POST['accountcoded'],
        $_POST['keterangand'],$_POST['iscountd'],$_POST['isboldd'],
        $_POST['counttyped'],$_POST['accountcodek'],$_POST['keterangank'],$_POST['iscountk'],$_POST['isboldk'],$_POST['counttypek']));
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
        $sql     = 'call Purgebalancesheet(:vid,:vcreatedby)';
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
		$tgls = date_parse_from_format('Y-m-d',$tgl);
		$year = $tgls['year'];
		$month = $tgls['month'];
		$sql = "delete from repneracalajur where companyid = ".$companyid."  
      and tahun = year('".$tgl."')
      and bulan = month('".$tgl."')";
		Yii::app()->db->createCommand($sql)->execute();
		$sql = "select * from repneraca where companyid = " . $companyid. " order by nourut";
		$datareader = Yii::app()->db->createCommand($sql)->queryAll();
		$accountds;$accountks;
		foreach($datareader as $data) {
			$accountds = explode(';',$data['accountcoded']);
			$i = 0;
			if ($data['iscountd'] == 0) {
				$sql1 = "insert into repneracalajur (companyid,bulan,tahun,nourut,keterangand,accountcoded,accountvalued,iscountd,isboldd) 
					values (".$companyid.",month('".$tgl."'),
						year('".$tgl."'),
						".$data['nourut'].",'".$data['keterangand']."',null,null,".$data['iscountd'].",".$data['isboldd'].")";
				Yii::app()->db->createCommand($sql1)->execute();
      } else 
      {
        if ($data['counttyped'] == 2) {
          $accvalue = 0;
          $nouruts = explode('+',$accountds[$i]);
          foreach ($nouruts as $nourut) {
            $sql3 = "select ifnull(sum(ifnull(accountvalued,0)),0) as totalplant 
              from repneracalajur 
              where companyid = ".$companyid."  
              and tahun = year('".$tgl."')
              and bulan = month('".$tgl."')
              and nourut = ".$nourut;
            $accvalue = $accvalue + Yii::app()->db->createCommand($sql3)->queryScalar();
          }
          $sql1 = "insert into repneracalajur (companyid,bulan,tahun,nourut,keterangand,accountcoded,accountvalued,iscountd,isboldd) 
            values (".$companyid.",month('".$tgl."'),
              year('".$tgl."'),
              ".$data['nourut'].",'".$data['keterangand']."',null,".$accvalue.",".$data['iscountd'].",".$data['isboldd'].")";
          Yii::app()->db->createCommand($sql1)->execute();
        } else {
          $accountx = $accountds[$i];
          $accountd = explode('+',$accountx);
          $accvalue = 0;

          foreach($accountd as $accounte) {
            if ($data['counttyped'] == 0) {
              $sql3 = "select case when b.isdebit = 1 then ifnull(sum(ifnull(debit,0)*ratevalue)-sum(ifnull(credit,0)*ratevalue),0) else ifnull(sum(ifnull(credit,0)*ratevalue)-sum(ifnull(debit,0)*ratevalue),0)*-1 end  as total 
                from genledger a  
                join account b on b.accountid = a.accountid 
                where a.journaldate < concat(year('".$tgl."'),'-',month('01'),'-01')
                and a.accountcode = '".$accounte."'";
              $accvalue = $accvalue + Yii::app()->db->createCommand($sql3)->queryScalar();
            } else 
            if ($data['counttyped'] == 1) {
              $sql3 = "select case when b.isdebit = 1 then 
              ifnull(sum(ifnull(debit,0)*ratevalue)-sum(ifnull(credit,0)*ratevalue),0) 
              else ifnull(sum(ifnull(credit,0)*ratevalue)-sum(ifnull(debit,0)*ratevalue),0)*-1 end  as total 
                from genledger a  
                join account b on b.accountid = a.accountid 
                where a.journaldate <= '".$tgl."' and a.journaldate >= concat(year('".$tgl."'),'-01-01')
                and a.accountcode = '".$accounte."'";
              $accvalue = $accvalue + Yii::app()->db->createCommand($sql3)->queryScalar();
            } 
          }
          $sql1 = "insert into repneracalajur (companyid,bulan,tahun,nourut,keterangand,accountcoded,accountvalued,iscountd,isboldd) 
            values (".$companyid.",month('".$tgl."'),
              year('".$tgl."'),
              ".$data['nourut'].",'".$data['keterangand']."','".$data['accountcoded']."',".$accvalue.",".$data['iscountd'].",".$data['isboldd'].")";
          Yii::app()->db->createCommand($sql1)->execute();
        }
      }
			$accountds = explode(';',$data['accountcodek']);
			$i = 0;
			if ($data['iscountk'] == 0) {
				$sql1 = "update repneracalajur 
					set keterangank = '".$data['keterangank']."' 
						,accountcodek = null
						,accountvaluek = null
						,iscountk = ".$data['iscountk']."
						,isboldk = ".$data['isboldk']."
					where companyid = ".$companyid." 
						and bulan = month('".$tgl."')
						and tahun = year('".$tgl."') 
						and nourut = ".$data['nourut'];
				Yii::app()->db->createCommand($sql1)->execute();
      } else 
      {
				if ($data['counttypek'] == 3) {
					$sql1 = "update repneracalajur 
						set keterangank = '".$data['keterangank']."' 
							,accountcodek = null
							,accountvaluek = 0
							,iscountk = ".$data['iscountk']."
							,isboldk = ".$data['isboldk']."
						where companyid = ".$companyid." 
							and bulan = month('".$tgl."')
							and tahun = year('".$tgl."') 
							and nourut = ".$data['nourut'];
					Yii::app()->db->createCommand($sql1)->execute();
				} else 
				if ($data['counttypek'] == 2) {
					$accvalue = 0;
					$nouruts = explode('+',$accountds[$i]);
					foreach ($nouruts as $nourut) {
						$sql3 = "select ifnull(sum(ifnull(accountvaluek,0)),0) as totalplant 
							from repneracalajur 
							where companyid = ".$companyid."  
							and tahun = year('".$tgl."')
							and bulan = month('".$tgl."')
							and nourut = ".$nourut;
						$accvalue = $accvalue + Yii::app()->db->createCommand($sql3)->queryScalar();
					}
					$sql1 = "update repneracalajur 
						set keterangank = '".$data['keterangank']."' 
							,accountcodek = null
							,accountvaluek = ".$accvalue."
							,iscountk = ".$data['iscountk']."
							,isboldk = ".$data['isboldk']."
						where companyid = ".$companyid." 
							and bulan = month('".$tgl."')
							and tahun = year('".$tgl."') 
							and nourut = ".$data['nourut'];
					Yii::app()->db->createCommand($sql1)->execute();
				} else {
					$accountx = $accountds[$i];
					$accountd = explode('+',$accountx);
					$accvalue = 0;
					foreach($accountd as $accounte) {
						if ($data['counttypek'] == 0) {
							$sql3 = "select case when b.isdebit = 1 then ifnull(sum(ifnull(debit,0)*ratevalue)-sum(ifnull(credit,0)*ratevalue),0) else ifnull(sum(ifnull(credit,0)*ratevalue)-sum(ifnull(debit,0)*ratevalue),0) end  as total 
								from genledger a  
								join account b on b.accountid = a.accountid 
								where a.journaldate <= concat(year('".$tgl."'),'-',month('01'),'-01')
								and a.accountcode = '".$accounte."'";
							$accvalue = $accvalue + Yii::app()->db->createCommand($sql3)->queryScalar();
						} else 
						if ($data['counttypek'] == 1) {
							$sql3 = "select case when b.isdebit = 1 then 
							ifnull(sum(ifnull(debit,0)*ratevalue)-sum(ifnull(credit,0)*ratevalue),0) 
							else ifnull(sum(ifnull(credit,0)*ratevalue)-sum(ifnull(debit,0)*ratevalue),0) end  as total 
								from genledger a  
								join account b on b.accountid = a.accountid 
                where a.journaldate <= '".$tgl."' and a.journaldate >= concat(year('".$tgl."'),'-01-01')
								and a.accountcode = '".$accounte."'";
							$accvalue = $accvalue + Yii::app()->db->createCommand($sql3)->queryScalar();
						} 						
					}
					$sql1 = "update repneracalajur 
						set keterangank = '".$data['keterangank']."' 
							,accountcodek = ".$data['accountcodek']."
							,accountvaluek = ".$accvalue."
							,iscountk = ".$data['iscountk']."
							,isboldk = ".$data['isboldk']."
						where companyid = ".$companyid." 
							and bulan = month('".$tgl."')
							and tahun = year('".$tgl."') 
							and nourut = ".$data['nourut'];
					Yii::app()->db->createCommand($sql1)->execute();
				}
      }
    }
		$sql1 = "select ifnull(accountvaluek,0) - ifnull(accountvalued,0) as lababersih,accountvalued,accountvaluek 
							from repneracalajur 
							where companyid = ".$companyid."  
							and tahun = year('".$tgl."')
							and bulan = month('".$tgl."')
              and nourut = 100";
    $data = Yii::app()->db->createCommand($sql1)->queryRow();
		$sql3 = "update repneracalajur
							set accountvaluek = abs(".$data['lababersih'].")
							where companyid = ".$companyid."  
							and tahun = year('".$tgl."')
							and bulan = month('".$tgl."')
							and keterangank like '%LABA BERSIH%'";
		Yii::app()->db->createCommand($sql3)->execute();						
		$sql3 = "update repneracalajur
							set accountvaluek = ".$data['accountvalued']."
							where companyid = ".$companyid."  
							and tahun = year('".$tgl."')
							and bulan = month('".$tgl."')
							and keterangank = 'JUMLAH LIABILITAS + EKUITAS'";
		Yii::app()->db->createCommand($sql3)->execute();	
		$sql1 = "select accountcodek 
							from repneraca
							where companyid = ".$companyid."  
							and keterangank = 'JUMLAH EKUITAS'";
		$accountds = Yii::app()->db->createCommand($sql1)->queryScalar();
		$accvalue = 0;
		$nouruts = explode('+',$accountds);
		foreach ($nouruts as $nourut) {
			$sql3 = "select ifnull(sum(ifnull(accountvaluek,0)),0) as totalplant 
				from repneracalajur 
				where companyid = ".$companyid."  
				and tahun = year('".$tgl."')
				and bulan = month('".$tgl."')
				and nourut = ".$nourut;
			$accvalue = $accvalue + Yii::app()->db->createCommand($sql3)->queryScalar();
		}
		$sql1 = "update repneracalajur 
			set accountvaluek = ".$accvalue."
			where companyid = ".$companyid." 
				and bulan = month('".$tgl."')
				and tahun = year('".$tgl."') 
				and keterangank = 'JUMLAH EKUITAS'";
		Yii::app()->db->createCommand($sql1)->execute();
    GetMessage(false, 'Data Generated');
  }
  public function actionDownPDF() {
    parent::actionDownload();
    $connection = Yii::app()->db;
    $sql        = "select a.nourut,a.keterangand,a.iscountd,a.isboldd,ifnull(sum(ifnull(accountvalued,0)),0) as accountvalued,a.iscountk,
			a.isboldk, ifnull(sum(ifnull(accountvaluek,0)),0) as accountvaluek,keterangank
      from repneracalajur a 
      where a.companyid = " . $_GET['company'] . " 
      and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      group by a.companyid,a.bulan,a.tahun,a.nourut
			order by cast(nourut as int)";
    $datareader = $this->connection->createCommand($sql)->queryAll();
    $this->pdf->AddPage('L',array(230,297));
    $this->pdf->companyid = $_GET['company'];
    $this->pdf->Cell(0, -10, GetCatalog('balancesheet'), 0, 0, 'C');
    $this->pdf->Cell(-277, 0, 'Per : ' . date("d F Y", strtotime($_GET['date'])), 0, 0, 'C');
    $i = 0;
		
		$colalign = array('C','C');
		$header = array('Keterangan','Jumlah','','Keterangan','Jumlah');
		$colwidth = array(100,45,2,90,45);
		$colalign = array('C','C','C','C','C');
		$coldetailalign = array('L','R','L','L','R');
		$this->pdf->setFont('Arial', 'B', 10);
		$this->pdf->sety($this->pdf->gety() + 5);
		$this->pdf->colalign = $colalign;
		$this->pdf->colheader = $header;
		$this->pdf->setwidths($colwidth);
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = $coldetailalign;
		foreach ($datareader as $data) {
			$total = 0;
			$this->pdf->rowstyles = array(
				array (
					'Arial',
					($data['isboldd'] == 1)?'B':'',
					'8'
				),
				array (
					'Arial',
					($data['isboldd'] == 1)?'B':'',
					'8'
				),
				array (
					'Arial','','8'
				),
				array (
					'Arial',
					($data['isboldk'] == 1)?'B':'',
					'8'
				),
				array (
					'Arial',
					($data['isboldk'] == 1)?'B':'',
					'8'
				),
			);
			$datadetail = array(
				$data['keterangand'],
				($data['iscountd'] == 1)?Yii::app()->format->formatNumberWODecimal(round_up($data['accountvalued'],1)):null,
				null,
				$data['keterangank'],
				($data['iscountk'] == 1)?Yii::app()->format->formatNumberWODecimal(round_up($data['accountvaluek'],1)):null,
			);			
			$this->pdf->row($datadetail);
		}
    $this->pdf->Output();
  }
  public function actionDownRatioPDF() {
    parent::actionDownload();
    $connection = Yii::app()->db;
    $akt  = "select a.accblninitotal
      from repneracalajur a 
      where a.companyid = " . $_GET['company'] . " 
      and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      ";
    $pas  = "select a.pasblninitotal
      from repneracalajur a 
      where a.companyid = " . $_GET['company'] . " 
      and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      ";
    $lr   = "select a.actualblninitotal
      from repprofitlosslajur a 
      where a.companyid = " . $_GET['company'] . " 
      and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      ";
    $this->pdf->AddPage('P');
    $this->pdf->SetFont('Arial','B',12);
    $this->pdf->companyid = $_GET['company'];
    $per = $_GET['per'];
    $this->pdf->Cell(0, 0, 'FINANCIAL RATIO', 0, 0, 'C');
    $this->pdf->Cell(-192, 10, 'Per : ' . date("d F Y", strtotime($_GET['date'])), 0, 0, 'C');
    
    $sql1 = $akt.'and a.accactivaname = "TOTAL AKTIVA LANCAR" ';
    $aktivalancar = $connection->createCommand($sql1)->queryScalar();
    $sql3 = $akt.'and a.accactivaname = " PERSEDIAAN" ';
    $persediaan = $connection->createCommand($sql3)->queryScalar();
    $sql4 = $akt.'and a.accactivaname = " KAS" ';
    $kas = $connection->createCommand($sql4)->queryScalar();
    $sql5 = $akt.'and a.accactivaname = " BANK" ';
    $bank = $connection->createCommand($sql5)->queryScalar();
    $sql6 = $akt.'and a.accactivaname = "TOTAL AKTIVA" ';
    $aktiva = $connection->createCommand($sql6)->queryScalar();
    $sql13 = $akt.'and a.accactivaname = " PIUTANG DAGANG" ';
    $piutangdagang = $connection->createCommand($sql13)->queryScalar();
    $sql14 = $akt.'and a.accactivaname = " PIUTANG GIRO" ';
    $piutanggiro = $connection->createCommand($sql14)->queryScalar();
    $sql19 = $akt.'and a.accactivaname = " PERSEDIAAN BARANG JADI (FG)" ';
    $fg = $connection->createCommand($sql19)->queryScalar();
    $sql20 = $akt.'and a.accactivaname = " PERSEDIAAN BAHAN BAKU" ';
    $rw = $connection->createCommand($sql20)->queryScalar();
    $sql21 = $akt.'and a.accactivaname = " PERSEDIAAN WIP" ';
    $wip = $connection->createCommand($sql21)->queryScalar();
    
    
    $sql2 = $pas.'and a.accpasivaname = "TOTAL KEWAJIBAN LANCAR" ';
    $kewajibanlancar = $connection->createCommand($sql2)->queryScalar();
    $sql7 = $pas.'and a.accpasivaname = "TOTAL EKUITAS" ';
    $ekuitas = $connection->createCommand($sql7)->queryScalar();
    $sql8 = $pas.'and a.accpasivaname = "TOTAL KEWAJIBAN JANGKA PANJANG" ';
    $kewajibanjangkapanjang = $connection->createCommand($sql8)->queryScalar();
    $sql15 = $pas.'and a.accpasivaname = "HUTANG DAGANG" ';
    $hutangdagang = $connection->createCommand($sql15)->queryScalar();
    $sql16 = $pas.'and a.accpasivaname = "HUTANG GIRO" ';
    $hutanggiro = $connection->createCommand($sql16)->queryScalar();
    $sql17 = $pas.'and a.accpasivaname = "HUTANG AFILIASI" ';
    $hutangafiliasi = $connection->createCommand($sql17)->queryScalar();
    
    $sql9 = $lr.'and a.accountname = "Total PENJUALAN BERSIH BARANG JADI (FG)" ';
    $penjualanbersih = $connection->createCommand($sql9)->queryScalar();
    $sql10 = $lr.'and a.accountname = " LABA (RUGI) BERSIH" ';
    $labarugibersih = $connection->createCommand($sql10)->queryScalar();
    $sql11 = $lr.'and a.accountname = "Total LABA KOTOR BARANG JADI (FG)" ';
    $labakotor = $connection->createCommand($sql11)->queryScalar();
    $sql12 = $lr.'and a.accountname = "Total HARGA POKOK PENJUALAN" ';
    $hpp = $connection->createCommand($sql12)->queryScalar();
    
    $sql18 = "select sum(debit)
              from genledger a
              join genjournal b on b.genjournalid=a.genjournalid
              join account c on c.accountid=a.accountid and c.companyid=b.companyid
              where a.companyid = 1 
              and year(b.journaldate) = year('2017-08-31')
              and month(b.journaldate) = month('2017-08-31')
              and c.accountcode between '110501' and '1105019999999999999999'
              and b.referenceno like 'GR-%' ";
    $pembelian = $connection->createCommand($sql18)->queryScalar();
    
    
    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(3,35,'A. Liquiditas Ratio','B');
    
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(10,42,'- Current Ratio');
    $this->pdf->text(55,39,'Aktiva Lancar');
    $this->pdf->text(40,42,'= ');
    $this->pdf->text(45,40,'_______________________');
    $this->pdf->text(88,42,'x 100% ');
    $this->pdf->text(52,44,'Kewajiban Lancar');
    $this->pdf->text(50,51,Yii::app()->format->formatCurrency($aktivalancar/$per));
    $this->pdf->text(40,54,'= ');
    $this->pdf->text(45,52,'_______________________');
    $this->pdf->text(88,54,'x 100% ');
    $this->pdf->text(50,56,Yii::app()->format->formatCurrency($kewajibanlancar/$per));
    $this->pdf->text(40,62,'= ');
    $this->pdf->text(45,62,Yii::app()->format->formatCurrency(($aktivalancar/$kewajibanlancar)*100).' %');
    
    $this->pdf->text(10,72,'- Quick Ratio');
    $this->pdf->text(47,69,'Aktiva Lancar - Persediaan');
    $this->pdf->text(40,72,'= ');
    $this->pdf->text(45,70,'_______________________');
    $this->pdf->text(88,72,'x 100% ');
    $this->pdf->text(53,74,'Kewajiban Lancar');
    $this->pdf->text(40,84,'= ');
    $this->pdf->text(50,81,Yii::app()->format->formatCurrency(($aktivalancar-($fg+$wip+$rw))/$per));
    $this->pdf->text(45,82,'_______________________');
    $this->pdf->text(50,86,Yii::app()->format->formatCurrency($kewajibanlancar/$per));
    $this->pdf->text(88,84,'x 100% ');
    $this->pdf->text(40,92,'= ');
    $this->pdf->text(45,92,Yii::app()->format->formatCurrency((($aktivalancar - ($fg+$wip+$rw))/$kewajibanlancar)*100).' %');
    
    $this->pdf->text(10,102,'- Cash Ratio');
    $this->pdf->text(40,102,'= ');
    $this->pdf->text(58,99,'Kas & Bank');
    $this->pdf->text(45,100,'_______________________');
    $this->pdf->text(53,104,'Kewajiban Lancar');
    $this->pdf->text(88,102,'x 100% ');
    $this->pdf->text(40,112,'= ');
    $this->pdf->text(50,109,Yii::app()->format->formatCurrency(($kas+$bank)/$per));
    $this->pdf->text(45,110,'_______________________');
    $this->pdf->text(50,114,Yii::app()->format->formatCurrency($kewajibanlancar/$per));
    $this->pdf->text(88,112,'x 100% ');
    $this->pdf->text(40,120,'= ');
    $this->pdf->text(45,120,Yii::app()->format->formatCurrency((($kas + $bank)/$kewajibanlancar)*100).' %');
    
    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(3,135,'B. Solvabilitas Ratio','B');
    
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(13,140,'Total Assets To');
    $this->pdf->text(10,142,' - ');
    $this->pdf->text(13,145,'Debt ratio');
    $this->pdf->text(40,143,'= ');
    $this->pdf->text(55,140,'Jumlah Aktiva');
    $this->pdf->text(45,141,'_______________________');
    $this->pdf->text(52,145,'Jumlah Kewajiban');
    $this->pdf->text(88,142,'x 100% ');
    $this->pdf->text(40,155,'= ');
    $this->pdf->text(50,152,Yii::app()->format->formatCurrency($aktiva/$per));
    $this->pdf->text(45,153,'_______________________');
    $this->pdf->text(50,157,Yii::app()->format->formatCurrency(($kewajibanlancar+$kewajibanjangkapanjang)/$per));
    $this->pdf->text(88,155,'x 100% ');
    $this->pdf->text(40,163,'= ');
    $this->pdf->text(45,163,Yii::app()->format->formatCurrency(($aktiva/($kewajibanlancar+$kewajibanjangkapanjang))*100).' %');
    
    $this->pdf->text(13,173,'Capital To');
    $this->pdf->text(10,175,' - ');
    $this->pdf->text(13,178,'Total Debt ratio');
    $this->pdf->text(40,174,'= ');
    $this->pdf->text(54 ,172,'Jumlah Ekuitas');
    $this->pdf->text(45,173,'_______________________');
    $this->pdf->text(52,177,'Jumlah Kewajiban');
    $this->pdf->text(88,175,'x 100% ');
    $this->pdf->text(40,187,'= ');
    $this->pdf->text(50,185,Yii::app()->format->formatCurrency($ekuitas/$per));
    $this->pdf->text(45,186,'_______________________');
    $this->pdf->text(50,190,Yii::app()->format->formatCurrency(($kewajibanlancar+$kewajibanjangkapanjang)/$per));
    $this->pdf->text(88,188,'x 100% ');
    $this->pdf->text(40,196,'= ');
    $this->pdf->text(45,196,Yii::app()->format->formatCurrency(($ekuitas/($kewajibanlancar+$kewajibanjangkapanjang))*100).' %');  

    $this->pdf->text(13,206,'Total Debt To');
    $this->pdf->text(10,208,' - ');
    $this->pdf->text(13,211,'Total Assets Ratio');
    $this->pdf->text(40,207,'= ');
    $this->pdf->text(52,205,'Jumlah Kewajiban');
    $this->pdf->text(45,206,'_______________________');
    $this->pdf->text(55,210,'Jumlah Aktiva');
    $this->pdf->text(88,208,'x 100% ');
    $this->pdf->text(40,220,'= ');
    $this->pdf->text(50,218,Yii::app()->format->formatCurrency(($kewajibanlancar+$kewajibanjangkapanjang)/$per));
    $this->pdf->text(45,219,'_______________________');
    $this->pdf->text(50,223,Yii::app()->format->formatCurrency($aktiva/$per));
    $this->pdf->text(88,219,'x 100% ');
    $this->pdf->text(40,229,'= ');
    $this->pdf->text(45,229,Yii::app()->format->formatCurrency((($kewajibanlancar + $kewajibanjangkapanjang)/$aktiva)*100).' %');
    
    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(105,35,'C. Rentabilitas Ratio','B');
    
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(113,40,'Net Operating To');
    $this->pdf->text(166,39,'Laba Bersih');
    $this->pdf->text(110,42,'- ');
    $this->pdf->text(150,42,'= ');
    $this->pdf->text(155,40,'_____________________');
    $this->pdf->text(195,42,'x 100% ');
    $this->pdf->text(113,45,'Net Revenue Ratio');
    $this->pdf->text(166,44,'Penjualan Bersih');
    $this->pdf->text(160,51,Yii::app()->format->formatCurrency($labarugibersih/$per));
    $this->pdf->text(150,54,'= ');
    $this->pdf->text(155,52,'_____________________');
    $this->pdf->text(195,54,'x 100% ');
    $this->pdf->text(160,56,Yii::app()->format->formatCurrency($penjualanbersih/$per));
    $this->pdf->text(150,62,'= ');
    $this->pdf->text(155,62,Yii::app()->format->formatCurrency(($labarugibersih/$penjualanbersih)*100).' %'); 
    
    $this->pdf->text(113,69,'Gross Profit To');
    $this->pdf->text(166,69,'Laba Kotor');
    $this->pdf->text(110,72,'- ');
    $this->pdf->text(150,72,'= ');
    $this->pdf->text(155,70,'_____________________');
    $this->pdf->text(195,72,'x 100% ');
    $this->pdf->text(113,74,'Cost of Good Sold');
    $this->pdf->text(170,74,'HPP');
    $this->pdf->text(150,84,'= ');
    $this->pdf->text(155,82,'_____________________');
    $this->pdf->text(195,84,'x 100% ');
    $this->pdf->text(160,81,Yii::app()->format->formatCurrency($labakotor/$per));
    $this->pdf->text(160,86,Yii::app()->format->formatCurrency(-$hpp/$per));
    $this->pdf->text(150,92,'= ');
    $this->pdf->text(155,92,Yii::app()->format->formatCurrency(($labakotor/-$hpp)*100).' %');
    
    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(105,102,'D. Average Collection Period','B');
    $this->pdf->text(108,107,'(Umur Piutang Dagang)');
    
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(150,105,'= ');
    $this->pdf->text(166,102,'P/D + P/G');
    $this->pdf->text(155,103,'_____________________');
    $this->pdf->text(163,107,'Penjualan Bersih');
    $this->pdf->text(195,105,'x 30 ');
    $this->pdf->text(150,115,'= ');
    $this->pdf->text(155,113,'_____________________');
    $this->pdf->text(195,115,'x 30 ');
    $this->pdf->text(160,112,Yii::app()->format->formatCurrency(($piutangdagang+$piutanggiro)/$per));
    $this->pdf->text(160,117,Yii::app()->format->formatCurrency($penjualanbersih/$per));
    $this->pdf->text(150,123,'= ');
    $this->pdf->text(155,123,Yii::app()->format->formatCurrency((($piutangdagang+$piutanggiro)/$penjualanbersih)*30).' Hari');  

    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(105,133,'E. Average Days Inventory','B');
    $this->pdf->text(108,138,'(Umur Persediaan/Stok)'); 
    
    $this->pdf->SetFont('Arial','',9);      
    $this->pdf->text(150,136,'= ');
    $this->pdf->text(165,133,'Persediaan');
    $this->pdf->text(155,134,'_____________________');
    $this->pdf->text(169,138,'HPP');
    $this->pdf->text(195,135,'x 30 ');
    $this->pdf->text(150,148,'= ');
    $this->pdf->text(160,145,Yii::app()->format->formatCurrency(($fg+$wip+$rw)/$per));
    $this->pdf->text(155,146,'_____________________');
    $this->pdf->text(160,150,Yii::app()->format->formatCurrency(-$hpp/$per));
    $this->pdf->text(195,148,'x 30 ');
    $this->pdf->text(150,156,'= ');
    $this->pdf->text(155,156,Yii::app()->format->formatCurrency((($fg+$wip+$rw)/-$hpp)*30).' Hari');

    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(105,165,'F. Average Payment Period','B');
    $this->pdf->text(108,170,'(Umur Hutang Dagang)'); 
    
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(150,167,'= ');
    $this->pdf->text(160,165,'H/D + H/G + H/Afiliasi');
    $this->pdf->text(155,166,'_____________________');
    $this->pdf->text(163,170,'Pembelian Kredit');
    $this->pdf->text(195,168,'x 30 ');
    $this->pdf->text(150,180,'= ');
    $this->pdf->text(160,178,Yii::app()->format->formatCurrency(($hutangdagang+$hutanggiro+$hutangafiliasi)/$per));
    $this->pdf->text(155,179,'_____________________');
    $this->pdf->text(160,183,Yii::app()->format->formatCurrency($pembelian/$per));
    $this->pdf->text(195,181,'x 30 ');
    $this->pdf->text(150,189,'= ');
    $this->pdf->text(155,189,Yii::app()->format->formatCurrency((($hutangdagang+$hutanggiro+$hutangafiliasi)/$pembelian)*30).' Hari');

    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(105,198,'G. ROI / Return On Investment','B');
    $this->pdf->text(105,203,'(Hasil Pengembalian Investasi)'); 
    
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(150,200,'= ');
    $this->pdf->text(165,198,'Laba Bersih');
    $this->pdf->text(155,199,'_____________________');
    $this->pdf->text(163,203,'Jumlah Aktiva');
    $this->pdf->text(195,201,'x 100% ');
    $this->pdf->text(150,213,'= ');
    $this->pdf->text(160,211,Yii::app()->format->formatCurrency($labarugibersih/$per));
    $this->pdf->text(155,212,'_____________________');
    $this->pdf->text(160,216,Yii::app()->format->formatCurrency($aktiva/$per));
    $this->pdf->text(195,212,'x 100% ');
    $this->pdf->text(150,222,'= ');
    $this->pdf->text(155,222,Yii::app()->format->formatCurrency(($labarugibersih/$aktiva)*100).' %');

    $this->pdf->SetFont('Arial','B',9);
    $this->pdf->text(105,231,'H. ROE / Return On Equity','B');
    $this->pdf->text(105,236,'(Hasil Pengembalian Ekuitas)'); 
    
    $this->pdf->SetFont('Arial','',9);
    $this->pdf->text(150,233,'= ');
    $this->pdf->text(165,231,'Laba Bersih');
    $this->pdf->text(155,232,'_____________________');
    $this->pdf->text(167,236,'Ekuitas');
    $this->pdf->text(195,234,'x 100% ');
    $this->pdf->text(150,246,'= ');
    $this->pdf->text(160,244,Yii::app()->format->formatCurrency($labarugibersih/$per));
    $this->pdf->text(155,245,'_____________________');
    $this->pdf->text(160,249,Yii::app()->format->formatCurrency($ekuitas/$per));
    $this->pdf->text(195,245,'x 100% ');
    $this->pdf->text(150,255,'= ');
    $this->pdf->text(155,255,Yii::app()->format->formatCurrency(($labarugibersih/$ekuitas)*100).' %');
    
    $this->pdf->Output();
  }
}