<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productplanid',
	'formtype'=>'list',
	'isxls'=>1,
	'wfapp'=>'appprodplan',
	'url'=>Yii::app()->createUrl('production/repprodplan/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('production/productplan/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/productplan/downxls'),
	'columns'=>"
		{
			field:'productplanid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".getStatusColor('appprodplan')."
		}},
		{
			field:'companyname',
			title:'".GetCatalog('company') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plantcode') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'productplandate',
			title:'".GetCatalog('productplandate') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productplanno',
			title:'".GetCatalog('productplanno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'soheaderid',
			title:'".GetCatalog('sono') ."',
			sortable: true,
			width:'120px',
			formatter: function(value,row,index){
				return row.sono;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'description',
			title:'".GetCatalog('description') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return row.recordstatusname;
		}},",
		'downloadbuttons'=>"
		<a href='javascript:void(0)' title='".getCatalog('pdfoperator')."' class='easyui-linkbutton' iconCls='icon-pdf' plain='true' onclick='pdfoperator()'></a>
	",
		'addonscripts'=>"
		function pdfoperator() {
			var rows = $('#dg-repprodplan').edatagrid('getSelected');
			var array = 'id='+rows.productplanid;
			window.open('".Yii::app()->createUrl('production/productplan/pdfoperator')."?'+array);
		};
		",
	'searchfield'=> array ('productplanid','plantcode','productplanno','productplandate','sono','customer','sloccode','productname','description','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'productplanfg',
			'idfield'=>'productplanfgid',
			'urlsub'=>Yii::app()->createUrl('production/productplan/indexhasil',array('grid'=>true)),
			'subs'=>"
				{field:'productplanfgid',title:'".getCatalog('planfgid') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'450px'},
				{field:'qtystock',title:'".GetCatalog('qtystock') ."',formatter: function(value,row,index){
					if (row.stockcount == '1') {
					return '<div style=\"background-color:cyan\">'+formatnumber('',value)+'</div>';
					} else {
						return formatnumber('',value);
					}
				},width:'100px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'qtyres',title:'".GetCatalog('qtyprod') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'qtyout',title:'".GetCatalog('qtyout') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'qtyokfree',title:'".GetCatalog('qtyokfree') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'80px'},
				{field:'bomversion',title:'".GetCatalog('bomversion') ."',width:'100px'},
				{field:'processprdname',title:'".GetCatalog('processprdname') ."',width:'100px'},
				{field:'namamesin',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'100px'},
				{field:'startdate',title:'".GetCatalog('startdate') ."',width:'100px'},
				{field:'enddate',title:'".GetCatalog('enddate') ."',width:'100px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'100px'}
			",
		),
		array(
			'id'=>'productplandetail',
			'idfield'=>'productplandetailid',
			'urlsub'=>Yii::app()->createUrl('production/productplan/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'productplanfgid',title:'".GetCatalog('planfgid') ."',width:'50px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'450px'},
				{field:'qtystock',title:'".GetCatalog('qtystock') ."',formatter: function(value,row,index){
					if (row.stockcount == '1') {
					return '<div style=\"background-color:cyan\">'+formatnumber('',value)+'</div>';
					} else {
						return formatnumber('',value);
					}
				},width:'100px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'qtyres',title:'".GetCatalog('qtyuse') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'qtyres2',title:'".GetCatalog('qtyuse2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom2code',title:'".GetCatalog('uom2code') ."',width:'80px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'qtyres3',title:'".GetCatalog('qtyuse3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom3code',title:'".GetCatalog('uom3code') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'qtyres4',title:'".GetCatalog('qtyuse4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'80px'},
				{field:'bomversion',title:'".GetCatalog('bomversion') ."',width:'200px'},
				{field:'slocfromcode',title:'".GetCatalog('slocfromcode') ."',width:'150px'},
				{field:'sloctocode',title:'".GetCatalog('sloctocode') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
			",
		)
	),	
));
