<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'taxid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/tax/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/tax/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/tax/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/tax/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/tax/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/tax/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/tax/downxls'),
	'downdoc'=>Yii::app()->createUrl('accounting/tax/downdoc'),
	'columns'=>"
		{
			field:'taxid',
			title:'".getCatalog('taxid')."', 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'taxcode',
			title:'".getCatalog('taxcode')."', 
			editor:{
				type: 'textbox',
				options: {
					required: true
				}
			},
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'taxvalue',
			title:'".getCatalog('taxvalue')."', 
			editor:{
				type: 'numberbox',
				options: {
					required: true,
					precision:4,
					decimalSeparator:',',
					groupSeparator:'.',
				}
			},
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'description',
			title:'".getCatalog('description')."', 
			editor:{
				type: 'textbox',
				options: {
					required: true
				}
			},
			width:'350px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'recordstatus',
			title:'".getCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable:'true',
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
			}
		}",
	'searchfield'=> array ('taxid','taxcode','taxvalue','description')
));