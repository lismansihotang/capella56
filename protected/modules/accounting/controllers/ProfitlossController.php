<?php
class ProfitlossController extends Controller {
  public $menuname = 'profitloss';
  public function actionIndex() {
    if (isset($_GET['grid']))
      echo $this->search();
    else
      $this->renderPartial('index', array());
  }
  public function search() {
    header('Content-Type: application/json');
    $repprofitlossid = isset($_POST['repprofitlossid']) ? $_POST['repprofitlossid'] : '';
    $companyid       = isset($_POST['companyid']) ? $_POST['companyid'] : '';
    $accountcode       = isset($_POST['accountcode']) ? $_POST['accountcode'] : '';
    $isdebet         = isset($_POST['isdebet']) ? $_POST['isdebet'] : '';
    $nourut          = isset($_POST['nourut']) ? $_POST['nourut'] : '';
    $page            = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows            = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
    $sort            = isset($_POST['sort']) ? strval($_POST['sort']) : 'repprofitlossid';
    $order           = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
    $offset          = ($page - 1) * $rows;
    $result          = array();
    $row             = array();
    $cmd             = Yii::app()->db->createCommand()->select('count(1) as total')
			->from('repprofitloss t')
			->join('company a', 'a.companyid=t.companyid')
			->leftjoin('counttype b', 'b.counttypeid=t.counttypeid')
			->where('((t.repprofitlossid like :repprofitlossid) or
								(a.companyname like :companyid) or
								(t.accountcode like :accountcode) or
								(t.nourut like :nourut)) and t.companyid in ('.getUserObjectValues('company').')', array(
      ':repprofitlossid' => '%' . $repprofitlossid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':accountcode' => '%' . $accountcode . '%',
      ':nourut' => '%' . $nourut . '%'
    ))->queryScalar();
    $result['total'] = $cmd;
    $cmd             = Yii::app()->db->createCommand()->select('t.*,a.companyid,a.companyname,b.counttypename')
			->from('repprofitloss t')
			->join('company a', 'a.companyid=t.companyid')
			->leftjoin('counttype b', 'b.counttypeid=t.counttypeid')
			->where('((t.repprofitlossid like :repprofitlossid) or
								(a.companyname like :companyid) or
								(t.accountcode like :accountcode) or
								(t.nourut like :nourut)) and t.companyid in ('.getUserObjectValues('company').')', array(
      ':repprofitlossid' => '%' . $repprofitlossid . '%',
      ':companyid' => '%' . $companyid . '%',
      ':accountcode' => '%' . $accountcode . '%',
      ':nourut' => '%' . $nourut . '%'
    ))->offset($offset)->limit($rows)->order($sort . ' ' . $order)->queryAll();
    foreach ($cmd as $data) {
      $row[] = array(
        'repprofitlossid' => $data['repprofitlossid'],
        'companyid' => $data['companyid'],
        'companyname' => $data['companyname'],
        'accountcode' => $data['accountcode'],
        'plantcode' => $data['plantcode'],
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
        $sql     = 'call Insertprofitloss(:vcompanyid,:vnourut,:vaccountcode,:vplantcode,:viscount,:vcounttype,:vsourcemenu,:visbold,:vketerangan,:vcreatedby)';
        $command = $connection->createCommand($sql);
      } else {
        $sql     = 'call Updateprofitloss(:vid,:vcompanyid,:vnourut,:vaccountcode,:vplantcode,:viscount,:vcounttype,:vsourcemenu,:visbold,:vketerangan,:vcreatedby)';
        $command = $connection->createCommand($sql);
        $command->bindvalue(':vid', $arraydata[0], PDO::PARAM_STR);
        $this->DeleteLock($this->menuname, $arraydata[0]);
      }
      $command->bindvalue(':vcompanyid', $arraydata[1], PDO::PARAM_STR);
      $command->bindvalue(':vnourut', $arraydata[2], PDO::PARAM_STR);
      $command->bindvalue(':vaccountcode', $arraydata[3], PDO::PARAM_STR);
      $command->bindvalue(':vplantcode', $arraydata[4], PDO::PARAM_STR);
      $command->bindvalue(':viscount', $arraydata[5], PDO::PARAM_STR);
      $command->bindvalue(':vcounttype', $arraydata[6], PDO::PARAM_STR);
      $command->bindvalue(':vsourcemenu', $arraydata[7], PDO::PARAM_STR);
      $command->bindvalue(':visbold', $arraydata[8], PDO::PARAM_STR);
      $command->bindvalue(':vketerangan', $arraydata[9], PDO::PARAM_STR);
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
			$_POST['accountcode'],$_POST['plantcode'],$_POST['iscount'],$_POST['counttypeid'],$_POST['sourcemenu'],$_POST['isbold'],$_POST['keterangan']));
  }
  public function actionPurge() {
    header('Content-Type: application/json');
    if (isset($_POST['id'])) {
      $id          = $_POST['id'];
      $connection  = Yii::app()->db;
      $transaction = $connection->beginTransaction();
      try {
        $sql     = 'call Purgeprofitloss(:vid,:vcreatedby)';
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
  public function actionGenerateplsebelumpajak() {
    parent::actionDownload();
		$companyid = $_POST['companyid'];
		$tgl = date(Yii::app()->params['datetodb'], strtotime($_POST['bsdate']));
		$sql = "delete from repprofitlosslajur where companyid = ".$companyid."  
      and tahun = year('".$tgl."')
      and bulan = month('".$tgl."')";
		Yii::app()->db->createCommand($sql)->execute();
		$sql = "select * from repprofitloss where companyid = " . $companyid. " order by nourut";
		$datareader = Yii::app()->db->createCommand($sql)->queryAll();
		$plants;$accounts;
		foreach($datareader as $data) {
			if ($data['keterangan'] == 'TAKSIRAN PAJAK PENGHASILAN') {
				break;
			} else {
				$plants = explode(';',$data['plantcode']);
				$accounts = explode(';',$data['accountcode']);
				$i = 0;
				foreach($plants as $plant) {
					if ($data['iscount'] == 0) {
						$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
							values (".$companyid.",month('".$tgl."'),
								year('".$tgl."'),
								".$data['nourut'].",'".$data['keterangan']."','".$plant."',0,null,".$data['iscount'].",".$data['isbold'].")";
						Yii::app()->db->createCommand($sql1)->execute();
					} else {
						if ($data['counttypeid'] == 5) {
							$accvalue = 0;
							$nouruts = explode('+',$accounts[$i]);
							foreach ($nouruts as $nourut) {
								$sql3 = "select ifnull(sum(ifnull(plantvalue,0)),0) as totalplant 
									from repprofitlosslajur 
									where companyid = ".$companyid."  
									and tahun = year('".$tgl."')
									and bulan = month('".$tgl."')
									and nourut = ".$nourut;
								$retvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
								if ($retvalue == null) {
									$retvalue = 0;
								}
								$accvalue = $accvalue + $retvalue; 
							}
							$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
								values (".$companyid.",month('".$tgl."'),
									year('".$tgl."'),
									".$data['nourut'].",'".$data['keterangan']."','".$plant."',".$accvalue.",null,".$data['iscount'].",".$data['isbold'].")";
							Yii::app()->db->createCommand($sql1)->execute();
						} else 
						if ($data['counttypeid'] == 4) {
							$accvalue = '';
							$nouruts = explode('-',$accounts[$i]);
							foreach ($nouruts as $nourut) {
								$sql3 = "select ifnull(sum(ifnull(plantvalue,0)),0) as totalplant 
									from repprofitlosslajur 
									where companyid = ".$companyid."  
									and tahun = year('".$tgl."')
									and bulan = month('".$tgl."')
									and plantcode = '".$plant."' 
									and nourut = ".$nourut;
								$retvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
								if ($retvalue == null) {
									$retvalue = 0;
								}
								if ($accvalue == '') {
									$accvalue = $retvalue;
								} else {
								$accvalue = $accvalue - $retvalue;
								}
							}
							$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
								values (".$companyid.",month('".$tgl."'),
									year('".$tgl."'),
									".$data['nourut'].",'".$data['keterangan']."','".$plant."',".$accvalue.",null,".$data['iscount'].",".$data['isbold'].")";
							Yii::app()->db->createCommand($sql1)->execute();
						} else 
						if ($data['counttypeid'] == 3) {
							if ($accounts[$i] != '') {
								$sql3 = "select plantvalue  
									from ".$data['sourcemenu']." 
									where companyid = ".$companyid."  
										and tahun = year('".$tgl."')
										and bulan = month('".$tgl."')
										and plantcode = '".$plant."' 
										and nourut = ".$accounts[$i];
								$accvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
								if ($accvalue == null) {
									$accvalue = 0;
								}
								if ($accvalue < 0) {
									$accvalue = $accvalue * -1;
								}
							} else {
								$accvalue = 0;
							}
							$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
								values (".$companyid.",month('".$tgl."'),
									year('".$tgl."'),
									".$data['nourut'].",'".$data['keterangan']."','".$plant."',".$accvalue.",null,".$data['iscount'].",".$data['isbold'].")";
							Yii::app()->db->createCommand($sql1)->execute();
						} else 
						if ($data['counttypeid'] == 2) {
							$accvalue = 0;
							$nouruts = explode('+',$accounts[$i]);
							foreach ($nouruts as $nourut) {
								if ($nourut != '') {
									$sql3 = "select ifnull(sum(ifnull(plantvalue,0)),0) as totalplant 
										from repprofitlosslajur 
										where companyid = ".$companyid."  
										and tahun = year('".$tgl."')
										and bulan = month('".$tgl."')
										and plantcode = '".$plant."' 
										and nourut = ".$nourut;
									$retvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
									if ($retvalue == null) {
										$retvalue = 0;
									}
									$accvalue = $accvalue + $retvalue;
								} 
							}
							$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
								values (".$companyid.",month('".$tgl."'),
									year('".$tgl."'),
									".$data['nourut'].",'".$data['keterangan']."','".$plant."',".$accvalue.",null,".$data['iscount'].",".$data['isbold'].")";
							Yii::app()->db->createCommand($sql1)->execute();
						} else {
							if (isset($accounts[$i])) {
								$accountx = $accounts[$i];
								$accountd = explode('+',$accountx);
								$accvalue = 0;
								foreach($accountd as $accounte) {
									if ($data['iscount'] == 1) {
										if ($data['counttypeid'] == 0) {
											$sql3 = "select case when b.isdebit = 1 then ifnull(sum(ifnull(debit,0))-sum(ifnull(credit,0)),0) else ifnull(sum(ifnull(credit,0))-sum(ifnull(debit,0)),0) end  as total 
												from genledger a  
												join account b on b.accountid = a.accountid 
												where a.journaldate < concat(year('".$tgl."'),'-01-01')
												and a.accountcode = '".$accounte."'";
											$accvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
											if ($accvalue == null) {
												$accvalue = 0;
											}
										} else 
										if ($data['counttypeid'] == 1) {
											$sql3 = "select case when b.isdebit = 1 then ifnull(sum(ifnull(debit,0))-sum(ifnull(credit,0)),0) else ifnull(sum(ifnull(credit,0))-sum(ifnull(debit,0)),0) end  as total 
												from genledger a 
												join account b on b.accountid = a.accountid 
												where month(a.journaldate) <= month('".$tgl."')
												and year(a.journaldate) = year('".$tgl."')
												and a.accountcode = '".$accounte."'";
											$accvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
											if ($accvalue == null) {
												$accvalue = 0;
											}
										} 
									}
									$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
										values (".$companyid.",month('".$tgl."'),
											year('".$tgl."'),
											".$data['nourut'].",'".$data['keterangan']."','".$plant."',".$accvalue.",'".$accounte."',".$data['iscount'].",".$data['isbold'].")";
									Yii::app()->db->createCommand($sql1)->execute();
								} 
							} else {
								$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
										values (".$companyid.",month('".$tgl."'),
											year('".$tgl."'),
											".$data['nourut'].",'".$data['keterangan']."','".$plant."',0,'".$accounte."',".$data['iscount'].",".$data['isbold'].")";
									Yii::app()->db->createCommand($sql1)->execute();
							} 
						}
					}
					$i++;
				}
			}
		}
		GetMessage('success', 'Data Generated');
  }
  public function actionGeneratepl() {
    parent::actionDownload();
		$companyid = $_POST['companyid'];
		$tgl = date(Yii::app()->params['datetodb'], strtotime($_POST['bsdate']));
		$sql = "delete from repprofitlosslajur where companyid = ".$companyid."  
      and tahun = year('".$tgl."')
      and bulan = month('".$tgl."')";
		Yii::app()->db->createCommand($sql)->execute();
		$sql = "select * from repprofitloss where companyid = " . $companyid. " order by nourut";
		$datareader = Yii::app()->db->createCommand($sql)->queryAll();
		$plants;$accounts;
		foreach($datareader as $data) {
				$plants = explode(';',$data['plantcode']);
				$accounts = explode(';',$data['accountcode']);
				$i = 0;
				foreach($plants as $plant) {
					if ($data['iscount'] == 0) {
						$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
							values (".$companyid.",month('".$tgl."'),
								year('".$tgl."'),
								".$data['nourut'].",'".$data['keterangan']."','".$plant."',0,null,".$data['iscount'].",".$data['isbold'].")";
						Yii::app()->db->createCommand($sql1)->execute();
					} else {
						if ($data['counttypeid'] == 5) {
							$accvalue = 0;
							$nouruts = explode('+',$accounts[$i]);
							foreach ($nouruts as $nourut) {
								$sql3 = "select ifnull(sum(ifnull(plantvalue,0)),0) as totalplant 
									from repprofitlosslajur 
									where companyid = ".$companyid."  
									and tahun = year('".$tgl."')
									and bulan = month('".$tgl."')
									and nourut = ".$nourut;
								$retvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
								if ($retvalue == null) {
									$retvalue = 0;
								}
								$accvalue = $accvalue + $retvalue; 
							}
							$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
								values (".$companyid.",month('".$tgl."'),
									year('".$tgl."'),
									".$data['nourut'].",'".$data['keterangan']."','".$plant."',".$accvalue.",null,".$data['iscount'].",".$data['isbold'].")";
							Yii::app()->db->createCommand($sql1)->execute();
						} else 
						if ($data['counttypeid'] == 4) {
							$accvalue = '';
							$nouruts = explode('-',$accounts[$i]);
							foreach ($nouruts as $nourut) {
								$sql3 = "select ifnull(sum(ifnull(plantvalue,0)),0) as totalplant 
									from repprofitlosslajur 
									where companyid = ".$companyid."  
									and tahun = year('".$tgl."')
									and bulan = month('".$tgl."')
									and plantcode = '".$plant."' 
									and nourut = ".$nourut;
								$retvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
								if ($retvalue == null) {
									$retvalue = 0;
								}
								if ($accvalue == '') {
									$accvalue = $retvalue;
								} else {
								$accvalue = $accvalue - $retvalue;
								}
							}
							$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
								values (".$companyid.",month('".$tgl."'),
									year('".$tgl."'),
									".$data['nourut'].",'".$data['keterangan']."','".$plant."',".$accvalue.",null,".$data['iscount'].",".$data['isbold'].")";
							Yii::app()->db->createCommand($sql1)->execute();
						} else 
						if ($data['counttypeid'] == 3) {
							if ($accounts[$i] != '') {
								try {
									$sql3 = "select plantvalue  
										from ".$data['sourcemenu']." 
										where companyid = ".$companyid."  
											and tahun = year('".$tgl."')
											and bulan = month('".$tgl."')
											and plantcode = '".$plant."' 
											and nourut = ".$accounts[$i];
									$accvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
								} catch (Exception $ex) {
									$sql3 = "select plantvalue  
										from ".$data['sourcemenu']." 
										where companyid = ".$companyid."  
											and tahun = year('".$tgl."')
											and bulan = month('".$tgl."')
											and nourut = ".$accounts[$i];
									$accvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
								}
								if ($accvalue == null) {
									$accvalue = 0;
								}
								if ($accvalue < 0) {
									$accvalue = $accvalue * -1;
								}
							} else {
								$accvalue = 0;
							}
							$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
								values (".$companyid.",month('".$tgl."'),
									year('".$tgl."'),
									".$data['nourut'].",'".$data['keterangan']."','".$plant."',".$accvalue.",null,".$data['iscount'].",".$data['isbold'].")";
							Yii::app()->db->createCommand($sql1)->execute();
						} else 
						if ($data['counttypeid'] == 7) {
							$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
								values (".$companyid.",month('".$tgl."'),
									year('".$tgl."'),
									".$data['nourut'].",'".$data['keterangan']."','".$plant."',".$data['accountcode'].",null,".$data['iscount'].",".$data['isbold'].")";
							Yii::app()->db->createCommand($sql1)->execute();
						} else
						if ($data['counttypeid'] == 2) {
							$accvalue = 0;
							$nouruts = explode('+',$accounts[$i]);
							foreach ($nouruts as $nourut) {
								if ($nourut != '') {
									$sql3 = "select ifnull(sum(ifnull(plantvalue,0)),0) as totalplant 
										from repprofitlosslajur 
										where companyid = ".$companyid."  
										and tahun = year('".$tgl."')
										and bulan = month('".$tgl."')
										and plantcode = '".$plant."' 
										and nourut = ".$nourut;
									$retvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
									if ($retvalue == null) {
										$retvalue = 0;
									}
									$accvalue = $accvalue + $retvalue;
								} 
							}
							$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
								values (".$companyid.",month('".$tgl."'),
									year('".$tgl."'),
									".$data['nourut'].",'".$data['keterangan']."','".$plant."',".$accvalue.",null,".$data['iscount'].",".$data['isbold'].")";
							Yii::app()->db->createCommand($sql1)->execute();
						} else {
							if (isset($accounts[$i])) {
								$accountx = $accounts[$i];
								$accountd = explode('+',$accountx);
								$accvalue = 0;
								foreach($accountd as $accounte) {
									if ($data['iscount'] == 1) {
										if ($data['counttypeid'] == 0) {
											$sql3 = "select case when b.isdebit = 1 then ifnull(sum(ifnull(debit,0))-sum(ifnull(credit,0)),0) else ifnull(sum(ifnull(credit,0))-sum(ifnull(debit,0)),0) end  as total 
												from genledger a  
												join account b on b.accountid = a.accountid 
												where a.journaldate < concat(year('".$tgl."'),'-01-01')
												and a.accountcode = '".$accounte."'";
											$accvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
											if ($accvalue == null) {
												$accvalue = 0;
											}
										} else 
										if ($data['counttypeid'] == 1) {
											$sql3 = "select case when b.isdebit = 1 then ifnull(sum(ifnull(debit,0))-sum(ifnull(credit,0)),0) else ifnull(sum(ifnull(credit,0))-sum(ifnull(debit,0)),0) end  as total 
												from genledger a 
												join account b on b.accountid = a.accountid 
												where month(a.journaldate) <= month('".$tgl."')
												and year(a.journaldate) = year('".$tgl."')
												and a.accountcode = '".$accounte."'";
											$accvalue = Yii::app()->db->createCommand($sql3)->queryScalar();
											if ($accvalue == null) {
												$accvalue = 0;
											}
										} 
									}
									$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
										values (".$companyid.",month('".$tgl."'),
											year('".$tgl."'),
											".$data['nourut'].",'".$data['keterangan']."','".$plant."',".$accvalue.",'".$accounte."',".$data['iscount'].",".$data['isbold'].")";
									Yii::app()->db->createCommand($sql1)->execute();
								} 
							} else {
								$sql1 = "insert into repprofitlosslajur (companyid,bulan,tahun,nourut,keterangan,plantcode,plantvalue,accountcode,iscount,isbold) 
										values (".$companyid.",month('".$tgl."'),
											year('".$tgl."'),
											".$data['nourut'].",'".$data['keterangan']."','".$plant."',0,'".$accounte."',".$data['iscount'].",".$data['isbold'].")";
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
      from repprofitlosslajur a 
      where a.companyid = " . $_GET['company'] . " 
      and a.tahun = year('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      and a.bulan = month('" . date(Yii::app()->params['datetodb'], strtotime($_GET['date'])) . "')
      order by cast(nourut as int)";
    $datareader = $this->connection->createCommand($sql)->queryAll();
    $this->pdf->AddPage('L','A4');
    $this->pdf->companyid = $_GET['company'];
    $this->pdf->Cell(0, 0, GetCatalog('profitloss'), 0, 0, 'C');
    $this->pdf->Cell(-277, 10, 'Per : ' . date("d F Y", strtotime($_GET['date'])), 0, 0, 'C');
    $i = 0;
		
		foreach ($datareader as $data) {
			$sql1 = "select distinct plantcode from repprofitloss where companyid = ".$_GET['company']." limit 1";
			$datareader1 = $this->connection->createCommand($sql1)->queryScalar();
			$plants = explode(';',$datareader1);
			$colalign = array('C','C');
			$header = array('Keterangan');
			$colwidth = array(120);
			$coldetailalign = array('L');
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
			$sql1 = "select distinct plantcode from repprofitloss where companyid = ".$_GET['company']." and nourut = ".$data['nourut'];
			$datareader1 = $this->connection->createCommand($sql1)->queryScalar();
			$plants = explode(';',$datareader1);
			$total = 0;
			$datadetail = array($data['keterangan']);
			if ($plants[0] == 'TAHO') {
				array_push($datadetail,'');
				array_push($datadetail,'');
			}
			foreach ($plants as $plant) {
				$sql2 = "
					select sum(ifnull(plantvalue,0)) as plantvalue
					from repprofitlosslajur a 
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
				if ($data['iscount'] != 0) {
					$total = $total + $value;
					array_push($datadetail,Yii::app()->format->formatNumberWODecimal($value));
				}
			}
			if ($data['iscount'] != 0) {
				array_push($datadetail,Yii::app()->format->formatNumberWODecimal($total));
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