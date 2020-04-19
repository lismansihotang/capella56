<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'accounttypeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/accounttype/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/accounttype/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/accounttype/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/accounttype/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/accounttype/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/accounttype/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/accounttype/downxls'),
	'columns'=>"
		{
			field:'accounttypeid',
			title:localStorage.getItem('catalogaccounttypeid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'accounttypename',
			title:localStorage.getItem('catalogaccounttypename'),
			editor:{
				type: 'textbox',
				options: {
					required:true
				}
			},
			width:'150px',
			sortable: true,
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
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}
		},",
	'searchfield'=> array ('accounttypeid','accounttypename')
));