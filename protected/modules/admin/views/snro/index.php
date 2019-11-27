<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'snroid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('admin/snro/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('admin/snro/getData'),
	'saveurl'=>Yii::app()->createUrl('admin/snro/save'),
	'updateurl'=>Yii::app()->createUrl('admin/snro/save'),
	'destroyurl'=>Yii::app()->createUrl('admin/snro/purge'),
	'uploadurl'=>Yii::app()->createUrl('admin/snro/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/snro/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/snro/downxls'),
	'rowstyler'=>"
		if (row.jumlah == 0) {
			return 'background-color:red;color:#fff;';
		}  
	",	
	'columns'=>"
		{
		field:'snroid',
		title:'".GetCatalog('snroid')."',
		sortable: true,
		width:'50px',
		formatter: function(value,row,index){
			return value;
		}},
		{
			field:'description',
			title:'".GetCatalog('description')."',
			editor:'text',
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'formatdoc',
			title:'".GetCatalog('formatdoc')."',
			editor:'text',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'formatno',
			title:'".GetCatalog('formatno')."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'repeatby',
			title:'".GetCatalog('repeatby')."',
			editor:'text',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('snroid','description','formatdoc','formatno','repeatby'),
	'headerform'=>"
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('description')."</td>
				<td><input class='easyui-textbox' id='snro-description' name='snro-description' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('formatdoc')."</td>
				<td><input class='easyui-textbox' id='snro-formatdoc' name='snro-formatdoc' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('formatno')."</td>
				<td><input class='easyui-textbox' id='snro-formatno' name='snro-formatno' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('repeatby')."</td>
				<td><input class='easyui-textbox' id='snro-repeatby' name='snro-repeatby' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('recordstatus')."</td>
				<td><input id='snro-recordstatus' name='snro-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'loadsuccess' => "
		$('#snro-description').textbox('setValue',data.description);
		$('#snro-formatdoc').textbox('setValue',data.formatdoc);
		$('#snro-formatno').textbox('setValue',data.formatno);
		$('#snro-repeatby').textbox('setValue',data.repeatby);
		if (data.recordstatus == 1)
		{
			$('#snro-recordstatus').prop('checked', true);
		} else
		{
			$('#snro-recordstatus').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'snrodetail',
			'idfield'=>'snrodid',
			'urlsub'=>Yii::app()->createUrl('admin/snro/indexsnrodet',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('admin/snro/indexsnrodet',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('admin/snro/savesnrodet'),
			'updateurl'=>Yii::app()->createUrl('admin/snro/savesnrodet'),
			'destroyurl'=>Yii::app()->createUrl('admin/snro/purgesnrodet'),
			'subs'=>"
				{field:'plantcode',title:'".GetCatalog('plant')."',width:'150px'},
				{field:'curdd',title:'".GetCatalog('curdd')."',width:'100px'},
				{field:'curmm',title:'".GetCatalog('curmm')."',width:'100px'},
				{field:'curyy',title:'".GetCatalog('curyy')."',width:'100px'},
				{field:'curvalue',title:'".GetCatalog('curvalue')."',width:'100px'},
			",
			'columns'=>"
				{
					field:'snrodid',
					title:'".GetCatalog('snrodid') ."',
					sortable: true,
					width:'50px',
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'snroid',
					title:'".GetCatalog('snrodid') ."',
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'plantid',
					title:'".GetCatalog('plant') ."',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'plantid',
							textField:'plantcode',
							url:'".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'plantid',title:'".GetCatalog('plantid')."',width:'50px'},
								{field:'plantcode',title:'".GetCatalog('plantcode')."',width:'150px'},
								{field:'description',title:'".GetCatalog('description')."',width:'200px'},
							]]
						}	
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
						return row.plantcode;
					}
				},
				{
					field:'curdd',
					title:'".GetCatalog('curdd') ."',
					editor:'numberbox',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'curmm',
					title:'".GetCatalog('curmm') ."',
					editor:'numberbox',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'curyy',
					title:'".GetCatalog('curyy') ."',
					editor:'numberbox',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'curvalue',
					title:'".GetCatalog('curvalue') ."',
					editor:'numberbox',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}}
			"
		)
	)
));
