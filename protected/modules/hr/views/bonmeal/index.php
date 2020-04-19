<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'bonmealid',
	'formtype'=>'master',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appbm',
	'url'=>Yii::app()->createUrl('hr/bonmeal/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('hr/bonmeal/save'),
	'updateurl'=>Yii::app()->createUrl('hr/bonmeal/save'),
	'destroyurl'=>Yii::app()->createUrl('hr/bonmeal/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('hr/bonmeal/approve'),
	'uploadurl'=>Yii::app()->createUrl('hr/bonmeal/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/bonmeal/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/bonmeal/downxls'),
	'columns'=>"
		{
			field:'bonmealid',
			title:'".GetCatalog('bonmealid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plant')."',
			width:'200px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'plantid',
					textField:'plantcode',
					pagination:true,
					url:'". Yii::app()->createUrl('common/plant/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'plantid',title:'".GetCatalog('plantid')."',width:'80px'},
						{field:'plantcode',title:'".GetCatalog('plantcode')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.plantcode;
			}
		},
		{
			field:'bonmealno',
			title:'".GetCatalog('bonmealno')."',
			editor:'textbox',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'bonmealdate',
			title:'".GetCatalog('bonmealdate')."',
			editor: {
				type:'datebox'
			},
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('addressbook')."',
			width:'200px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'addressbookid',
					textField:'fullname',
					pagination:true,
					url:'". Yii::app()->createUrl('common/supplier/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'addressbookid',title:'".GetCatalog('addressbookid')."',width:'80px'},
						{field:'fullname',title:'".GetCatalog('fullname')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.fullname;
			}
		},
		{
			field:'mealtypeid',
			title:'".GetCatalog('mealtype')."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'mealtypeid',
					textField:'mealtypename',
					pagination:true,
					url:'". Yii::app()->createUrl('hr/mealtype/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'mealtypeid',title:'".GetCatalog('mealtypeid')."',width:'80px'},
						{field:'mealtypename',title:'".GetCatalog('mealtypename')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.mealtypename;
			}
		},
		{
			field:'price',
			title:'". GetCatalog('price') ."',
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
			field:'qty',
			title:'". GetCatalog('qty') ."',
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
			field:'taxid',
			title:'".GetCatalog('tax')."',
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'taxid',
					textField:'taxcode',
					pagination:true,
					url:'". Yii::app()->createUrl('accounting/tax/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'taxid',title:'".GetCatalog('taxid')."',width:'80px'},
						{field:'taxcode',title:'".GetCatalog('taxcode')."',width:'200px'}
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.taxcode;
			}
		},
		{
			field:'jumlah',
			title:'".getCatalog('jumlah') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'description',
			title:'".GetCatalog('description')."',
			editor:'textbox',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'left',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.statusname
		}}",
	'searchfield'=> array ('bonmealid','bonmealno','fullname','mealtypename','plantcode'),	
));