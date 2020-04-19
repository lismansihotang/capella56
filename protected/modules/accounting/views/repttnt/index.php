<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'ttntid',
	'formtype'=>'list',
	'wfapp'=>'appttnt',
	'url'=>Yii::app()->createUrl('accounting/repttnt/index',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/repttnt/downpdf'),
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
			field:'ttntdate',
			title:'".GetCatalog('ttntdate') ."',
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
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
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'ttntdetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/ttnt/indexdetail',array('grid'=>true)),
			'subs'=>"
				{field:'invoicearno',title:'".GetCatalog('invoicearno') ."',width:'250px'},
				{field:'description',title:'".GetCatalog('description') ."',width:'350px'},
			",
		),
	),	
));