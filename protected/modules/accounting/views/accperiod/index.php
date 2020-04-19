<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'accperiodid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/accperiod/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/accperiod/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/accperiod/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/accperiod/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/accperiod/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/accperiod/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/accperiod/downxls'),
	'columns'=>"
		{
			field:'accperiodid',
			title:localStorage.getItem('catalogaccperiodid'), 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'period',
			title:localStorage.getItem('catalogperiod'), 
			editor:{
				type:'datebox',
				options: {
					formatter:dateformatter,
					parser:dateparser,
					required:true,
				}
			},
			width:'150px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'recordstatus',
			title:localStorage.getItem('catalogrecordstatus'),
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
	'searchfield'=> array ('accperiodid','period')
));