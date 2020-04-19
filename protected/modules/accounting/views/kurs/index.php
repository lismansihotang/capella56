<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'kursid',
	'formtype'=>'master',
	'isupload'=>false,
	'url'=>Yii::app()->createUrl('accounting/kurs/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/kurs/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/kurs/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/kurs/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/kurs/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/kurs/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/kurs/downxls'),
	'downdoc'=>Yii::app()->createUrl('accounting/kurs/downdoc'),
	'columns'=>"
		{
			field:'kursid',
			title:'". GetCatalog('kursid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'kursdate',
			title:'".GetCatalog('kursdate')."',
			editor: {
				type:'datebox',
				options:{
					required:true,
					formatter:dateformatter,
					parser:dateparser
				}
			},
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.kursdate;
		}},
		{
			field:'currencyid',
			title:'". GetCatalog('currency') ."',
			width:'400px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'currencyid',
					textField:'currencyname',
					url:'". Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'currencyid',title:'". GetCatalog('currencyid')."',width:'50px'},
						{field:'currencyname',title:'". GetCatalog('currencyname')."',width:'200px'},
						{field:'symbol',title:'". GetCatalog('symbol')."',width:'150px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.currencyname;
		}},
		{
			field:'taxrate',
			title:'". GetCatalog('taxrate') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
				}
			},
			sortable: true,
			width:'90px',
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'birate',
			title:'". GetCatalog('birate') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
				}
			},
			sortable: true,
			width:'90px',
			formatter: function(value,row,index){
				return formatnumber('',value);
		}}",
			'loadsuccess' => "
		$('#kurs-kursdate').datebox('setValue',data.kursdate);
	",
	'searchfield'=> array ('kursid','kursdate','currencyname')
));