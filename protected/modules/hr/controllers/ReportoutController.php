<?php
class ReportoutController extends Controller
{
	public $menuname = 'reportout';
	public function actionIndex()
	{
		if(isset($_GET['grid']))
			echo $this->search();
		else
			$this->renderPartial('index',array());
	}
	
	public function search()
	{
		header('Content-Type: application/json');
		
		// search 
		$reportoutid = isset ($_POST['reportoutid']) ? $_POST['reportoutid'] : '';
$employeeid = isset ($_POST['employeeid']) ? $_POST['employeeid'] : '';
$fullname = isset ($_POST['fullname']) ? $_POST['fullname'] : '';
$fulldivision = isset ($_POST['fulldivision']) ? $_POST['fulldivision'] : '';
$month = isset ($_POST['month']) ? $_POST['month'] : '';
$year = isset ($_POST['year']) ? $_POST['year'] : '';
$s1 = isset ($_POST['s1']) ? $_POST['s1'] : '';
$d1 = isset ($_POST['d1']) ? $_POST['d1'] : '';
$s2 = isset ($_POST['s2']) ? $_POST['s2'] : '';
$d2 = isset ($_POST['d2']) ? $_POST['d2'] : '';
$s3 = isset ($_POST['s3']) ? $_POST['s3'] : '';
$d3 = isset ($_POST['d3']) ? $_POST['d3'] : '';
$s4 = isset ($_POST['s4']) ? $_POST['s4'] : '';
$d4 = isset ($_POST['d4']) ? $_POST['d4'] : '';
$s5 = isset ($_POST['s5']) ? $_POST['s5'] : '';
$d5 = isset ($_POST['d5']) ? $_POST['d5'] : '';
$s6 = isset ($_POST['s6']) ? $_POST['s6'] : '';
$d6 = isset ($_POST['d6']) ? $_POST['d6'] : '';
$s7 = isset ($_POST['s7']) ? $_POST['s7'] : '';
$d7 = isset ($_POST['d7']) ? $_POST['d7'] : '';
$s8 = isset ($_POST['s8']) ? $_POST['s8'] : '';
$d8 = isset ($_POST['d8']) ? $_POST['d8'] : '';
$s9 = isset ($_POST['s9']) ? $_POST['s9'] : '';
$d9 = isset ($_POST['d9']) ? $_POST['d9'] : '';
$s10 = isset ($_POST['s10']) ? $_POST['s10'] : '';
$d10 = isset ($_POST['d10']) ? $_POST['d10'] : '';
$s11 = isset ($_POST['s11']) ? $_POST['s11'] : '';
$d11 = isset ($_POST['d11']) ? $_POST['d11'] : '';
$s12 = isset ($_POST['s12']) ? $_POST['s12'] : '';
$d12 = isset ($_POST['d12']) ? $_POST['d12'] : '';
$s13 = isset ($_POST['s13']) ? $_POST['s13'] : '';
$d13 = isset ($_POST['d13']) ? $_POST['d13'] : '';
$s14 = isset ($_POST['s14']) ? $_POST['s14'] : '';
$d14 = isset ($_POST['d14']) ? $_POST['d14'] : '';
$s15 = isset ($_POST['s15']) ? $_POST['s15'] : '';
$d15 = isset ($_POST['d15']) ? $_POST['d15'] : '';
$s16 = isset ($_POST['s16']) ? $_POST['s16'] : '';
$d16 = isset ($_POST['d16']) ? $_POST['d16'] : '';
$s17 = isset ($_POST['s17']) ? $_POST['s17'] : '';
$d17 = isset ($_POST['d17']) ? $_POST['d17'] : '';
$s18 = isset ($_POST['s18']) ? $_POST['s18'] : '';
$d18 = isset ($_POST['d18']) ? $_POST['d18'] : '';
$s19 = isset ($_POST['s19']) ? $_POST['s19'] : '';
$d19 = isset ($_POST['d19']) ? $_POST['d19'] : '';
$s20 = isset ($_POST['s20']) ? $_POST['s20'] : '';
$d20 = isset ($_POST['d20']) ? $_POST['d20'] : '';
$s21 = isset ($_POST['s21']) ? $_POST['s21'] : '';
$d21 = isset ($_POST['d21']) ? $_POST['d21'] : '';
$s22 = isset ($_POST['s22']) ? $_POST['s22'] : '';
$d22 = isset ($_POST['d22']) ? $_POST['d22'] : '';
$s23 = isset ($_POST['s23']) ? $_POST['s23'] : '';
$d23 = isset ($_POST['d23']) ? $_POST['d23'] : '';
$s24 = isset ($_POST['s24']) ? $_POST['s24'] : '';
$d24 = isset ($_POST['d24']) ? $_POST['d24'] : '';
$s25 = isset ($_POST['s25']) ? $_POST['s25'] : '';
$d25 = isset ($_POST['d25']) ? $_POST['d25'] : '';
$s26 = isset ($_POST['s26']) ? $_POST['s26'] : '';
$d26 = isset ($_POST['d26']) ? $_POST['d26'] : '';
$s27 = isset ($_POST['s27']) ? $_POST['s27'] : '';
$d27 = isset ($_POST['d27']) ? $_POST['d27'] : '';
$s28 = isset ($_POST['s28']) ? $_POST['s28'] : '';
$d28 = isset ($_POST['d28']) ? $_POST['d28'] : '';
$s29 = isset ($_POST['s29']) ? $_POST['s29'] : '';
$d29 = isset ($_POST['d29']) ? $_POST['d29'] : '';
$s30 = isset ($_POST['s30']) ? $_POST['s30'] : '';
$d30 = isset ($_POST['d30']) ? $_POST['d30'] : '';
$s31 = isset ($_POST['s31']) ? $_POST['s31'] : '';
$d31 = isset ($_POST['d31']) ? $_POST['d31'] : '';
$oldnik = isset ($_POST['oldnik']) ? $_POST['oldnik'] : '';
		$reportoutid = isset ($_GET['q']) ? $_GET['q'] : $reportoutid;
$employeeid = isset ($_GET['q']) ? $_GET['q'] : $employeeid;
$fullname = isset ($_GET['q']) ? $_GET['q'] : $fullname;
$fulldivision = isset ($_GET['q']) ? $_GET['q'] : $fulldivision;
$month = isset ($_GET['q']) ? $_GET['q'] : $month;
$year = isset ($_GET['q']) ? $_GET['q'] : $year;
$s1 = isset ($_GET['q']) ? $_GET['q'] : $s1;
$d1 = isset ($_GET['q']) ? $_GET['q'] : $d1;
$s2 = isset ($_GET['q']) ? $_GET['q'] : $s2;
$d2 = isset ($_GET['q']) ? $_GET['q'] : $d2;
$s3 = isset ($_GET['q']) ? $_GET['q'] : $s3;
$d3 = isset ($_GET['q']) ? $_GET['q'] : $d3;
$s4 = isset ($_GET['q']) ? $_GET['q'] : $s4;
$d4 = isset ($_GET['q']) ? $_GET['q'] : $d4;
$s5 = isset ($_GET['q']) ? $_GET['q'] : $s5;
$d5 = isset ($_GET['q']) ? $_GET['q'] : $d5;
$s6 = isset ($_GET['q']) ? $_GET['q'] : $s6;
$d6 = isset ($_GET['q']) ? $_GET['q'] : $d6;
$s7 = isset ($_GET['q']) ? $_GET['q'] : $s7;
$d7 = isset ($_GET['q']) ? $_GET['q'] : $d7;
$s8 = isset ($_GET['q']) ? $_GET['q'] : $s8;
$d8 = isset ($_GET['q']) ? $_GET['q'] : $d8;
$s9 = isset ($_GET['q']) ? $_GET['q'] : $s9;
$d9 = isset ($_GET['q']) ? $_GET['q'] : $d9;
$s10 = isset ($_GET['q']) ? $_GET['q'] : $s10;
$d10 = isset ($_GET['q']) ? $_GET['q'] : $d10;
$s11 = isset ($_GET['q']) ? $_GET['q'] : $s11;
$d11 = isset ($_GET['q']) ? $_GET['q'] : $d11;
$s12 = isset ($_GET['q']) ? $_GET['q'] : $s12;
$d12 = isset ($_GET['q']) ? $_GET['q'] : $d12;
$s13 = isset ($_GET['q']) ? $_GET['q'] : $s13;
$d13 = isset ($_GET['q']) ? $_GET['q'] : $d13;
$s14 = isset ($_GET['q']) ? $_GET['q'] : $s14;
$d14 = isset ($_GET['q']) ? $_GET['q'] : $d14;
$s15 = isset ($_GET['q']) ? $_GET['q'] : $s15;
$d15 = isset ($_GET['q']) ? $_GET['q'] : $d15;
$s16 = isset ($_GET['q']) ? $_GET['q'] : $s16;
$d16 = isset ($_GET['q']) ? $_GET['q'] : $d16;
$s17 = isset ($_GET['q']) ? $_GET['q'] : $s17;
$d17 = isset ($_GET['q']) ? $_GET['q'] : $d17;
$s18 = isset ($_GET['q']) ? $_GET['q'] : $s18;
$d18 = isset ($_GET['q']) ? $_GET['q'] : $d18;
$s19 = isset ($_GET['q']) ? $_GET['q'] : $s19;
$d19 = isset ($_GET['q']) ? $_GET['q'] : $d19;
$s20 = isset ($_GET['q']) ? $_GET['q'] : $s20;
$d20 = isset ($_GET['q']) ? $_GET['q'] : $d20;
$s21 = isset ($_GET['q']) ? $_GET['q'] : $s21;
$d21 = isset ($_GET['q']) ? $_GET['q'] : $d21;
$s22 = isset ($_GET['q']) ? $_GET['q'] : $s22;
$d22 = isset ($_GET['q']) ? $_GET['q'] : $d22;
$s23 = isset ($_GET['q']) ? $_GET['q'] : $s23;
$d23 = isset ($_GET['q']) ? $_GET['q'] : $d23;
$s24 = isset ($_GET['q']) ? $_GET['q'] : $s24;
$d24 = isset ($_GET['q']) ? $_GET['q'] : $d24;
$s25 = isset ($_GET['q']) ? $_GET['q'] : $s25;
$d25 = isset ($_GET['q']) ? $_GET['q'] : $d25;
$s26 = isset ($_GET['q']) ? $_GET['q'] : $s26;
$d26 = isset ($_GET['q']) ? $_GET['q'] : $d26;
$s27 = isset ($_GET['q']) ? $_GET['q'] : $s27;
$d27 = isset ($_GET['q']) ? $_GET['q'] : $d27;
$s28 = isset ($_GET['q']) ? $_GET['q'] : $s28;
$d28 = isset ($_GET['q']) ? $_GET['q'] : $d28;
$s29 = isset ($_GET['q']) ? $_GET['q'] : $s29;
$d29 = isset ($_GET['q']) ? $_GET['q'] : $d29;
$s30 = isset ($_GET['q']) ? $_GET['q'] : $s30;
$d30 = isset ($_GET['q']) ? $_GET['q'] : $d30;
$s31 = isset ($_GET['q']) ? $_GET['q'] : $s31;
$d31 = isset ($_GET['q']) ? $_GET['q'] : $d31;
$oldnik = isset ($_GET['q']) ? $_GET['q'] : $oldnik;
		
		// pagging
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'reportoutid';
		$order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
		$offset = ($page-1) * $rows;
		
		$page = isset($_GET['page']) ? intval($_GET['page']) : $page;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : $rows;
		$sort = isset($_GET['sort']) ? strval($_GET['sort']) : (strpos($sort,'t.')>0)?$sort:'t.'.$sort;
		$order = isset($_GET['order']) ? strval($_GET['order']) : $order ;
		$offset = ($page-1) * $rows;
		
		$result = array();
		$row = array();
	
		// result
		/*
            $criteria = new CDbCriteria;
		$criteria->compare('t.reportoutid',$reportoutid,true,'or');
$criteria->compare('t.employeeid',$employeeid,true,'or');
$criteria->compare('t.fullname',$fullname,true,'or');
$criteria->compare('t.fulldivision',$fulldivision,true,'or');
$criteria->compare('t.month',$month,true,'or');
$criteria->compare('t.year',$year,true,'or');
$criteria->compare('t.s1',$s1,true,'or');
$criteria->compare('t.d1',$d1,true,'or');
$criteria->compare('t.s2',$s2,true,'or');
$criteria->compare('t.d2',$d2,true,'or');
$criteria->compare('t.s3',$s3,true,'or');
$criteria->compare('t.d3',$d3,true,'or');
$criteria->compare('t.s4',$s4,true,'or');
$criteria->compare('t.d4',$d4,true,'or');
$criteria->compare('t.s5',$s5,true,'or');
$criteria->compare('t.d5',$d5,true,'or');
$criteria->compare('t.s6',$s6,true,'or');
$criteria->compare('t.d6',$d6,true,'or');
$criteria->compare('t.s7',$s7,true,'or');
$criteria->compare('t.d7',$d7,true,'or');
$criteria->compare('t.s8',$s8,true,'or');
$criteria->compare('t.d8',$d8,true,'or');
$criteria->compare('t.s9',$s9,true,'or');
$criteria->compare('t.d9',$d9,true,'or');
$criteria->compare('t.s10',$s10,true,'or');
$criteria->compare('t.d10',$d10,true,'or');
$criteria->compare('t.s11',$s11,true,'or');
$criteria->compare('t.d11',$d11,true,'or');
$criteria->compare('t.s12',$s12,true,'or');
$criteria->compare('t.d12',$d12,true,'or');
$criteria->compare('t.s13',$s13,true,'or');
$criteria->compare('t.d13',$d13,true,'or');
$criteria->compare('t.s14',$s14,true,'or');
$criteria->compare('t.d14',$d14,true,'or');
$criteria->compare('t.s15',$s15,true,'or');
$criteria->compare('t.d15',$d15,true,'or');
$criteria->compare('t.s16',$s16,true,'or');
$criteria->compare('t.d16',$d16,true,'or');
$criteria->compare('t.s17',$s17,true,'or');
$criteria->compare('t.d17',$d17,true,'or');
$criteria->compare('t.s18',$s18,true,'or');
$criteria->compare('t.d18',$d18,true,'or');
$criteria->compare('t.s19',$s19,true,'or');
$criteria->compare('t.d19',$d19,true,'or');
$criteria->compare('t.s20',$s20,true,'or');
$criteria->compare('t.d20',$d20,true,'or');
$criteria->compare('t.s21',$s21,true,'or');
$criteria->compare('t.d21',$d21,true,'or');
$criteria->compare('t.s22',$s22,true,'or');
$criteria->compare('t.d22',$d22,true,'or');
$criteria->compare('t.s23',$s23,true,'or');
$criteria->compare('t.d23',$d23,true,'or');
$criteria->compare('t.s24',$s24,true,'or');
$criteria->compare('t.d24',$d24,true,'or');
$criteria->compare('t.s25',$s25,true,'or');
$criteria->compare('t.d25',$d25,true,'or');
$criteria->compare('t.s26',$s26,true,'or');
$criteria->compare('t.d26',$d26,true,'or');
$criteria->compare('t.s27',$s27,true,'or');
$criteria->compare('t.d27',$d27,true,'or');
$criteria->compare('t.s28',$s28,true,'or');
$criteria->compare('t.d28',$d28,true,'or');
$criteria->compare('t.s29',$s29,true,'or');
$criteria->compare('t.d29',$d29,true,'or');
$criteria->compare('t.s30',$s30,true,'or');
$criteria->compare('t.d30',$d30,true,'or');
$criteria->compare('t.s31',$s31,true,'or');
$criteria->compare('t.d31',$d31,true,'or');
$criteria->compare('t.oldnik',$oldnik,true,'or');
	
		$result['total'] = count(Reportout::model()->findAll($criteria));
		
		$criteria->offset=$offset;
		$criteria->limit=$rows;
		$criteria->order=$sort.' '.$order;
		*/
        if (isset($_GET['combo']))
        {
			$cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('reportout t')
				->where('((t.fullname like :fullname) or (t.fulldivision like :fulldivision) or (t.month like :month) or (t.year like :year)) ',
					array(':fullname'=>'%'.$fullname.'%',':fulldivision'=>'%'.$fulldivision.'%',
							':month'=>'%'.$month.'%',':year'=>'%'.$year.'%'))
				->queryScalar();
		}
        else
        {
            $cmd = Yii::app()->db->createCommand()
				->select('count(1) as total')	
				->from('reportout t')
				->where("(coalesce(t.fullname,'') like :fullname) and (coalesce(t.fulldivision,'') like :fulldivision) and (coalesce(t.month,'') like :month) and (coalesce(t.year,'') like :year)",
					array(':fullname'=>'%'.$fullname.'%',':fulldivision'=>'%'.$fulldivision.'%',
							':month'=>'%'.$month.'%',':year'=>'%'.$year.'%'))
				->queryScalar();
        }
        $result['total'] = $cmd;
        if (isset($_GET['combo']))
        {
			$cmd = Yii::app()->db->createCommand()
				->select('t.*')	
				->from('reportout t')
				->where('((t.fullname like :fullname) or (t.fulldivision like :fulldivision) or (t.month like :month) or (t.year like :year)) ',
					array(':fullname'=>'%'.$fullname.'%',':fulldivision'=>'%'.$fulldivision.'%',
							':month'=>'%'.$month.'%',':year'=>'%'.$year.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
		}
        else
        {
            $cmd = Yii::app()->db->createCommand()
				->select("t.*")	
				->from('reportout t')
				->where("(coalesce(t.fullname,'') like :fullname) and (coalesce(t.fulldivision,'') like :fulldivision) and (coalesce(t.month,'') like :month) and (coalesce(t.year,'') like :year)",
					array(':fullname'=>'%'.$fullname.'%',':fulldivision'=>'%'.$fulldivision.'%',
							':month'=>'%'.$month.'%',':year'=>'%'.$year.'%'))
				->offset($offset)
				->limit($rows)
				->order($sort.' '.$order)
				->queryAll();
        }
		//foreach(Reportout::model()->findAll($criteria) as $data)
        foreach($cmd as $data)
		{	
			$row[] = array(
		'reportoutid'=>$data['reportoutid'],
'employeeid'=>$data['employeeid'],
'fullname'=>$data['fullname'],
'oldnik'=>$data['oldnik'],
'fulldivision'=>$data['fulldivision'],
'month'=>$data['month'],
'year'=>$data['year'],
's1'=>$data['s1'],
'd1'=>$data['d1'],
's2'=>$data['s2'],
'd2'=>$data['d2'],
's3'=>$data['s3'],
'd3'=>$data['d3'],
's4'=>$data['s4'],
'd4'=>$data['d4'],
's5'=>$data['s5'],
'd5'=>$data['d5'],
's6'=>$data['s6'],
'd6'=>$data['d6'],
's7'=>$data['s7'],
'd7'=>$data['d7'],
's8'=>$data['s8'],
'd8'=>$data['d8'],
's9'=>$data['s9'],
'd9'=>$data['d9'],
's10'=>$data['s10'],
'd10'=>$data['d10'],
's11'=>$data['s11'],
'd11'=>$data['d11'],
's12'=>$data['s12'],
'd12'=>$data['d12'],
's13'=>$data['s13'],
'd13'=>$data['d13'],
's14'=>$data['s14'],
'd14'=>$data['d14'],
's15'=>$data['s15'],
'd15'=>$data['d15'],
's16'=>$data['s16'],
'd16'=>$data['d16'],
's17'=>$data['s17'],
'd17'=>$data['d17'],
's18'=>$data['s18'],
'd18'=>$data['d18'],
's19'=>$data['s19'],
'd19'=>$data['d19'],
's20'=>$data['s20'],
'd20'=>$data['d20'],
's21'=>$data['s21'],
'd21'=>$data['d21'],
's22'=>$data['s22'],
'd22'=>$data['d22'],
's23'=>$data['s23'],
'd23'=>$data['d23'],
's24'=>$data['s24'],
'd24'=>$data['d24'],
's25'=>$data['s25'],
'd25'=>$data['d25'],
's26'=>$data['s26'],
'd26'=>$data['d26'],
's27'=>$data['s27'],
'd27'=>$data['d27'],
's28'=>$data['s28'],
'd28'=>$data['d28'],
's29'=>$data['s29'],
'd29'=>$data['d29'],
's30'=>$data['s30'],
'd30'=>$data['d30'],
's31'=>$data['s31'],
'd31'=>$data['d31'],
			);
		}
		$result=array_merge($result,array('rows'=>$row));
		return CJSON::encode($result);
	}
	
	public function actionSave()
	{
		header('Content-Type: application/json');
		if(!Yii::app()->request->isPostRequest)
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		$connection=Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
						if (isset($_POST['isNewRecord']))
			{
				$sql = 'call Insertreportout(:vemployeeid,:vfullname,:vfulldivision,:vmonth,:vyear,:vs1,:vd1,:vs2,:vd2,:vs3,:vd3,:vs4,:vd4,:vs5,:vd5,:vs6,:vd6,:vs7,:vd7,:vs8,:vd8,:vs9,:vd9,:vs10,:vd10,:vs11,:vd11,:vs12,:vd12,:vs13,:vd13,:vs14,:vd14,:vs15,:vd15,:vs16,:vd16,:vs17,:vd17,:vs18,:vd18,:vs19,:vd19,:vs20,:vd20,:vs21,:vd21,:vs22,:vd22,:vs23,:vd23,:vs24,:vd24,:vs25,:vd25,:vs26,:vd26,:vs27,:vd27,:vs28,:vd28,:vs29,:vd29,:vs30,:vd30,:vs31,:vd31,:voldnik,:vcreatedby)';
				$command=$connection->createCommand($sql);
			}
			else
			{
				$sql = 'call Updatereportout(:vid,:vemployeeid,:vfullname,:vfulldivision,:vmonth,:vyear,:vs1,:vd1,:vs2,:vd2,:vs3,:vd3,:vs4,:vd4,:vs5,:vd5,:vs6,:vd6,:vs7,:vd7,:vs8,:vd8,:vs9,:vd9,:vs10,:vd10,:vs11,:vd11,:vs12,:vd12,:vs13,:vd13,:vs14,:vd14,:vs15,:vd15,:vs16,:vd16,:vs17,:vd17,:vs18,:vd18,:vs19,:vd19,:vs20,:vd20,:vs21,:vd21,:vs22,:vd22,:vs23,:vd23,:vs24,:vd24,:vs25,:vd25,:vs26,:vd26,:vs27,:vd27,:vs28,:vd28,:vs29,:vd29,:vs30,:vd30,:vs31,:vd31,:voldnik,:vcreatedby)';
				$command=$connection->createCommand($sql);
				$command->bindvalue(':vid',$_POST['reportoutid'],PDO::PARAM_STR);
				$this->DeleteLock($this->menuname, $_POST['reportoutid']);
			}
			$command->bindvalue(':vemployeeid',$_POST['employeeid'],PDO::PARAM_STR);
$command->bindvalue(':vfullname',$_POST['fullname'],PDO::PARAM_STR);
$command->bindvalue(':vfulldivision',$_POST['fulldivision'],PDO::PARAM_STR);
$command->bindvalue(':vmonth',$_POST['month'],PDO::PARAM_STR);
$command->bindvalue(':vyear',$_POST['year'],PDO::PARAM_STR);
$command->bindvalue(':vs1',$_POST['s1'],PDO::PARAM_STR);
$command->bindvalue(':vd1',$_POST['d1'],PDO::PARAM_STR);
$command->bindvalue(':vs2',$_POST['s2'],PDO::PARAM_STR);
$command->bindvalue(':vd2',$_POST['d2'],PDO::PARAM_STR);
$command->bindvalue(':vs3',$_POST['s3'],PDO::PARAM_STR);
$command->bindvalue(':vd3',$_POST['d3'],PDO::PARAM_STR);
$command->bindvalue(':vs4',$_POST['s4'],PDO::PARAM_STR);
$command->bindvalue(':vd4',$_POST['d4'],PDO::PARAM_STR);
$command->bindvalue(':vs5',$_POST['s5'],PDO::PARAM_STR);
$command->bindvalue(':vd5',$_POST['d5'],PDO::PARAM_STR);
$command->bindvalue(':vs6',$_POST['s6'],PDO::PARAM_STR);
$command->bindvalue(':vd6',$_POST['d6'],PDO::PARAM_STR);
$command->bindvalue(':vs7',$_POST['s7'],PDO::PARAM_STR);
$command->bindvalue(':vd7',$_POST['d7'],PDO::PARAM_STR);
$command->bindvalue(':vs8',$_POST['s8'],PDO::PARAM_STR);
$command->bindvalue(':vd8',$_POST['d8'],PDO::PARAM_STR);
$command->bindvalue(':vs9',$_POST['s9'],PDO::PARAM_STR);
$command->bindvalue(':vd9',$_POST['d9'],PDO::PARAM_STR);
$command->bindvalue(':vs10',$_POST['s10'],PDO::PARAM_STR);
$command->bindvalue(':vd10',$_POST['d10'],PDO::PARAM_STR);
$command->bindvalue(':vs11',$_POST['s11'],PDO::PARAM_STR);
$command->bindvalue(':vd11',$_POST['d11'],PDO::PARAM_STR);
$command->bindvalue(':vs12',$_POST['s12'],PDO::PARAM_STR);
$command->bindvalue(':vd12',$_POST['d12'],PDO::PARAM_STR);
$command->bindvalue(':vs13',$_POST['s13'],PDO::PARAM_STR);
$command->bindvalue(':vd13',$_POST['d13'],PDO::PARAM_STR);
$command->bindvalue(':vs14',$_POST['s14'],PDO::PARAM_STR);
$command->bindvalue(':vd14',$_POST['d14'],PDO::PARAM_STR);
$command->bindvalue(':vs15',$_POST['s15'],PDO::PARAM_STR);
$command->bindvalue(':vd15',$_POST['d15'],PDO::PARAM_STR);
$command->bindvalue(':vs16',$_POST['s16'],PDO::PARAM_STR);
$command->bindvalue(':vd16',$_POST['d16'],PDO::PARAM_STR);
$command->bindvalue(':vs17',$_POST['s17'],PDO::PARAM_STR);
$command->bindvalue(':vd17',$_POST['d17'],PDO::PARAM_STR);
$command->bindvalue(':vs18',$_POST['s18'],PDO::PARAM_STR);
$command->bindvalue(':vd18',$_POST['d18'],PDO::PARAM_STR);
$command->bindvalue(':vs19',$_POST['s19'],PDO::PARAM_STR);
$command->bindvalue(':vd19',$_POST['d19'],PDO::PARAM_STR);
$command->bindvalue(':vs20',$_POST['s20'],PDO::PARAM_STR);
$command->bindvalue(':vd20',$_POST['d20'],PDO::PARAM_STR);
$command->bindvalue(':vs21',$_POST['s21'],PDO::PARAM_STR);
$command->bindvalue(':vd21',$_POST['d21'],PDO::PARAM_STR);
$command->bindvalue(':vs22',$_POST['s22'],PDO::PARAM_STR);
$command->bindvalue(':vd22',$_POST['d22'],PDO::PARAM_STR);
$command->bindvalue(':vs23',$_POST['s23'],PDO::PARAM_STR);
$command->bindvalue(':vd23',$_POST['d23'],PDO::PARAM_STR);
$command->bindvalue(':vs24',$_POST['s24'],PDO::PARAM_STR);
$command->bindvalue(':vd24',$_POST['d24'],PDO::PARAM_STR);
$command->bindvalue(':vs25',$_POST['s25'],PDO::PARAM_STR);
$command->bindvalue(':vd25',$_POST['d25'],PDO::PARAM_STR);
$command->bindvalue(':vs26',$_POST['s26'],PDO::PARAM_STR);
$command->bindvalue(':vd26',$_POST['d26'],PDO::PARAM_STR);
$command->bindvalue(':vs27',$_POST['s27'],PDO::PARAM_STR);
$command->bindvalue(':vd27',$_POST['d27'],PDO::PARAM_STR);
$command->bindvalue(':vs28',$_POST['s28'],PDO::PARAM_STR);
$command->bindvalue(':vd28',$_POST['d28'],PDO::PARAM_STR);
$command->bindvalue(':vs29',$_POST['s29'],PDO::PARAM_STR);
$command->bindvalue(':vd29',$_POST['d29'],PDO::PARAM_STR);
$command->bindvalue(':vs30',$_POST['s30'],PDO::PARAM_STR);
$command->bindvalue(':vd30',$_POST['d30'],PDO::PARAM_STR);
$command->bindvalue(':vs31',$_POST['s31'],PDO::PARAM_STR);
$command->bindvalue(':vd31',$_POST['d31'],PDO::PARAM_STR);
$command->bindvalue(':voldnik',$_POST['oldnik'],PDO::PARAM_STR);
			$command->bindvalue(':vcreatedby', Yii::app()->user->name,PDO::PARAM_STR);
			$command->execute();
			$transaction->commit();			
			getmessage(false,getcatalog('insertsuccess'));
		}
		catch (Exception $e)
		{
			$transaction->rollBack();
			getmessage(true,$e->getMessage());
		}
	}
	
	public function actionPurge()
	{
		header('Content-Type: application/json');
		
		if (isset($_POST['id']))
		{
			$id=$_POST['id'];
			$connection=Yii::app()->db;
			$transaction=$connection->beginTransaction();
			try
			{
				$sql = 'call Purgereportout(:vid,:vcreatedby)';
				$command=$connection->createCommand($sql);
				foreach($id as $ids)
				{
					$command->bindvalue(':vid',$ids,PDO::PARAM_STR);
					$command->bindvalue(':vcreatedby',Yii::app()->user->name,PDO::PARAM_STR);
					$command->execute();
				}
				$transaction->commit();
				getmessage(false,getcatalog('insertsuccess'));
			}
			catch (Exception $e)
			{
				$transaction->rollback();
				getmessage(true,$e->getMessage());
			}
		}
		else
		{
			getmessage(true,getcatalog('chooseone'));
		}
	}
	
	/*public function actionDownPDF()
	{
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select employeeid,fullname,fulldivision,month,year,s1,d1,s2,d2,s3,d3,s4,d4,s5,d5,s6,d6,s7,d7,s8,d8,s9,d9,s10,d10,s11,d11,s12,d12,s13,d13,s14,d14,s15,d15,s16,d16,s17,d17,s18,d18,s19,d19,s20,d20,s21,d21,s22,d22,s23,d23,s24,d24,s25,d25,s26,d26,s27,d27,s28,d28,s29,d29,s30,d30,s31,d31,oldnik
				from reportout a ";
		if ($_GET['id'] !== '') {
				$sql = $sql . "where a.reportoutid in (".$_GET['id'].")";
		}
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('reportout');
		$this->pdf->AddPage('P');
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('employeeid'),
getCatalog('fullname'),
getCatalog('fulldivision'),
getCatalog('month'),
getCatalog('year'),
getCatalog('s1'),
getCatalog('d1'),
getCatalog('s2'),
getCatalog('d2'),
getCatalog('s3'),
getCatalog('d3'),
getCatalog('s4'),
getCatalog('d4'),
getCatalog('s5'),
getCatalog('d5'),
getCatalog('s6'),
getCatalog('d6'),
getCatalog('s7'),
getCatalog('d7'),
getCatalog('s8'),
getCatalog('d8'),
getCatalog('s9'),
getCatalog('d9'),
getCatalog('s10'),
getCatalog('d10'),
getCatalog('s11'),
getCatalog('d11'),
getCatalog('s12'),
getCatalog('d12'),
getCatalog('s13'),
getCatalog('d13'),
getCatalog('s14'),
getCatalog('d14'),
getCatalog('s15'),
getCatalog('d15'),
getCatalog('s16'),
getCatalog('d16'),
getCatalog('s17'),
getCatalog('d17'),
getCatalog('s18'),
getCatalog('d18'),
getCatalog('s19'),
getCatalog('d19'),
getCatalog('s20'),
getCatalog('d20'),
getCatalog('s21'),
getCatalog('d21'),
getCatalog('s22'),
getCatalog('d22'),
getCatalog('s23'),
getCatalog('d23'),
getCatalog('s24'),
getCatalog('d24'),
getCatalog('s25'),
getCatalog('d25'),
getCatalog('s26'),
getCatalog('d26'),
getCatalog('s27'),
getCatalog('d27'),
getCatalog('s28'),
getCatalog('d28'),
getCatalog('s29'),
getCatalog('d29'),
getCatalog('s30'),
getCatalog('d30'),
getCatalog('s31'),
getCatalog('d31'),
getCatalog('oldnik'));
		$this->pdf->setwidths(array(40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['employeeid'],$row1['fullname'],$row1['fulldivision'],$row1['month'],$row1['year'],$row1['s1'],$row1['d1'],$row1['s2'],$row1['d2'],$row1['s3'],$row1['d3'],$row1['s4'],$row1['d4'],$row1['s5'],$row1['d5'],$row1['s6'],$row1['d6'],$row1['s7'],$row1['d7'],$row1['s8'],$row1['d8'],$row1['s9'],$row1['d9'],$row1['s10'],$row1['d10'],$row1['s11'],$row1['d11'],$row1['s12'],$row1['d12'],$row1['s13'],$row1['d13'],$row1['s14'],$row1['d14'],$row1['s15'],$row1['d15'],$row1['s16'],$row1['d16'],$row1['s17'],$row1['d17'],$row1['s18'],$row1['d18'],$row1['s19'],$row1['d19'],$row1['s20'],$row1['d20'],$row1['s21'],$row1['d21'],$row1['s22'],$row1['d22'],$row1['s23'],$row1['d23'],$row1['s24'],$row1['d24'],$row1['s25'],$row1['d25'],$row1['s26'],$row1['d26'],$row1['s27'],$row1['d27'],$row1['s28'],$row1['d28'],$row1['s29'],$row1['d29'],$row1['s30'],$row1['d30'],$row1['s31'],$row1['d31'],$row1['oldnik']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	*/
	public function actionDownPDF()
	{
	  parent::actionDownload();
		//masukkan perintah download
	  $sql = "select employeeid,fullname,oldnik,fulldivision,month,year,s1,d1,s2,d2,s3,d3,s4,d4,s5,d5,s6,d6,s7,d7,s8,d8,s9,d9,s10,d10,s11,d11,s12,d12,s13,d13,s14,d14,s15,d15,s16,d16,s17,d17,s18,d18,s19,d19,s20,d20,s21,d21,s22,d22,s23,d23,s24,d24,s25,d25,s26,d26,s27,d27,s28,d28,s29,d29,s30,d30,s31,d31
      from reportout a ";
		$employee = filter_input(INPUT_GET,'fullname');
        $fulldivision = filter_input(INPUT_GET,'fulldivision');
		$month = filter_input(INPUT_GET,'month');
		$year = filter_input(INPUT_GET,'year');
        
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.reportinid in (".$_GET['id'].")";
		}
        $sql .= " where coalesce(a.fullname,'') like '%".$employee."%' 
			 and coalesce(a.fulldivision,'') like '%".$fulldivision."%' 
			 and coalesce(a.month,'') like '%".$month."%'
			 and coalesce(a.year,'') like '%".$year."%'"; 
		$sql = $sql . " order by fullname";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();

		//masukkan judul
		$this->pdf->title=getCatalog('reportout');
		$this->pdf->AddPage('L',array(450,935));
		//masukkan posisi judul
		$this->pdf->colalign = array('L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L','L');
		//masukkan colom judul
		$this->pdf->colheader = array(getCatalog('employeeid'),
getCatalog('fullname'),
getCatalog('oldnik'),
getCatalog('fulldivision'),
getCatalog('month'),
getCatalog('year'),
getCatalog('s1'),
getCatalog('d1'),
getCatalog('s2'),
getCatalog('d2'),
getCatalog('s3'),
getCatalog('d3'),
getCatalog('s4'),
getCatalog('d4'),
getCatalog('s5'),
getCatalog('d5'),
getCatalog('s6'),
getCatalog('d6'),
getCatalog('s7'),
getCatalog('d7'),
getCatalog('s8'),
getCatalog('d8'),
getCatalog('s9'),
getCatalog('d9'),
getCatalog('s10'),
getCatalog('d10'),
getCatalog('s11'),
getCatalog('d11'),
getCatalog('s12'),
getCatalog('d12'),
getCatalog('s13'),
getCatalog('d13'),
getCatalog('s14'),
getCatalog('d14'),
getCatalog('s15'),
getCatalog('d15'),
getCatalog('s16'),
getCatalog('d16'),
getCatalog('s17'),
getCatalog('d17'),
getCatalog('s18'),
getCatalog('d18'),
getCatalog('s19'),
getCatalog('d19'),
getCatalog('s20'),
getCatalog('d20'),
getCatalog('s21'),
getCatalog('d21'),
getCatalog('s22'),
getCatalog('d22'),
getCatalog('s23'),
getCatalog('d23'),
getCatalog('s24'),
getCatalog('d24'),
getCatalog('s25'),
getCatalog('d25'),
getCatalog('s26'),
getCatalog('d26'),
getCatalog('s27'),
getCatalog('d27'),
getCatalog('s28'),
getCatalog('d28'),
getCatalog('s29'),
getCatalog('d29'),
getCatalog('s30'),
getCatalog('d30'),
getCatalog('s31'),
getCatalog('d31'));
		$this->pdf->setwidths(array(10,90,26,90,9,12,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,9,13,7));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L','L','L','L','L','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C','C');
		
		foreach($dataReader as $row1)
		{
			//masukkan baris untuk cetak
		  $this->pdf->row(array($row1['employeeid'],$row1['fullname'],$row1['oldnik'],$row1['fulldivision'],$row1['month'],$row1['year'],$row1['s1'],$row1['d1'],$row1['s2'],$row1['d2'],$row1['s3'],$row1['d3'],$row1['s4'],$row1['d4'],$row1['s5'],$row1['d5'],$row1['s6'],$row1['d6'],$row1['s7'],$row1['d7'],$row1['s8'],$row1['d8'],$row1['s9'],$row1['d9'],$row1['s10'],$row1['d10'],$row1['s11'],$row1['d11'],$row1['s12'],$row1['d12'],$row1['s13'],$row1['d13'],$row1['s14'],$row1['d14'],$row1['s15'],$row1['d15'],$row1['s16'],$row1['d16'],$row1['s17'],$row1['d17'],$row1['s18'],$row1['d18'],$row1['s19'],$row1['d19'],$row1['s20'],$row1['d20'],$row1['s21'],$row1['d21'],$row1['s22'],$row1['d22'],$row1['s23'],$row1['d23'],$row1['s24'],$row1['d24'],$row1['s25'],$row1['d25'],$row1['s26'],$row1['d26'],$row1['s27'],$row1['d27'],$row1['s28'],$row1['d28'],$row1['s29'],$row1['d29'],$row1['s30'],$row1['d30'],$row1['s31'],$row1['d31']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
	
	public function actionDownxls()
	{
		parent::actionDownxls();
		$sql = "select employeeid,fullname,fulldivision,month,year,s1,d1,s2,d2,s3,d3,s4,d4,s5,d5,s6,d6,s7,d7,s8,d8,s9,d9,s10,d10,s11,d11,s12,d12,s13,d13,s14,d14,s15,d15,s16,d16,s17,d17,s18,d18,s19,d19,s20,d20,s21,d21,s22,d22,s23,d23,s24,d24,s25,d25,s26,d26,s27,d27,s28,d28,s29,d29,s30,d30,s31,d31,oldnik
        from reportout a ";
        
		$employee = filter_input(INPUT_GET,'fullname');
        $fulldivision = filter_input(INPUT_GET,'fulldivision');
		$month = filter_input(INPUT_GET,'month');
		$year = filter_input(INPUT_GET,'year');
        
		if ($_GET['id'] !== '') {
				$sql = $sql . " and a.reportinid in (".$_GET['id'].")";
		}
        $sql .= " where coalesce(a.fullname,'') like '%".$employee."%' 
			 and coalesce(a.fulldivision,'') like '%".$fulldivision."%' 
			 and coalesce(a.month,'') like '%".$month."%'
			 and coalesce(a.year,'') like '%".$year."%'"; 
		$sql = $sql . " order by fullname";
		$command=$this->connection->createCommand($sql);
		$dataReader=$command->queryAll();
        
        $i=1;
        $line=2;
		$this->phpExcel->setActiveSheetIndex(0)
		->setCellValueByColumnAndRow(0,$line,getCatalog('employeeid'))
->setCellValueByColumnAndRow(1,$line,getCatalog('fullname'))
->setCellValueByColumnAndRow(2,$line,getCatalog('oldnik'))
->setCellValueByColumnAndRow(3,$line,getCatalog('fulldivision'))
->setCellValueByColumnAndRow(4,$line,getCatalog('month'))
->setCellValueByColumnAndRow(5,$line,getCatalog('year'))
->setCellValueByColumnAndRow(6,$line,getCatalog('s1'))
->setCellValueByColumnAndRow(7,$line,getCatalog('d1'))
->setCellValueByColumnAndRow(8,$line,getCatalog('s2'))
->setCellValueByColumnAndRow(9,$line,getCatalog('d2'))
->setCellValueByColumnAndRow(10,$line,getCatalog('s3'))
->setCellValueByColumnAndRow(11,$line,getCatalog('d3'))
->setCellValueByColumnAndRow(12,$line,getCatalog('s4'))
->setCellValueByColumnAndRow(13,$line,getCatalog('d4'))
->setCellValueByColumnAndRow(14,$line,getCatalog('s5'))
->setCellValueByColumnAndRow(15,$line,getCatalog('d5'))
->setCellValueByColumnAndRow(16,$line,getCatalog('s6'))
->setCellValueByColumnAndRow(17,$line,getCatalog('d6'))
->setCellValueByColumnAndRow(18,$line,getCatalog('s7'))
->setCellValueByColumnAndRow(19,$line,getCatalog('d7'))
->setCellValueByColumnAndRow(20,$line,getCatalog('s8'))
->setCellValueByColumnAndRow(21,$line,getCatalog('d8'))
->setCellValueByColumnAndRow(22,$line,getCatalog('s9'))
->setCellValueByColumnAndRow(23,$line,getCatalog('d9'))
->setCellValueByColumnAndRow(24,$line,getCatalog('s10'))
->setCellValueByColumnAndRow(25,$line,getCatalog('d10'))
->setCellValueByColumnAndRow(26,$line,getCatalog('s11'))
->setCellValueByColumnAndRow(27,$line,getCatalog('d11'))
->setCellValueByColumnAndRow(28,$line,getCatalog('s12'))
->setCellValueByColumnAndRow(29,$line,getCatalog('d12'))
->setCellValueByColumnAndRow(30,$line,getCatalog('s13'))
->setCellValueByColumnAndRow(31,$line,getCatalog('d13'))
->setCellValueByColumnAndRow(32,$line,getCatalog('s14'))
->setCellValueByColumnAndRow(33,$line,getCatalog('d14'))
->setCellValueByColumnAndRow(34,$line,getCatalog('s15'))
->setCellValueByColumnAndRow(35,$line,getCatalog('d15'))
->setCellValueByColumnAndRow(36,$line,getCatalog('s16'))
->setCellValueByColumnAndRow(37,$line,getCatalog('d16'))
->setCellValueByColumnAndRow(38,$line,getCatalog('s17'))
->setCellValueByColumnAndRow(39,$line,getCatalog('d17'))
->setCellValueByColumnAndRow(40,$line,getCatalog('s18'))
->setCellValueByColumnAndRow(41,$line,getCatalog('d18'))
->setCellValueByColumnAndRow(42,$line,getCatalog('s19'))
->setCellValueByColumnAndRow(43,$line,getCatalog('d19'))
->setCellValueByColumnAndRow(44,$line,getCatalog('s20'))
->setCellValueByColumnAndRow(45,$line,getCatalog('d20'))
->setCellValueByColumnAndRow(46,$line,getCatalog('s21'))
->setCellValueByColumnAndRow(47,$line,getCatalog('d21'))
->setCellValueByColumnAndRow(48,$line,getCatalog('s22'))
->setCellValueByColumnAndRow(49,$line,getCatalog('d22'))
->setCellValueByColumnAndRow(50,$line,getCatalog('s23'))
->setCellValueByColumnAndRow(51,$line,getCatalog('d23'))
->setCellValueByColumnAndRow(52,$line,getCatalog('s24'))
->setCellValueByColumnAndRow(53,$line,getCatalog('d24'))
->setCellValueByColumnAndRow(54,$line,getCatalog('s25'))
->setCellValueByColumnAndRow(55,$line,getCatalog('d25'))
->setCellValueByColumnAndRow(56,$line,getCatalog('s26'))
->setCellValueByColumnAndRow(57,$line,getCatalog('d26'))
->setCellValueByColumnAndRow(58,$line,getCatalog('s27'))
->setCellValueByColumnAndRow(59,$line,getCatalog('d27'))
->setCellValueByColumnAndRow(60,$line,getCatalog('s28'))
->setCellValueByColumnAndRow(61,$line,getCatalog('d28'))
->setCellValueByColumnAndRow(62,$line,getCatalog('s29'))
->setCellValueByColumnAndRow(63,$line,getCatalog('d29'))
->setCellValueByColumnAndRow(64,$line,getCatalog('s30'))
->setCellValueByColumnAndRow(65,$line,getCatalog('d30'))
->setCellValueByColumnAndRow(66,$line,getCatalog('s31'))
->setCellValueByColumnAndRow(67,$line,getCatalog('d31'))
;
        $line++;
        foreach($dataReader as $row1)
		{
			  $this->phpExcel->setActiveSheetIndex(0)
				->setCellValueByColumnAndRow(0, $line, $row1['employeeid'])
->setCellValueByColumnAndRow(1, $line, $row1['fullname'])
->setCellValueByColumnAndRow(2, $line, $row1['oldnik'])
->setCellValueByColumnAndRow(3, $line, $row1['fulldivision'])
->setCellValueByColumnAndRow(4, $line, $row1['month'])
->setCellValueByColumnAndRow(5, $line, $row1['year'])
->setCellValueByColumnAndRow(6, $line, $row1['s1'])
->setCellValueByColumnAndRow(7, $line, $row1['d1'])
->setCellValueByColumnAndRow(8, $line, $row1['s2'])
->setCellValueByColumnAndRow(9, $line, $row1['d2'])
->setCellValueByColumnAndRow(10, $line, $row1['s3'])
->setCellValueByColumnAndRow(11, $line, $row1['d3'])
->setCellValueByColumnAndRow(12, $line, $row1['s4'])
->setCellValueByColumnAndRow(13, $line, $row1['d4'])
->setCellValueByColumnAndRow(14, $line, $row1['s5'])
->setCellValueByColumnAndRow(15, $line, $row1['d5'])
->setCellValueByColumnAndRow(16, $line, $row1['s6'])
->setCellValueByColumnAndRow(17, $line, $row1['d6'])
->setCellValueByColumnAndRow(18, $line, $row1['s7'])
->setCellValueByColumnAndRow(19, $line, $row1['d7'])
->setCellValueByColumnAndRow(20, $line, $row1['s8'])
->setCellValueByColumnAndRow(21, $line, $row1['d8'])
->setCellValueByColumnAndRow(22, $line, $row1['s9'])
->setCellValueByColumnAndRow(23, $line, $row1['d9'])
->setCellValueByColumnAndRow(24, $line, $row1['s10'])
->setCellValueByColumnAndRow(25, $line, $row1['d10'])
->setCellValueByColumnAndRow(26, $line, $row1['s11'])
->setCellValueByColumnAndRow(27, $line, $row1['d11'])
->setCellValueByColumnAndRow(28, $line, $row1['s12'])
->setCellValueByColumnAndRow(29, $line, $row1['d12'])
->setCellValueByColumnAndRow(30, $line, $row1['s13'])
->setCellValueByColumnAndRow(31, $line, $row1['d13'])
->setCellValueByColumnAndRow(32, $line, $row1['s14'])
->setCellValueByColumnAndRow(33, $line, $row1['d14'])
->setCellValueByColumnAndRow(34, $line, $row1['s15'])
->setCellValueByColumnAndRow(35, $line, $row1['d15'])
->setCellValueByColumnAndRow(36, $line, $row1['s16'])
->setCellValueByColumnAndRow(37, $line, $row1['d16'])
->setCellValueByColumnAndRow(38, $line, $row1['s17'])
->setCellValueByColumnAndRow(39, $line, $row1['d17'])
->setCellValueByColumnAndRow(40, $line, $row1['s18'])
->setCellValueByColumnAndRow(41, $line, $row1['d18'])
->setCellValueByColumnAndRow(42, $line, $row1['s19'])
->setCellValueByColumnAndRow(43, $line, $row1['d19'])
->setCellValueByColumnAndRow(44, $line, $row1['s20'])
->setCellValueByColumnAndRow(45, $line, $row1['d20'])
->setCellValueByColumnAndRow(46, $line, $row1['s21'])
->setCellValueByColumnAndRow(47, $line, $row1['d21'])
->setCellValueByColumnAndRow(48, $line, $row1['s22'])
->setCellValueByColumnAndRow(49, $line, $row1['d22'])
->setCellValueByColumnAndRow(50, $line, $row1['s23'])
->setCellValueByColumnAndRow(51, $line, $row1['d23'])
->setCellValueByColumnAndRow(52, $line, $row1['s24'])
->setCellValueByColumnAndRow(53, $line, $row1['d24'])
->setCellValueByColumnAndRow(54, $line, $row1['s25'])
->setCellValueByColumnAndRow(55, $line, $row1['d25'])
->setCellValueByColumnAndRow(56, $line, $row1['s26'])
->setCellValueByColumnAndRow(57, $line, $row1['d26'])
->setCellValueByColumnAndRow(58, $line, $row1['s27'])
->setCellValueByColumnAndRow(59, $line, $row1['d27'])
->setCellValueByColumnAndRow(60, $line, $row1['s28'])
->setCellValueByColumnAndRow(61, $line, $row1['d28'])
->setCellValueByColumnAndRow(62, $line, $row1['s29'])
->setCellValueByColumnAndRow(63, $line, $row1['d29'])
->setCellValueByColumnAndRow(64, $line, $row1['s30'])
->setCellValueByColumnAndRow(65, $line, $row1['d30'])
->setCellValueByColumnAndRow(66, $line, $row1['s31'])
->setCellValueByColumnAndRow(67, $line, $row1['d31'])
;		$i+=1;
        $line++;
		}
		/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="reportin.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save('php://output');
		unset($excel);*/
        $this->getFooterXLS($this->phpExcel);
	}
	

}
