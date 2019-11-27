<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'slocid',
	'formtype'=>'masterdetail',
  'url'=>Yii::app()->createUrl('common/sloc/index',array('grid'=>true)),
  'urlgetdata'=>Yii::app()->createUrl('common/sloc/getData'),
	'saveurl'=>Yii::app()->createUrl('common/sloc/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/sloc/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/sloc/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/sloc/upload'),
	'downpdf'=>Yii::app()->createUrl('common/sloc/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/sloc/downxls'),
	'columns'=>"
		{
			field:'slocid',
			title:'". GetCatalog('slocid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'plantid',
			title:'". GetCatalog('plant') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.plantcode;
		}},
		{
			field:'sloccode',
			title:'". GetCatalog('sloccode') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'description',
			title:'". GetCatalog('description') ."',
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'isprd',
			title:'". GetCatalog('isprd') ."',
			width:'80px',
			align:'center',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},
			{
			field:'isbb',
			title:'". GetCatalog('isbb') ."',
			width:'80px',
			align:'center',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},
			{
			field:'isbj',
			title:'". GetCatalog('isbj') ."',
			width:'80px',
			align:'center',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},
		{
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
			width:'50px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
			}}",
	'searchfield'=> array ('slocid','plantcode','sloccode','description'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('plant')."</td>
				<td><select class='easyui-combogrid' id='sloc-plantid' name='sloc-plantid' style='width:300px' data-options=\"
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'plantid',
							textField:'plantcode',
							url:'". $this->createUrl('plant/index',array('grid'=>true,'combo'=>true)) ."',
							fitColumns:true,
							required:true,
							loadMsg: '". GetCatalog('pleasewait')."',
							columns:[[
								{field:'plantid',title:'". GetCatalog('plantid')."',width:'50px'},
								{field:'plantcode',title:'". GetCatalog('plantcode')."',width:'80px'},
								{field:'description',title:'". GetCatalog('description')."',width:'200px'},
							]]
					\">
			</select></td>
			</tr>
			<tr>
				<td>".GetCatalog('sloccode')."</td>
				<td><input class='easyui-textbox' id='sloc-sloccode' name='sloc-sloccode' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('description')."</td>
				<td><input class='easyui-textbox' id='sloc-description' name='sloc-description' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('isprd')."</td>
				<td><input id='sloc-isprd' name='sloc-isprd' type='checkbox'></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('isbb')."</td>
				<td><input id='sloc-isbb' name='sloc-isbb' type='checkbox'></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('isbj')."</td>
				<td><input id='sloc-isbj' name='sloc-isbj' type='checkbox'></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('recordstatus')."</td>
				<td><input id='sloc-recordstatus' name='sloc-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'loadsuccess' => "
		if (data.recordstatus == 1)
		{
			$('#sloc-recordstatus').prop('checked', true);
		} else
		{
			$('#sloc-recordstatus').prop('checked', false);
		}
		if (data.isprd == 1)
		{
			$('#sloc-isprd').prop('checked', true);
		} else
		{
			$('#sloc-isprd').prop('checked', false);
		}
		if (data.isbb == 1)
		{
			$('#sloc-isbb').prop('checked', true);
		} else
		{
			$('#sloc-isbb').prop('checked', false);
		}
		if (data.isbj == 1)
		{
			$('#sloc-isbj').prop('checked', true);
		} else
		{
			$('#sloc-isbj').prop('checked', false);
		}
	",
	'columndetails'=>array (
		array(
			'id'=>'storagebin',
			'idfield'=>'storagebinid',
			'urlsub'=>Yii::app()->createUrl('common/sloc/indexstoragebin',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('common/sloc/indexstoragebin',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/sloc/savestoragebin'),
			'updateurl'=>Yii::app()->createUrl('common/sloc/savestoragebin'),
			'destroyurl'=>Yii::app()->createUrl('common/sloc/purgestoragebin'),
			'subs'=>"
				{
					field:'description',
					title:'". GetCatalog('description') ."',
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'ismultiproduct',
					title:'". GetCatalog('ismultiproduct') ."',
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
						} else {
							return '';
						}
				}},
				{
					field:'qtymax',
					title:'". GetCatalog('qtymax') ."',
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber('',value);
				}},
				{
					field:'recordstatus',
					title:'". GetCatalog('recordstatus') ."',
					align:'center',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
						} else {
							return '';
						}
				}}
			",
			'columns'=>"
				{
					field:'storagebinid',
					title:'". GetCatalog('storagebinid') ."',
					width:'50px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
				}},
				{
					field:'slocid',
					title:'".GetCatalog('slocid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'description',
					title:'". GetCatalog('description') ."',
					editor:{
						type: 'validatebox',
						options:{
							required:true
						}
					},
					width:'250px',
					sortable: true,
					formatter: function(value,row,index){
					return value;
				}},
				{
					field:'ismultiproduct',
					title:'". GetCatalog('ismultiproduct') ."',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					width:'150px',
					sortable: true,
					formatter: function(value,row,index){
					if (value == 1){
						return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
					} else {
						return '';
					}
				}},
				{
					field:'qtymax',
					title:'". GetCatalog('qtymax') ."',
					editor:{
						type:'numberbox',
						options:{
							required:true,
							precision: 4,
							decimalSeparator:',',
							groupSeparator:'.',
						}
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return formatnumber('',value);
				}},
				{
					field:'recordstatus',
					title:'". GetCatalog('recordstatus') ."',
					align:'center',
					width:'80px',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
						} else {
							return '';
						}
					}}",
		)
	)
));
