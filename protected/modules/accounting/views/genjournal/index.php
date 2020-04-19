<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'genjournalid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'wfapp'=>'appjournal',
	'url'=>Yii::app()->createUrl('accounting/genjournal/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('accounting/genjournal/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/genjournal/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/genjournal/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/genjournal/purge',array('grid'=>true)),
	'approveurl'=>Yii::app()->createUrl('accounting/genjournal/approve',array('grid'=>true)),
	'rejecturl'=>Yii::app()->createUrl('accounting/genjournal/reject',array('grid'=>true)),
	'downpdf'=>Yii::app()->createUrl('accounting/genjournal/downpdf'),
	'columns'=>"
		{
			field:'genjournalid',
			title:localStorage.getItem('catalogdescription')'".GetCatalog('genjournalid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				".GetStatusColor('appjournal')."
		}},
		{
			field:'plantid',
			title:localStorage.getItem('catalogplant'),
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'journalno',
			title:localStorage.getItem('catalogjournalno'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'referenceno',
			title:localStorage.getItem('catalogreferenceno'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
						return value;
					}},
		{
			field:'journaldate',
			title:localStorage.getItem('catalogjournaldate'),
			sortable: true,
			width:'80px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'journalnote',
			title:localStorage.getItem('catalogjournalnote'),
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatusname',
			title:localStorage.getItem('catalogrecordstatus'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
			return value;
		}},",
	'rowstyler'=>"
		if (row.debit != row.credit){
			return 'background-color:blue;color:#fff;';
		}
	",
	'searchfield'=> array ('genjournalid','plantcode','journalno','referenceno','journaldate','journalnote','accountcode','accountname','recordstatus'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td id='genjournaltext-journaldate'></td>
				<td><input class='easyui-datebox' type='text' id='genjournal-journaldate' name='genjournal-journaldate' data-options='formatter:dateformatter,required:true,parser:dateparser'></input></td>
			</tr>
			<tr>
				<td id='genjournaltext-plant'></td>
				<td>
					<select class='easyui-combogrid' id='genjournal-plantid' name='genjournal-plantid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'plantid',
						textField: 'plantcode',
						mode:'remote',
						url: '".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'auth'=>true)) ."',
						method: 'get',
						columns: [[
								{field:'plantid',title:localStorage.getItem('catalogplantid'),width:'50px'},
								{field:'plantcode',title:localStorage.getItem('catalogplantcode'),width:'250px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td id='genjournaltext-journalnote'></td>
				<td><input class='easyui-textbox' id='genjournal-journalnote' name='genjournal-journalnote' data-options='multiline:true,required:true' style='width:300px;height:100px'></input></td>
			</tr>
			<tr>
				<td id='genjournaltext-referenceno'></td>
				<td><input class='easyui-textbox' id='genjournal-referenceno' name='genjournal-referenceno' ></input></td>
			</tr>
		</table>
	",
	'addonscripts' => "
		$(document).ready(function(){
			var parel = document.getElementById('genjournaltext-journaldate');
			parel.innerHTML = localStorage.getItem('catalogjournaldate');
			parel = document.getElementById('genjournaltext-plant');
			parel.innerHTML = localStorage.getItem('catalogplant');
			parel = document.getElementById('genjournaltext-journalnote');
			parel.innerHTML = localStorage.getItem('catalogjournalnote');
			parel = document.getElementById('genjournaltext-referenceno');
			parel.innerHTML = localStorage.getItem('catalogreferenceno');
		});
	",
	'addload'=>"
		$('#genjournal-journaldate').datebox({value: (new Date().toString('dd-MMM-yyyy'))});	
	",
	'loadsuccess'=>"
		$('#genjournal-journaldate').datebox('setValue',data.journaldate);
		$('#genjournal-plantid').combogrid('setValue',data.plantid);
		$('#genjournal-journalnote').textbox('setValue',data.journalnote);
		$('#genjournal-referenceno').textbox('setValue',data.referenceno);
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'journaldetailid',
			'urlsub'=>Yii::app()->createUrl('accounting/genjournal/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('accounting/genjournal/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('accounting/genjournal/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('accounting/genjournal/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('accounting/genjournal/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'250px'},
				{field:'debit',title:localStorage.getItem('catalogdebit'),
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'140px'},
				{field:'credit',title:localStorage.getItem('catalogcredit'),
					formatter: function(value,row,index){
						return formatnumber(row.symbol,value);
					},width:'140px'},
				{field:'ratevalue',title:localStorage.getItem('catalogratevalue'),align:'right',width:'60px'},
				{field:'detailnote',title:localStorage.getItem('catalogdetailnote'),width:'350px'},
			",
			'columns'=>"
				{
					field:'genjournalid',
					title:localStorage.getItem('cataloggenjournalid'),
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'journaldetailid',
					title:localStorage.getItem('catalogjournaldetailid'),
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
										return value;
					}
				},
				{
					field:'accountid',
					title:localStorage.getItem('catalogaccountname'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'800px',
							mode : 'remote',
							method:'get',
							idField:'accountid',
							textField:'accountname',
							url:'".$this->createUrl('account/index',array('grid'=>true,'trxplant'=>true)) ."',
							fitColumns:true,
							required:true,
							onBeforeLoad: function(param) {
								 param.plantid = $('#genjournal-plantid').combogrid('getValue');
							},
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'80px'},
								{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'350px'},
								{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'120px'},
								{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'250px'},
							]]
						}	
					},
					width:'300px',
					sortable: true,
					formatter: function(value,row,index){
						return row.accountname;
					}
				},
				{
					field:'debit',
					title:localStorage.getItem('catalogdebit'),
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
					field:'credit',
					title:localStorage.getItem('catalogcredit'),
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
					field:'currencyid',
					title:localStorage.getItem('catalogcurrency'),
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'currencyid',
							textField:'currencyname',
							url:'".Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'currencyid',title:localStorage.getItem('catalogcurrencyid'),width:'50px'},
								{field:'currencyname',title:localStorage.getItem('catalogcurrencyname'),width:'150px'},
							]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
										return row.currencyname;
					}
				},
				{
					field:'ratevalue',
					title:localStorage.getItem('catalogratevalue'),
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
							value:1,
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
											return formatnumber('',value);
					}
				},
				{
					field:'detailnote',
					title:localStorage.getItem('catalogdetailnote'),
					editor: 'textbox',
					sortable: true,
					width:'300px',
					formatter: function(value,row,index){
										return value;
					}
				},
			"
		),
	),	
));