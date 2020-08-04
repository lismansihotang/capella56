<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productplantid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/productplant/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/productplant/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/productplant/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/productplant/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/productplant/upload'),
	'downpdf'=>Yii::app()->createUrl('common/productplant/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/productplant/downxls'),
	'columns'=>"
		{
			field:'productplantid',
			title:'". GetCatalog('productplantid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addressbookid',
			title:'". GetCatalog('addressbook') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'600px',
					mode : 'remote',
					method:'get',
					idField:'addressbookid',
					textField:'fullname',
					url:'". $this->createUrl('addressbook/index',array('grid'=>true,'productplant'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'addressbookid',title:'". GetCatalog('addressbookid')."',width:'50px'},
						{field:'fullname',title:'". GetCatalog('fullname')."',width:'250px'},
						{field:'iscustomer',title:'". GetCatalog('iscustomer')."',width:'100px',
							formatter: function(value,row,index){
								if (value == 1){
										return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
									} else {
										return '';
									}
								}},
						{field:'isvendor',title:'". GetCatalog('isvendor')."',width:'100px',
							formatter: function(value,row,index){
								if (value == 1){
										return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
									} else {
										return '';
									}
							}}
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'materialgroupid',
			title:'". GetCatalog('materialgroup') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'materialgroupid',
					textField:'description',
					url:'". $this->createUrl('materialgroup/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'materialgroupid',title:'". GetCatalog('unitofmeasureid')."',width:'50px'},
						{field:'materialgroupcode',title:'". GetCatalog('materialgroupcode')."',width:'80px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.materialgroupcode;
		}},
		{
			field:'materialtypecode',
			title:'". GetCatalog('materialtype') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.materialtypecode;
		}},
		{
			field:'productid',
			title:'". GetCatalog('productname') ."',
			width:'350px',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'1150x',
						mode : 'remote',
						method:'get',
						idField:'productid',
						textField:'productname',
						url:'". $this->createUrl('product/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'productid',title:'". GetCatalog('productid')."',width:'80px'},
							{field:'materialtypecode',title:'". GetCatalog('materialtypecode')."',width:'100px'},
							{field:'productname',title:'". GetCatalog('productname')."',width:'950px'},
						]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.productname;
		}},
		{
			field:'slocid',
			title:'". GetCatalog('sloc') ."',
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'slocid',
						textField:'sloccode',
						url:'". $this->createUrl('sloc/indexcombo',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'slocid',title:'". GetCatalog('slocid')."',width:'80px'},
							{field:'sloccode',title:'". GetCatalog('sloccode')."',width:'80px'},
							{field:'description',title:'". GetCatalog('description')."',width:'200px'},
						]]
				}	
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.sloccode;
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
			field:'uom1',
			title:'". GetCatalog('uom1') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'unitofmeasureid',
					textField:'uomcode',
					url:'". $this->createUrl('unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'unitofmeasureid',title:'". GetCatalog('unitofmeasureid')."',width:'80px'},
						{field:'uomcode',title:'". GetCatalog('uomcode')."',width:'80px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uomcode;
		}},
		{
			field:'qty2',
			title:'". GetCatalog('qty2') ."',
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
			field:'uom2',
			title:'". GetCatalog('uom2') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'unitofmeasureid',
					textField:'uomcode',
					url:'". $this->createUrl('unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'unitofmeasureid',title:'". GetCatalog('unitofmeasureid')."',width:'80px'},
						{field:'uomcode',title:'". GetCatalog('uomcode')."',width:'80px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom2code;
		}},
		{
			field:'qty3',
			title:'". GetCatalog('qty3') ."',
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
			field:'uom3',
			title:'". GetCatalog('uom3') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'unitofmeasureid',
					textField:'uomcode',
					url:'". $this->createUrl('unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'unitofmeasureid',title:'". GetCatalog('unitofmeasureid')."',width:'80px'},
						{field:'uomcode',title:'". GetCatalog('uomcode')."',width:'80px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom3code;
		}},
		{
			field:'qty4',
			title:'". GetCatalog('qty4') ."',
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
			field:'uom4',
			title:'". GetCatalog('uom4') ."',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'unitofmeasureid',
					textField:'uomcode',
					url:'". $this->createUrl('unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'unitofmeasureid',title:'". GetCatalog('unitofmeasureid')."',width:'80px'},
						{field:'uomcode',title:'". GetCatalog('uomcode')."',width:'80px'},
						{field:'description',title:'". GetCatalog('description')."',width:'200px'},
					]]
				}	
			},
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom4code;
		}},
		{
			field:'isautolot',
			title:'". GetCatalog('isautolot') ."',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'sled',
			title:'". GetCatalog('sled') ."',
			editor:{type:'numberbox',precision:'0'},
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'issource',
			title:'". GetCatalog('source') ."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('productplantid','materialtypecode','productcode','productname','sloc','uom1','uom2','uom3','uom4','snro','materialgroup','addressbook')
));