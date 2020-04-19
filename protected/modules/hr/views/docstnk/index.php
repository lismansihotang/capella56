<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'docstnkid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/docstnk/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/docstnk/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('hr/docstnk/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('hr/docstnk/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/docstnk/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/docstnk/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/docstnk/downxls'),
	'columns'=>"
		{
			field:'docid',
			title:'". getCatalog('docid') ."',
			width:'60px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title:'". getCatalog('company') ."',
			width:'250px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					pagination:true,
					url:'". Yii::app()->createUrl('admin/company/index',array('grid'=>true)) ."',
					fitColumns:true,
					loadMsg: '". getCatalog('pleasewait')."',
					columns:[[
						{field:'companyid',title:'". getCatalog('companyid')."',width:'50px'},
						{field:'companyname',title:'". getCatalog('companyname')."',width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'namedoc',
			title:'". getCatalog('namedoc') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'nodoc',
			title:'". getCatalog('nodoc') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'exdate',
			title:'". getCatalog('exdate') ."',
			width:'100px',
			editor:{type:'datebox'},
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'cost',
			title:'". getCatalog('cost') ."',
			width:'150px',
			editor:'text',
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'docupload',
			title:'". getCatalog('docupload') ."',
			width:'250px',
			editor:{
				type:'text',
			},
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}}",
		'beginedit'=> "
        var ed = $('#dg-".$this->menuname."').edatagrid('getEditor',{index:index,field:'exdate'});
				var s = row.exdate;
				var ss = s.split('-');
				var y = parseInt(ss[2],10);
				var m = parseInt(ss[1],10);
				var d = parseInt(ss[0],10);
				var dateperiod = m+'/'+d+'/'+y; 
        $(ed.target).datebox('setValue',dateperiod);
		",		
		'searchfield'=> array ('docid','companyname','namedoc')
));