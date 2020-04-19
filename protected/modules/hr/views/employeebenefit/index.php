<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'employeebenefitid',
	'formtype'=>'masterdetail',
	'ispost'=>1,
	'isreject'=>1,
	'url'=>Yii::app()->createUrl('hr/employeebenefit/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('hr/employeebenefit/getData'),
	'approveurl'=>Yii::app()->createUrl('hr/employeebenefit/approve'),
	'rejecturl'=>Yii::app()->createUrl('hr/employeebenefit/reject'),
	'saveurl'=>Yii::app()->createUrl('hr/employeebenefit/save'),
	'destroyurl'=>Yii::app()->createUrl('hr/employeebenefit/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('hr/employeebenefit/upload'),
	'downpdf'=>Yii::app()->createUrl('hr/employeebenefit/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/employeebenefit/downxls'),
	'columns'=>"
		{
			field:'employeebenefitid',
			title:'".getCatalog('employeebenefitid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeeid',
			title:'".getCatalog('employee') ."',
			sortable: true,
			width:'700px',
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
			field:'recordstatusbenefit',
			title:'".getCatalog('recordstatus') ."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
	}}",
	'searchfield'=> array ('employeebenefitid','employeename'),
	'headerform'=> "
		<input type='hidden' name='employeebenefitid' id='employeebenefitid'></input>
		<table cellpadding='5'>
			<tr>
				<td>".getCatalog('employee')."</td>
				<td><select class='easyui-combogrid' id='employeeid' name='employeeid' style='width:250px' data-options=\"
					panelWidth: '500px',
					required: true,
					idField: 'employeeid',
					textField: 'fullname',
					pagination:true,
					mode:'remote',
					url: '".Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ."',
					method: 'get',
					columns: [[
						{field:'employeeid',title:'".getCatalog('employeeid') ."'},
						{field:'fullname',title:'".getCatalog('fullname') ."'},
					]],
					fitColumns: true\">
				</select></td>
			</tr>
			<tr>
				<td>".getCatalog('recordstatusbenefit')."</td>
				<td><input id='recordstatusbenefit' name='recordstatusbenefit' type='checkbox'></input></td>
			</tr>
	",
	'loadsuccess' => "
		if (data.recordstatusbenefit == 1) {
			$('#recordstatusbenefit').prop('checked', true);
		} else {
			$('#recordstatusbenefit').prop('checked', false);
		};
	",
	'columndetails'=> array (
		array(
			'id'=>'detail',
			'idfield'=>'employeebenefitdetid',
			'urlsub'=>Yii::app()->createUrl('hr/employeebenefit/indexdetail',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('hr/employeebenefit/searchdetail',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('hr/employeebenefit/savedetail',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('hr/employeebenefit/savedetail',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('hr/employeebenefit/purgedetail',array('grid'=>true)),
			'subs'=>"
				{field:'wagename',title:'".getCatalog('wagename') ."',width:'200px'},
				{field:'startdate',title:'".getCatalog('startdate') ."',width:'100px'},
				{field:'enddate',title:'".getCatalog('enddate') ."',width:'100px'},
				{field:'amount',title:'".getCatalog('amount') ."',align:'right',width:'200px'},
				{field:'currencyname',title:'".getCatalog('currencyname') ."',width:'150px'},
				{field:'ratevalue',title:'".getCatalog('ratevalue') ."',align:'right',width:'100px'},
				{field:'reason',title:'".getCatalog('reason') ."',width:'300px'}
			",
			'columns'=>"
				{
					field:'employeebenefitid',
					title:'".getCatalog('employeebenefitid') ."',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'employeebenefitdetailid',
					title:'".getCatalog('employeebenefitdetailid') ."',
					sortable: true,
					width:'80px',
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'wagetypeid',
					title:'".getCatalog('wagetype') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'wagetypeid',
							textField:'wagename',
							url:'".Yii::app()->createUrl('hr/wagetype/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							pagination:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'wagetypeid',title:'".getCatalog('wagetypeid')."',width:50},
								{field:'wagename',title:'".getCatalog('wagename')."',width:200},
							]]
						}	
					},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						return row.wagename;
					}
				},
				{
					field:'startdate',
					title:'".getCatalog('startdate') ."',
					editor:'datebox',
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'enddate',
					title:'".getCatalog('enddate') ."',
					editor:'datebox',
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'amount',
					title:'".getCatalog('wageamount') ."',
					sortable: true,
					editor:{
						type:'numberbox',
						options:{
							precision:4,
							decimalSeparator:',',
							groupSeparator:'.',
						}
					},
					width:'150px',
					required:true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'currencyid',
					title:'".getCatalog('currency') ."',
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
							pagination:true,
							required:true,
							loadMsg: '".getCatalog('pleasewait')."',
							columns:[[
								{field:'currencyid',title:'".getCatalog('currencyid')."',width:'50px'},
								{field:'currencyname',title:'".getCatalog('currencyname')."',width:'200px'},
							]]
						}	
					},
					width:'150px',			
					sortable: true,
					formatter: function(value,row,index){
										return row.currencyname;
					}
				},
				{
					field:'ratevalue',
					title:'".getCatalog('currencyrate') ."',
					editor:{
						type:'numberbox',
						options:{
							precision:2,
							decimalSeparator:',',
							groupSeparator:'.',
							required:true,
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				}, 
				{
					field:'isfinal',
					title:'".getCatalog('isfinal') ."',
					align:'center',
					width:'100px',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
										return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
						} else {
										return '';
						}
				}},
				{
					field:'reason',
					title:'".getCatalog('reason') ."',
					editor:'text',
					width:'250px',
					multiline:true,
					sortable: true,
					formatter: function(value,row,index){
											return value;
					}
				},
			"
		)
	),	
));