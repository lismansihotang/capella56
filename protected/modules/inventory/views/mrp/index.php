<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'mrpid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('inventory/mrp/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('inventory/mrp/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('inventory/mrp/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('inventory/mrp/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('inventory/mrp/upload'),
	'downpdf'=>Yii::app()->createUrl('inventory/mrp/downpdf'),
	'downxls'=>Yii::app()->createUrl('inventory/mrp/downxls'),
	'columns'=>"
		{
			field:'mrpid',
			title:'".getCatalog('mrpid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
			{
			field:'materialtypecode',
			title:'".GetCatalog('materialtypecode')."',
			editor: {
				type: 'textbox',
				options:{
					required:true,
					readonly:true,
				}
			},
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
							return value;
			}
		},
		{
			field:'productid',
			title:'".getCatalog('productname') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'productid',
					textField:'productname',
					url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					queryParams:{
						combo:true
					},
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'productid',title:'".getCatalog('productid')."',width:'80px'},
						{field:'materialtypecode',title:'".getCatalog('materialtypecode')."',width:'110px'},
						{field:'productname',title:'".getCatalog('productname')."',width:'250px'},
					]]
				}	
			},
			width:'350px',			
			sortable: true,
			formatter: function(value,row,index){
				return row.productname;
		}},
		{
			field:'slocid',
			title:'".getCatalog('sloc') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'slocid',
					textField:'sloccode',
					url:'".Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					pagination:true,
					required:true,
					loadMsg: '".getCatalog('pleasewait')."',
					columns:[[
						{field:'slocid',title:'".getCatalog('slocid')."',width:'80px'},
						{field:'sloccode',title:'".getCatalog('sloccode')."',width:'150px'},
						{field:'description',title:'".getCatalog('description')."',width:'250px'},
					]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'minstock',
			title:'".getCatalog('minstock') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'reordervalue',
			title:'".getCatalog('reordervalue') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'maxvalue',
			title:'".getCatalog('maxvalue') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'leadtime',
			title:'".getCatalog('leadtime') ."',
			editor:{
				type:'numberbox',
				options:{
					precision:0,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'uomid',
			title:'".getCatalog('uomid') ."',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:450,
						mode : 'remote',
						method:'get',
						idField:'unitofmeasureid',
						textField:'uomcode',
						url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)) ."',
						fitColumns:true,
						pagination:true,
						required:true,
						queryParams:{
							combo:true
						},
						loadMsg: '".getCatalog('pleasewait')."',
						columns:[[
							{field:'unitofmeasureid',title:'".getCatalog('unitofmeasureid')."'},
							{field:'uomcode',title:'".getCatalog('uomcode')."'},
							{field:'description',title:'".getCatalog('description')."'},
						]]
				}	
			},			
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uomcode;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable:'true',
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
			}
		}
	",
	'searchfield'=> array ('mrpid','uom','sloc','product')
));