<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'formrequestid',
	'formtype'=>'list',
	'isxls'=>1,
	'wfapp'=>'appdaok',
	'url'=>Yii::app()->createUrl('production/repformreqplan/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('production/formrequestplan/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/formrequestplan/downxls'),
	'columns'=>"
		{
			field:'formrequestid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".getStatusColor('appda')."
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
			field:'formrequestdate',
			title:'".GetCatalog('formrequestdate') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'formrequestno',
			title:'".GetCatalog('formrequestno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productplanid',
			title:'".GetCatalog('productplan') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.productplanno;
		}},
		{
			field:'isjasa',
			title:'". GetCatalog('isjasa') ."',
			align:'center',
			width:'60px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
				return '';
				}				
		}},
		{
			field:'slocfromid',
			title:'".GetCatalog('slocfrom') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.sloccode;
		}},
		{
			field:'requestedbyid',
			title:'".GetCatalog('requestedby') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.requestedbycode;
		}},
		{
			field:'headernote',
			title:'".GetCatalog('headernote') ."',
			sortable: true,
			width:'300px',
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
		}},
	",
	'searchfield'=> array ('formrequestid','plantcode','formrequestdate','formrequestno','productplanno','sloccode','requestedbycode',
		'productname','description','recordstatus'),
	'columndetails'=> array (
		array(
			'id'=>'formrequestraw',
			'idfield'=>'formrequestrawid',
			'urlsub'=>Yii::app()->createUrl('production/formrequestplan/indexraw',array('grid'=>true)),
			'subs'=>"
				{field:'formrequestrawid',title:'".getCatalog('ID') ."',width:'60px'},
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
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
					{field:'prqty',title:'".GetCatalog('prqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'tsqty',title:'".GetCatalog('tsqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom2code);
					},width:'100px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom3code);
					},width:'100px'},
				{field:'reqdate',title:'".GetCatalog('reqdate') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('slocto') ."',width:'100px'},
				{field:'mesincode',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
		),
		array(
			'id'=>'formrequestjasa',
			'idfield'=>'formrequestjasaid',
			'urlsub'=>Yii::app()->createUrl('production/formrequestplan/indexjasa',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/formrequestplan/searchjasa',array('grid'=>true)),
			'subs'=>"
				{field:'formrequestjasaid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'100px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
					{field:'prqty',title:'".GetCatalog('prqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'tsqty',title:'".GetCatalog('tsqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'reqdate',title:'".GetCatalog('reqdate') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('slocto') ."',width:'100px'},
				{field:'mesincode',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
		),
		array(
			'id'=>'formrequestresult',
			'idfield'=>'formrequestresultid',
			'urlsub'=>Yii::app()->createUrl('production/formrequestplan/indexresult',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/formrequestplan/searchresult',array('grid'=>true)),
			'subs'=>"
				{field:'formrequestresultid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'productcode',title:'".GetCatalog('productcode') ."',width:'100px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'prqty',title:'".GetCatalog('prqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'tsqty',title:'".GetCatalog('tsqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom2code);
					},width:'100px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom3code);
					},width:'100px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
		),
	),	
));
