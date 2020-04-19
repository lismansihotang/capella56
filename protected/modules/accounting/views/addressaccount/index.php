<input type='hidden' id='addressaccount-companyid' name='addressaccount-companyid'/>
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'addressaccountid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/addressaccount/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/addressaccount/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/addressaccount/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/addressaccount/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/addressaccount/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/addressaccount/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/addressaccount/downxls'),
	'downdoc'=>Yii::app()->createUrl('accounting/addressaccount/downdoc'),
	'columns'=>"
		{
			field:'addressaccountid',
			title:localStorage.getItem('catalogaddressaccountid'),
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'companyid',
			title:localStorage.getItem('catalogcompanyname'),
			width:'200px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'600px',
					mode : 'remote',
					method:'get',
					idField:'companyid',
					textField:'companyname',
					url:'".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true))."',
					onChange:function(newValue,oldValue) {
						$('#addressaccount-companyid').val(newValue);
					},
					fitColumns:true,
					required:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'companyid',title:localStorage.getItem('catalogcompanyid'),width:'100px'},
						{field:'companyname',title:localStorage.getItem('catalogcompanyname')'".GetCatalog('')."',width:'300px'},
					]],
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.companyname;
		}},
		{
		field:'addressbookid',
		title:localStorage.getItem('catalogaddressbook'),
		width:'200px',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'addressbookid',
				textField:'fullname',
				url:'".Yii::app()->createUrl('common/addressbook/index',array('grid'=>true,'combo'=>true))."',
				fitColumns:true,
				required:true,
				loadMsg: localStorage.getItem('catalogpleasewait'),
				columns:[[
					{field:'addressbookid',title:localStorage.getItem('catalogaddressbookid'),width:'50px'},
					{field:'fullname',title:localStorage.getItem('catalogfullname'),width:'250px'},
				]]
			}	
		},
			sortable: true,
			formatter: function(value,row,index){
				return row.fullname;
		}},
		{
		field:'accpiutangid',
		title:localStorage.getItem('catalogaccpiutang'),
		width:'250px',
		editor:{
			type:'combogrid',
				options:{
					panelWidth:'600px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountname',
					url:'".Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'trxcom'=>true))."',
					onBeforeLoad:function(param) {
						param.companyid = $('#addressaccount-companyid').val();
					},
					fitColumns:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'50px'},
						{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'80px'},
						{field:'accountname',title:localStorage.getItem('catalogaccpiutang'),width:'200px'},
						{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.accpiutang;
		}},
		{
			field:'acchutangid',
			title:localStorage.getItem('catalogacchutang'),
			width:'250px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountname',
					url:'".Yii::app()->createUrl('accounting/account/index',array('grid'=>true,'trxcom'=>true))."',
					onBeforeLoad:function(param) {
						param.companyid = $('#addressaccount-companyid').val();
					},
					fitColumns:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'80px'},
						{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'100px'},
						{field:'accountname',title:localStorage.getItem('catalogacchutang'),width:'200px'},
						{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.acchutang;
		}},",
	'searchfield'=> array ('addressaccountid','companyname','addressbook')
));