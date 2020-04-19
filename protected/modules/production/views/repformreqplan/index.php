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
						return formatnumber('',value);
					},width:'100px'},
					{field:'prqty',title:'".GetCatalog('prqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'tsqty',title:'".GetCatalog('tsqty') ."',
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
				{field:'reqdate',title:'".GetCatalog('reqdate') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('slocto') ."',width:'100px'},
				{field:'mesincode',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'formrequestid',
					title:'".GetCatalog('formrequestid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'formrequestrawid',
					title:'".GetCatalog('formrequestrawid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'productid',
					title:'".getCatalog('productcode') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'650px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productcode',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplant'=>true)) ."',
							onBeforeLoad: function(param){
								param.slocid = $('#formrequestplan-slocfromid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'productid'});
								var productname = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'productname'});
								var uomid = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-formrequestplan-formrequestraw').datagrid('getEditor', {index: index, field:'uom4id'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(productname.target).textbox('setValue',data.productname);
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(uom3id.target).combogrid('setValue',data.uom3);
										$(uom4id.target).combogrid('setValue',data.uom4);
									} ,
									'cache':false});
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
								{field:'productcode',title:'".getCatalog('productcode')."',width:'150px'},
								{field:'productname',title:'".getCatalog('productname')."',width:'450px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productcode;
					}
				},
				{
					field:'productname',
					title:'".GetCatalog('productname') ."',
					editor: {
						type: 'textbox',
						options:{
							readonly:true,
						}
					},
					sortable: true,
					width:'250px',
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'qty',
					title:'".GetCatalog('qty') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'uomid',
					title:'".GetCatalog('uomcode') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							disabled:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}		
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uomcode;
					}
				},
				{
					field:'qty2',
					title:'".GetCatalog('qty2') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'uom2id',
					title:'".GetCatalog('uom2code') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							disabled:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom2code;
					}
				},
				{
					field:'qty3',
					title:'".GetCatalog('qty3') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'uom3id',
					title:'".GetCatalog('uom3code') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							disabled:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom3code;
					}
				},
				{
					field:'qty4',
					title:'".GetCatalog('qty4') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'uom4id',
					title:'".GetCatalog('uom4code') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							disabled:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom4code;
					}
				},
				{
					field:'reqdate',
					title:'".GetCatalog('reqdate')."',
					editor: 'datebox',
					sortable: true,
					width:'150px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'sloctoid',
					title:'".GetCatalog('slocto') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'slocid',
							textField:'sloccode',
							url:'".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true)) ."',
							onBeforeLoad: function(param){
								param.plantid = $('#formrequestplan-plantid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'slocid',title:'".GetCatalog('slocid')."',width:'50px'},
								{field:'sloccode',title:'".GetCatalog('sloccode')."',width:'150px'},
								{field:'description',title:'".GetCatalog('description')."',width:'250px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.sloccode;
					}
				},
				{
					field:'mesinid',
					title:'".getCatalog('mesin') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'mesinid',
							textField:'namamesin',
							url:'".Yii::app()->createUrl('common/mesin/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'mesinid',title:'".getCatalog('mesinid')."',width:'80px'},
								{field:'namamesin',title:'".getCatalog('namamesin')."',width:'150px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return row.namamesin;
					}
				},
				{
					field:'description',
					title:'".GetCatalog('description')."',
					editor: 'textbox',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
		array(
			'id'=>'formrequestjasa',
			'idfield'=>'formrequestjasaid',
			'urlsub'=>Yii::app()->createUrl('production/formrequestplan/indexjasa',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/formrequestplan/searchjasa',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/formrequestplan/savejasa',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/formrequestplan/savejasa',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/formrequestplan/purgejasa',array('grid'=>true)),
			'ispurge'=>0,'isnew'=>1,
			'subs'=>"
				{field:'formrequestjasaid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'materialtypecode',title:'".GetCatalog('materialtypecode') ."',width:'100px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
					{field:'prqty',title:'".GetCatalog('prqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'tsqty',title:'".GetCatalog('tsqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'150px'},
				{field:'reqdate',title:'".GetCatalog('reqdate') ."',width:'100px'},
				{field:'sloccode',title:'".GetCatalog('slocto') ."',width:'100px'},
				{field:'mesincode',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'formrequestid',
					title:'".GetCatalog('formrequestid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'formrequestjasaid',
					title:'".GetCatalog('formrequestjasaid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'productid',
					title:'".getCatalog('productcode') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'650px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productcode',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplant'=>true)) ."',
							onBeforeLoad: function(param){
								param.slocid = $('#formrequest-slocfromid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-formrequestplan-formrequestjasa').datagrid('getEditor', {index: index, field:'productid'});
								var productname = $('#dg-formrequestplan-formrequestjasa').datagrid('getEditor', {index: index, field:'productname'});
								var uomid = $('#dg-formrequestplan-formrequestjasa').datagrid('getEditor', {index: index, field:'uomid'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(productname.target).textbox('setValue',data.productname);
										$(uomid.target).combogrid('setValue',data.uom1);
									} ,
									'cache':false});
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
								{field:'productcode',title:'".getCatalog('productcode')."',width:'150px'},
								{field:'productname',title:'".getCatalog('productname')."',width:'450px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productcode;
					}
				},
				{
					field:'productname',
					title:'".GetCatalog('productname') ."',
					editor: {
						type: 'textbox',
						options:{
							required:true,
							disabled:true,
						}
					},
					sortable: true,
					width:'250px',
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'qty',
					title:'".GetCatalog('qty') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'uomid',
					title:'".GetCatalog('uomcode') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							disabled:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}		
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uomcode;
					}
				},
				{
					field:'reqdate',
					title:'".GetCatalog('reqdate')."',
					editor: 'datebox',
					sortable: true,
					width:'150px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'sloctoid',
					title:'".GetCatalog('slocto') ."',
					editor:{
							type:'combogrid',
							options:{
									panelWidth:'500px',
									mode : 'remote',
									method:'get',
									idField:'slocid',
									textField:'sloccode',
									url:'".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true)) ."',
									fitColumns:true,
									required:true,
									onBeforeLoad: function(param){
										param.plantid = $('#formrequestplan-plantid').combogrid('getValue');
									},
									loadMsg: '".GetCatalog('pleasewait')."',
									columns:[[
										{field:'slocid',title:'".GetCatalog('slocid')."',width:'50px'},
										{field:'sloccode',title:'".GetCatalog('sloccode')."',width:'150px'},
										{field:'description',title:'".GetCatalog('description')."',width:'150px'},
									]]
							}	
						},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.sloccode;
					}
				},
				{
					field:'mesinid',
					title:'".getCatalog('mesin') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'mesinid',
							textField:'namamesin',
							url:'".Yii::app()->createUrl('common/mesin/index',array('grid'=>true,'sloc'=>true)) ."',
							onBeforeLoad: function(param) {
								param.plantid = $('#formrequestplan-plantid').combogrid('getValue');
							},
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'mesinid',title:'".getCatalog('mesinid')."',width:'80px'},
								{field:'namamesin',title:'".getCatalog('namamesin')."',width:'150px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return row.namamesin;
					}
				},
				{
					field:'description',
					title:'".GetCatalog('description')."',
					editor: 'textbox',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
		array(
			'id'=>'formrequestresult',
			'idfield'=>'formrequestresultid',
			'urlsub'=>Yii::app()->createUrl('production/formrequestplan/indexresult',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/formrequestplan/searchresult',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/formrequestplan/saveresult',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/formrequestplan/saveresult',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/formrequestplan/purgeresult',array('grid'=>true)),
			'subs'=>"
				{field:'formrequestresultid',title:'".getCatalog('ID') ."',width:'60px'},
				{field:'productcode',title:'".GetCatalog('productcode') ."',width:'100px'},
				{field:'productname',title:'".GetCatalog('productname') ."',width:'250px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'prqty',title:'".GetCatalog('prqty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'tsqty',title:'".GetCatalog('tsqty') ."',
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
				{field:'uom3code',titl21e:'".GetCatalog('uom3code') ."',width:'80px'},
				{field:'qty4',title:'".GetCatalog('qty4') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'uom4code',title:'".GetCatalog('uom4code') ."',width:'80px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'formrequestid',
					title:'".GetCatalog('formrequestid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'formrequestresultid',
					title:'".GetCatalog('formrequestresultid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'productid',
					title:'".getCatalog('productcode') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'650px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productcode',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'productplant'=>true)) ."',
							onBeforeLoad: function(param){
								param.slocid = $('#formrequestplan-slocfromid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'productid'});
								var productname = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'productname'});
								var uomid = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'uom3id'});
								var uom4id = $('#dg-formrequestplan-formrequestresult').datagrid('getEditor', {index: index, field:'uom4id'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(productname.target).textbox('setValue',data.productname);
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(uom3id.target).combogrid('setValue',data.uom3);
										$(uom4id.target).combogrid('setValue',data.uom4);
									} ,
									'cache':false});
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
								{field:'productcode',title:'".getCatalog('productcode')."',width:'150px'},
								{field:'productname',title:'".getCatalog('productname')."',width:'450px'},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productcode;
					}
				},				
				{
					field:'productname',
					title:'".GetCatalog('productname') ."',
					editor: {
						type: 'textbox',
						options:{
							required:true,
							disabled:true,
						}
					},
					sortable: true,
					width:'250px',
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'qty',
					title:'".GetCatalog('qty') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'uomid',
					title:'".GetCatalog('uomcode') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							disabled:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}		
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uomcode;
					}
				},
				{
					field:'qty2',
					title:'".GetCatalog('qty2') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							required:true,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'uom2id',
					title:'".GetCatalog('uom2code') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							disabled:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom2code;
					}
				},
				{
					field:'qty3',
					title:'".GetCatalog('qty3') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
							
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'uom3id',
					title:'".GetCatalog('uom3code') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							disabled:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom3code;
					}
				},
				{
					field:'qty4',
					title:'".GetCatalog('qty4') ."',
					width:'100px',
					editor: {
						type: 'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							value:0,
						}
					},
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
				{
					field:'uom4id',
					title:'".GetCatalog('uom4code') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							disabled:true,
							hasDownArrow:false,
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
								{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom4code;
					}
				},
				{
					field:'description',
					title:'".GetCatalog('description')."',
					editor: 'textbox',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
	),	
));