<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'jenisanggotaid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/jenisanggota/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/jenisanggota/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/jenisanggota/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/jenisanggota/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/jenisanggota/upload'),
	'downpdf'=>Yii::app()->createUrl('common/jenisanggota/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/jenisanggota/downxls'),
	'downdoc'=>Yii::app()->createUrl('common/jenisanggota/downdoc'),
	'columns'=>"
		{
			field:'jenisanggotaid',
			title: localStorage.getItem('catalogjenisanggotaid'),
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'kodejenisanggota',
			title: localStorage.getItem('catalogkodejenisanggota'),
			width:'200px',
			editor: {
				type: 'textbox',
				options:{
					required:true
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'namajenisanggota',
			title: localStorage.getItem('catalognamajenisanggota'),
			width:'200px',
			editor: {
				type: 'textbox',
				options:{
					required:true
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title: localStorage.getItem('catalogrecordstatus'),
			width:'80px',
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/ok.png"."\"></img>';
				} else {
					return '';
				}
			}},
			",
	'searchfield'=> array ('jenisanggotaid','kodejenisanggota','namajenisanggota')
));