<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'accountid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/account/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/account/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/account/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/account/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/account/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/account/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/account/downxls'),
	'downdoc'=>Yii::app()->createUrl('accounting/account/downdoc'),
	'columns'=>"
		{
			field:'accountid',
			title:localStorage.getItem('catalogaccountid'),
			sortable: true,
			width:'60px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'companyid',
			title:localStorage.getItem('catalogcompanyname'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					url:'". Yii::app()->createUrl('admin/company/indexcombo',array('grid'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'companyid',title:localStorage.getItem('catalogcompanyid'),width:'50px'},
						{field:'companycode',title:localStorage.getItem('catalogcompanycode'),width:'100px'},
						{field:'companyname',title:localStorage.getItem('catalogcompanyname'),width:'300px'},
					]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
			return row.companyname;
		}},
		{
			field:'accountcode',
			title:localStorage.getItem('catalogaccountcode'),
			editor: {
				type: 'textbox',
				options: {
					required: true
				},
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'accountname',
			title:localStorage.getItem('catalogaccountname'),
			editor: {
				type: 'textbox',
				options: {
					required: true
				}
			},
			width:'320px',
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'parentaccountid',
			title:localStorage.getItem('catalogparentaccount'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountcode',
					url:'". $this->createUrl('account/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'50px'},
						{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'100px'},
						{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'200px'},
						{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'200px'},
					]]
				}	
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.parentaccountcode;
		}},
		{
			field:'currencyid',
			title:localStorage.getItem('catalogcurrency'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'currencyid',
					textField:'currencyname',
					url:'". Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'currencyid',title:localStorage.getItem('catalogcurrencyid'),width:'50px'},
						{field:'symbol',title:localStorage.getItem('catalogsymbol'),width:'80px'},
						{field:'currencyname',title:localStorage.getItem('catalogcurrencyname'),width:'200px'},
					]]
				}	
			},
			width:'90px',
			sortable: true,
			formatter: function(value,row,index){
				return row.currencyname;
		}},
		{
			field:'accounttypeid',
			title:localStorage.getItem('catalogaccounttype'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'accounttypeid',
					textField:'accounttypename',
					url:'". $this->createUrl('accounttype/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'accounttypeid',title:localStorage.getItem('catalogaccounttypeid'),width:'50px'},
						{field:'accounttypename',title:localStorage.getItem('catalogaccounttypename'),width:'200px'},
					]]
				}	
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.accounttypename;
		}},
		{
			field:'isdebit',
			title:localStorage.getItem('catalogisdebet'),
			align:'center',
			width:'90px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'recordstatus',
			title:localStorage.getItem('catalogrecordstatus'),
			align:'center',
			width:'80px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},",
	'searchfield'=> array ('accountid','companyname','accountcode','accountname','accounttypename','parentaccountcode','parentaccountname','currencyname')
));