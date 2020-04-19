<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'ttntid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'isupload'=>0,
	'wfapp'=>'appttnt',
	'url'=>Yii::app()->createUrl('accounting/ttnt/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/ttnt/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/ttnt/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/ttnt/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/ttnt/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/ttnt/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/ttnt/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/ttnt/downpdf'),
	'columns'=>"
		{
			field:'ttntid',
			title:'".GetCatalog('ttntid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appttnt')."
		}},
		{
			field:'plantid',
			title:'".GetCatalog('plant') ."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'ttntdate',
			title:'".GetCatalog('ttntdate') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'ttntno',
			title:'".GetCatalog('ttntno') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'addressbookid',
			title:'".GetCatalog('customer') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return row.customername;
					}},
		{
			field:'description',
			title:'".GetCatalog('description') ."',
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'statusname',
			title:'".GetCatalog('recordstatus') ."',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},",
	'searchfield'=> array ('ttntid','plantcode','ttntno','referenceno','ttntdate','headernote','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('ttntdate')."</td>
				<td><input class='easyui-datebox' id='ttnt-ttntdate' name='ttnt-ttntdate' data-options='readonly:true,formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('plant')."</td>
				<td>
					<select class='easyui-combogrid' id='ttnt-plantid' name='ttnt-plantid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'plantid',
						textField: 'companyname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'auth'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'plantid',title:'".GetCatalog('plantid') ."',width:'50px'},
								{field:'plantcode',title:'".GetCatalog('plantcode') ."',width:'100px'},
								{field:'description',title:'".GetCatalog('description') ."',width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('customer')."</td>
				<td>
					<select class='easyui-combogrid' id='ttnt-addressbookid' name='ttnt-addressbookid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'addressbookid',
						textField: 'fullname',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/customer/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
							{field:'addressbookid',title:'".GetCatalog('addressbookid') ."',width:'50px'},
							{field:'fullname',title:'".GetCatalog('fullname') ."',width:'150px'},
						]],
						fitColumns: true\">
					</select>
				</td>
				</tr>	
			<tr>
				<td>".GetCatalog('description')."</td>
				<td><input class='easyui-textbox' id='ttnt-description' name='ttnt-description' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
		</table>
	",
	'addload'=>"
		$('#ttnt-ttntdate').datebox({
			value: (new Date().toString('dd-MMM-yyyy'))
		});	",
	'loadsuccess'=>"
		$('#ttnt-ttntdate').datebox('setValue',data.ttntdate);
		$('#ttnt-plantid').combogrid('setValue',data.plantid);
		$('#ttnt-description').textbox('setValue',data.description);
		$('#ttnt-addressbookid').combogrid('setValue',data.addressbookid);
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'ttntdetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/ttnt/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/ttnt/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/ttnt/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/ttnt/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/ttnt/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'ttntdetailid',title:'".GetCatalog('ttntdetailid') ."',width:'80px'},
				{field:'invoicearno',title:'".GetCatalog('invoicearno') ."',align:'left',width:'200px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'350px'},
			",
			'columns'=>"
				{
					field:'ttntid',
					title:'".GetCatalog('ttntid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'ttntdetailid',
					title:'".GetCatalog('ttntdetailid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'invoicearid',
					title:'".GetCatalog('invoicear') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'invoicearid',
							textField:'invoicearno',
							url:'".$this->createUrl('invoicear/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							onBeforeLoad: function(param) {
								 param.plantid = $('#ttnt-plantid').combogrid('getValue');
								 param.addressbookid = $('#ttnt-addressbookid').combogrid('getValue');
							},
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'invoicearid',title:'".GetCatalog('invoicearid')."',width:'80px'},
								{field:'invoicearno',title:'".GetCatalog('invoicearno')."',width:'120px'},
							]]
						}	
					},
					width:'200px',
					sortable: true,
					formatter: function(value,row,index){
						return row.invoicearno;
					}
				},
				{
					field:'description',
					title:'".GetCatalog('detailnote')."',
					editor: {
						type: 'textbox', 
						options: {
							multiline:true
						}
					},
					sortable: true,
					width:'250px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
	),	
));