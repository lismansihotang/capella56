<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'countryid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/country/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/country/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/country/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/country/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/country/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/country/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/country/downxls'),
	'downdoc'=>Yii::app()->createUrl('admin/country/downdoc'),
	'columns'=>"
		{
			field:'countryid',
			title:localStorage.getItem('catalogcountryid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'countrycode',
			title:localStorage.getItem('catalogcountrycode'),
			editor: {
        type: 'textbox',
      },
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'countryname',
			title:localStorage.getItem('catalogcountryname'),
      editor: {
        type: 'textbox',
        options: {
          required: true
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
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
			}
		},",
	'searchfield'=> array ('countryid','countrycode','countryname')
));