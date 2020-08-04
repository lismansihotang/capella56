<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'addressbookid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('common/supplier/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('common/supplier/getData'),
	'saveurl'=>Yii::app()->createUrl('common/supplier/save'),
	'destroyurl'=>Yii::app()->createUrl('common/supplier/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/supplier/upload'),
	'downpdf'=>Yii::app()->createUrl('common/supplier/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/supplier/downxls'),
	'downdoc'=>Yii::app()->createUrl('common/supplier/downxls'),
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
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'taxno',
			title: localStorage.getItem('catalogtaxno'),
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'paymentmethodid',
			title: localStorage.getItem('catalogpaymentmethod'),
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.paycode;
		}}, 
		{
			field:'bankaccountno',
			title: localStorage.getItem('catalogbankaccountno'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bankname',
			title: localStorage.getItem('catalogbankname'),
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'accountowner',
			title: localStorage.getItem('catalogaccountowner'),
			width:'250px',
			sortable: true,
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
	'searchfield'=> array ('addressbookid','fullname','bankname','accountowner'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td id='suppliertext-fullname'></td>
				<td><input class='easyui-textbox' id='supplier-fullname' name='supplier-fullname' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td id='suppliertext-taxno'></td>
				<td><input class='easyui-textbox' id='supplier-taxno' name='supplier-taxno' ></input></td>
			</tr>
			<tr>
				<td id='suppliertext-bankaccountno'></td>
				<td><input class='easyui-textbox' id='supplier-bankaccountno' name='supplier-bankaccountno' ></input></td>
			</tr>
			<tr>
				<td id='suppliertext-bankname'></td>
				<td><input class='easyui-textbox' id='supplier-bankname' name='supplier-bankname' ></input></td>
			</tr>
			<tr>
				<td id='suppliertext-accountowner'></td>
				<td><input class='easyui-textbox' id='supplier-accountowner' name='supplier-accountowner' ></input></td>
			</tr>
			<tr>
				<td id='suppliertext-paymentmethod'></td>
				<td>
					<select class='easyui-combogrid' id='supplier-paymentmethodid' name='supplier-paymentmethodid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'paymentmethodid',
						textField: 'paycode',
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
							{field:'paymentmethodid',title: localStorage.getItem('catalogpaymentmethodid'),width:'50px'},
							{field:'paycode',title: localStorage.getItem('catalogpaycode'),width:'120px'},
							{field:'paydays',title: localStorage.getItem('catalogpaydays'),width:'120px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td id='suppliertext-recordstatus'></td>
				<td><input id='supplier-recordstatus' name='supplier-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
  ",
  'addonscripts'=>"
    $(document).ready(function(){
      var parel = document.getElementById('suppliertext-fullname');
      parel.innerHTML = localStorage.getItem('catalogfullname');
      parel = document.getElementById('suppliertext-taxno');
      parel.innerHTML = localStorage.getItem('catalogtaxno');
      parel = document.getElementById('suppliertext-bankaccountno');
      parel.innerHTML = localStorage.getItem('catalogbankaccountno');
      parel = document.getElementById('suppliertext-bankname');
      parel.innerHTML = localStorage.getItem('catalogbankname');
      parel = document.getElementById('suppliertext-accountowner');
      parel.innerHTML = localStorage.getItem('catalogaccountowner');
      parel = document.getElementById('suppliertext-paymentmethod');
      parel.innerHTML = localStorage.getItem('catalogpaymentmethod');
      parel = document.getElementById('suppliertext-recordstatus');
      parel.innerHTML = localStorage.getItem('catalogrecordstatus');
    });
  ",
	'loadsuccess' => "
		$('#supplier-fullname').textbox('setValue',data.fullname);
		$('#supplier-taxno').textbox('setValue',data.taxno);
		$('#supplier-bankaccountno').textbox('setValue',data.bankaccountno);
		$('#supplier-bankname').textbox('setValue',data.bankname);
		$('#supplier-accountowner').textbox('setValue',data.accountowner);
		$('#supplier-paymentmethodid').combogrid('setValue',data.paymentmethodid);
		if (data.recordstatus == 1) {
			$('#supplier-recordstatus').prop('checked', true);
		} else {
			$('#supplier-recordstatus').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'address',
			'idfield'=>'addressid',
			'urlsub'=>Yii::app()->createUrl('common/supplier/indexaddress',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('common/supplier/searchaddress',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/supplier/saveaddress',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('common/supplier/saveaddress',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('common/supplier/purgeaddress',array('grid'=>true)),
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
							url:'". Yii::app()->createUrl('common/addresstype/index',array('grid'=>true,'combo'=>true))."',
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
						options: {
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
							url:'". Yii::app()->createUrl('admin/city/index',array('grid'=>true,'combo'=>true))."',
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
					editor:{
						type: 'textbox',
					},
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
			'urlsub'=>Yii::app()->createUrl('common/supplier/indexcontact',array('grid'=>true)),
			'url'=> Yii::app()->createUrl('common/supplier/searchcontact',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/supplier/savecontact',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('common/supplier/savecontact',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('common/supplier/purgecontact',array('grid'=>true)),
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
						url:'". Yii::app()->createUrl('common/contacttype/index',array('grid'=>true,'combo'=>true))."',
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
		)
	),	
));