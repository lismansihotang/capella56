<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'cashbankinid',
	'formtype'=>'list',
	'wfapp'=>'appcbin',
	'url'=>Yii::app()->createUrl('accounting/repcbin/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/cashbankin/downpdf'),
	'columns'=>"
		{
			field:'cashbankinid',
			title:'".GetCatalog('cashbankinid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appcbout')."
		}},
		{
			field:'companyid',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.companycode;
		}},
		{
			field:'cashbankindate',
			title:'".GetCatalog('cashbankindate') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'cashbankinno',
			title:'".GetCatalog('cashbankinno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'chequeid',
			title:'".GetCatalog('chequeno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return row.chequeno;
		}},
		{
			field:'accountid',
			title:'".GetCatalog('accountname') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return row.accountname;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatusname',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},",
	'rowstyler'=>"
		if (row.debit != row.credit){
			return 'background-color:blue;color:#fff;';
		}
	",
	'searchfield'=> array ('cashbankinid','cashbankindate','customer','plantcode','cashbankinno','invoicearno','accountname','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'cashbankindetail',
			'idfield'=>'cashbankindetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/cashbankin/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/cashbankin/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/cashbankin/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/cashbankin/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/cashbankin/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'invoicearno',title:'".GetCatalog('invoicearno') ."',width:'120px'},
				{field:'amount',title:'".GetCatalog('amount') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,row.amount);
					},
				},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',align:'right',width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'200px'},
			",
			'columns'=>"
				{
					field:'cashbankinid',
					title:'".GetCatalog('cashbankinid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'cashbankindetailid',
					title:'".GetCatalog('cashbankindetailid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'invoicearid',
					title:'".GetCatalog('invoicearno') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'invoicearid',
							textField:'invoicearno',
							url:'".Yii::app()->createUrl('accounting/invoicear/indexcbin',array('grid'=>true)) ."',
							fitColumns:true,
							required:true,
							onBeforeLoad: function(param){
								param.plantid = $('#cashbankin-plantid').combogrid('getValue');
								param.addressbookid = $('#cashbankin-addressbookid').combogrid('getValue');
							},
							onHidePanel: function(){
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var invoicearid = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'invoicearid'});
								var amount = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'amount'});
								var currencyid = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'currencyid'});
								var ratevalue = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'ratevalue'});
								var detailnote = $('#dg-cashbankin-cashbankindetail').datagrid('getEditor', {index: index, field:'detailnote'});
								jQuery.ajax({'url':'". Yii::app()->createUrl('accounting/cashbankin/generateinvar') ."',
									'data':{'invoicearid':$(invoicearid.target).combogrid('getValue')},
									'type':'post',
									'dataType':'json',
									'success':function(data)
									{
										$(amount.target).numberbox('setValue',data.amount);
										$(currencyid.target).combogrid('setValue',data.currencyid);
										$(ratevalue.target).numberbox('setValue',data.ratevalue);
										$(detailnote.target).textbox('setValue',data.detailnote);
									} ,
									'cache':false});
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'invoicearid',title:'".GetCatalog('invoicearid')."',width:'50px'},
								{field:'invoicearno',title:'".GetCatalog('invoicearno')."',width:'150px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
										return row.invoicearno;
					}
				},
				{
					field:'amount',
					title:'".GetCatalog('value') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
							readonly:true,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					}
				},
				{
					field:'currencyid',
					title:'".GetCatalog('currency') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'currencyid',
							textField:'currencyname',
							url:'".Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							readonly:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'currencyid',title:'".GetCatalog('currencyid')."',width:'50px'},
								{field:'currencyname',title:'".GetCatalog('currencyname')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.currencyname;
					}
				},
				{
					field:'ratevalue',
					title:'".GetCatalog('ratevalue') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
							readonly:true,
							value:1,
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
											return formatnumber('',value);
					}
				},
				{
					field:'detailnote',
					title:'".GetCatalog('detailnote')."',
					editor: 'textbox',
					sortable: true,
					width:'300px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
		array(
			'id'=>'cashbankinjurnal',
			'idfield'=>'cashbankinjurnalid',
			'isnew'=> 0,
			'iswrite'=> 0,
			'ispurge'=> 0,
			'urlsub'=>Yii::app()->createUrl('accounting/cashbankin/indexjurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/cashbankin/searchjurnal',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/cashbankin/savejurnal',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/cashbankin/savejurnal',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/cashbankin/purgejurnal',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:'".GetCatalog('accountname') ."',width:'250px'},
				{field:'debit',title:'".GetCatalog('debit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value)
					},width:'120px'},
				{field:'credit',title:'".GetCatalog('credit') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value)
					},width:'120px'},
				{field:'ratevalue',title:'".GetCatalog('ratevalue') ."',
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value)
					},width:'60px'},
				{field:'detailnote',title:'".GetCatalog('detailnote') ."',width:'350px'},
			",
			'columns'=>"
				{
					field:'cashbankinid',
					title:'".GetCatalog('cashbankinid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'cashbankinjurnalid',
					title:'".GetCatalog('cashbankinjurnalid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'accountid',
					title:'".GetCatalog('accountname') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'accountid',
							textField:'accountname',
							url:'".$this->createUrl('account/index',array('grid'=>true,'trxcom'=>true)) ."',
							fitColumns:true,
							required:true,
							onBeforeLoad: function(param) {
								 param.companyid = $('#cashbankin-plantid').combogrid('getValue');
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'accountid',title:'".GetCatalog('accountid')."',width:'80px'},
								{field:'companyname',title:'".GetCatalog('company')."',width:'350px'},
								{field:'accountcode',title:'".GetCatalog('accountcode')."',width:'120px'},
								{field:'accountname',title:'".GetCatalog('accountname')."',width:'250px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.accountname;
					}
				},
				{
					field:'debit',
					title:'".GetCatalog('debit') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return formatnumber('',value);
					}
				},
				{
					field:'credit',
					title:'".GetCatalog('credit') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return formatnumber('',value);
					}
				},
				{
					field:'currencyid',
					title:'".GetCatalog('currency') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'currencyid',
							textField:'currencyname',
							url:'".Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'currencyid',title:'".GetCatalog('currencyid')."',width:'50px'},
								{field:'currencyname',title:'".GetCatalog('currencyname')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.currencyname;
					}
				},
				{
					field:'ratevalue',
					title:'".GetCatalog('ratevalue') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
							value:1,
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
											return formatnumber('',value);
					}
				},
				{
					field:'detailnote',
					title:'".GetCatalog('detailnote')."',
					editor: 'textbox',
					sortable: true,
					width:'300px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
	),
));