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
	'downdoc'=>Yii::app()->createUrl('common/customer/downdoc'),
	'columns'=>"
		{
			field:'addressbookid',
			title: localStorage.getItem('catalogaddressbookid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title: localStorage.getItem('catalogfullname'),
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'taxno',
			title: localStorage.getItem('catalogtaxno'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'ktpno',
			title: localStorage.getItem('catalogktpno'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'creditlimit',
			title: localStorage.getItem('catalogcreditlimit'),
			sortable: true,
			align:'right',
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'currentlimit',
			title: localStorage.getItem('catalogcurrentlimit'),
			sortable: true,
			align:'right',
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'overdue',
			title: localStorage.getItem('catalogoverdue'),
			sortable: true,
			align:'right',
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'isstrictlimit',
			title: localStorage.getItem('catalogisstrictlimit'),
			align:'center',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'salesareaid',
			title: localStorage.getItem('catalogsalesarea'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.areaname;
		}},
		{
			field:'pricecategoryid',
			title: localStorage.getItem('catalogpricecategory'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.categoryname;
		}},
		{
			field:'groupcustomerid',
			title: localStorage.getItem('cataloggroupcustomer'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.groupname;
		}},
		{
			field:'bankaccountno',
			title: localStorage.getItem('catalogbankaccountno'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bankname',
			title: localStorage.getItem('catalogbankname'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'accountowner',
			title: localStorage.getItem('catalogaccountowner'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title: localStorage.getItem('catalogrecordstatus'),
			align:'center',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
								return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
								return '';
				}
		}}",
	'searchfield'=> array ('addressbookid','fullname'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td id='customertext-fullname'></td>
				<td><input class='easyui-textbox' id='customer-fullname' name='customer-fullname' style='width:400px' data-options='required:true'></input></td>
			</tr>
			<tr>
				<td id='customertext-taxno'></td>
				<td><input class='easyui-textbox' id='customer-taxno' name='customer-taxno' ></input></td>
			</tr>
      <tr>
				<td id='customertext-ktpno'></td>
				<td><input class='easyui-textbox' id='customer-ktpno' name='customer-ktpno' ></input></td>
			</tr>
      <tr>
				<td id='customertext-creditlimit'></td>
				<td><input id='customer-creditlimit' type='numberbox' name='customer-creditlimit' class='easyui-numberbox'				
				data-options=\"precision:2,decimalSeparator:',',groupSeparator:'.'\" value='999999999' style='width:250px'></select></td>
			</tr>
			<tr>
				<td id='customertext-isstrictlimit'></td>
				<td><input id='customer-isstrictlimit' type='checkbox' name='customer-isstrictlimit' style='width:250px'></select></td>
			</tr>
			<tr>
				<td id='customertext-salesarea'></td>
				<td><select class='easyui-combogrid' id='customer-salesareaid' name='customer-salesareaid' style='width:400px' data-options=\"
							panelWidth: '500px',
							idField: 'salesareaid',
							textField: 'areaname',
							required:true,
							url: '". Yii::app()->createUrl('common/salesarea/index',array('grid'=>true,'combo'=>true)) ."',
							method: 'get',
							mode: 'remote',
							columns: [[
									{field:'salesareaid',title: localStorage.getItem('catalogsalesareaid'),width:'80px'},
									{field:'areaname',title: localStorage.getItem('catalogareaname'),width:'150px'},
							]],
							fitColumns: true
					\">
			</select></td>
			</tr>
			<tr>
				<td id='customertext-pricecategory'></td>
				<td><select class='easyui-combogrid' id='customer-pricecategoryid' name='customer-pricecategoryid' style='width:250px' data-options=\"
						panelWidth: 500,
						idField: 'pricecategoryid',
						textField: 'categoryname',
						required:true,
						url: '". Yii::app()->createUrl('common/pricecategory/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						mode: 'remote',
						columns: [[
								{field:'pricecategoryid',title: localStorage.getItem('catalogpricecategoryid'),width:80},
								{field:'categoryname',title: localStorage.getItem('catalogcategoryname'),width:120},
						]],
						fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td id='customertext-bankaccountno'></td>
				<td><input class='easyui-textbox' id='customer-bankaccountno' name='customer-bankaccountno' ></input></td>
			</tr>
			<tr>
				<td id='customertext-bankname'></td>
				<td><input class='easyui-textbox' id='customer-bankname' name='customer-bankname' ></input></td>
			</tr>
			<tr>
				<td id='customertext-accountowner'></td>
				<td><input class='easyui-textbox' id='customer-accountowner' name='customer-accountowner' ></input></td>
			</tr>
			<tr>
				<td id='customertext-groupcustomer'></td>
				<td><select class='easyui-combogrid' id='customer-groupcustomerid' name='customer-groupcustomerid' style='width:250px' data-options=\"
					panelWidth: '500px',
					idField: 'groupcustomerid',
					textField: 'groupname',
					required:true,
					url: '". Yii::app()->createUrl('common/groupcustomer/index',array('grid'=>true,'combo'=>true)) ."',
					method: 'get',
					mode: 'remote',
					columns: [[
							{field:'groupcustomerid',title: localStorage.getItem('cataloggroupcustomerid'),width:'80px'},
							{field:'groupname',title: localStorage.getItem('cataloggroupname'),width:'150px'},
					]],
					fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td id='customertext-paymentmethod'></td>
				<td><select class='easyui-combogrid' id='customer-paymentmethodid' name='customer-paymentmethodid' style='width:250px' data-options=\"
						panelWidth: 500,
						idField: 'paymentmethodid',
						textField: 'paycode',
						required:true,
						url: '". Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						mode: 'remote',
						columns: [[
								{field:'paymentmethodid',title: localStorage.getItem('catalogpaymentmethodid'),width:'80px'},
								{field:'paycode',title: localStorage.getItem('catalogpaycode'),width:'100px'},
								{field:'paydays',title: localStorage.getItem('catalogpaydays'),width:'120px'},
								{field:'paymentname',title: localStorage.getItem('catalogpaymentname'),width:'200px'},
						]],
						fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td id='customertext-recordstatus'></td>
				<td><input id='customer-recordstatus' name='customer-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
  ",
  'addonscripts'=>"
    $(document).ready(function(){
      var parel = document.getElementById('customertext-fullname');
      parel.innerHTML = localStorage.getItem('catalogfullname');
      parel = document.getElementById('customertext-taxno');
      parel.innerHTML = localStorage.getItem('catalogtaxno');
      parel = document.getElementById('customertext-ktpno');
      parel.innerHTML = localStorage.getItem('catalogktpno');
      parel = document.getElementById('customertext-creditlimit');
      parel.innerHTML = localStorage.getItem('catalogcreditlimit');
      parel = document.getElementById('customertext-isstrictlimit');
      parel.innerHTML = localStorage.getItem('catalogisstrictlimit');
      parel = document.getElementById('customertext-salesarea');
      parel.innerHTML = localStorage.getItem('catalogsalesarea');
      parel = document.getElementById('customertext-pricecategory');
      parel.innerHTML = localStorage.getItem('catalogpricecategory');
      parel = document.getElementById('customertext-bankaccountno');
      parel.innerHTML = localStorage.getItem('catalogbankaccountno');
      parel = document.getElementById('customertext-bankname');
      parel.innerHTML = localStorage.getItem('catalogbankname');
      parel = document.getElementById('customertext-accountowner');
      parel.innerHTML = localStorage.getItem('catalogaccountowner');
      parel = document.getElementById('customertext-groupcustomer');
      parel.innerHTML = localStorage.getItem('cataloggroupcustomer');
      parel = document.getElementById('customertext-paymentmethod');
      parel.innerHTML = localStorage.getItem('catalogpaymentmethod');
      parel = document.getElementById('customertext-recordstatus');
      parel.innerHTML = localStorage.getItem('catalogrecordstatus');
    });
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
				{field:'addresstypename',title: localStorage.getItem('catalogaddresstypename'),width:'200px'},
				{field:'addressname',title: localStorage.getItem('catalogaddressname'),width:'200px'},
				{field:'rt',title: localStorage.getItem('catalogrt'),width:'200px'},
				{field:'rw',title: localStorage.getItem('catalogrw'),width:'200px'},
				{field:'cityname',title: localStorage.getItem('catalogcityname'),width:'200px'},
				{field:'phoneno',title: localStorage.getItem('catalogphoneno'),width:'200px'},
				{field:'faxno',title: localStorage.getItem('catalogfaxno'),width:'200px'}
			",
			'columns'=>"
				{
					field:'addressbookid',
					title: localStorage.getItem('catalogaddressbookid'),
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'addressid',
					title: localStorage.getItem('catalogaddressid'),
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'addresstypeid',
					title: localStorage.getItem('catalogaddresstype'),
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
							loadMsg:  localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'addresstypeid',title: localStorage.getItem('catalogaddresstypeid'),width:'80px'},
								{field:'addresstypename',title: localStorage.getItem('catalogaddresstypename'),width:'200px'}
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
					title: localStorage.getItem('catalogaddressname'),
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
					title: localStorage.getItem('catalogrt'),
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
					title: localStorage.getItem('catalogrw'),
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
					title: localStorage.getItem('catalogcity'),
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
							loadMsg:  localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'cityid',title: localStorage.getItem('catalogcityid'),width:'50px'},
								{field:'cityname',title: localStorage.getItem('catalogcityname'),width:'200px'}
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
					title: localStorage.getItem('catalogphoneno'),
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
					title: localStorage.getItem('catalogfaxno'),
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
					title: localStorage.getItem('cataloglat'),
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'lng',
					title: localStorage.getItem('cataloglng'),
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
				{field:'contacttypename',title: localStorage.getItem('catalogcontacttypename'),width:'200px'},
				{field:'addresscontactname',title: localStorage.getItem('catalogaddresscontactname'),width:'200px'},
				{field:'phoneno',title: localStorage.getItem('catalogphoneno'),width:'200px'},
				{field:'mobilephone',title: localStorage.getItem('catalogmobilephone'),width:'200px'},
				{field:'emailaddress',title: localStorage.getItem('catalogemailaddress'),width:'200px'}
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
				title: localStorage.getItem('catalogaddresscontactid'),
				width:'50px',
				sortable: true,
				formatter: function(value,row,index){
									return value;
				}
			},
			{
				field:'contacttypeid',
				title: localStorage.getItem('catalogcontacttype'),
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
						loadMsg:  localStorage.getItem('catalogpleasewait'),
						columns:[[
							{field:'contacttypeid',title: localStorage.getItem('catalogcontacttypeid'),width:'80px'},
							{field:'contacttypename',title: localStorage.getItem('catalogcontacttypename'),width:'200px'}
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
				title: localStorage.getItem('catalogaddresscontactname'),
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
				title: localStorage.getItem('catalogphoneno'),
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
				title: localStorage.getItem('catalogmobilephone'),
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
				title: localStorage.getItem('catalogemailaddress'),
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