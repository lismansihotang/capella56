<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'addressbookid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('hr/hospital/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('hr/hospital/getData'),
	'saveurl'=>Yii::app()->createUrl('hr/hospital/save'),
	'destroyurl'=>Yii::app()->createUrl('hr/hospital/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/hospital/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/hospital/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/hospital/downxls'),
	'columns'=>"
		{
			field:'addressbookid',
			title:'".GetCatalog('addressbookid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title:'".GetCatalog('fullname')."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'taxno',
			title:'".GetCatalog('taxno')."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'bankaccountno',
			title:'".GetCatalog('bankaccountno')."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bankname',
			title:'".GetCatalog('bankname')."',
			editor:'text',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'accountowner',
			title:'".GetCatalog('accountowner')."',
			editor:'text',
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatushospital',
			title:'".GetCatalog('recordstatus')."',
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
		<input type='hidden' name='addressbookid' id='addressbookid'></input>
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('fullname')."</td>
				<td><input class='easyui-textbox' name='fullname' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('taxno')."</td>
				<td><input class='easyui-textbox' name='taxno' ></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('bankaccountno')."</td>
				<td><input class='easyui-textbox' name='bankaccountno' ></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('bankname')."</td>
				<td><input class='easyui-textbox' name='bankname' ></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('accountowner')."</td>
				<td><input class='easyui-textbox' name='accountowner' ></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('recordstatus')."</td>
				<td><input id='recordstatushospital' name='recordstatushospital' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'loadsuccess' => "
		if (data.recordstatushospital == 1) {
			$('#recordstatushospital').prop('checked', true);
		} else {
			$('#recordstatushospital').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'address',
			'idfield'=>'addressid',
			'urlsub'=>Yii::app()->createUrl('common/hospital/indexaddress',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('common/hospital/searchaddress',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/hospital/saveaddress',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('common/hospital/saveaddress',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('common/hospital/purgeaddress',array('grid'=>true)),
			'subs'=>"
				{field:'addresstypename',title:'".GetCatalog('addresstypename')."',width:'200px'},
				{field:'addressname',title:'".GetCatalog('addressname')."',width:'200px'},
				{field:'rt',title:'".GetCatalog('rt')."',width:'200px'},
				{field:'rw',title:'".GetCatalog('rw')."',width:'200px'},
				{field:'cityname',title:'".GetCatalog('cityname')."',width:'200px'},
				{field:'phoneno',title:'".GetCatalog('phoneno')."',width:'200px'},
				{field:'faxno',title:'".GetCatalog('faxno')."',width:'200px'}
			",
			'columns'=>"
				{
					field:'addressbookid',
					title:'".GetCatalog('addressbookid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'addressid',
					title:'".GetCatalog('addressid')."',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'addresstypeid',
					title:'".GetCatalog('addresstype')."',
					width:'200px',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'addresstypeid',
							textField:'addresstypename',
							pagination:true,
							url:'". Yii::app()->createUrl('addresstype/index',array('grid'=>true))."',
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'addresstypeid',title:'".GetCatalog('addresstypeid')."',width:'80px'},
								{field:'addresstypename',title:'".GetCatalog('addresstypename')."',width:'200px'}
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
					title:'".GetCatalog('addressname')."',
					width:'200px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'rt',
					title:'".GetCatalog('rt')."',
					width:'50px',
					editor:'numberbox',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'rw',
					title:'".GetCatalog('rw')."',
					width:'50px',
					editor:'numberbox',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'cityid',
					title:'".GetCatalog('city')."',
					width:'150px',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'cityid',
							textField:'cityname',
							pagination:true,
							url:'". Yii::app()->createUrl('admin/city/index',array('grid'=>true))."',
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'cityid',title:'".GetCatalog('cityid')."',width:'50px'},
								{field:'cityname',title:'".GetCatalog('cityname')."',width:'200px'}
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
					title:'".GetCatalog('phoneno')."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'faxno',
					title:'".GetCatalog('faxno')."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'lat',
					title:'".GetCatalog('lat')."',
					width:'150px',
					editor:'text',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'lng',
					title:'".GetCatalog('lng')."',
					width:'150px',
					editor:'text',
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
			'urlsub'=>Yii::app()->createUrl('common/hospital/indexcontact',array('grid'=>true)),
			'url'=> Yii::app()->createUrl('common/hospital/searchcontact',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/hospital/savecontact',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('common/hospital/savecontact',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('common/hospital/purgecontact',array('grid'=>true)),
			'subs'=>"
				{field:'contacttypename',title:'".GetCatalog('contacttypename')."',width:'200px'},
				{field:'addresscontactname',title:'".GetCatalog('addresscontactname')."',width:'200px'},
				{field:'phoneno',title:'".GetCatalog('phoneno')."',width:'200px'},
				{field:'mobilephone',title:'".GetCatalog('mobilephone')."',width:'200px'},
				{field:'emailaddress',title:'".GetCatalog('emailaddress')."',width:'200px'}
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
				title:'".GetCatalog('addresscontactid')."',
				width:'50px',
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'contacttypeid',
				title:'".GetCatalog('contacttype')."',
				width:'150px',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'contacttypeid',
						textField:'contacttypename',
						url:'". Yii::app()->createUrl('contacttype/index',array('grid'=>true))."',
						fitColumns:true,
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'contacttypeid',title:'".GetCatalog('contacttypeid')."',width:'80px'},
							{field:'contacttypename',title:'".GetCatalog('contacttypename')."',width:'200px'}
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
				title:'".GetCatalog('addresscontactname')."',
				width:'150px',
				editor:'text',
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'phoneno',
				title:'".GetCatalog('phoneno')."',
				width:'150px',
				editor:'text',
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'mobilephone',
				title:'".GetCatalog('mobilephone')."',
				width:'150px',
				editor:'text',
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'emailaddress',
				title:'".GetCatalog('emailaddress')."',
				width:'150px',
				editor:'text',
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			}	
			"
		)
	),	
));