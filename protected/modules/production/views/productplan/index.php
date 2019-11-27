<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productplanid',
	'formtype'=>'masterdetail',
	'isxls'=>1,
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appprodplan',
	'url'=>Yii::app()->createUrl('production/productplan/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('production/productplan/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('production/productplan/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('production/productplan/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('production/productplan/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('production/productplan/approve',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/productplan/upload'),
	'rejecturl'=>Yii::app()->createUrl('production/productplan/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('production/productplan/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/productplan/downxls'),
	'columns'=>"
		{
			field:'productplanid',
			title:'".GetCatalog('ID') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appprodplan')."
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
			width:'100px',
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
			width:'140px',
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
			field:'parentplanid',
			title:'".GetCatalog('okparent') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return row.parentplanno;
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
	'addload'=>"
$('#productplan-plantid').combogrid('setValue',".Yii::app()->user->defaultplant.");
		$('#productplan-productplandate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	",
	'searchfield'=> array ('productplanid','plantcode','productplanno','productplandate','sono','customer','sloccode','productname','description','recordstatus'),
	'headerform'=> "
		<input type='hidden' id='productplan-productid' name='productplan-productid' value=''></input>
		<table cellpadding='5'>
		<tr>
				<td>".GetCatalog('productplandate')."</td>
				<td><input class='easyui-datebox' id='productplan-productplandate' name='productplan-productplandate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='productplan-plantid' name='productplan-plantid' style='width:150px' data-options=\"
								panelWidth: '500px',
								idField: 'plantid',
								required: true,
								textField: 'plantcode',
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'auth'=>true)) ."',
								method: 'get',
								columns: [[
										{field:'plantid',title:'".getCatalog('plantid') ."',width:'50px'},
										{field:'plantcode',title:'".getCatalog('plantcode') ."',width:'100px'},
										{field:'description',title:'".getCatalog('description') ."',width:'200px'},
								]],
								fitColumns: true
						\">
				</select></td>
</tr>
<tr>
			<td>".GetCatalog('soheader')."</td>
				<td>
					<select class='easyui-combogrid' id='productplan-soheaderid' name='productplan-soheaderid' style='width:250px' data-options=\"
						panelWidth: '700px',
						idField: 'soheaderid',
						textField: 'sono',
						mode:'remote',
						url: '".Yii::app()->createUrl('order/soheader/index',array('grid'=>true,'ppso'=>true)) ."',
						method: 'get',
						onShowPanel: function() {
							$('#productplan-soheaderid').combogrid('grid').datagrid('reload');
						},							
						onBeforeLoad: function(param) {
							param.plantid = $('#productplan-plantid').combogrid('getValue');
						},
						onHidePanel: function(){
							jQuery.ajax({'url':'".Yii::app()->createUrl('order/soheader/index',array('grid'=>true,'getdata'=>true)) ."',
								'data':{
									'soheaderid':$('#productplan-soheaderid').combogrid('getValue')
								},
								'type':'post',
								'dataType':'json',
								'success':function(data)
								{
									$('#productplan-addressbookid').combogrid('setValue',data.addressbookid);
									$('#productplan-description').textbox('setValue',data.headernote);
									jQuery.ajax({'url':'".Yii::app()->createUrl('production/productplan/generatedetail') ."',
										'data':{
											'id':$('#productplan-soheaderid').combogrid('getValue'),
											'hid':$('#productplan-productplanid').val(),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#dg-productplan-productplandetail').datagrid('reload');
											$('#dg-productplan-productplanfg').datagrid('reload');
										},
										'cache':false});
								},
								'cache':false});
						},
						columns: [[
								{field:'soheaderid',title:'".GetCatalog('soheaderid') ."',width:'50px'},
								{field:'sono',title:'".GetCatalog('sono') ."',width:'200px'},
								{field:'fullname',title:'".GetCatalog('fullname') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				<td>".GetCatalog('customer')."</td>
				<td>
					<select class='easyui-combogrid' id='productplan-addressbookid' name='productplan-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
								{field:'fullname',title:'".GetCatalog('fullname') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('okparent')."</td>
				<td>
					<select class='easyui-combogrid' id='productplan-parentplanid' name='productplan-parentplanid' style='width:250px' data-options=\"
						panelWidth: '500px',
						idField: 'productplanid',
						textField: 'productplanno',
						mode:'remote',
						url: '".Yii::app()->createUrl('production/productplan/index',array('grid'=>true,'lintaspp'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'productplanid',title:'".GetCatalog('productplanid') ."',width:'50px'},
								{field:'productplanno',title:'".GetCatalog('productplanno') ."',width:'150px'},
								{field:'fullname',title:'".GetCatalog('customer') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('description')."</td>
				<td><input class='easyui-textbox' name='productplan-description' id='productplan-description' data-options='required:false,multiline:true' style='width:300px;height:100px'/></td>
			</tr>
		</table>
	",
	'writebuttons'=>"
		<a href='javascript:void(0)' title='".getCatalog('holdok')."' class='easyui-linkbutton' iconCls='icon-lock' plain='true' onclick='holdok()'></a>
		<a href='javascript:void(0)' title='".getCatalog('openok')."' class='easyui-linkbutton' iconCls='icon-more' plain='true' onclick='openok()'></a>
		<a href='javascript:void(0)' title='".getCatalog('closeok')."' class='easyui-linkbutton' iconCls='icon-tip' plain='true' onclick='closeok()'></a>
		<a href='javascript:void(0)' title='".getCatalog('copyok')."' class='easyui-linkbutton' iconCls='icon-bom' plain='true' onclick='copyok()'></a>
	",
	'downloadbuttons'=>"
		<a href='javascript:void(0)' title='".getCatalog('pdfoperator')."' class='easyui-linkbutton' iconCls='icon-pdf' plain='true' onclick='pdfoperator()'></a>
		<a href='javascript:void(0)' title='".getCatalog('pdfFG')."' class='easyui-linkbutton' iconCls='icon-pdf' plain='true' onclick='pdfFG()'></a>
	",
	'loadsuccess' => "
		$('#productplan-productplanno').textbox('setValue',data.productplanno);
		$('#productplan-productplandate').datebox('setValue',data.productplandate);
		$('#productplan-plantid').combogrid('setValue',data.plantid);
		$('#productplan-soheaderid').combogrid('setValue',data.soheaderid);
		$('#productplan-addressbookid').combogrid('setValue',data.addressbookid);
		$('#productplan-parentplanid').combogrid('setValue',data.parentplanid);
		$('#productplan-description').textbox('setValue',data.description);
	",
	'addonscripts'=>"
		function holdok() {
			var rows = $('#dg-productplan').edatagrid('getSelected');
			jQuery.ajax({'url':'".$this->createUrl('productplan/holdok') ."',
				'data':{'id':rows.productplanid},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-productplan').edatagrid('reload');				
				} ,
				'cache':false});
		};
		function closeok() {
			var rows = $('#dg-productplan').edatagrid('getSelected');
			jQuery.ajax({'url':'".$this->createUrl('productplan/closeok') ."',
				'data':{'id':rows.productplanid},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-productplan').edatagrid('reload');				
				} ,
				'cache':false});
		};
		function openok() {
			var rows = $('#dg-productplan').edatagrid('getSelected');
			jQuery.ajax({'url':'".$this->createUrl('productplan/openok') ."',
				'data':{'id':rows.productplanid},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-productplan').edatagrid('reload');				
				} ,
				'cache':false});
		};
		function copyok() {
			var rows = $('#dg-productplan').edatagrid('getSelected');
			jQuery.ajax({'url':'".$this->createUrl('productplan/copyok') ."',
				'data':{'id':rows.productplanid},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-productplan').edatagrid('reload');				
				} ,
				'cache':false});
		};
		function pdfoperator() {
			var rows = $('#dg-productplan').edatagrid('getSelected');
			var array = 'id='+rows.productplanid;
			window.open('".Yii::app()->createUrl('production/productplan/pdfoperator')."?'+array);
		};
		function pdfFG() {
			var rows = $('#dg-productplan').edatagrid('getSelected');
			var array = 'id='+rows.productplanid;
			window.open('".Yii::app()->createUrl('production/productplan/pdfFG')."?'+array);
		};
		function GenerateBOMPP() {
			var rows = $('#dg-productplan-productplanfg').edatagrid('getSelections');
			jQuery.ajax({'url':'".$this->createUrl('productplan/startup') ."',
				'data':{
					'hid':$('#productplan-productplanid').val(),
				},'type':'post','dataType':'json',
				error: function(xhr,status,error) {
					show('Pesan','An Error Occured: '+xhr.status+ ' ' +xhr.responseText,'error');
				},
				'success':function(data)
				{
					jQuery.ajax({'url':'".$this->createUrl('productplan/GenerateBOMPP') ."',
						'data':{
							'plantid':$('#productplan-plantid').combogrid('getValue'),
							'id':rows,
							'hid':$('#productplan-productplanid').val(),
						},'type':'post','dataType':'json',
						error: function(xhr,status,error) {
							show('Pesan','An Error Occured: '+xhr.status+ ' ' +xhr.responseText,'error');
						},
						'success':function(data)
						{
							show('Pesan',data.msg);
							$('#dg-productplan-productplanfg').edatagrid('reload');				
							$('#dg-productplan-productplandetail').edatagrid('reload');				
						} ,
						'cache':false});
				} ,
				'cache':false});
		}
		",
	'columndetails'=> array (
		array(
			'id'=>'productplanfg',
			'idfield'=>'productplanfgid',
			'urlsub'=>Yii::app()->createUrl('production/productplan/indexhasil',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/productplan/searchhasil',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/productplan/savehasil',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/productplan/savehasil',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/productplan/purgehasil',array('grid'=>true)),
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
				{field:'qtyso',title:'".GetCatalog('qtyso') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'qty',title:'".GetCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'qtyres',title:'".GetCatalog('qtyprod') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'qtyout',title:'".GetCatalog('qtyout') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'100px'},
				{field:'qtyokfree',title:'".GetCatalog('qtyokfree') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'uomcode',title:'".GetCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom2code);
					},width:'100px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom3code);
					},width:'100px'},
				{field:'bomversion',title:'".GetCatalog('bomversion') ."',width:'100px'},
				{field:'processprdname',title:'".GetCatalog('processprdname') ."',width:'100px'},
				{field:'namamesin',title:'".GetCatalog('mesin') ."',width:'150px'},
				{field:'sloccode',title:'".GetCatalog('sloccode') ."',width:'100px'},
				{field:'delvdate',title:'".GetCatalog('delvdate') ."',width:'100px'},
				{field:'startdate',title:'".GetCatalog('startdate') ."',width:'100px'},
				{field:'enddate',title:'".GetCatalog('enddate') ."',width:'100px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'100px'}
			",
			'addonbuttons'=>"
				<a href='javascript:void(0)' title='Generate Plan'class='easyui-linkbutton' iconCls='icon-bom' plain='true' onclick='GenerateBOMPP()'></a>
			",
			'columns'=>"
				{
					field:'productplanid',
					title:'".GetCatalog('productplanid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'productplanfgid',
					title:'".GetCatalog('productplanfgid') ."',
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'sodetailid',
					title:'".GetCatalog('sodetailid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'stdqty',
					title:'".getCatalog('stdqty') ."',
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
					field:'stdqty2',
					title:'".getCatalog('stdqty2') ."',
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
					field:'stdqty3',
					title:'".getCatalog('stdqty3') ."',
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
					title:'".GetCatalog('materialtypecode') ."',
					editor: {
						type: 'textbox',
						options:{
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
							panelWidth:'650px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plantplanhp'=>true)) ."',
							onBeforeLoad: function(param){
								param.plantid = $('#productplan-plantid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onHidePanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'uom3id'});
								var stdqty = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'stdqty3'});
								var bomid = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'bomid'});								
								var processprdid = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'processprdid'});								
								var mesinid = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'mesinid'});								
								$('#productplan-productid').val($(productid.target).combogrid('getValue'));
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/productplant/index',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(uom3id.target).combogrid('setValue',data.uom3);
										$(stdqty.target).numberbox('setValue',data.qty);
										$(stdqty2.target).numberbox('setValue',data.qty2);
										$(stdqty3.target).numberbox('setValue',data.qty3);
										$(bomid.target).combogrid('setValue',data.bomid);
										$(processprdid.target).combogrid('setValue',data.processprdid);
										$(mesinid.target).combogrid('setValue',data.mesinid);
									} ,
									'cache':false});
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
								{field:'materialtypecode',title:'".getCatalog('materialtypecode')."',width:'150px'},
								{field:'productname',title:'".getCatalog('productname')."',width:'450px'},
							]]
						}	
					},
					width:'350px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productname;
					}
				},
				{
					field:'qtyso',
					title:'".getCatalog('qtyos') ."',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
						return formatnumber('',value);
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
							required:true,
							onChange: function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var stdqty = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'stdqty3'});
								var qty2 = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'qty3'});								
								$(qty2.target).numberbox('setValue',$(stdqty2.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
								$(qty3.target).numberbox('setValue',$(stdqty3.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
							}
						}
					},
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber('',value);
					}
				},
				{
					field:'qtyres',
					title:'".getCatalog('qtyprod') ."',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
						return formatnumber('',value);
					}
				},
				{
					field:'qtyout',
					title:'".getCatalog('qtyout') ."',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
						return formatnumber('',value);
					}
				},
				{
					field:'qtyokfree',
					title:'".getCatalog('qtyokfree') ."',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
						return formatnumber('',value);
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
									idField:'unitofmeasureid',
									textField:'uomcode',
									url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
									fitColumns:true,
									required:true,
									hasDownArrow:false,
									readonly:true,
									loadMsg: '".GetCatalog('pleasewait')."',
									columns:[[
										{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
										{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
									]]
							}	
						},
					width:'80px',
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
						return formatnumber('',value);
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
									idField:'unitofmeasureid',
									textField:'uomcode',
									url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
									fitColumns:true,
									required:true,
									hasDownArrow:false,
									readonly:true,
									loadMsg: '".GetCatalog('pleasewait')."',
									columns:[[
										{field:'unitofmeasureid',title:'".GetCatalog('unitofmeasureid')."',width:'50px'},
										{field:'uomcode',title:'".GetCatalog('uomcode')."',width:'150px'},
									]]
							}	
						},
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
										return row.uom2code;
					}
				},
				{
					field:'qty3',
					title:'".GetCatalog('qty3') ."',
					width:'80px',
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
						return formatnumber('',value);
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
									idField:'unitofmeasureid',
									textField:'uomcode',
									url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
									fitColumns:true,
									hasDownArrow:false,
									readonly:true,
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
					field:'sloctoid',
					title:'".GetCatalog('slocprocess') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'slocid',
							textField:'sloccode',
							url:'".Yii::app()->createUrl('common/sloc/indextrxplantsloc',array('grid'=>true)) ."',
							onShowPanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var sloctoid = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'sloctoid'});
								$(sloctoid.target).combogrid('grid').datagrid('reload');
							},
							onBeforeLoad: function(param){
								param.plantid = $('#productplan-plantid').combogrid('getValue');
								param.productid = $('#productplan-productid').val();
								param.issource = 1;
							},
							fitColumns:true,
							required:true,
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
					field:'startdate',
					title:'".GetCatalog('startdate')."',
					editor: {
						type: 'datebox',
						options: {
							required:true
						}
					},
					sortable: true,
					width:'150px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'delvdate',
					title:'".GetCatalog('delvdate')."',
					sortable: true,
					width:'150px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'enddate',
					title:'".GetCatalog('enddate')."',
					editor: {
						type: 'datebox',
						options: {
							required:true
						}
					},
					sortable: true,
					width:'150px',
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'processprdid',
					title:'".GetCatalog('processprd') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'processprdid',
							textField:'processprdname',
							url:'".Yii::app()->createUrl('production/processprd/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'processprdid',title:'".GetCatalog('processprdid')."',width:'50px'},
								{field:'processprdname',title:'".GetCatalog('processprdname')."',width:'250px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return row.processprdname;
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
							url:'".Yii::app()->createUrl('common/mesin/index',array('grid'=>true,'mesinsloc'=>true)) ."',
							onShowPanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var mesinid = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'mesinid'});
								var slocid = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'sloctoid'});
								$(mesinid.target).combogrid('grid').datagrid('load',{
									slocid: $(slocid.target).combogrid('getValue')
								});
							},
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'mesinid',title:'".getCatalog('mesinid')."',width:'80px'},
								{field:'kodemesin',title:'".getCatalog('kodemesin')."',width:'80px'},
								{field:'namamesin',title:'".getCatalog('namamesin')."',width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return row.namamesin;
					}
				},
				{
					field:'bomid',
					title:'".getCatalog('bom') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'550px',
							mode : 'remote',
							method:'get',
							idField:'bomid',
							textField:'bomversion',
							url:'".$this->createUrl('bom/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							onShowPanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var bomid = $('#dg-productplan-productplanfg').datagrid('getEditor', {index: index, field:'bomid'});
								$(bomid.target).combogrid('grid').datagrid('reload');
							},
							onBeforeLoad: function(param){
								param.plantid = $('#productplan-plantid').combogrid('getValue');
								param.productid = $('#productplan-productid').val();
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'bomid',title:'".getCatalog('bomid')."',width:'80px'},
								{field:'bomversion',title:'".getCatalog('bomversion')."',width:'120px'},
								{field:'productname',title:'".getCatalog('productname')."',width:'200px'},
							]]
						}	
					},
					width:'350px',
					sortable: true,
					formatter: function(value,row,index){
						return row.bomversion;
					}
				},
				{
					field:'description',
					title:'".GetCatalog('description')."',
					editor: 'textbox',
					sortable: true,
					width:'200px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
		array(
			'id'=>'productplandetail',
			'idfield'=>'productplandetailid',
			'urlsub'=>Yii::app()->createUrl('production/productplan/indexdetail',array('grid'=>true)),
			'url'=> Yii::app()->createUrl('production/productplan/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/productplan/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/productplan/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/productplan/purgebahan',array('grid'=>true)),
			'subs'=>"
				{field:'productplandetailid',title:'".GetCatalog('ID') ."',width:'50px'},
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
						return formatnumber('',value,row.uomcode);
					},width:'100px'},
				{field:'qtyfr',title:'".GetCatalog('qtyfr') ."',
					formatter: function(value,row,index){
						if (row.frcount == '1') {
							return '<div style=\"background-color:cyan\">'+formatnumber('',value)+'</div>';
						} else {
							return formatnumber('',value,row.uomcode);
						}
					},width:'100px'},
				{field:'qtytrf',title:'".GetCatalog('qtytrf') ."',
					formatter: function(value,row,index){
						if (row.trfcount == '1') {
							return '<div style=\"background-color:cyan\">'+formatnumber('',value)+'</div>';
						} else {
							return formatnumber('',value,row.uomcode);
						}
					},width:'100px'},
				{field:'qtyres',title:'".GetCatalog('qtyuse') ."',
					formatter: function(value,row,index){
						if (row.rescount == '1') {
							return '<div style=\"background-color:cyan\">'+formatnumber('',value)+'</div>';
						} else {
							return formatnumber('',value,row.uomcode);
						}
					},width:'100px'},
				{field:'qty2',title:'".GetCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom2code);
					},width:'100px'},
				{field:'qtyres2',title:'".GetCatalog('qtyuse2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom2code);
					},width:'100px'},
				{field:'qty3',title:'".GetCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom3code);
					},width:'100px'},
				{field:'qtyres3',title:'".GetCatalog('qtyuse3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value,row.uom3code);
					},width:'100px'},
				{field:'bomversion',title:'".GetCatalog('bomversion') ."',width:'200px'},
				{field:'slocfromcode',title:'".GetCatalog('slocfromcode') ."',width:'150px'},
				{field:'sloctocode',title:'".GetCatalog('sloctocode') ."',width:'150px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
			",
			'onbeginedit'=>"
				row.productplanid = $('#productplanid').val();
				var rowx = $('#dg-productplan-productplandetail').edatagrid('getSelected');
				if (rowx)
				{
					row.productplandetailid = rowx.productplandetailid;
				}
			",
			'columns'=>"
			{
				field:'productplandetailid',
				title:'".GetCatalog('productplandetailid') ."',
				sortable: true,
				hidden:true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'productplanid',
				title:'".GetCatalog('productplanid') ."',
				hidden:true,
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'productplandetailid',
				title:'".GetCatalog('productplandetailid') ."',
				sortable: true,
				hidden:true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
					field:'stdqty',
					title:'".getCatalog('stdqty') ."',
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
					field:'stdqty2',
					title:'".getCatalog('stdqty2') ."',
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
					field:'stdqty3',
					title:'".getCatalog('stdqty3') ."',
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
				field:'productplanfgid',
				title:'".GetCatalog('planfgid') ."',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'productplanfgid',
						textField:'productname',
						url:'".Yii::app()->createUrl('production/productplan/indexhasil',array('grid'=>true)) ."',
						onBeforeLoad: function(param) {
							param.id = $('#productplan-productplanid').val();
						},
						fitColumns:true,
						required:true,
						width:'280px',
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'productplanfgid',title:'".GetCatalog('productplanfgid')."',width:'50px'},
							{field:'productname',title:'".GetCatalog('productname')."',width:'200px'},
						]]
					}	
				},
				sortable: true,
				formatter: function(value,row,index){
					return row.productplanfgid;
				}
			},
							{
					field:'materialtypecode',
					title:'".GetCatalog('materialtypecode') ."',
					editor: {
						type: 'textbox',
						options:{
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
							panelWidth:'650px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
							onBeforeLoad: function(param){
								param.plantid = $('#productplan-plantid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onChange:function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'productid'});
								var uomid = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'uom3id'});
								var stdqty = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var bomid = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'bomid'});								
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/product/indexproductplant',array('grid'=>true,'getdata'=>true)) ."',
									'data':{'productid':$(productid.target).combogrid('getValue')},
									'type':'post','dataType':'json',
									'success':function(data)
									{
										$(uomid.target).combogrid('setValue',data.uom1);
										$(uom2id.target).combogrid('setValue',data.uom2);
										$(uom3id.target).combogrid('setValue',data.uom3);
										$(stdqty.target).numberbox('setValue',data.qty1);
										$(stdqty2.target).numberbox('setValue',data.qty2);
										$(stdqty3.target).numberbox('setValue',data.qty3);
										$(bomid.target).combogrid('setValue',data.bomid);
										$('#productplan-productid').val(data.productid);
									} ,
									'cache':false});
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
								{field:'materialtypecode',title:'".getCatalog('materialtypecode')."',width:'150px'},
								{field:'productname',title:'".getCatalog('productname')."',width:'450px'},
							]]
						}	
					},
					width:'350px',
					sortable: true,
					formatter: function(value,row,index){
										return row.productname;
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
							required:true,
							onChange: function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var stdqty = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var qty2 = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'qty3'});								
								$(qty2.target).numberbox('setValue',$(stdqty2.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
								$(qty3.target).numberbox('setValue',$(stdqty3.target).numberbox('getValue') * (newValue / $(stdqty.target).numberbox('getValue')));
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
					title:'".GetCatalog('uomcode') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							hasDownArrow:false,
							readonly:true,
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
						return formatnumber('',value);
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
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							hasDownArrow:false,
							readonly:true,
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
						return formatnumber('',value);
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
							idField:'unitofmeasureid',
							textField:'uomcode',
							url:'".Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							hasDownArrow:false,
							readonly:true,
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
				field:'bomid',
				title:'".GetCatalog('bom') ."',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'bomid',
						textField:'bomversion',
						url:'".$this->createUrl('bom/index',array('grid'=>true,'combo'=>true)) ."',
						onShowPanel: function() {
							var tr = $(this).closest('tr.datagrid-row');
							var index = parseInt(tr.attr('datagrid-row-index'));
							var bomid = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'bomid'});
							$(bomid.target).combogrid('grid').datagrid('reload');
						},
						onBeforeLoad: function(param){
							param.plantid = $('#productplan-plantid').combogrid('getValue');
							param.productid = $('#productplan-productid').val();
						},
						fitColumns:true,
						width:'280px',
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'bomid',title:'".GetCatalog('bomid')."',width:'50px'},
							{field:'bomversion',title:'".GetCatalog('bomversion')."',width:'200px'},
							{field:'productname',title:'".GetCatalog('productname')."',width:'250px'},
						]]
					}	
				},
				width:'300px',
				sortable: true,
				formatter: function(value,row,index){
					return row.bomversion;
				}
			},
			{
					field:'slocfromid',
					title:'".GetCatalog('slocfrom') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'slocid',
							textField:'sloccode',
							url:'".Yii::app()->createUrl('common/sloc/indextrxplantsource',array('grid'=>true)) ."',
							onShowPanel:function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var slocfromid = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'slocfromid'});
								var productid = $('#dg-productplan-productplandetail').datagrid('getEditor', {index: index, field:'productid'});
								$(slocfromid.target).combogrid('grid').datagrid('reload');
							},
							onBeforeLoad: function(param){
								param.plantid = $('#productplan-plantid').combogrid('getValue');
								param.productid = $('#productplan-productid').val();
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
										return row.slocfromcode;
					}
				},
			{
					field:'sloctoid',
					title:'".GetCatalog('slocprocess') ."',
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
								param.plantid = $('#productplan-plantid').combogrid('getValue');
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
										return row.sloctocode;
					}
				},
			{
				field:'description',
				title:'".GetCatalog('description')."',
				editor:{
					type: 'textbox',
				},
				sortable: true,
				width:'250px',
				formatter: function(value,row,index){
					return value;
				}
			},	
			"
		)
	),	
));
