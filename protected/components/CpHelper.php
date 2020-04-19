<?php
function GetCatalog($menuname) {
	$dependency = new CDbCacheDependency('select max(updatedate) from catalogsys');
	$connection = Yii::app()->db;
	if (Yii::app()->user->id !== null) {
		$sql = 'select catalogval as katalog 
			from catalogsys a 
			inner join useraccess b on b.languageid = a.languageid 
			where catalogname = :catalogname 
			and b.username = :user';
			$comm = $connection->cache(1000, $dependency)->createCommand($sql);
			$comm->bindvalue(':catalogname',$menuname,PDO::PARAM_STR);
			$comm->bindvalue(':user',Yii::app()->user->id,PDO::PARAM_STR);
		} else {
		$sql = 'select catalogval as katalog 
			from catalogsys a 
			where languageid = 1 
			and catalogname = :catalogname';
		$comm = $connection->cache(1000, $dependency)->createCommand($sql);
	}
	$comm->bindvalue(':catalogname',$menuname,PDO::PARAM_STR);
	$menu = $comm->queryScalar();
	if (($menu != null) || ($menu != '')) {
		return $menu;
	} else {
		return $menuname;
	}
}
function GetMessage($isError = false, $catalogname = '', $typeerror = 0) {
	header('Content-Type: application/json');	
	if ($isError == true) {
		$isError = 1;
	} else {
		$isError = 0;
	}
	echo CJSON::encode(array(
		'isError' => $isError,
		'msg' => $catalogname
	));
	Yii::app()->end();
}
function GetMessageConsole($catalogname = '') {
	$catalogname = strtr($catalogname,array('CDbCommand failed to execute the SQL statement: SQLSTATE[45000]:'=>''));
	$catalogname = strtr($catalogname,array('<<Unknown error>>:'=>''));
	$catalogname = strtr($catalogname,array(' The SQL statement'=>''));
	$catalogname = 'Pesan: '.$catalogname;
	return $catalogname;
}
function CheckDoc($wfname) {
	$sql = 'select getwfmaxstatbywfname(:wfname)';
	$comm = Yii::app()->db->createCommand($sql);
	$comm->bindvalue(':wfname',$wfname,PDO::PARAM_STR);
  $isallow = $comm->queryScalar();
  return $isallow;
}
function GetKey($username) {
  $dependency = new CDbCacheDependency('select max(updatedate) from useraccess');
	$sql = 'select authkey from useraccess where lower(username) = :username';
	$comm = Yii::app()->db->cache(1000, $dependency)->createCommand($sql);
	return $comm->queryScalar();
}
function GetMenuAuth($menuobject) {
  $dependency = new CDbCacheDependency("select max(gm.updatedate) 
    from groupmenuauth gm
    inner join menuauth ma on ma.menuauthid = gm.menuauthid
    inner join usergroup ug on ug.groupaccessid = gm.groupaccessid
    inner join useraccess ua on ua.useraccessid = ug.useraccessid
    where upper(ma.menuobject) = upper('" . $menuobject . "') 
    and lower(ua.username) = lower('" . Yii::app()->user->id . "')");
  $sql     = 'select ifnull(count(1),0)
    from groupmenuauth gm
    inner join menuauth ma on ma.menuauthid = gm.menuauthid
    inner join usergroup ug on ug.groupaccessid = gm.groupaccessid
    inner join useraccess ua on ua.useraccessid = ug.useraccessid
    where upper(ma.menuobject) = upper(:menuobject) 
		and lower(ua.username) = lower(:user)';
	$comm = Yii::app()->db->cache(1000, $dependency)->createCommand($sql);
	$comm->bindvalue(':menuobject',$menuobject,PDO::PARAM_STR);
	$comm->bindvalue(':user',Yii::app()->user->id,PDO::PARAM_STR);
  return $comm->queryScalar();
}
function GetItems() {
  $dependency = new CDbCacheDependency("SELECT max(b.updatedate)
    from menuaccess a 
    join groupmenu b on b.menuaccessid = a.menuaccessid 
    join usergroup c on c.groupaccessid = b.groupaccessid 
    join useraccess d on d.useraccessid = c.useraccessid
    where a.parentid is null and a.recordstatus = 1 and b.isread = 1  
      and lower(d.username) = lower('" . Yii::app()->user->name . "')");
  switch(Yii::app()->user->name) {
    case null :
      $results = Yii::app()->db->cache(1000, $dependency)->createCommand("
        select distinct a.menuicon,a.menuname, a.menuaccessid, a.description, a.menuurl,a.parentid,a.sortorder,a.description
        from menuaccess a, groupmenu b, usergroup c, useraccess d 
        where b.menuaccessid = a.menuaccessid and c.groupaccessid = b.groupaccessid and a.recordstatus = 1 and b.isread = 1 and 
          d.useraccessid = c.useraccessid and lower(d.username) = lower('guest') and parentid is null")->queryAll();
      break;
    default :
      $results = Yii::app()->db->cache(1000, $dependency)->createCommand("select distinct a.menuicon,a.menuname, a.menuaccessid, a.description, a.menuurl,a.parentid,a.sortorder,a.description
      from menuaccess a 
      join groupmenu b on b.menuaccessid = a.menuaccessid 
      join usergroup c on c.groupaccessid = b.groupaccessid 
      join useraccess d on d.useraccessid = c.useraccessid
      where a.parentid is null and a.recordstatus = 1 and b.isread = 1  
        and lower(d.username) = lower('" . Yii::app()->user->name . "')
      order by a.sortorder ASC, a.description ASC ")->queryAll();
  }
  $items = array();
  foreach ($results AS $result) {
    $items[] = array(
      'name' => $result['menuname'],
      'label' => getCatalog($result['menuname']),
      'url' => Yii::app()->createUrl($result['menuurl']),
      'icon' => $result['menuicon'],
      'parentid' => $result['menuaccessid']
    );
  }
  return $items;
}
function getSubMenu($menuname) {
	$dependency = new CDbCacheDependency("SELECT max(b.updatedate) 
		from menuaccess a 
			join groupmenu b on b.menuaccessid = a.menuaccessid 
			join usergroup c on c.groupaccessid = b.groupaccessid 
			join useraccess d on d.useraccessid = c.useraccessid
			where a.parentid = " . $menuname . " and d.username = '" . Yii::app()->user->id . "' and b.isread = 1 and a.recordstatus = 1 and d.recordstatus = 1");
	$results    = Yii::app()->db->cache(1000, $dependency)->createCommand("select distinct t.menuaccessid,t.menuname,t.description,t.menuurl,t.menuicon 
		from menuaccess t 
		inner join groupmenu a on a.menuaccessid = t.menuaccessid
		inner join usergroup b on b.groupaccessid = a.groupaccessid
		inner join useraccess c on c.useraccessid = b.useraccessid
		where t.parentid = " . $menuname . " and c.username = '" . Yii::app()->user->id . "' and a.isread = 1 and t.recordstatus = 1 and c.recordstatus = 1
		order by t.sortorder asc, t.description asc")->queryAll();
	$items      = array();
	foreach ($results AS $result) {
		$items[] = array(
			'name' => $result['menuname'],
			'label' => getCatalog($result['menuname']),
			'url' => Yii::app()->createUrl($result['menuurl']),
			'icon' => $result['menuicon'],
			'id' => $result['menuaccessid'],
			'parentid' => $result['menuaccessid']
		);
	}
	return $items;
}
function eja($number) {
  $number       = strtr($number,array(','=>''));
  $before_comma = trim(to_word($number));
  $after_comma  = trim(comma($number));
  $results      = $before_comma . ' koma ' . $after_comma;
  $results = strtr($results,array('nol nol nol nol nol'=>''));
  $results = strtr($results,array('nol nol nol nol nol'=>''));
  $results = strtr($results,array('nol nol nol'=>''));
  $results = strtr($results,array('nol nol nol'=>''));
  $results = strtr($results,array('nol nol'=>''));
  $results = strtr($results,array('koma nol'=>''));
  return ucwords($results);
}
function to_word($number) {
  $words      = '';
  $arr_number = array(
    '',
    'satu',
    'dua',
    'tiga',
    'empat',
    'lima',
    'enam',
    'tujuh',
    'delapan',
    'sembilan',
    'sepuluh',
    'sebelas'
  );
  switch (true) {
    case ($number == 0) :
      $words = ' ';
      break;
    case (($number > 0) && ($number < 12)) :
      $words = ' ' . $arr_number[$number];
      break;
    case ($number < 20) :
      $words = to_word($number - 10) . ' belas';
      break;
    case ($number < 100) :
      $words = to_word($number / 10) . ' puluh ' . to_word($number % 10);
      break;
    case ($number < 200) :
      $words = 'seratus ' . to_word($number - 100);
      break;
    case ($number < 1000) :
      $words = to_word($number / 100) . ' ratus ' . to_word($number % 100);
      break;
    case ($number < 2000) :
      $words = 'seribu ' . to_word($number - 1000);
      break;
    case ($number < 1000000) :
      $words = to_word($number / 1000) . ' ribu ' . to_word($number % 1000);
      break;
    case ($number < 1000000000) :
      $words = to_word($number / 1000000) . ' juta ' . to_word($number % 1000000);
      break;
    case ($number < 1000000000000) :
      $words = to_word($number / 1000000000) . ' milyar ' . to_word($number % 1000000000);
      break;
    case ($number < 1000000000000000) :
      $words = to_word($number / 1000000000000) . ' trilyun ' . to_word($number % 1000000000000);
      break;
    default :
      $words = 'undefined';
  }
  return $words;
}
function comma($number) {
  $after_comma = stristr($number, '.');
  $arr_number  = array(
    'nol',
    'satu',
    'dua',
    'tiga',
    'empat',
    'lima',
    'enam',
    'tujuh',
    'delapan',
    'sembilan'
  );
  $results = '';
  $length  = strlen($after_comma);
  $i       = 1;
  while ($i < $length) {
    $get = substr($after_comma, $i, 1);
    $results .= ' ' . $arr_number[$get];
    $i++;
  }
  return $results;
}
function ValidateData($datavalidate) {
  $messages = '';
  for ($row = 0; $row < count($datavalidate); $row++) {
    if ($datavalidate[$row][2] == 'emptystring') {
      if ($datavalidate[$row][0] == '') {
        $message = getCatalog($datavalidate[$row][1]);
        if ($message != null) {
          $messages = $message->catalogval;
        } else {
          $messages = $datavalidate[$row][1];
        }
      }
    }
    if ($messages !== '') {
      $this->GetMessage('failure', $messages);
    }
  }
}
function CheckAccess($menuname, $menuaction) {
	$baccess    = false;
	$dependency = new CDbCacheDependency("select max(c.updatedate) 
	from useraccess a 
	inner join usergroup b on b.useraccessid = a.useraccessid 
	inner join groupmenu c on c.groupaccessid = b.groupaccessid 
	inner join menuaccess d on d.menuaccessid = c.menuaccessid 
	where lower(username) = lower('" . Yii::app()->user->id . "') and lower(menuname) = lower('" . $menuname . "')");
	$sql        = "select " . $menuaction . " as akses " . " from useraccess a 
	inner join usergroup b on b.useraccessid = a.useraccessid 
	inner join groupmenu c on c.groupaccessid = b.groupaccessid 
	inner join menuaccess d on d.menuaccessid = c.menuaccessid 
	where lower(username) = lower('" . Yii::app()->user->id . "') and lower(menuname) = lower('" . $menuname . "')";
	$results		  = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
	foreach ($results as $result) {
		if ($result['akses'] == 1) {
			$baccess = true;
		}
	}
	return $baccess;
}
function findstatusbyuser($workflow) {
	$dependency = new CDbCacheDependency("select max(b.updatedate)
		from workflow a
		inner join wfgroup b on b.workflowid = a.workflowid
		inner join groupaccess c on c.groupaccessid = b.groupaccessid
		inner join usergroup d on d.groupaccessid = c.groupaccessid
		inner join useraccess e on e.useraccessid = d.useraccessid
		where upper(a.wfname) = upper('". $workflow ."') and upper(e.username)=upper('".Yii::app()->user->name."') 
		order by b.wfbefstat asc limit 1");
	$status = Yii::app()->db->cache(1000, $dependency)->createCommand("select b.wfbefstat
		from workflow a
		inner join wfgroup b on b.workflowid = a.workflowid
		inner join groupaccess c on c.groupaccessid = b.groupaccessid
		inner join usergroup d on d.groupaccessid = c.groupaccessid
		inner join useraccess e on e.useraccessid = d.useraccessid
		where upper(a.wfname) = upper('". $workflow ."') and upper(e.username)=upper('".Yii::app()->user->name."') 
		order by b.wfbefstat asc limit 1")->queryScalar();
	if ($status !== '') {
		return $status;
	}
	else {
		return 0;
	}
}
function getwfbefstat($workflow) {
  $dependency = new CDbCacheDependency("select max(updatedate) from workflow a
		inner join wfgroup b on b.workflowid = a.workflowid
		inner join groupaccess c on c.groupaccessid = b.groupaccessid
		inner join usergroup d on d.groupaccessid = c.groupaccessid
		inner join useraccess e on e.useraccessid = d.useraccessid
		where upper(a.wfname) = upper('".$workflow."') and upper(e.username)=upper('".Yii::app()->user->name."')");	
  $status = Yii::app()->db->cache(1000, $dependency)->createCommand("select wfbefstat
		from workflow a
		inner join wfgroup b on b.workflowid = a.workflowid
		inner join groupaccess c on c.groupaccessid = b.groupaccessid
		inner join usergroup d on d.groupaccessid = c.groupaccessid
		inner join useraccess e on e.useraccessid = d.useraccessid
		where upper(a.wfname) = upper('".$workflow."') and upper(e.username)=upper('".Yii::app()->user->name."')")->queryScalar();
	if ($status !== null) {
		return $status;
	} else {
		return 0;
	}
}
function getip() {
	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = $_SERVER['REMOTE_ADDR'];
	if(filter_var($client, FILTER_VALIDATE_IP)) {
			$ip = $client;
	}
	else if(filter_var($forward, FILTER_VALIDATE_IP)) {
			$ip = $forward;
	}
	else {
			$ip = $remote;
	}
	return $ip;
}
function GetUserPC(){
	$username = Yii::app()->user->name;
	$ippublic = isset($_POST['clientippublic'])?$_POST['clientippublic']:getip();
	$iplocal = isset($_POST['clientiplocal'])?$_POST['clientiplocal']:getip();
	$lat = isset($_POST['clientlat'])?$_POST['clientlat']:'';
	$lng = isset($_POST['clientlng'])?$_POST['clientlng']:'';
	return $username.','.$ippublic.','.$iplocal.','.$lat.','.$lng;
}
function GetCompanyCode($id) {
	return Yii::app()->db->createCommand('
		select companycode
		from company 
		where companyid = ' . $id)->queryScalar();
}
function getUserFavs() {
  $dependency = new CDbCacheDependency("select max(a.updatedate) 
  from userfav a
  join menuaccess b on b.menuaccessid = a.menuaccessid 
  join useraccess c on c.useraccessid = a.useraccessid 
  where c.username = '". Yii::app()->user->id. "'");
	$sql = "select distinct b.menuaccessid,b.menuname,b.description,b.menuurl,b.menuicon
		from userfav a
		join menuaccess b on b.menuaccessid = a.menuaccessid 
		join useraccess c on c.useraccessid = a.useraccessid 
		where c.username = '". Yii::app()->user->id. "'		
	";
	$results = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
	$items      = array();
	foreach ($results AS $result) {
		$items[] = array(
			'name' => $result['menuname'],
			'label' => getCatalog($result['menuname']),
			'url' => Yii::app()->createUrl($result['menuurl']),
			'icon' => $result['menuicon'],
			'parentid' => $result['menuaccessid']
		);
	}
	return $items;
}
function getUserObjectValues($menuobject='company') {
  $dependency = new CDbCacheDependency("select max(a.updatedate) 
  from groupmenuauth a
  inner join menuauth b on b.menuauthid = a.menuauthid
  inner join usergroup c on c.groupaccessid = a.groupaccessid 
  inner join useraccess d on d.useraccessid = c.useraccessid 
  where b.menuobject = '".$menuobject."'
  and d.username = '" . Yii::app()->user->name . "'");
	$sql = "select distinct a.menuvalueid 
				from groupmenuauth a
				inner join menuauth b on b.menuauthid = a.menuauthid
				inner join usergroup c on c.groupaccessid = a.groupaccessid 
				inner join useraccess d on d.useraccessid = c.useraccessid 
				where b.menuobject = '".$menuobject."'
				and d.username = '" . Yii::app()->user->name . "'";
	$cid = '';
	$datas = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
	foreach ($datas as $data) {
		if ($cid == '') {
			$cid = $data['menuvalueid'];
		} else 
		if ($cid !== '') {
			$cid .= ','.$data['menuvalueid'];
		}
	}
	return $cid;
}
function getUserObjectWfValues($menuobject='company',$workflow='appso') {
  $dependency = new CDbCacheDependency("select max(a.updatedate) 
  from groupmenuauth a
				inner join menuauth b on b.menuauthid = a.menuauthid
				inner join usergroup c on c.groupaccessid = a.groupaccessid 
				inner join useraccess d on d.useraccessid = c.useraccessid 
				inner join wfgroup e on e.groupaccessid = a.groupaccessid
				inner join workflow f on f.workflowid = e.workflowid
				where b.menuobject = '".$menuobject."'
				and d.username = '" . Yii::app()->user->name . "'
			and c.groupaccessid in (select l.groupaccessid
			from wfgroup j
			join workflow k on k.workflowid=j.workflowid
			join usergroup l on l.groupaccessid=j.groupaccessid
			where k.wfname = '".$workflow."'
			and l.useraccessid=d.useraccessid)");
	$sql = "select distinct a.menuvalueid 
				from groupmenuauth a
				inner join menuauth b on b.menuauthid = a.menuauthid
				inner join usergroup c on c.groupaccessid = a.groupaccessid 
				inner join useraccess d on d.useraccessid = c.useraccessid 
				inner join wfgroup e on e.groupaccessid = a.groupaccessid
				inner join workflow f on f.workflowid = e.workflowid
				where b.menuobject = '".$menuobject."'
				and d.username = '" . Yii::app()->user->name . "'
			and c.groupaccessid in (select l.groupaccessid
			from wfgroup j
			join workflow k on k.workflowid=j.workflowid
			join usergroup l on l.groupaccessid=j.groupaccessid
			where k.wfname = '".$workflow."'
			and l.useraccessid=d.useraccessid)";
	$cid = '';
	$datas = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
	foreach ($datas as $data) {
		if ($cid == '') {
			$cid = $data['menuvalueid'];
		} else 
		if ($cid !== '') {
			$cid .= ','.$data['menuvalueid'];
		}
	}
	return $cid;
}
function getUserRecordStatus($wfname) {
	$sql = 'select distinct b.wfbefstat
				from workflow a
				inner join wfgroup b on b.workflowid = a.workflowid
				inner join usergroup d on d.groupaccessid = b.groupaccessid
				inner join useraccess e on e.useraccessid = d.useraccessid
				where a.wfname = :wfname and e.username = :user';
	$cid = '';
	$comm = Yii::app()->db->createCommand($sql);
	$comm->bindvalue(':wfname',$wfname,PDO::PARAM_STR);
	$comm->bindvalue(':user',Yii::app()->user->name,PDO::PARAM_STR);
	$datas = $comm->queryAll();
	foreach ($datas as $data) {
		if ($cid == '') {
			$cid = $data['wfbefstat'];
		} else 
		if ($cid !== '') {
			$cid .= ','.$data['wfbefstat'];
		}
	}
	return $cid;
}
function findstatusname($workflowname,$recordstatus) {
	$sql = 'select wfstatusname
		from wfstatus a
		inner join workflow b on b.workflowid = a.workflowid
		where b.wfname = :wfname and a.wfstat = :wfstat';
	$comm = Yii::app()->db->createCommand($sql);
	$comm->bindvalue(':wfname',$workflowname,PDO::PARAM_STR);
	$comm->bindvalue(':wfstat',$recordstatus,PDO::PARAM_STR);
	$status = $comm->queryScalar();
	if ($status != '') {
		return $status;
	}
	else {
		return 0;
	}
}
function getcurrencyid() {
	$a = 0;
	$sql = 'select currencyid from company limit 1';
	$command=Yii::app()->db->createCommand($sql);
	$a = $command->queryscalar();
	return $a;
}
function getcity() {
	$a = 0;
	$sql = 'select cityname from company a join city b on b.cityid = a.cityid limit 1';
	$command=Yii::app()->db->createCommand($sql);
	$a = $command->queryscalar();
	return $a;
}
function getcurrencyname() {
	$a = 0;
	$sql = 'select currencyname from company a join currency b on b.currencyid = a.currencyid limit 1';
	$command=Yii::app()->db->createCommand($sql);
	$a = $command->queryscalar();
	return $a;
}
function getcurrencysymbol() {
	$a = 0;
	$sql = 'select symbol from company a join currency b on b.currencyid = a.currencyid limit 1';
	$command=Yii::app()->db->createCommand($sql);
	$a = $command->queryscalar();
	return $a;
}
function CheckEmptyUser() {
	if (Yii::app()->user == null) {
		echo '<script type="text/javascript">window.location.href="'.Yii::app()->createUrl('/site/login').'"; </script>';
	} else 
	if (Yii::app()->user->id == '') {
		echo '<script type="text/javascript">window.location.href="'.Yii::app()->createUrl('/site/login').'"; </script>';
	}
	Yii::app()->end();
}
function GetStatusColor($wfname) {
	$sql = "
		select backcolor,fontcolor,wfstat
		from workflow a 
		join wfstatus b on b.workflowid = a.workflowid 
		where a.wfname = '".$wfname."'
	";
	$cmd = Yii::app()->db->createCommand($sql)->queryAll();
	$s = '';
	foreach ($cmd as $data) {
		if ($s == '') {
			$s .= " if (row.recordstatus == ".$data['wfstat'].") {
					return '<div style=\"background-color:".$data['backcolor'].";color:".$data['fontcolor']."\">'+value+'</div>';
				}";
		} else {
			$s .= " else if (row.recordstatus == ".$data['wfstat'].") {
					return '<div style=\"background-color:".$data['backcolor'].";color:".$data['fontcolor']."\">'+value+'</div>';
				}";
		}
	}
	return $s;
}
function GetSearchText($paramtype=[],$param,$default='',$datatype='string') {
	$s = $default;
	for ($i = 0;$i<count($paramtype);$i++) {
		if (strtoupper($paramtype[$i]) == 'POST') {
			$s = isset ($_POST[$param]) ? filter_input(INPUT_POST,$param) : $s;
		}
		if (strtoupper($paramtype[$i]) == 'GET') {
			$s = isset ($_GET[$param]) ? filter_input(INPUT_GET,$param) : $s;
		}
		if (strtoupper($paramtype[$i]) == 'Q') {
			$s = isset ($_GET['q']) ? filter_input(INPUT_GET,'q') : $s;
		}
	}
	if ($datatype=='string') {
		$s = '%'.strtr(trim($s),array(' '=>'%')).'%';
	}
	return $s;
}
function IsNullOrEmptyString($str){
    return (!isset($str) || trim($str) === '');
}
function GetAllWfStatus($wfname) {
	$sql = "
		select wfstat,wfstatusname 
		from wfstatus a 
		join workflow b on b.workflowid = a.workflowid 
		where b.wfname = '".$wfname."'";
	$dataReader = Yii::app()->db->createCommand($sql)->queryAll();
	$s = '<option value="">All Status</option>';
	foreach($dataReader as $data) {
		$s .= '<option value="'.$data['wfstat'].'">'.$data['wfstatusname'].'</option>';
	}
	return $s;
}
function GetRemoteData($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, Yii::app()->params['ReportTimeout']);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
function PrintPDF($reportname,$dataprint){
	$dataprint['j_username']=Yii::app()->params['ReportServerUser'];
	$dataprint['j_password']=Yii::app()->params['ReportServerPass'];
	$dataprint['titlereport']=GetCatalog($reportname);
	$dataprint['titlecompany']=Yii::app()->params['title'];
	$dataprint['titleuser']=getcatalog('printby').' '.Yii::app()->user->id;
	$url = Yii::app()->params['baseUrlReport']."/".$reportname.".pdf?".http_build_query($dataprint);
	$data = GetRemoteData($url);
	if (strpos($data,'PDF') != 0) {        
		header('Cache-Control: public');
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="'.$reportname.'.pdf"');
		header('Content-Length: '.strlen($data));
	} 
	echo $data;
}
function PrintXLS($reportname,$dataprint){
	$dataprint['j_username']=Yii::app()->params['ReportServerUser'];
	$dataprint['j_password']=Yii::app()->params['ReportServerPass'];
	$dataprint['titlereport']=GetCatalog($reportname);
	$dataprint['titlecompany']=Yii::app()->params['title'];
	$dataprint['titleuser']=getcatalog('printby').' '.Yii::app()->user->id;
	$url = Yii::app()->params['baseUrlReport']."/".$reportname.".xlsx?".http_build_query($dataprint);
	$data = GetRemoteData($url);
	header('Cache-Control: public');
	header('Content-type: application/vnd.ms-excel');
	header('Content-Disposition: inline; filename="'.$reportname.'.xlsx"');
	header('Content-Length: '.strlen($data));
	echo $data;
}
function PrintDoc($reportname,$dataprint){
	$dataprint['j_username']=Yii::app()->params['ReportServerUser'];
	$dataprint['j_password']=Yii::app()->params['ReportServerPass'];
	$dataprint['titlereport']=GetCatalog($reportname);
	$dataprint['titlecompany']=Yii::app()->params['title'];
	$dataprint['titleuser']=getcatalog('printby').' '.Yii::app()->user->id;
	$url = Yii::app()->params['baseUrlReport']."/".$reportname.".docx?".http_build_query($dataprint);
	$data = GetRemoteData($url);
	header('Cache-Control: public');
	header('Content-type: application/vnd.ms-word');
	header('Content-Disposition: inline; filename="'.$reportname.'.docx"');
	header('Content-Length: '.strlen($data));
	echo $data;
}
function GetDashboard() {
  $dependency = new CDbCacheDependency("select max(d.updatedate) 
  from userdash a
  join usergroup b on b.groupaccessid = a.groupaccessid 
  join useraccess c on c.useraccessid = b.useraccessid
  join widget d on d.widgetid = a.widgetid 
  join menuaccess e on e.menuaccessid = a.menuaccessid 
  where lower(menuname) = lower('dashboard') and c.username = '".Yii::app()->user->name."'");
  $sql = "select distinct d.width,d.height,d.widgetname,d.widgettitle,d.widgeturl 		
    from userdash a
		join usergroup b on b.groupaccessid = a.groupaccessid 
		join useraccess c on c.useraccessid = b.useraccessid
		join widget d on d.widgetid = a.widgetid 
		join menuaccess e on e.menuaccessid = a.menuaccessid 
		where lower(menuname) = lower('dashboard') and c.username = '".Yii::app()->user->name."'";
	return Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
}
function CreateCode($menuname) {
  $sql = "select viewcode,controllercode,b.modulename,ifnull(menudep,'') as menudep
    from menuaccess a
    join modules b on b.moduleid = a.moduleid 
    where menuname = '".$menuname."'";
  $cmd = Yii::app()->db->createCommand($sql)->queryRow();
  $foldermodule = Yii::getPathOfAlias('webroot')."/protected/modules/".$cmd['modulename'];
  if (!file_exists($foldermodule)) {
    mkdir($foldermodule, 0777);
  }
  $foldercontroller = Yii::getPathOfAlias('webroot')."/protected/modules/".$cmd['modulename']."/controllers";
  if (!file_exists($foldercontroller)) {
    mkdir($foldercontroller, 0777);
  }
  $filecontroller = Yii::getPathOfAlias('webroot')."/protected/modules/".$cmd['modulename']."/controllers/".ucfirst($menuname)."Controller.php";
  $fh = fopen($filecontroller, 'w');
  fwrite($fh, $cmd['controllercode']."\n");
  fclose($fh);

  $folderview = Yii::getPathOfAlias('webroot')."/protected/modules/".$cmd['modulename']."/views";
  if (!file_exists($folderview)) {
    mkdir($folderview, 0777);
  }
  $foldermenu = Yii::getPathOfAlias('webroot')."/protected/modules/".$cmd['modulename']."/views/".$menuname;
  if (!file_exists($foldermenu)) {
    mkdir($foldermenu, 0777);
  }
  $fileview = Yii::getPathOfAlias('webroot')."/protected/modules/".$cmd['modulename']."/views/".$menuname."/index.php";
  $fh = fopen($fileview, 'w');
  fwrite($fh, $cmd['viewcode']."\n");
  fclose($fh);

  $sql = "update menuaccess set isgen = 1 where menuname = '".$menuname."'";
  Yii::app()->db->createCommand($sql)->execute();
}
function GetParamValue($paramname) {
  $sql = "select paramvalue from parameter where paramname = '".$paramname."'";
  return Yii::app()->db->createCommand($sql)->queryScalar();
}
function GetAllCatalog() {
	$sql = 'SELECT catalogname,catalogval
		FROM catalogsys a 
		JOIN language b ON b.languageid = a.languageid 
		JOIN useraccess c ON c.languageid = b.languageid
		WHERE c.username = :user';
	$comm = Yii::app()->db->createCommand($sql);
	$comm->bindvalue(':user',Yii::app()->user->id,PDO::PARAM_STR);
	return $comm->queryAll();
}