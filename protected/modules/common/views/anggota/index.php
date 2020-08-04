<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'jenisdepositoid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('common/anggota/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/anggota/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/anggota/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/anggota/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/anggota/upload'),
	'downpdf'=>Yii::app()->createUrl('common/anggota/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/anggota/downxls'),
	'downdoc'=>Yii::app()->createUrl('common/anggota/downdoc'),
	'columns'=>"
		{
			field:'anggotaid',
			title: localStorage.getItem('cataloganggotaid'),
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'nomoranggota',
			title: localStorage.getItem('catalognomoranggota'),
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
			field:'namaanggota',
			title: localStorage.getItem('catalognamaanggota'),
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
			field:'ktp',
			title: localStorage.getItem('catalogktp'),
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
			field:'jeniskelamin',
			title: localStorage.getItem('catalogjeniskelamin'),
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
			field:'tempatlahir',
			title: localStorage.getItem('catalogtempatlahir'),
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
			field:'tanggallahir',
			title: localStorage.getItem('catalogtanggallahir'),
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
			field:'bunga',
			title: localStorage.getItem('catalogbunga'),
			width:'200px',
			editor: {
				type: 'numberbox',
				options:{
					required:true,
					precision: 2,
					decimalSeparator:',',
					groupSeparator:'.',
					value:0,
				}
			},
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'fixed',
			title: localStorage.getItem('catalogfixed'),
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
		{
			field:'tenor',
			title: localStorage.getItem('catalogtenor'),
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
		{
			field:'isauto',
			title: localStorage.getItem('catalogisauto'),
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
	'searchfield'=> array ('jenisdepositoid','jenisdepositoname')
));