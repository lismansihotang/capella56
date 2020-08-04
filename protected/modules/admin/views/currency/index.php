<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'currencyid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/currency/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/currency/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/currency/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/currency/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/currency/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/currency/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/currency/downxls'),
	'downdoc'=>Yii::app()->createUrl('admin/currency/downdoc'),
	'columns'=>"
		{
			field:'currencyid',
			title:localStorage.getItem('catalogcurrencyid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'countryid',
			title:localStorage.getItem('catalogcountry'),
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'countryid',
						textField:'countryname',
						url:'". $this->createUrl('country/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'countryid',title:localStorage.getItem('catalogcountryid'),width:'50px'},
							{field:'countrycode',title:localStorage.getItem('catalogcountrycode'),width:'100px'},
							{field:'countryname',title:localStorage.getItem('catalogcountryname'),width:'200px'},
						]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.countryname;
		}},
		{
			field:'currencyname',
			title:localStorage.getItem('catalogcurrencyname'),
			sortable: true,
			editor: {
        type:'textbox', 
        options: {
          required:true
      }},
			width:'250px',
			formatter: function(value,row,index){
				return value;
			},				
		},
		{
			field:'symbol',
			title:localStorage.getItem('catalogsymbol'),
			editor: {
        type: 'textbox', 
        options: {
          required:true
      }},
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'i18n',
			title:localStorage.getItem('catalogi18n'),
			editor: {type: 'textbox', options: {required:true}},
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:localStorage.getItem('catalogrecordstatus'),
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('currencyid','countryname','currencyname')
));