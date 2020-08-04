<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'companyid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('admin/company/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('admin/company/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('admin/company/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('admin/company/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('admin/company/upload'),
	'downpdf'=>Yii::app()->createUrl('admin/company/downpdf'),
	'downxls'=>Yii::app()->createUrl('admin/company/downxls'),
	'downdoc'=>Yii::app()->createUrl('admin/company/downdoc'),
	'columns'=>"
		{
			field:'companyid',
			title:localStorage.getItem('catalogcompanyid'),
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyname',
			title:localStorage.getItem('catalogcompanyname'),
			editor: { 
        type: 'textbox',
        options: {
          required: true
        }
      },
			sortable: true,
			width:'200px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companycode',
			title:localStorage.getItem('catalogcompanycode'),
			editor:{ 
        type: 'textbox',
        options: {
          required: true
        }
      },
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'address',
			title:localStorage.getItem('catalogaddress'),
			editor:{ 
        type: 'textbox',
        options: {
          required: true
        }
      },
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'cityid',
			title:localStorage.getItem('catalogcity'),
			editor:{
				type:'combogrid',
				options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'cityid',
						textField:'cityname',
						url:'". $this->createUrl('city/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg: localStorage.getItem('catalogpleasewait'),
						columns:[[
							{field:'cityid',title:localStorage.getItem('catalogcityid'),width:'80px'},
							{field:'cityname',title:localStorage.getItem('catalogcityname'),width:'250px'},
						]]
				}	
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return row.cityname;
		}},
		{
			field:'zipcode',
			title:localStorage.getItem('catalogzipcode'),
			editor:{ 
        type: 'textbox',
        options: {
          required: true
        }
      },
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'taxno',
			title:localStorage.getItem('catalogtaxno'),
			editor:{ 
        type: 'textbox',
        options: {
        }
      },
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'currencyid',
			title:localStorage.getItem('catalogcurrency'),
			sortable: true,
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'currencyid',
					textField:'currencyname',
					url:'".$this->createUrl('currency/index',array('grid'=>true,'combo'=>true))."',
					required:true,
					fitColumns:true,
					loadMsg: '".GetCatalog('pleasewait')."',
					columns:[[
						{field:'currencyid',title:localStorage.getItem('catalogcurrencyid'),width:'50px'},
						{field:'countryname',title:localStorage.getItem('catalogcountryname'),width:'200px'},
						{field:'currencyname',title:localStorage.getItem('catalogcurrencyname'),width:'200px'},
					]]
				}	
			},
			formatter: function(value,row,index){
				return row.currencyname;
		}},
		{
			field:'faxno',
			title:localStorage.getItem('catalogfaxno'),
			editor:{ 
        type: 'textbox',
        options: {
        }
      },
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'phoneno',
			title:localStorage.getItem('catalogphoneno'),
			editor:{ 
        type: 'textbox',
        options: {
        }
      },
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'webaddress',
			title:localStorage.getItem('catalogwebaddress'),
			editor:{ 
        type: 'textbox',
        options: {
        }
      },
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'email',
			title:localStorage.getItem('catalogemail'),
			editor:{ 
        type: 'textbox',
        options: {
        }
      },
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'leftlogofile',
			title:localStorage.getItem('catalogleftlogofile'),
			editor:{ 
        type: 'textbox',
        options: {
        }
      },
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'rightlogofile',
			title:localStorage.getItem('catalogrightlogofile'),
			editor:{ 
        type: 'textbox',
        options: {
        }
      },
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'isholding',
			title:localStorage.getItem('catalogisholding'),
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'billto',
			title:localStorage.getItem('catalogbillto'),
			editor:{ 
        type: 'textbox',
        options: {
          required: true,
          multiline: true
        }
      },
			sortable: true,
			width:'350px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bankacc1',
			title:localStorage.getItem('catalogbankacc1'),
			editor:{ 
        type: 'textbox',
        options: {
        }
      },
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bankacc2',
			title:localStorage.getItem('catalogbankacc2'),
			editor:{ 
        type: 'textbox',
        options: {
        }
      },
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bankacc3',
			title:localStorage.getItem('catalogbankacc3'),
			editor:{ 
        type: 'textbox',
        options: {
        }
      },
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:localStorage.getItem('catalogrecordstatus'),
			align:'center',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('companyid','companyname','companycode')
));