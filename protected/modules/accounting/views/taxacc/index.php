<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'taxaccid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/taxacc/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/taxacc/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/taxacc/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/taxacc/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/taxacc/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/taxacc/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/taxacc/downxls'),
	'downdoc'=>Yii::app()->createUrl('accounting/taxacc/downdoc'),
	'columns'=>"
		{
			field:'taxaccid',
			title:'".GetCatalog('ID')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'companyid',
			title:'".GetCatalog('company')."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'450px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					required:true,
					url:'".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'companyid',title:'".GetCatalog('companyid')."',width:'50px'},
						{field:'companycode',title:'".GetCatalog('companycode')."',width:'80px'},
						{field:'companyname',title:'".GetCatalog('companyname')."',width:'250px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
			}
		},
		{
			field:'taxid',
			title:'".GetCatalog('Tax')."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'450px',
					mode : 'remote',
					method:'get',
					idField:'taxid',
					textField:'taxcode',
					required:true,
					url:'".Yii::app()->createUrl('accounting/tax/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'taxid',title:'".GetCatalog('taxid')."',width:'50px'},
						{field:'taxcode',title:'".GetCatalog('taxcode')."',width:'80px'},
						{field:'taxvalue',title:'".GetCatalog('taxvalue')."',width:'150px'},
					]]
				}	
			},
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return row.taxcode;
			}
		},
		{
			field:'accountid',
			title:'".GetCatalog('accountcode')."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'450px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountcode',
					url:'".Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'".GetCatalog('accountid')."',width:'50px'},
						{field:'accountcode',title:'".GetCatalog('accountcode')."',width:'80px'},
						{field:'accountname',title:'".GetCatalog('accountname')."',width:'150px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.accountcode;
			}
		},",
	'searchfield'=> array ('taxaccid','companycode','companyname','tax','accountcode','accountname')
));