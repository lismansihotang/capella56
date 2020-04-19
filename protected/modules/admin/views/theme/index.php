<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'themeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/theme/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/theme/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/theme/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/theme/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/theme/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/theme/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/theme/downxls'),
	'downdoc'=>Yii::app()->createUrl('admin/theme/downdoc'),
	'columns'=>"
		{
			field:'themeid',
			title:localStorage.getItem('catalogthemeid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'themename',
			title:localStorage.getItem('catalogthemename'),
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'description',
			title:localStorage.getItem('catalogdescription'),
			editor:'text',
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'themeprev',
			title:localStorage.getItem('catalogthemeprev'),
			editor:'text',
			width:'150px',
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
	'searchfield'=> array ('themeid','themename','description','themeprev')
));