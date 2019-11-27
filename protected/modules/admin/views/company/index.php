<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'companyid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/company/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/company/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/company/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/company/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/company/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/company/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/company/downxls'),
	'columns'=>"
		{
			field:'companyid',
			title:'".GetCatalog('companyid')."',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyname',
			title:'".GetCatalog('companyname')."',
			editor:'text',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companycode',
			title:'".GetCatalog('companycode')."',
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'address',
			title:'".GetCatalog('address')."',
			editor:'text',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'cityid',
			title:'". GetCatalog('city') ."',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'cityid',
						textField:'cityname',
						url:'". $this->createUrl('city/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'cityid',title:'". GetCatalog('cityid')."',width:'80px'},
							{field:'cityname',title:'". GetCatalog('cityname')."',width:'250px'},
						]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.cityname;
		}},
		{
			field:'zipcode',
			title:'".GetCatalog('zipcode')."',
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'taxno',
			title:'".GetCatalog('taxno')."',
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'currencyid',
			title:'".GetCatalog('currency')."',
			sortable: true,
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'currencyid',
					textField:'currencyname',
					url:'".$this->createUrl('currency/index',array('grid'=>true,'combo'=>true))."',
					required:true,
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'currencyid',title:'".GetCatalog('currencyid')."',width:'50px'},
						{field:'countryname',title:'".GetCatalog('countryname')."',width:'200px'},
						{field:'currencyname',title:'".GetCatalog('currencyname')."',width:'200px'},
					]]
				}	
			},
			formatter: function(value,row,index){
				return row.currencyname;
		}},
		{
			field:'faxno',
			title:'".GetCatalog('faxno')."',
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'phoneno',
			title:'".GetCatalog('phoneno')."',
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'webaddress',
			title:'".GetCatalog('webaddress')."',
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'email',
			title:'".GetCatalog('email')."',
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'leftlogofile',
			title:'".GetCatalog('leftlogofile')."',
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'rightlogofile',
			title:'".GetCatalog('rightlogofile')."',
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'isholding',
			title:'".GetCatalog('isholding')."',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'billto',
			title:'".GetCatalog('billto')."',
			editor:'textarea',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bankacc1',
			title:'".GetCatalog('bankacc1')."',
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bankacc2',
			title:'".GetCatalog('bankacc2')."',
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bankacc3',
			title:'".GetCatalog('bankacc3')."',
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/icons/ok.png'."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('companyid','companyname','companycode')
));
