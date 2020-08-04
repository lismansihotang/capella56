<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'unitofmeasureid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/unitofmeasure/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/unitofmeasure/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/unitofmeasure/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/unitofmeasure/upload'),
	'downpdf'=>Yii::app()->createUrl('common/unitofmeasure/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/unitofmeasure/downxls'),
	'downdoc'=>Yii::app()->createUrl('common/unitofmeasure/downdoc'),
	'columns'=>"
		{
			field:'unitofmeasureid',
			title: localStorage.getItem('catalogunitofmeasureid'),
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'uomcode',
			title: localStorage.getItem('cataloguomcode'),
			width:'150px',
			editor:{
				type: 'validatebox',
				options: {
					required: true
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'description',
			title: localStorage.getItem('catalogdescription'),
			width:'250px',
			editor:{
				type: 'validatebox',
				options: {
					required: true
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title: localStorage.getItem('catalogrecordstatus'),
			width:'50px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}}",
	'searchfield'=> array ('unitofmeasureid','uomcode','description')
));