<input type='hidden' id='bom-stdqty' name='bom-stdqty'></input>
<input type='hidden' id='bom-stdqty2' name='bom-stdqty2'></input>
<input type='hidden' id='bom-stdqty3' name='bom-stdqty3'></input>
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'bomid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('production/bom/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('production/bom/getData'),
	'saveurl'=>Yii::app()->createUrl('production/bom/save'),
	'destroyurl'=>Yii::app()->createUrl('production/bom/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('production/bom/upload'),
	'downpdf'=>Yii::app()->createUrl('production/bom/downpdf'),
	'downxls'=>Yii::app()->createUrl('production/bom/downxls'),
	'rowstyler'=>"
		if (row.jumlah == 0) {
			return 'background-color:red;color:#fff;';
		}  
	",
	'columns'=>"
		{
			field:'bomid',
			title:'".getCatalog('bomid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productname',
			title:'".getCatalog('productname') ."',
			sortable: true,
			width:'450px',
			formatter: function(value,row,index){
				return row.productname;
		}},
		{
			field:'plantid',
			title:'".getCatalog('plantcode') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'bomversion',
			title:'".getCatalog('bomversion') ."',
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bomdate',
			title:'".getCatalog('bomdate') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'materialtypecode',
			title:'".getCatalog('materialtypecode') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return row.materialtypecode;
		}},
		{
			field:'qty',
			title:'".getCatalog('qty') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return formatnumber('',row.qtyview);
		}},
		{ 
			field:'uomid',
			title:'".getCatalog('uomcode') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return row.uomcode;
		}},
		{
			field:'qty2',
			title:'".getCatalog('qty2') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
						return formatnumber('',row.qty2view);
		}},
		{
			field:'uom2id',
			title:'".getCatalog('uom2code') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return row.uom2code;
		}},
		{
			field:'qty3',
			title:'".getCatalog('qty3') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
						return formatnumber('',row.qty3view);
		}},
		{
			field:'uom3id',
			title:'".getCatalog('uom3code') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return row.uom3code;
		}},
		{
			field:'mesinid',
			title:'".getCatalog('mesin') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return row.kodemesin;
		}},
		{
			field:'numoperator',
			title:'".getCatalog('numoperator') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'processprdid',
			title:'".getCatalog('processprd') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.processprdname;
		}},
		{
			field:'description',
			title:'".getCatalog('description') ."',
			sortable: true,
			width:'250px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus') ."',
			align:'center',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},",
	'searchfield'=> array ('bomid','plantcode','bomversion','bomdate','materialtypecode','productname','kodemesin','namamesin','processprd','description'),
	'headerform'=> "
		<table cellpadding='5'>
		<tr>
				<td>".getCatalog('bomdate')."</td>
				<td><input class='easyui-datebox' type='text' id='bom-bomdate' name='bom-bomdate' data-options='readonly:true,formatter:dateformatter,required:true,parser:dateparser' ></input></td>
				<td>".getCatalog('bomversion')."</td>
				<td><input class='easyui-textbox' type='text' id='bom-bomversion' name='bom-bomversion' data-options='required:true' style='width:280px'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='bom-plantid' name='bom-plantid' style='width:150px' data-options=\"
								panelWidth: '500px',
								idField: 'plantid',
								required: true,
								textField: 'plantcode',
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'combo'=>true)) ."',
								method: 'get',
								onHidePanel: function(){
									jQuery.ajax({'url':'".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'getdata'=>true)) ."',
										'data':{
											'plantid':$('#bom-plantid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#bom-companyname').textbox('setValue',data.companyname);
										},
										'cache':false});
								},
								columns: [[
										{field:'plantid',title:'".getCatalog('plantid') ."',width:'50px'},
										{field:'plantcode',title:'".getCatalog('plantcode') ."',width:'100px'},
										{field:'description',title:'".getCatalog('description') ."',width:'200px'},
								]],
								fitColumns: true
						\">
				</select></td>
				<td>".GetCatalog('company')."</td>
				<td><input class='easyui-textbox' id='bom-companyname' name='bom-companyname' style='width:280px' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('product')."</td>
				<td><select class='easyui-combogrid' id='bom-productid' name='bom-productid' style='width:150px' data-options=\"
								panelWidth: '600px',
								idField: 'productid',
								required: true,
								textField: 'productcode',
								mode: 'remote',
								url: '".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plantplanhp'=>true)) ."',
								onShowPanel:function() {
									$('#bom-productid').combogrid('grid').datagrid('reload');
								},
								onBeforeLoad: function(param) {
									param.plantid = $('#bom-plantid').combogrid('getValue');
								},
								method: 'get',
								onHidePanel: function() {
									jQuery.ajax({'url':'".Yii::app()->createUrl('common/product/getproductplant') ."',
										'data':{
											'productid':$('#bom-productid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#bom-bomversion').textbox('setValue',data.productcode + 'x');
											$('#bom-productname').textbox('setValue',data.productname);
											$('#bom-uomid').val(data.uom1);
											$('#bom-uomcode').textbox('setValue',data.uom1code);	
											$('#bom-uom2id').val(data.uom2);
											$('#bom-uom2code').textbox('setValue',data.uom2code);	
											$('#bom-uom3id').val(data.uom3);
											$('#bom-uom3code').textbox('setValue',data.uom3code);	
											$('#bom-stdqty').val(data.qty);	
											$('#bom-stdqty2').val(data.qty2);	
											$('#bom-stdqty3').val(data.qty3);	
										},
										'cache':false});
								},
								columns: [[
										{field:'productid',title:'".getCatalog('productid') ."',width:'50px'},
										{field:'materialtypecode',title:'".getCatalog('materialtypecode') ."',width:'100px'},
										{field:'productcode',title:'".getCatalog('productcode') ."',width:'120px'},
										{field:'productname',title:'".getCatalog('productname') ."',width:'450px'},
								]],
								fitColumns: true
						\">
				</select></td>
				<td>".GetCatalog('productname')."</td>
				<td><input class='easyui-textbox' id='bom-productname' name='bom-productname' style='width:280px' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('qty')."</td>
				<td><input class='easyui-numberbox' type='number' id='bom-qty' name='bom-qty' style='width:100px' value='1' data-options=\"
					required:true,
					precision:4,
					mode : 'remote',
					onChange: function(newValue, oldValue){
						$('#bom-qty2').numberbox('setValue',(newValue / $('#bom-stdqty').val()) * $('#bom-stdqty2').val());
						$('#bom-qty3').numberbox('setValue',(newValue / $('#bom-stdqty').val()) * $('#bom-stdqty3').val());
					},
					queryParams:{
						combo:true
					}
				\"></td>
				<td>".getCatalog('uomcode')."</td>
				<td><input type='hidden' id='bom-uomid' name='bom-uomid'></input>
				<input class='easyui-textbox' id='bom-uomcode' name='bom-uomcode' style='width:280px' data-options='readonly:true'></input>
				</td>
			</tr>
			<tr>
			</tr>			
			<tr>
				<td>".getCatalog('qty2')."</td>
				<td><input class='easyui-numberbox' type='number' id='bom-qty2' name='bom-qty2' style='width:100px' data-options='required:true,precision:4' ></td>
				<td>".getCatalog('uom2code')."</td>
				<td><input type='hidden' id='bom-uom2id' name='bom-uom2id'></input>
				<input class='easyui-textbox' id='bom-uom2code' name='bom-uom2code' style='width:280px' data-options='readonly:true'></input></td>
			</tr>
			<tr>
			</tr>			
			<tr>
				<td>".getCatalog('qty3')."</td>
				<td><input class='easyui-numberbox' type='number' id='bom-qty3' name='bom-qty3' style='width:100px' data-options='required:false,precision:4' ></td>
				<td>".getCatalog('uom3code')."</td>
				<td><input type='hidden' id='bom-uom3id' name='bom-uom3id'></input>
				<input class='easyui-textbox' id='bom-uom3code' name='bom-uom3code' style='width:280px' data-options='readonly:true'></input></td>
			</tr>
			<tr>
				<td>".getCatalog('mesin')."</td>
				<td><select class='easyui-combogrid' id='bom-mesinid' name='bom-mesinid' style='width:200px' data-options=\"
								panelWidth: '500px',
								idField: 'mesinid',
								required: true,
								textField: 'kodemesin',
								mode : 'remote',
								url:'".Yii::app()->createUrl('common/mesin/index',array('grid'=>true,'mesinplant'=>true)) ."',
								onShowPanel:function() {
									$('#bom-mesinid').combogrid('grid').datagrid('reload');
								},
								onBeforeLoad:function(param){
									param.plantid = $('#bom-plantid').combogrid('getValue');
								},
								method: 'get',
								onHidePanel: function(){
									jQuery.ajax({'url':'".Yii::app()->createUrl('common/mesin/index',array('grid'=>true,'getdata'=>true)) ."',
										'data':{
											'mesinid':$('#bom-mesinid').combogrid('getValue'),
										},
										'type':'post',
										'dataType':'json',
										'success':function(data)
										{
											$('#bom-numoperator').numberbox('setValue',data.orgpershift);	
										},
										'cache':false});
								},
								columns: [[
										{field:'mesinid',title:'".getCatalog('mesinid') ."',width:'80px'},
										{field:'kodemesin',title:'".getCatalog('kodemesin') ."',width:'100px'},
										{field:'namamesin',title:'".getCatalog('namamesin') ."',width:'250px'},
								]],
								fitColumns: true
						\">
				</select></td>
				<td>".getCatalog('numoperator')."</td>
				<td><input class='easyui-numberbox' type='number' id='bom-numoperator' name='bom-numoperator' style='width:80px' data-options='required:true' ></td>
			</tr>
			<tr>
			</tr>
			<tr>
				<td>".getCatalog('processprd')."</td>
				<td><select class='easyui-combogrid' id='bom-processprdid' name='bom-processprdid' style='width:200px' data-options=\"
								panelWidth: '500px',
								idField: 'processprdid',
								required: true,
								textField: 'processprdname',
								mode : 'remote',
								url:'".Yii::app()->createUrl('production/processprd/index',array('grid'=>true,'combo'=>true)) ."',
								onShowPanel:function() {
									$('#bom-processprdid').combogrid('grid').datagrid('reload');
								},
								method: 'get',
								columns: [[
										{field:'processprdid',title:'".getCatalog('processprdid') ."',width:'80px'},
										{field:'processprdname',title:'".getCatalog('processprdname') ."',width:'250px'},
								]],
								fitColumns: true
						\">
				</select></td>
				<td>".getCatalog('recordstatus')."</td>
				<td><input class='easyui-checkbox' id='bom-recordstatus' name='bom-recordstatus'></input></td>
			</tr>			
			<tr>
				<td>".getCatalog('description')."</td>
				<td><input class='easyui-textbox' id='bom-description' name='bom-description' data-options='multiline:true' style='width:300px;height:100px'></input></td>
			</tr>
			<tr>
			</tr>
		</table>
	",
	'downloadbuttons'=>"
		<a href='javascript:void(0)' title='".getCatalog('Copy')."' class='easyui-linkbutton' iconCls='icon-bom' plain='true' onclick='copyBom()'></a>
	",
	'addonscripts'=>"
		function copyBom() {
			var ss = [];
			var rows = $('#dg-bom').edatagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				ss.push(row.bomid);
			}
			jQuery.ajax({'url':'".$this->createUrl('bom/copybom') ."',
				'data':{'id':ss},'type':'post','dataType':'json',
				'success':function(data)
				{
					show('Pesan',data.msg);
					$('#dg-bom').edatagrid('reload');				
				} ,
				'cache':false});
		};
	",
	'columndetails'=> array (
		array(
			'id'=>'bomdetail',
			'idfield'=>'bomdetailid',
			'urlsub'=>Yii::app()->createUrl('production/bom/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('production/bom/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('production/bom/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('production/bom/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('production/bom/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'materialtypecode',title:'".getCatalog('materialtypecode') ."',width:'150px'},
				{field:'productname',title:'".getCatalog('productname') ."',width:'500px'},
				{field:'qty',title:'".getCatalog('qty') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'80px'},
				{field:'uomcode',title:'".getCatalog('uomcode') ."',width:'80px'},
				{field:'qty2',title:'".getCatalog('qty2') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					},width:'80px'},
				{field:'uom2code',title:'".getCatalog('uom2code') ."',width:'80px'},
				{field:'qty3',title:'".getCatalog('qty3') ."',
					formatter: function(value,row,index){
						return formatnumber('',value);
					}, width:'80px'},
				{field:'uom3code',title:'".getCatalog('uom3code') ."',width:'80px'},
				{field:'productbomversion',title:'".getCatalog('bom') ."',width:'300px'},
			",
			'columns'=>"
				{
					field:'bomid',
					title:'".getCatalog('bomid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'bomdetailid',
					title:'".getCatalog('bomdetailid') ."',
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
					field:'materialtypecode',
					title:'".GetCatalog('materialtypecode') ."',
					width:'150px',
					editor:{
						type: 'textbox',
						options: {
							readonly:true,
						}
					},
					sortable: true,
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
							panelWidth:'600px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
							onBeforeLoad:function(param) {
								param.plantid = $('#bom-plantid').combogrid('getValue');
							},
							fitColumns:true,
							required:true,
							onHidePanel: function() {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var productid = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'productid'});
								var stdqty = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var uomid = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'uomid'});
								var uom2id = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'uom2id'});
								var uom3id = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'uom3id'});
								var productbomid = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'productbomid'});
								jQuery.ajax({'url':'".Yii::app()->createUrl('common/product/getproductplant') ."',
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
										$(productbomid.target).combogrid('setValue',data.bomid);
									} ,
									'cache':false});
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
								{field:'materialtypecode',title:'".getCatalog('materialtypecode')."',width:'100px'},
								{field:'productname',title:'".getCatalog('productname')."',width:'450px'},
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
							onChange: function(newValue,oldValue) {
								var tr = $(this).closest('tr.datagrid-row');
								var index = parseInt(tr.attr('datagrid-row-index'));
								var stdqty = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'stdqty'});
								var stdqty2 = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'stdqty2'});
								var stdqty3 = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'stdqty3'});
								var qty2 = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'qty2'});								
								var qty3 = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'qty3'});								
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
							readonly:true,
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
							readonly:true,
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
							readonly:true,
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
					field:'productbomid',
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
								var productbomid = $('#dg-bom-bomdetail').datagrid('getEditor', {index: index, field:'productbomid'});
								$(productbomid.target).combogrid('grid').datagrid('reload');
							},
							onBeforeLoad: function(param){
								param.plantid = $('#bom-plantid').combogrid('getValue');
								param.productid = $('#bom-productid').val();
							},
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'bomid',title:'".getCatalog('bomid')."',width:'80px'},
								{field:'bomversion',title:'".getCatalog('bomversion')."',width:'120px'},
								{field:'productname',title:'".getCatalog('productname')."',width:'200px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.productbomversion;
					}
				},
				{
					field:'productparentid',
					title:'".getCatalog('productparentname') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'600px',
							mode: 'remote',
							method:'get',
							idField:'productid',
							textField:'productname',
							url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'plant'=>true)) ."',
							onBeforeLoad:function(param) {
								param.plantid = $('#bom-plantid').combogrid('getValue');
							},
							fitColumns:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'productid',title:'".getCatalog('productid')."',width:'50px'},
								{field:'materialtypecode',title:'".getCatalog('materialtypecode')."',width:'100px'},
								{field:'productname',title:'".getCatalog('productname')."',width:'450px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
										return row.parentname;
					}
				},
				{
					field:'description',
					title:'".getCatalog('description') ."',
					editor: {
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
			"
		),
	),	
));