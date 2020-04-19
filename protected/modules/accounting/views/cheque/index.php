<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'chequeid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/cheque/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/cheque/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/cheque/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/cheque/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/cheque/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/cheque/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/cheque/downxls'),
	'columns'=>"
		{
			field:'chequeid',
			title:localStorage.getItem('catalogchequeid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'companyname',
			title:localStorage.getItem('catalogcompany'),
			sortable: true,
			width:'150px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'plantid',
			title:localStorage.getItem('catalogplant'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'600px',
					mode : 'remote',
					method:'get',
					idField:'plantid',
					textField:'plantcode',
					url:'".Yii::app()->createUrl('common/plant/index',array('grid'=>true,'auth'=>true))."',
					fitColumns:true,
					required:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'plantid',title:localStorage.getItem('catalogplantid'),width:'100px'},
						{field:'plantcode',title:localStorage.getItem('catalogplantcode'),width:'100px'},
						{field:'description',title:localStorage.getItem('catalogdescription'),width:'250px'},
					]],
				}	
			},
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return row.plantcode;
			}
		},
		{
			field:'isin',
			title:localStorage.getItem('catalogisin'),
			align:'center',
			width:'70px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"".Yii::app()->request->baseUrl.'/images/ok.png'."\"></img>';
				} else {
					return '';
				}
			}
		},
		{
			field:'bilyetgirono',
			title:localStorage.getItem('catalogbilyetgirono'),
			editor: {
				type: 'textbox',
				options:{
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
			field:'bankname',
			title:localStorage.getItem('catalogbankname'),
			editor: {
				type: 'textbox',
				options:{
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
			field:'bilyetgirovalue',
			title:localStorage.getItem('catalogbilyetgirovalue'),
			editor: {
				type: 'numberbox',
				options:{
					required: true,
					precision:4,
					decimalSeparator:',',
					groupSeparator:'.',
					value:0,
				}
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
			}
		},
		{
			field:'currencyid',
			title:localStorage.getItem('catalogcurrency'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'600px',
					mode : 'remote',
					method:'get',
					idField:'currencyid',
					textField:'currencyname',
					url:'".Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					required:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'currencyid',title:localStorage.getItem('catalogcurrencyid'),width:'100px'},
						{field:'currencyname',title:localStorage.getItem('catalogcurrencyname'),width:'100px'},
					]],
				}	
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.currencyname;
			}
		},
		{
			field:'chequedate',
			title:localStorage.getItem('catalogchequedate'),
			editor: {
				type: 'datebox',
				options:{
					required: true
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'expiredate',
			title:localStorage.getItem('catalogexpiredate'),
			editor: {
				type: 'datebox',
				options:{
					required: true
				}
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'addressbookid',
			title:localStorage.getItem('catalogaddressbook'),
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'600px',
					mode : 'remote',
					method:'get',
					idField:'addressbookid',
					textField:'fullname',
					url:'".Yii::app()->createUrl('common/addressbook/index',array('grid'=>true,'combo'=>true))."',
					fitColumns:true,
					required:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'addressbookid',title:localStorage.getItem('catalogaddressbookid'),width:'100px'},
						{field:'fullname',title:localStorage.getItem('catalogfullname'),width:'200px'},
					]],
				}	
			},
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'cairtolakdate',
			title:localStorage.getItem('catalogcairtolakdate'),
			editor: {
				type: 'datebox',
			},
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'iscair',
			title:localStorage.getItem('catalogiscair'),
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
		},
		{
			field:'description',
			title:localStorage.getItem('catalogdescription'),
			editor: {
				type: 'textbox',
				options:{
					multiline: true,
				}
			},
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
			}
		},",
	'searchfield'=> array ('chequeid','plantcode','currencyname','addressbook','bilyetgirono')
));