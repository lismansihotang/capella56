<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'addressbookid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('common/customer/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('common/customer/getData'),
	'saveurl'=>Yii::app()->createUrl('common/customer/save'),
	'destroyurl'=>Yii::app()->createUrl('common/customer/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/customer/upload'),
	'downpdf'=>Yii::app()->createUrl('common/customer/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/customer/downxls'),
	'columns'=>"
		{
			field:'addressbookid',
			title:'". GetCatalog('addressbookid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title:'". GetCatalog('fullname') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'taxno',
			title:'". GetCatalog('taxno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'ktpno',
			title:'". GetCatalog('ktpno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'creditlimit',
			title:'". GetCatalog('creditlimit') ."',
			sortable: true,
			align:'right',
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'currentlimit',
			title:'". GetCatalog('currentlimit') ."',
			sortable: true,
			align:'right',
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'overdue',
			title:'". GetCatalog('overdue') ."',
			sortable: true,
			align:'right',
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'isstrictlimit',
			title:'". GetCatalog('isstrictlimit') ."',
			align:'center',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'salesareaid',
			title:'". GetCatalog('salesarea') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.areaname;
		}},
		{
			field:'pricecategoryid',
			title:'". GetCatalog('pricecategory') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.categoryname;
		}},
		{
			field:'groupcustomerid',
			title:'". GetCatalog('groupcustomer') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.groupname;
		}},
		{
			field:'bankaccountno',
			title:'". GetCatalog('bankaccountno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bankname',
			title:'". GetCatalog('bankname') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'accountowner',
			title:'". GetCatalog('accountowner') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
			align:'center',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
								return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
								return '';
				}
		}}",
	'searchfield'=> array ('addressbookid','fullname'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>". GetCatalog('fullname')."</td>
				<td><input class='easyui-textbox' id='customer-fullname' name='customer-fullname' style='width:400px' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('taxno')."</td>
				<td><input class='easyui-textbox' id='customer-taxno' name='customer-taxno' ></input></td>
			</tr>
            <tr>
				<td>". GetCatalog('ktpno')."</td>
				<td><input class='easyui-textbox' id='customer-ktpno' name='customer-ktpno' ></input></td>
			</tr>
            <tr>
				<td>". GetCatalog('creditlimit')."</td>
				<td><input id='customer-creditlimit' type='numberbox' name='customer-creditlimit' class='easyui-numberbox'				
				data-options=\"precision:2,decimalSeparator:',',groupSeparator:'.'\" value='999999999' style='width:250px'></select></td>
			</tr>
			<tr>
				<td>". GetCatalog('isstrictlimit')."</td>
				<td><input id='customer-isstrictlimit' type='checkbox' name='customer-isstrictlimit' style='width:250px'></select></td>
			</tr>
			<tr>
				<td>". GetCatalog('salesarea')."</td>
				<td><select class='easyui-combogrid' id='customer-salesareaid' name='customer-salesareaid' style='width:400px' data-options=\"
							panelWidth: '500px',
							idField: 'salesareaid',
							textField: 'areaname',
							required:true,
							url: '". Yii::app()->createUrl('common/salesarea/index',array('grid'=>true,'combo'=>true)) ."',
							method: 'get',
							mode: 'remote',
							columns: [[
									{field:'salesareaid',title:'". GetCatalog('salesareaid') ."',width:'80px'},
									{field:'areaname',title:'". GetCatalog('areaname') ."',width:'150px'},
							]],
							fitColumns: true
					\">
			</select></td>
			</tr>
			<tr>
				<td>". GetCatalog('pricecategory')."</td>
				<td><select class='easyui-combogrid' id='customer-pricecategoryid' name='customer-pricecategoryid' style='width:250px' data-options=\"
						panelWidth: 500,
						idField: 'pricecategoryid',
						textField: 'categoryname',
						required:true,
						url: '". Yii::app()->createUrl('common/pricecategory/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						mode: 'remote',
						columns: [[
								{field:'pricecategoryid',title:'". GetCatalog('pricecategoryid') ."',width:80},
								{field:'categoryname',title:'". GetCatalog('categoryname') ."',width:120},
						]],
						fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td>". GetCatalog('bankaccountno')."</td>
				<td><input class='easyui-textbox' id='customer-bankaccountno' name='customer-bankaccountno' ></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('bankname')."</td>
				<td><input class='easyui-textbox' id='customer-bankname' name='customer-bankname' ></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('accountowner')."</td>
				<td><input class='easyui-textbox' id='customer-accountowner' name='customer-accountowner' ></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('groupcustomer')."</td>
				<td><select class='easyui-combogrid' id='customer-groupcustomerid' name='customer-groupcustomerid' style='width:250px' data-options=\"
					panelWidth: '500px',
					idField: 'groupcustomerid',
					textField: 'groupname',
					required:true,
					url: '". Yii::app()->createUrl('common/groupcustomer/index',array('grid'=>true,'combo'=>true)) ."',
					method: 'get',
					mode: 'remote',
					columns: [[
							{field:'groupcustomerid',title:'". GetCatalog('groupcustomerid') ."',width:'80px'},
							{field:'groupname',title:'". GetCatalog('groupname') ."',width:'150px'},
					]],
					fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td>". GetCatalog('paymentmethod')."</td>
				<td><select class='easyui-combogrid' id='customer-paymentmethodid' name='customer-paymentmethodid' style='width:250px' data-options=\"
						panelWidth: 500,
						idField: 'paymentmethodid',
						textField: 'paycode',
						required:true,
						url: '". Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						mode: 'remote',
						columns: [[
								{field:'paymentmethodid',title:'". GetCatalog('paymentmethodid') ."',width:80},
								{field:'paycode',title:'". GetCatalog('paycode') ."',width:100},
								{field:'paydays',title:'". GetCatalog('paydays') ."',width:120},
								{field:'paymentname',title:'". GetCatalog('paymentname') ."',width:200},
						]],
						fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td>". GetCatalog('recordstatus')."</td>
				<td><input id='customer-recordstatus' name='customer-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'loadsuccess' => "
		$('#customer-fullname').textbox('setValue',data.fullname);
		$('#customer-taxno').textbox('setValue',data.taxno);
		$('#customer-ktpno').textbox('setValue',data.ktpno);
		$('#customer-creditlimit').numberbox('setValue',data.creditlimit);
		$('#customer-salesareaid').combogrid('setValue',data.salesareaid);
		$('#customer-pricecategoryid').combogrid('setValue',data.pricecategoryid);
		$('#customer-bankaccountno').textbox('setValue',data.bankaccountno);
		$('#customer-bankname').textbox('setValue',data.bankname);
		$('#customer-accountowner').textbox('setValue',data.accountowner);
		$('#customer-groupcustomerid').combogrid('setValue',data.groupcustomerid);
		$('#customer-paymentmethodid').combogrid('setValue',data.paymentmethodid);
		if (data.isstrictlimit == 1)
		{
			$('#customer-isstrictlimit').prop('checked', true);
		} else
		{
			$('#customer-isstrictlimit').prop('checked', false);
		}
		if (data.recordstatus == 1)
		{
			$('#customer-recordstatus').prop('checked', true);
		} else
		{
			$('#customer-recordstatus').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'address',
			'idfield'=>'addressid',
			'urlsub'=>Yii::app()->createUrl('common/customer/indexaddress',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('common/customer/searchaddress',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/customer/saveaddress',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('common/customer/saveaddress',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('common/customer/purgeaddress',array('grid'=>true)),
			'subs'=>"
				{field:'addresstypename',title:'". GetCatalog('addresstypename') ."',width:'200px'},
				{field:'addressname',title:'". GetCatalog('addressname') ."',width:'200px'},
				{field:'rt',title:'". GetCatalog('rt') ."',width:'200px'},
				{field:'rw',title:'". GetCatalog('rw') ."',width:'200px'},
				{field:'cityname',title:'". GetCatalog('cityname') ."',width:'200px'},
				{field:'phoneno',title:'". GetCatalog('phoneno') ."',width:'200px'},
				{field:'faxno',title:'". GetCatalog('faxno') ."',width:'200px'}
			",
			'columns'=>"
				{
					field:'addressbookid',
					title:'". GetCatalog('addressbookid') ."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'addressid',
					title:'". GetCatalog('addressid') ."',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'addresstypeid',
					title:'". GetCatalog('addresstype') ."',
					width:'200px',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'addresstypeid',
							textField:'addresstypename',
							url:'". $this->createUrl('addresstype/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '". GetCatalog('pleasewait')."',
							columns:[[
								{field:'addresstypeid',title:'". GetCatalog('addresstypeid')."',width:'80px'},
								{field:'addresstypename',title:'". GetCatalog('addresstypename')."',width:'200px'}
							]]
						}	
					},
					sortable: true,
					formatter: function(value,row,index){
						return row.addresstypename;
					}
				},
				{
					field:'addressname',
					title:'". GetCatalog('addressname') ."',
					width:'200px',
					editor:{
						type: 'textbox',
						options:{
							required:true,
							multiline:true
						}
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'rt',
					title:'". GetCatalog('rt') ."',
					width:'50px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'rw',
					title:'". GetCatalog('rw') ."',
					width:'50px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'cityid',
					title:'". GetCatalog('city') ."',
					width:'150px',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'cityid',
							textField:'cityname',
							url:'". Yii::app()->createUrl('admin/city/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '". GetCatalog('pleasewait')."',
							columns:[[
								{field:'cityid',title:'". GetCatalog('cityid')."',width:'50px'},
								{field:'cityname',title:'". GetCatalog('cityname')."',width:'200px'}
							]]
						}	
					},
					sortable: true,
					formatter: function(value,row,index){
						return row.cityname;
					}
				},
				{
					field:'phoneno',
					title:'". GetCatalog('phoneno') ."',
					width:'150px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'faxno',
					title:'". GetCatalog('faxno') ."',
					width:'150px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'lat',
					title:'". GetCatalog('lat') ."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'lng',
					title:'". GetCatalog('lng') ."',
					width:'150px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				}
			"
		),
		array(
			'id'=>'addresscontact',
			'idfield'=>'addresscontactid',
			'urlsub'=>Yii::app()->createUrl('common/customer/indexcontact',array('grid'=>true)),
			'url'=> Yii::app()->createUrl('common/customer/searchcontact',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/customer/savecontact',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('common/customer/savecontact',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('common/customer/purgecontact',array('grid'=>true)),
			'subs'=>"
				{field:'contacttypename',title:'". GetCatalog('contacttypename') ."',width:'200px'},
				{field:'addresscontactname',title:'". GetCatalog('addresscontactname') ."',width:'200px'},
				{field:'phoneno',title:'". GetCatalog('phoneno') ."',width:'200px'},
				{field:'mobilephone',title:'". GetCatalog('mobilephone') ."',width:'200px'},
				{field:'emailaddress',title:'". GetCatalog('emailaddress') ."',width:'200px'}
			",
			'columns'=>"
			{
				field:'addressbookid',
				width:'50px',
				hidden:true,
				formatter: function(value,row,index){
									return value;
				}
			},
			{
				field:'addresscontactid',
				title:'". GetCatalog('addresscontactid') ."',
				width:'50px',
				sortable: true,
				formatter: function(value,row,index){
									return value;
				}
			},
			{
				field:'contacttypeid',
				title:'". GetCatalog('contacttype') ."',
				width:'150px',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'contacttypeid',
						textField:'contacttypename',
						url:'". $this->createUrl('contacttype/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'contacttypeid',title:'". GetCatalog('contacttypeid')."',width:'80px'},
							{field:'contacttypename',title:'". GetCatalog('contacttypename')."',width:'200px'}
						]]
					}	
				},
				sortable: true,
				formatter: function(value,row,index){
										return row.contacttypename;
				}
			},
			{
				field:'addresscontactname',
				title:'". GetCatalog('addresscontactname') ."',
				width:'150px',
				editor:{
					type: 'textbox',
					options:{
						required:true,
					}
				},
				sortable: true,
				formatter: function(value,row,index){
										return value;
				}
			},
			{
				field:'phoneno',
				title:'". GetCatalog('phoneno') ."',
				width:'150px',
				editor:{
					type: 'textbox',
				},
				sortable: true,
				formatter: function(value,row,index){
										return value;
				}
			},
			{
				field:'mobilephone',
				title:'". GetCatalog('mobilephone') ."',
				width:'150px',
				editor:{
					type: 'textbox',
				},
				sortable: true,
				formatter: function(value,row,index){
										return value;
				}
			},
			{
				field:'emailaddress',
				title:'". GetCatalog('emailaddress') ."',
				width:'150px',
				editor:{
					type: 'textbox',
				},
				sortable: true,
				formatter: function(value,row,index){
										return value;
				}
			}	
			"
		),
	),	
));
