<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'employeeid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('hr/employee/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('hr/employee/getData'),
	'saveurl'=>Yii::app()->createUrl('hr/employee/save'),
	'destroyurl'=>Yii::app()->createUrl('hr/employee/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/employee/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/employee/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/employee/downxls'),
	'columns'=>"
		{
			field:'employeeid',
			title:'".getCatalog('employeeid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title:'".getCatalog('fullname') ."',
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'oldnik',
			title:'".getCatalog('oldnik') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'positionid',
			title:'".getCatalog('position') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.positionname;
		}},
		{
			field:'employeetypeid',
			title:'".getCatalog('employeetype') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.employeetypename;
		}},
		{
			field:'sexid',
			title:'".getCatalog('sex') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.sexname;
		}},
		{
			field:'birthcityid',
			title:'".getCatalog('birthcity') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.birthcityname;
		}},
		{
			field:'birthdate',
			title:'".getCatalog('birthdate') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'religionid',
			title:'".getCatalog('religion') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.religionname;
		}},
		{
			field:'maritalstatusid',
			title:'".getCatalog('maritalstatus') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.maritalstatusname;
		}},
		{
			field:'referenceby',
			title:'".getCatalog('referenceby') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'joindate',
			title:'".getCatalog('joindate') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeestatusid',
			title:'".getCatalog('employeestatus') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.employeestatusname;
		}},
			{
			field:'istrial',
			title:'".getCatalog('istrial') ."',
			width:'80px',
			align:'center',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'barcode',
			title:'".getCatalog('barcode') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'photo',
			title:'".getCatalog('photo') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'resigndate',
			title:'".getCatalog('resigndate') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == '01-01-1970') {
					return '';
				} else {
					return value;
				}
		}},
		{
			field:'levelorgid',
			title:'".getCatalog('levelorg') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.levelorgname;
		}},
		{
			field:'email',
			title:'".getCatalog('email') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'phoneno',
			title:'".getCatalog('phoneno') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'alternateemail',
			title:'".getCatalog('alternateemail') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'hpno',
			title:'".getCatalog('hpno') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'taxno',
			title:'".getCatalog('taxno') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'dplkno',
			title:'".getCatalog('dplkno') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'hpno2',
			title:'".getCatalog('hpno2') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'accountno',
			title:'".getCatalog('accountno') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}}",
	'searchfield'=> array ('employeeid','fullname','oldnik','positionname','levelorgname'),
	'headerform'=> "
		<input type='hidden' name='employee-addressbookid' id='employee-addressbookid'></input>
		<table cellpadding='5'>
			<tr>
				<td>".getCatalog('fullname')."</td>
				<td><input class='easyui-textbox' id='employee-fullname' name='employee-fullname' data-options='required:true'></input></td>
				<td>".getCatalog('oldnik')."</td>
				<td><input class='easyui-textbox' id='employee-oldnik' name='employee-oldnik' ></input></td>
			</tr>
			<tr>
				<td>".getCatalog('position')."</td>
				<td><select class='easyui-combogrid' id='employee-positionid' name='employee-positionid' style='width:250px' data-options=\"
					panelWidth: '500px',
					required: false,
					idField: 'positionid',
					textField: 'positionname',
					mode:'remote',
					url: '".Yii::app()->createUrl('hr/position/index',array('grid'=>true,'combo'=>true)) ."',
					method: 'get',
					columns: [[
						{field:'positionid',title:'".getCatalog('positionid') ."',width:'50px'},
						{field:'positionname',title:'".getCatalog('positionname') ."',width:'150px'},
					]],
					fitColumns: true\">
				</select></td>
				<td>".getCatalog('employeetype')."</td>
				<td><select class='easyui-combogrid' id='employee-employeetypeid' name='employee-employeetypeid' style='width:250px' data-options=\"
								panelWidth: '500px',
								required: false,
								idField: 'employeetypeid',
								textField: 'employeetypename',
								mode:'remote',
								url: '".Yii::app()->createUrl('hr/employeetype/index',array('grid'=>true,'combo'=>true)) ."',
								method: 'get',
								columns: [[
									{field:'employeetypeid',title:'".getCatalog('employeetypeid') ."',width:'80px'},
									{field:'employeetypename',title:'".getCatalog('employeetypename') ."',width:'200px'},
								]],
								fitColumns: true\">
				</select></td>			
			</tr>
			<tr>
				<td>".getCatalog('sex')."</td>
				<td><select class='easyui-combogrid' id='employee-sexid' name='employee-sexid' style='width:250px' data-options=\"
								panelWidth: '500px',
								required: false,
								idField: 'sexid',
								textField: 'sexname',
								mode:'remote',
								url: '".Yii::app()->createUrl('hr/sex/index',array('grid'=>true,'combo'=>true)) ."',
								method: 'get',
								columns: [[
										{field:'sexid',title:'".getCatalog('sexid') ."',width:'80px'},
										{field:'sexname',title:'".getCatalog('sexname') ."',width:'150px'},
								]],
								fitColumns: true\">
				</select></td>
				<td>".getCatalog('religion')."</td>
				<td><select class='easyui-combogrid' id='employee-religionid' name='employee-religionid' style='width:250px' data-options=\"
								panelWidth: '500px',
								required: false,
								idField: 'religionid',
								textField: 'religionname',
								mode:'remote',
								url: '".Yii::app()->createUrl('hr/religion/index',array('grid'=>true,'combo'=>true)) ."',
								method: 'get',
								columns: [[
										{field:'religionid',title:'".getCatalog('religionid') ."',width:'80px'},
										{field:'religionname',title:'".getCatalog('religionname') ."',width:'150px'},
								]],
								fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('birthcity')."</td>
				<td><select class='easyui-combogrid' id='employee-birthcityid' name='employee-birthcityid' style='width:250px' data-options=\"
								panelWidth: '500px',
								required: false,
								idField: 'cityid',
								textField: 'cityname',
								mode:'remote',
								url: '".Yii::app()->createUrl('admin/city/index',array('grid'=>true,'combo'=>true)) ."',
								method: 'get',
								columns: [[
									{field:'cityid',title:'".getCatalog('cityid') ."',width:'80px'},
									{field:'cityname',title:'".getCatalog('cityname') ."',width:'150px'},
								]],
								fitColumns: true\">
				</select></td>
				<td>".getCatalog('birthdate')."</td>
				<td><input class='easyui-datebox' type='text' id='employee-birthdate' name='employee-birthdate' data-options='formatter:dateformatter,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('maritalstatus')."</td>
				<td><select class='easyui-combogrid' id='employee-maritalstatusid' name='employee-maritalstatusid' style='width:250px' data-options=\"
								panelWidth: '500px',
								required: false,
								idField: 'maritalstatusid',
								textField: 'maritalstatusname',
								mode:'remote',
								url: '".Yii::app()->createUrl('hr/maritalstatus/index',array('grid'=>true,'combo'=>true)) ."',
								method: 'get',
								columns: [[
										{field:'maritalstatusid',title:'".getCatalog('maritalstatusid') ."',width:'80px'},
										{field:'maritalstatusname',title:'".getCatalog('maritalstatusname') ."',width:'150px'},
								]],
								fitColumns: true\">
				</select></td>
				<td>".getCatalog('employeestatus')."</td>
				<td><select class='easyui-combogrid' id='employee-employeestatusid' name='employee-employeestatusid' style='width:250px' data-options=\"
								panelWidth: '500px',
								required: false,
								idField: 'employeestatusid',
								textField: 'employeestatusname',
								mode:'remote',
								url: '".Yii::app()->createUrl('hr/employeestatus/index',array('grid'=>true,'combo'=>true)) ."',
								method: 'get',
								columns: [[
									{field:'employeestatusid',title:'".getCatalog('employeestatusid') ."',width:'80px'},
									{field:'employeestatusname',title:'".getCatalog('employeestatusname') ."',width:'200px'},
								]],
								fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('referenceby')."</td>
				<td><input class='easyui-textbox' id='employee-referenceby' name='employee-referenceby' ></input></td>
				<td>".getCatalog('joindate')."</td>
				<td><input class='easyui-datebox' type='text' id='employee-joindate' name='employee-joindate' data-options='formatter:dateformatter,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('istrial')."</td>
				<td><input id='employee-istrial' name='employee-istrial' class='easyui-checkbox'></input></td>
				<td>".getCatalog('barcode')."</td>
				<td><input class='easyui-textbox' id='employee-barcode' name='employee-barcode' ></input></td>
			</tr>
			<tr>
				<td>".getCatalog('photo')."</td>
				<td><input class='easyui-textbox' id='employee-photo' name='employee-photo' ></input></td>
				<td>".getCatalog('resigndate')."</td>
				<td><input class='easyui-datebox' type='text' id='employee-resigndate' name='employee-resigndate' data-options='formatter:dateformatter,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('levelorg')."</td>
				<td><select class='easyui-combogrid' id='employee-levelorgid' name='employee-levelorgid' style='width:250px' data-options=\"
								panelWidth: '500px',
								required: false,
								idField: 'levelorgid',
								textField: 'employeestatusname',
								mode:'remote',
								url: '".Yii::app()->createUrl('hr/levelorg/index',array('grid'=>true,'combo'=>true)) ."',
								method: 'get',
								columns: [[
										{field:'levelorgid',title:'".getCatalog('levelorgid') ."',width:'80px'},
										{field:'levelorgname',title:'".getCatalog('levelorgname') ."',width:'150px'},
								]],
								fitColumns: true\">
				</select></td>
				<td>".getCatalog('email')."</td>
				<td><input class='easyui-textbox' id='employee-email' name='employee-email' ></input></td>
				<td>".getCatalog('alternateemail')."</td>
				<td><input class='easyui-textbox' id='employee-alternateemail' name='employee-alternateemail' ></input></td>
			</tr>
			<tr>
				<td>".getCatalog('phoneno')."</td>
				<td><input class='easyui-textbox' id='employee-phoneno' name='employee-phoneno' ></input></td>
				<td>".getCatalog('hpno')."</td>
				<td><input class='easyui-textbox' id='employee-hpno' name='employee-hpno' ></input></td>
			</tr>
			<tr>
				<td>".getCatalog('taxno')."</td>
				<td><input class='easyui-textbox' id='employee-taxno' name='employee-taxno' ></input></td>
				<td>".getCatalog('dplkno')."</td>
				<td><input class='easyui-textbox' id='employee-dplkno' name='employee-dplkno' ></input></td>
			</tr>
			<tr>
				<td>".getCatalog('hpno2')."</td>
				<td><input class='easyui-textbox' id='employee-hpno2' name='employee-hpno2' ></input></td>
				<td>".getCatalog('accountno')."</td>
				<td><input class='easyui-textbox' id='employee-accountno' name='employee-accountno' ></input></td>
			</tr>
			<tr>
				<td>".getCatalog('recordstatus')."</td>
				<td><input class='easyui-checkbox' id='employee-recordstatus' name='employee-recordstatus' ></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#employee-addressbookid').val(data.addressbookid);
	",
	'loadsuccess'=>"
		$('#employee-addressbookid').val(data.addressbookid);
		$('#employee-fullname').textbox('setValue',data.fullname);
		$('#employee-oldnik').textbox('setValue',data.oldnik);
		$('#employee-positionid').combogrid('setValue',data.positionid);
		$('#employee-employeetypeid').combogrid('setValue',data.employeetypeid);
		$('#employee-sexid').combogrid('setValue',data.sexid);
		$('#employee-religionid').combogrid('setValue',data.religionid);
		$('#employee-birthcityid').combogrid('setValue',data.birthcityid);
		$('#employee-birthdate').datebox('setValue',data.birthdate);
		$('#employee-maritalstatusid').combogrid('setValue',data.maritalstatusid);
		$('#employee-employeestatusid').combogrid('setValue',data.employeestatusid);
		$('#employee-referenceby').textbox('setValue',data.referenceby);
		$('#employee-joindate').datebox('setValue',data.joindate);
		$('#employee-barcode').textbox('setValue',data.barcode);
		$('#employee-photo').textbox('setValue',data.photo);
		$('#employee-resigndate').datebox('setValue',data.resigndate);
		$('#employee-levelorgid').combogrid('setValue',data.levelorgid);
		$('#employee-email').textbox('setValue',data.email);
		$('#employee-alternateemail').textbox('setValue',data.alternateemail);
		$('#employee-phoneno').textbox('setValue',data.phoneno);
		$('#employee-hpno').textbox('setValue',data.hpno);
		$('#employee-taxno').textbox('setValue',data.taxno);
		$('#employee-dplkno').textbox('setValue',data.dplkno);
		$('#employee-hpno2').textbox('setValue',data.hpno2);
		$('#employee-accountno').textbox('setValue',data.accountno);
		if (data.istrial == 1) {
			$('#employee-istrial').checkbox('check');
		} else {
			$('#employee-istrial').checkbox('uncheck');
		}
		if (data.recordstatus == 1) {
			$('#employee-recordstatus').checkbox('check');
		} else {
			$('#employee-recordstatus').checkbox('uncheck');
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'identitytype',
			'idfield'=>'employeeidentityid',
			'urlsub'=>Yii::app()->createUrl('hr/employee/indexdetail',array('grid'=>true,'combo'=>true)),
			'url'=>Yii::app()->createUrl('hr/employee/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('hr/employee/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('hr/employee/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('hr/employee/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'identitytypename',title:'".getCatalog('identitytypename') ."',width:'200px'},
				{field:'identityname',title:'".getCatalog('identityname') ."',width:'200px'},
				{field:'recordstatus',title:'".getCatalog('recordstatus') ."',width:'80px'},
			",
			'columns'=>"
				{
					field:'employeeid',
					title:'".getCatalog('employeeid') ."',
					width:'50px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'employeeidentityid',
					title:'".getCatalog('employeeidentityid') ."',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'identitytypeid',
					title:'".getCatalog('identitytype') ."',
					width:'150px',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'identitytypeid',
							textField:'identitytypename',
							url:'".Yii::app()->createUrl('common/identitytype/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'identitytypeid',title:'".getCatalog('identitytypeid')."',width:'50px'},
								{field:'identitytypename',title:'".getCatalog('identitytypename')."',width:'200px'}
							]]
						}	
					},
					sortable: true,
					formatter: function(value,row,index){
										return row.identitytypename;
					}
				},
				{
					field:'identityname',
					title:'".getCatalog('identityname') ."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'recordstatus',
					title:'".getCatalog('recordstatus') ."',
					width:'80px',
					align:'center',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
						} else {
							return '';
						}
					}
				}
			"
		),
		array(
			'id'=>'employeefamily',
			'idfield'=>'employeefamilyid',
			'urlsub'=>Yii::app()->createUrl('hr/employee/indexfamily',array('grid'=>true)),
			'url'=> Yii::app()->createUrl('hr/employee/searchfamily',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('hr/employee/savefamily',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('hr/employee/savefamily',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('hr/employee/purgefamily',array('grid'=>true)),
			'subs'=>"
				{field:'familyrelationname',title:'".getCatalog('familyrelationname') ."',width:'100px'},
				{field:'familyname',title:'".getCatalog('familyname') ."',width:'200px'},
				{field:'sexname',title:'".getCatalog('sexname') ."',width:'100px'},
				{field:'cityname',title:'".getCatalog('cityname') ."',width:'100px'},
				{field:'birthdate',title:'".getCatalog('birthdate') ."',width:'100px'},
				{field:'educationname',title:'".getCatalog('educationname') ."',width:'100px'},
				{field:'occupationname',title:'".getCatalog('occupationname') ."',width:'100px'},
				{field:'recordstatus',title:'".getCatalog('recordstatus') ."',width:'80px'}
			",
			'columns'=>"
			{
				field:'employeeid',
				title:'".getCatalog('employeeid') ."',
				width:'50px',
				hidden:true,
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'employeefamilyid',
				title:'".getCatalog('employeefamilyid') ."',
				width:'50px',
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'familyrelationid',
				title:'".getCatalog('familyrelation') ."',
				width:'150px',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'familyrelationid',
						textField:'familyrelationname',
						url:'".$this->createUrl('familyrelation/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						loadMsg: '".getCatalog('pleasewait')."',
						columns:[[
							{field:'familyrelationid',title:'".getCatalog('familyrelationid')."',width:'50px'},
							{field:'familyrelationname',title:'".getCatalog('familyrelationname')."',width:'200px'}
						]]
					}	
				},
				sortable: true,
				formatter: function(value,row,index){
					return row.familyrelationname;
				}
			},
			{
				field:'familyname',
				title:'".getCatalog('familyname') ."',
				width:'150px',
				editor:'text',
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'sexid',
				title:'".getCatalog('sex') ."',
				width:'150px',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'sexid',
						textField:'sexname',
						url:'".$this->createUrl('sex/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						loadMsg: '".getCatalog('pleasewait')."',
						columns:[[
							{field:'sexid',title:'".getCatalog('sexid')."',width:'50px'},
							{field:'sexname',title:'".getCatalog('sexname')."',width:'200px'}
						]]
					}	
				},
				sortable: true,
				formatter: function(value,row,index){
					return row.sexname;
				}
			},
			{
				field:'cityid',
				title:'".getCatalog('city') ."',
				width:'150px',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'cityid',
						textField:'cityname',
						url:'".Yii::app()->createUrl('admin/city/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						loadMsg: '".getCatalog('pleasewait')."',
						columns:[[
							{field:'cityid',title:'".getCatalog('cityid')."',width:'50px'},
							{field:'cityname',title:'".getCatalog('cityname')."',width:'200px'}
						]]
					}	
				},
				sortable: true,
				formatter: function(value,row,index){
					return row.cityname;
				}
			},
			{
				field:'birthdate',
				title:'".getCatalog('birthdate') ."',
				sortable: true,
				editor: {
					type: 'datebox',
					options:{
						formatter:dateformatter,
						required:true,
						parser:dateparser
					}
				},
				width:'100px',			
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'educationid',
				title:'".getCatalog('education') ."',
				width:'150px',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'educationid',
						textField:'educationname',
						url:'".$this->createUrl('education/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						loadMsg: '".getCatalog('pleasewait')."',
						columns:[[
							{field:'educationid',title:'".getCatalog('educationid')."',width:'50px'},
							{field:'educationname',title:'".getCatalog('educationname')."',width:'200px'}
						]]
					}	
				},
				sortable: true,
				formatter: function(value,row,index){
					return row.educationname;
				}
			},
			{
				field:'occupationid',
				title:'".getCatalog('occupation') ."',
				width:'150px',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'occupationid',
						textField:'occupationname',
						url:'".$this->createUrl('occupation/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						loadMsg: '".getCatalog('pleasewait')."',
						columns:[[
							{field:'occupationid',title:'".getCatalog('occupationid')."',width:'50px'},
							{field:'occupationname',title:'".getCatalog('occupationname')."',width:'200px'}
						]]
					}	
				},
				sortable: true,
				formatter: function(value,row,index){
					return row.occupationname;
				}
			},
			{
				field:'recordstatus',
				title:'".getCatalog('recordstatus') ."',
				width:'80px',
				align:'center',
				editor:{type:'checkbox',options:{on:'1',off:'0'}},
				sortable: true,
				formatter: function(value,row,index){
					if (value == 1){
						return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
					} else {
						return '';
					}
				}},	
			"
		),
		array(
			'id'=>'education',
			'idfield'=>'employeeeducationid',
			'urlsub'=>Yii::app()->createUrl('hr/employee/indexeducation',array('grid'=>true,'combo'=>true)),
			'url'=>Yii::app()->createUrl('hr/employee/searcheducation',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('hr/employee/saveeducation',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('hr/employee/saveeducation',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('hr/employee/purgeeducation',array('grid'=>true)),
			'subs'=>"
				{field:'educationname',title:'".getCatalog('educationname')."',width:'150px'},
				{field:'schoolname',title:'".getCatalog('schoolname')."',width:'150px'},
				{field:'yeargraduate',title:'".getCatalog('yeargraduate')."',width:'100px'},
				{field:'cityname',title:'".getCatalog('cityname')."',width:'150px'},
				{field:'isdiploma',title:'".getCatalog('isdiploma')."',width:'80px'},
				{field:'schooldegree',title:'".getCatalog('schooldegree')."',width:'100px'},
				{field:'recordstatus',title:'".getCatalog('recordstatus')."',width:'80px'}
			",
			'columns'=>"
				{
					field:'employeeid',
					title:'".getCatalog('employeeid')."',
					width:'50px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'employeeeducationid',
					title:'".getCatalog('employeeeducationid')."',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'educationid',
					title:'".getCatalog('education')."',
					width:'120px',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'educationid',
							textField:'educationname',
							url:'".$this->createUrl('education/index',array('grid'=>true,'combo'=>true))."',
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'educationid',title:'".getCatalog('educationid')."',width:'80px'},
								{field:'educationname',title:'".getCatalog('educationname')."',width:'200px'}
							]]
						}	
					},
					sortable: true,
					formatter: function(value,row,index){
						return row.educationname;
					}
				},
				{
					field:'schoolname',
					title:'".getCatalog('schoolname')."',
					width:150,
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
        {
					field:'cityid',
					title:'".getCatalog('city')."',
					width:'120px',
					editor:{
							type:'combogrid',
							options:{
								panelWidth:'500px',
								mode : 'remote',
								method:'get',
								idField:'cityid',
								textField:'cityname',
								url:'".Yii::app()->createUrl('admin/city/index',array('grid'=>true,'combo'=>true))."',
								fitColumns:true,
								loadMsg: '".getCatalog('pleasewait')."',
								columns:[[
									{field:'cityid',title:'".getCatalog('cityid')."',width:'50px'},
									{field:'cityname',title:'".getCatalog('cityname')."',width:'200px'}
								]]
							}	
						},
					sortable: true,
					formatter: function(value,row,index){
						return row.cityname;
					}
				},
        {
					field:'yeargraduate',
					title:'".getCatalog('yeargraduate')."',
					sortable: true,
					editor: {
					type: 'datebox',
						options:{
							formatter:dateformatter,
							required:true,
							parser:dateparser
						}
					},
					width:'100px',			
					formatter: function(value,row,index){
						return value;
					}
				},
        {
					field:'isdiploma',
					title:'".getCatalog('isdiploma')."',
					width:'80px',
					align:'center',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
						} else {
							return '';
						}
					}},
        {
					field:'schooldegree',
					title:'".getCatalog('schooldegree')."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
        {
					field:'recordstatus',
					title:'".getCatalog('recordstatus')."',
					width:'80px',
					align:'center',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
						} else {
							return '';
						}
				}},
			"
		),
		array(
			'id'=>'informal',
			'idfield'=>'employeeinformalid',
			'urlsub'=>Yii::app()->createUrl('hr/employee/indexinformal',array('grid'=>true,'combo'=>true)),
			'url'=>Yii::app()->createUrl('hr/employee/searchinformal',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('hr/employee/saveinformal',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('hr/employee/saveinformal',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('hr/employee/purgeinformal',array('grid'=>true)),
			'subs'=>"
				{field:'informalname',title:'".getCatalog('informalname') ."',width:'150px'},
				{field:'organizer',title:'".getCatalog('organizer') ."',width:'200px'},
				{field:'period',title:'".getCatalog('period') ."',width:'80px'},
				{field:'isdiploma',title:'".getCatalog('isdiploma') ."',width:'80px'},
				{field:'sponsoredby',title:'".getCatalog('sponsoredby') ."',width:'100px'},
				{field:'recordstatus',title:'".getCatalog('recordstatus') ."',width:'80px'}
			",
			'columns'=>"
				{
					field:'employeeid',
					title:'".getCatalog('employeeid') ."',
					width:'50px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'employeeinformalid',
					title:'".getCatalog('employeeinformalid') ."',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'informalname',
					title:'".getCatalog('informalname') ."',
					width:150,
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
        {
					field:'organizer',
					title:'".getCatalog('organizer') ."',
					width:150,
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
        {
					field:'period',
					title:'".getCatalog('period') ."',
					width:150,
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
        {
					field:'isdiploma',
					title:'".getCatalog('isdiploma') ."',
					width:80,
					align:'center',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
						} else {
							return '';
						}
					}},
        {
					field:'sponsoredby',
					title:'".getCatalog('sponsoredby') ."',
					width:150,
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
        {
					field:'recordstatus',
					title:'".getCatalog('recordstatus') ."',
					width:80,
					align:'center',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
						} else {
							return '';
						}
					}},
			"
		),
		array(
			'id'=>'workexperience',
			'idfield'=>'employeewoid',
			'urlsub'=>Yii::app()->createUrl('hr/employee/indexwo',array('grid'=>true,'combo'=>true)),
			'url'=>Yii::app()->createUrl('hr/employee/searchwo',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('hr/employee/savewo',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('hr/employee/savewo',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('hr/employee/purgewo',array('grid'=>true)),
			'subs'=>"
				{field:'informalname',title:'".getCatalog('informalname') ."',width:'150px'},
				{field:'organizer',title:'".getCatalog('organizer') ."',width:'200px'},
				{field:'period',title:'".getCatalog('period') ."',width:'80px'},
				{field:'isdiploma',title:'".getCatalog('isdiploma') ."',width:'80px'},
				{field:'sponsoredby',title:'".getCatalog('sponsoredby') ."',width:'100px'},
				{field:'recordstatus',title:'".getCatalog('recordstatus') ."',width:'80px'}
			",
			'columns'=>"
				{
					field:'employeeid',
					title:'".getCatalog('employeeid') ."',
					width:'50px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'employeewoid',
					title:'".getCatalog('employeewoid') ."',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'informalname',
					title:'".getCatalog('informalname') ."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
        {
					field:'organizer',
					title:'".getCatalog('organizer') ."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
        {
					field:'period',
					title:'".getCatalog('period') ."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
        {
					field:'isdiploma',
					title:'".getCatalog('isdiploma') ."',
					width:'80px',
					align:'center',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
						} else {
							return '';
						}
					}},
        {
					field:'sponsoredby',
					title:'".getCatalog('sponsoredby') ."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
        {
					field:'recordstatus',
					title:'".getCatalog('recordstatus') ."',
					width:80,
					align:'center',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
						} else {
							return '';
						}
					}},
			"
		),
		array(
			'id'=>'orgstructure',
			'idfield'=>'employeeorgstrucid',
			'urlsub'=>Yii::app()->createUrl('hr/employee/indexorgstruc',array('grid'=>true,'combo'=>true)),
			'url'=>Yii::app()->createUrl('hr/employee/searchorgstruc',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('hr/employee/saveorgstruc'),
			'updateurl'=>Yii::app()->createUrl('hr/employee/saveorgstruc'),
			'destroyurl'=>Yii::app()->createUrl('hr/employee/purgeorgstruc'),
			'subs'=>"
				{field:'structurename',title:'".getCatalog('orgstructure') ."',width:'250px'},
			",
			'columns'=>"
				{
					field:'employeeid',
					title:'".getCatalog('employeeid') ."',
					width:'50px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'employeeorgstrucid',
					title:'".getCatalog('employeeorgstrucid') ."',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'orgstructureid',
					title:'".getCatalog('orgstructure')."',
					width:'250px',
					editor:{
							type:'combogrid',
							options:{
								panelWidth:'600px',
								mode : 'remote',
								method:'get',
								idField:'orgstructureid',
								textField:'structurename',
								url:'".Yii::app()->createUrl('hr/orgstructure/index',array('grid'=>true,'combo'=>true))."',
								fitColumns:true,
								loadMsg: '".getCatalog('pleasewait')."',
								columns:[[
									{field:'orgstructureid',title:'".getCatalog('orgstructureid')."',width:'50px'},
									{field:'companyname',title:'".getCatalog('companyname')."',width:'200px'},
									{field:'structurename',title:'".getCatalog('structurename')."',width:'350px'}
								]]
							}	
						},
					sortable: true,
					formatter: function(value,row,index){
						return row.structurename;
					}
				},
				{
					field:'recordstatus',
					title:'".getCatalog('recordstatus') ."',
					width:80,
					align:'center',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
						} else {
							return '';
						}
					}},
			"
		),
	),	
));