<input type="hidden" id='fixasset-plantid' name='fixasset-plantid'>
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'fixassetid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/fixasset/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/fixasset/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/fixasset/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/fixasset/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/fixasset/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/fixasset/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/fixasset/downxls'),
	'columns'=>"
	{
		field:'fixassetid',
		title:localStorage.getItem('catalogfixassetid'), 
		sortable:'true',
		width:'50px',
		formatter: function(value,row,index){
			return value;
		}
	},
	{
		field:'plantid',
		title:localStorage.getItem('catalogplant'),
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'plantid',
				textField:'plantcode',
				url:'". Yii::app()->createUrl('common/plant/index',array('grid'=>true,'combo'=>true)) ."',
				fitColumns:true,
				onHidePanel: function() {
					var tr = $(this).closest('tr.datagrid-row');
					var index = parseInt(tr.attr('datagrid-row-index'));
					var plantid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'plantid'});
					var productid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'productid'});
					var poheaderid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'poheaderid'});
					$('#fixasset-plantid').val($(plantid.target).combogrid('getValue'));
					jQuery.ajax({'url':'".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'getdata'=>true)) ."',
						'data':{'plantid':$(plantid.target).combogrid('getValue')},
						'type':'post','dataType':'json',
						'success':function(data)
						{
							$(productid.target).combogrid('setValue',data.productid);
							$(poheaderid.target).combogrid('setValue',data.poheaderid);
							
						} ,
						'cache':false});
				},
				required:true,
				loadMsg: localStorage.getItem('catalogpleasewait'),
				columns:[[
					{field:'plantid',title:localStorage.getItem('catalogplantid'),width:'50px'},
					{field:'plantcode',title:localStorage.getItem('catalogplantcode'),width:'100px'},
					{field:'description',title:localStorage.getItem('catalogdescription'),width:'300px'},
				]]
			}	
		},
		width:'100px',
		sortable: true,
		formatter: function(value,row,index){
		return row.plantcode;
	}},
	{
		field:'poheaderid',
		title:localStorage.getItem('catalogpono'),
		editor:{
			type:'combogrid',
			options:{
					panelWidth:'550x',
					mode : 'remote',
					method:'get',
					idField:'poheaderid',
					textField:'pono',
					url:'". Yii::app()->createUrl('purchasing/poheader/index',array('grid'=>true)) ."',
					fitColumns:true,
					required:false,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'poheaderid',title:localStorage.getItem('catalogpoheaderid'),width:'80px'},
						{field:'pono',title:localStorage.getItem('catalogpono'),width:'100px'},
						{field:'headernote',title:localStorage.getItem('catalogheadernote'),width:'300px'},
					]]
			}	
		},
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return row.pono;
	}},
	{
		field:'stdqty2',
		title:localStorage.getItem('catalogstdqty2'),
		sortable: true,
		editor: {
			type: 'numberbox',
			options: {
				precision:4,
				decimalSeparator:',',
				groupSeparator:'.',
				value:0,
			}
		},
		hidden:true,
		formatter: function(value,row,index){
			return value;
		}
	},
	{
		field:'materialtypecode',
		title:localStorage.getItem('catalogmaterialtypecode'),
		width:'150px',
		sortable: true,
		formatter: function(value,row,index){
			return value;
		}
	},
	{
		field:'assetno',
		title:localStorage.getItem('catalogassetno'), 
		width:'120px',
		sortable:'true',
		formatter: function(value,row,index){
			return value;
		}
	},
	{
			field:'productid',
			title:localStorage.getItem('catalogproductname'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'600px',
					mode: 'remote',
					method:'get',
					idField:'productid',
					textField:'productname',
					url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'asset'=>true)) ."',
					fitColumns:true,
					required:true,
					onBeforeLoad:function(param) {
						param.plantid = $('#fixasset-plantid').val()
					},
					onShowPanel: function() {
						var tr = $(this).closest('tr.datagrid-row');
						var index = parseInt(tr.attr('datagrid-row-index'));
						var productid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'productid'});
						var plantid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'plantid'});
						$(productid.target).combogrid('grid').datagrid('load',{
							plantid:$('#fixasset-plantid').val()
						});
					},
					onHidePanel: function() {
						var tr = $(this).closest('tr.datagrid-row');
						var index = parseInt(tr.attr('datagrid-row-index'));
						var productid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'productid'});
						var stdqty2 = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'stdqty2'});
						var uomid = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'uomid'});
						var uom2id = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'uom2id'});
						jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
							'data':{'productid':$(productid.target).combogrid('getValue')},
							'type':'post','dataType':'json',
							'success':function(data)
							{
								$(uomid.target).combogrid('setValue',data.uom1);
								$(uom2id.target).combogrid('setValue',data.uom2);
								$(stdqty2.target).numberbox('setValue',data.qty2);
							} ,
							'cache':false});
					},
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'productid',title:localStorage.getItem('catalogproductid'),width:'50px'},
						{field:'materialtypecode',title:localStorage.getItem('catalogmaterialtypecode'),width:'100px'},
						{field:'productcode',title:localStorage.getItem('catalogproductcode'),width:'150px'},
						{field:'productname',title:localStorage.getItem('catalogproductname'),width:'450px'},
					]]
				}	
			},
			width:'500px',
			sortable: true,
			formatter: function(value,row,index){
								return row.productname;
			}
		},
		{
			field:'description',
			title:localStorage.getItem('catalogdescription'), 
			editor:{
				type:'text',
				options:{
				}
			},
			width:'400px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'qty',
			title:localStorage.getItem('catalogqty'),
			width:'100px',
			editor: {
				type: 'numberbox',
				options:{
					precision:4,
					decimalSeparator:',',
					groupSeparator:'.',
					value:0,
					required:true,
					onChange: function(newValue,oldValue) {
						var tr = $(this).closest('tr.datagrid-row');
						var index = parseInt(tr.attr('datagrid-row-index'));
						var stdqty2 = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'stdqty2'});
						var qty2 = $('#dg-fixasset').datagrid('getEditor', {index: index, field:'qty2'});								
						$(qty2.target).numberbox('setValue',$(stdqty2.target).numberbox('getValue') * newValue);
					}
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
			}
		},
		{
			field:'uomid',
			title:localStorage.getItem('cataloguomcode'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'unitofmeasureid',
					textField:'uomcode',
					readonly:true,
					url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'unitofmeasureid',title:localStorage.getItem('catalogunitofmeasureid'),width:'50px'},
						{field:'uomcode',title:localStorage.getItem('cataloguomcode'),width:'150px'},
					]]
				}	
			},
			width:'90px',
			sortable: true,
			formatter: function(value,row,index){
								return row.uomcode;
			}
		},
		{
			field:'qty2',
			title:localStorage.getItem('catalogqty2'),
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
									return formatnumber('',value);
			}
		},
		{
			field:'uom2id',
			title:localStorage.getItem('cataloguom2code'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					readonly:true,
					hasDownArrow:false,
					idField:'unitofmeasureid',
					textField:'uomcode',
					url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'unitofmeasureid',title:localStorage.getItem('catalogunitofmeasureid'),width:'50px'},
						{field:'uomcode',title:localStorage.getItem('cataloguomcode'),width:'150px'},
					]]
				}	
			},
			width:'90px',
			sortable: true,
			formatter: function(value,row,index){
								return row.uom2code;
			}
		},
		{
			field:'ratesusut',
			title:localStorage.getItem('catalogratesusut'),
			width:'150px',
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
									return formatnumber('',value);
			}
		},		
		{
			field:'price',
			title:localStorage.getItem('catalogprice'),
			width:'150px',
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
									return formatnumber('',value);
			}
		},
		{
			field:'buydate',
			title:localStorage.getItem('catalogbuydate'),
			editor: {
				type: 'datebox',
				options:{
					required:true,
					formatter:dateformatter,
							parser:dateparser
				}
			},
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
								return value;
			}
		},
		{
			field:'nilairesidu',
			title:localStorage.getItem('catalognilairesidu'),
			width:'150px',
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
									return formatnumber('',value);
			}
		},
		{
			field:'currencyid',
			title:localStorage.getItem('catalogcurrency'),
			width:'100px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'currencyid',
					textField:'currencyname',
					url:'". Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'currencyid',title:localStorage.getItem('catalogcurrencyid'),width:'50px'},
						{field:'currencyname',title:localStorage.getItem('catalogcurrencyname'),width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.currencyname;
			}	
		},
		{
			field:'currencyrate',
			title:localStorage.getItem('catalogcurrencyrate'),
			width:'80px',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					decimalSeparator:',',
					groupSeparator:'.',
					required:true,
				}
			},
			align: 'right',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
			}},
		{
			field:'accakum',
			title:localStorage.getItem('catalogaccakum'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'550px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountcode',
					url:'". $this->createUrl('account/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'50px'},
						{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'100px'},
						{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'200px'},
						{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'200px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.accakumcode;
		}},
		{
			field:'accbiaya',
			title:localStorage.getItem('catalogaccbiaya'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'550px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountcode',
					url:'". $this->createUrl('account/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'50px'},
						{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'100px'},
						{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'200px'},
						{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'200px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.accbiayacode;
		}},
		{
			field:'accperolehan',
			title:localStorage.getItem('catalogaccperolehan'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'550px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountcode',
					url:'". $this->createUrl('account/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'50px'},
						{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'100px'},
						{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'200px'},
						{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'200px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.accperolehancode;
		}},
		{
			field:'acckorpem',
			title:localStorage.getItem('catalogacckorpem'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'550px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountcode',
					url:'". $this->createUrl('account/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'50px'},
						{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'100px'},
						{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'200px'},
						{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'200px'},
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.acckorpemcode;
		}},
		{
			field:'umur',
			title:localStorage.getItem('catalogumur'),
			width:'150px',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					decimalSeparator:',',
					groupSeparator:'.',
					required:true,
				}
			},
			align: 'right',
			sortable: true,
			formatter: function(value,row,index){
			return value;
		}},
		{
			field:'famethodid',
			title:localStorage.getItem('catalogfamethod'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'famethodid',
					textField:'methodname',
					url:'". $this->createUrl('famethod/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'famethodid',title:localStorage.getItem('catalogfamethodid'),width:'50px'},
						{field:'methodname',title:localStorage.getItem('catalogmethodname'),width:'200px'}
					]]
				}	
			},
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return row.methodname;
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
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('fixassetid','assetno','plantcode','pono','productname','umur','metode','description'),
	'columndetails'=> array (
	array(
			'id'=>'fahistory',
			'idfield'=>'fahistoryid',
			'urlsub'=>Yii::app()->createUrl('accounting/fixasset/indexfahistory',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/fixasset/searchfahistory',array('grid'=>true)),
			'subs'=>"
				{field:'bulanke',title:localStorage.getItem('catalogbulanke'),width:'70px'},
				{field:'susutdate',title:localStorage.getItem('catalogsusutdate'),width:'100px'},
				{field:'nilai',title:localStorage.getItem('catalognilai'),
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'beban',title:localStorage.getItem('catalogbeban'),
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'nilaiakum',title:localStorage.getItem('catalognilaiakum'),
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'nilaibuku',title:localStorage.getItem('catalognilaibuku'),
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
			",
			),
	array(
			'id'=>'fajurnal',
			'idfield'=>'fajurnalid',
			'urlsub'=>Yii::app()->createUrl('accounting/fixasset/indexfajurnal',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/fixasset/searchfajurnal',array('grid'=>true)),
			'subs'=>"
			{field:'susutdate',title:localStorage.getItem('catalogsusutdate'),width:'100px'},
				{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'400px'},
				{field:'currencyname',title:localStorage.getItem('catalogcurrencyname'),width:'150px'},
				{field:'debet',title:localStorage.getItem('catalogdebet'),
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'credit',title:localStorage.getItem('catalogcredit'),
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'150px'},
				{field:'currencyrate',title:localStorage.getItem('catalogcurrencyrate'),
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'}
			"
		)
)));