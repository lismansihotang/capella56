<input type="hidden" name="companyid" id="companyid" />
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'budgetid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/budget/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/budget/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/budget/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/budget/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/budget/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/budget/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/budget/downxls'),
	'columns'=>"
		{
			field:'budgetid',
			title:localStorage.getItem('catalogbudgetid'), 
			sortable:'true',
			width:'50px',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'budgetdate',
			title:localStorage.getItem('catalogbudgetdate'), 
			editor:{type:'datebox'},
			width:'100px',
			sortable:'true',
			formatter: function(value,row,index){
				return value;
			}
		},
		{
			field:'companyid',
			title:localStorage.getItem('catalogcompany'),
			sortable: true,
			editor:{
				type : 'combogrid',
				options: {
						panelWidth:'500px',
						mode: 'remote',
						method: 'get',
						idField: 'companyid',
						textField: 'companyname',
						required: true,
						pagination: true,
						url:'". Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true,))."',
						fitColumns: true,
						loadMsg: localStorage.getItem('catalogpleasewait'),
						columns:[[
								{field:'companyid',title:localStorage.getItem('catalogcompanyid'), width:'50px'},
								{field:'companyname',title:localStorage.getItem('catalogcompany'), width:'250px'},
						]],
						onBeforeLoad: function(param) {
								var row = $('#dg-budget').edatagrid('getSelected');
								if(row==null){
										$(\"input[name='companyid']\").val('0');
								}
					},
					onSelect: function(index,row){
						var companyid = row.companyid;
						$(\"input[name='companyid']\").val(row.companyid);
					},
				},
			},
			width:'200px',
			formatter: function(value,row,index){
			return row.companyname;
		}},
		{
			field:'accountid',
			title:localStorage.getItem('catalogaccount'), 
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountname',
					required:true,
					pagination: true,
					url:'". $this->createUrl('account/index',array('grid'=>true,'trxcom'=>true)) ."',
					fitColumns:true,
					loadMsg: localStorage.getItem('catalogpleasewait'),
						queryParams:{
						companyid:0
					},
					onBeforeLoad: function(param) {
						var companyid = $(\"input[name='companyid']\").val();
						if(companyid=='') {
							var row = $('#dg-budget').datagrid('getSelected');
							param.companyid = row.companyid;
						}
						else {
							param.companyid = $(\"input[name='companyid']\").val(); 
						}
					},
					columns:[[
						{field:'accountid',title:localStorage.getItem('catalogaccountid'),width:'50px'},
						{field:'accountcode',title:localStorage.getItem('catalogaccountcode'),width:'100px'},
						{field:'accountname',title:localStorage.getItem('catalogaccountname'),width:'200px'},
						{field:'companyname',title:localStorage.getItem('catalogcompany'),width:'200px'},
					]]
				}	
			},
			width:'350px',
			sortable:'true',
			formatter: function(value,row,index){
				return row.accountcode+' - '+ row.accountname;
			}
		},
		{
			field:'budgetamount',
			title:localStorage.getItem('catalogbudgetamount'),
			editor:{
				type:'numberbox',
				options:{
					precision:4,
					required:true,
					decimalSeparator:',',
					groupSeparator:'.'
				}
			},
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber(value);
			}
		},
		{
			field:'pakaibudget',
			title:localStorage.getItem('catalogpakaibudget'),
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return '<div style=\"text-align:right\">'+value+'</div>';
			}
		},",
	'rowstyler'=>"
		if (row.warna == 1) {
			return 'background-color:red;color:white;';
		} else 
		if (row.warna == 2) {
			return 'background-color:cyan;color:black;';
		} else 
						if (row.warna == 3) {
			return 'background-color:red;color:white;';
		} else 
						if (row.warna == 4) {
			return 'background-color:cyan;color:black;';
		}
	",
	'searchfield'=> array ('budgetid','budgetdate','companyname','accountcode','accountname')
));