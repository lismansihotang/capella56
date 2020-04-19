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
	'downdoc'=>Yii::app()->createUrl('admin/snro/downdoc'),
	'rowstyler'=>"
		if (row.jumlah == 0) {
			return 'background-color:red;color:#fff;';
		}  
	",	
	'columns'=>"
		{
		field:'snroid',
		title:localStorage.getItem('catalogsnroid'),
		sortable: true,
		width:'50px',
		formatter: function(value,row,index){
			return value;
		}},
		{
			field:'description',
			title:localStorage.getItem('catalogdescription'),
			editor:'text',
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'formatdoc',
			title:localStorage.getItem('catalogformatdoc'),
			editor:'text',
			width:'200px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'formatno',
			title:localStorage.getItem('catalogformatno'),
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'repeatby',
			title:localStorage.getItem('catalogrepeatby'),
			editor:'text',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
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
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},",
	'searchfield'=> array ('snroid','description','formatdoc','formatno','repeatby'),
	'headerform'=>"
		<table cellpadding='5'>
			<tr>
				<td id='snrotext-description'></td>
				<td><input class='easyui-textbox' id='snro-description' name='snro-description' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td id='snrotext-formatdoc'></td>
				<td><input class='easyui-textbox' id='snro-formatdoc' name='snro-formatdoc' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td id='snrotext-formatno'></td>
				<td><input class='easyui-textbox' id='snro-formatno' name='snro-formatno' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td id='snrotext-repeatby'></td>
				<td><input class='easyui-textbox' id='snro-repeatby' name='snro-repeatby' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td id='snrotext-recordstatus'></td>
				<td><input id='snro-recordstatus' name='snro-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'addonscripts' => "
		$(document).ready(function(){
			var parel = document.getElementById('snrotext-description');
			parel.innerHTML = localStorage.getItem('catalogdescription');
			parel = document.getElementById('snrotext-formatdoc');
			parel.innerHTML = localStorage.getItem('catalogformatdoc');
			parel = document.getElementById('snrotext-formatno');
			parel.innerHTML = localStorage.getItem('catalogformatno');
			parel = document.getElementById('snrotext-repeatby');
			parel.innerHTML = localStorage.getItem('catalogrepeatby');
			parel = document.getElementById('snrotext-recordstatus');
			parel.innerHTML = localStorage.getItem('catalogrecordstatus');
		});
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
				{field:'plantcode',title:localStorage.getItem('catalogplant'),width:'150px'},
				{field:'curdd',title:localStorage.getItem('catalogcurdd'),width:'100px'},
				{field:'curmm',title:localStorage.getItem('catalogcurmm'),width:'100px'},
				{field:'curyy',title:localStorage.getItem('catalogcuryy'),width:'100px'},
				{field:'curvalue',title:localStorage.getItem('catalogcurvalue'),width:'100px'},
			",
			'columns'=>"
				{
					field:'snrodid',
					title:localStorage.getItem('catalogsnrodid'),
					sortable: true,
					width:'50px',
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'snroid',
					title:localStorage.getItem('catalogsnroid'),
					sortable: true,
					hidden:true,
					formatter: function(value,row,index){
						return value;
				}},
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
							url:'".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: localStorage.getItem('catalogpleasewait'),
							columns:[[
								{field:'plantid',title:localStorage.getItem('catalogplantid'),width:'50px'},
								{field:'plantcode',title:localStorage.getItem('catalogplantcode'),width:'150px'},
								{field:'description',title:localStorage.getItem('catalogdescription'),width:'200px'},
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
					title:localStorage.getItem('catalogcurdd'),
					editor:'numberbox',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'curmm',
					title:localStorage.getItem('catalogcurmm'),
					editor:'numberbox',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'curyy',
					title:localStorage.getItem('catalogcuryy'),
					editor:'numberbox',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'curvalue',
					title:localStorage.getItem('catalogcurvalue'),
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