<input type="hidden" name="slocaccounting-slocid" id="slocaccounting-slocid" />
<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'slocaccid',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('accounting/slocaccounting/index',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('accounting/slocaccounting/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('accounting/slocaccounting/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('accounting/slocaccounting/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('accounting/slocaccounting/upload'),
	'downpdf'=>Yii::app()->createUrl('accounting/slocaccounting/downpdf'),
	'downxls'=>Yii::app()->createUrl('accounting/slocaccounting/downxls'),
	'downdoc'=>Yii::app()->createUrl('accounting/slocaccounting/downdoc'),
	'columns'=>"
		{
		field:'slocaccid',
		title:'". GetCatalog('slocaccid') ."',
		sortable: true,
		width: '50px',
		formatter: function(value,row,index){
			return value;
	}},
	{
		field:'slocid',
		title:'". GetCatalog('sloc') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'slocid',
				textField:'sloccode',
				url:'". Yii::app()->createUrl('common/sloc/indexcombo',array('grid'=>true)) ."',
				fitColumns:true,
				required:true,
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'slocid',title:'". GetCatalog('slocid')."',width:50},
					{field:'sloccode',title:'". GetCatalog('sloccode')."',width:100},
					{field:'description',title:'". GetCatalog('description')."',width:150},
				]],
				onBeforeLoad: function(param) {
					var row = $('#dg-slocaccounting').edatagrid('getSelected');
					if(row==null){
						$(\"input[name='slocaccounting-slocid']\").val('0');
					}
				},
				onSelect: function(index,row){
					var sloc = row.slocid;
					$(\"input[name='slocaccounting-slocid']\").val(row.slocid);
				}
			}	
		},
		width: '120px',
		sortable: true,
		formatter: function(value,row,index){
			return row.sloccode;
	}},
	{
		field:'materialgroupid',
		title:'". GetCatalog('materialgroup') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'500px',
				mode : 'remote',
				method:'get',
				idField:'materialgroupid',
				textField:'description',
				url:'". Yii::app()->createUrl('common/materialgroup/index',array('grid'=>true,'combo'=>true)) ."',
				fitColumns:true,
				required:true,
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'materialgroupid',title:'". GetCatalog('unitofmeasureid')."',width:'50px'},
					{field:'materialgroupcode',title:'". GetCatalog('materialgroupcode')."',width:'80px'},
					{field:'description',title:'". GetCatalog('description')."',width:'200px'},
				]]
			}	
		},
		width:'200px',
		sortable: true,
		formatter: function(value,row,index){
			return row.description;
	}},
	{
		field:'accpembelian',
		title:'". GetCatalog('accpembelian') ."',
		editor:{
			type:'combogrid',
			options:{
					panelWidth:'600px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountname',
					url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
					fitColumns:true,
					queryParams:{
						slocid:0
					},              
					onBeforeLoad: function(param) {
						var sloc = $(\"input[name='slocaccounting-slocid']\").val();
						if(sloc==''){
							var row = $('#dg-slocaccounting').edatagrid('getSelected');
							param.slocid = row.slocid;
						} else {
							param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
						{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
						{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
						{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
					]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accpembelianname;
	}},
	{
		field:'accpersediaan',
		title:'". GetCatalog('accpersediaan') ."',
		editor:{
			type:'combogrid',
			options:{
					panelWidth:'600px',
					mode : 'remote',
					method:'get',
					idField:'accountid',
					textField:'accountname',
					url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
					fitColumns:true,
					queryParams:{
						slocid:0
					},              
					onBeforeLoad: function(param) {
						var sloc = $(\"input[name='slocaccounting-slocid']\").val();
						if(sloc==''){
							var row = $('#dg-slocaccounting').edatagrid('getSelected');
							param.slocid = row.slocid;
						} else {
							param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
						{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
						{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
						{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
					]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accpersediaanname;
	}},
	{
		field:'accreturpembelian',
		title:'". GetCatalog('accreturpembelian') ."',
		editor:{
			type:'combogrid',
			options:{
					panelWidth:'600px',
					mode : 'remote',
					method:'get',
					pagination:true,
					idField:'accountid',
					textField:'accountname',
					url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
					fitColumns:true,
					queryParams:{
						slocid:0
					},              
					onBeforeLoad: function(param) {
						var sloc = $(\"input[name='slocaccounting-slocid']\").val();
						if(sloc==''){
							var row = $('#dg-slocaccounting').edatagrid('getSelected');
							param.slocid = row.slocid;
						}else{
							param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
					},
					loadMsg: '". GetCatalog('pleasewait')."',
					columns:[[
						{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
						{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
						{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
						{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
					]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accreturpembelianname;
	}},
	{
		field:'accdiscpembelian',
		title:'". GetCatalog('accdiscpembelian') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'600px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocaccounting-slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
					 param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accdiscpembelianname;
	}},
	{
		field:'accbiayapembelian',
		title:'". GetCatalog('accbiayapembelian') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'600px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocaccounting-slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
					 param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accbiayapembelianname;
	}},
	{
		field:'accexpedisipembelian',
		title:'". GetCatalog('accexpedisipembelian') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'600px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocaccounting-slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
					 param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accexpedisipembelianname;
	}},
	{
		field:'accpenjualan',
		title:'". GetCatalog('accpenjualan') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'600px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocaccounting-slocid']\").val();
					if(sloc==''){
							var row = $('#dg-slocaccounting').edatagrid('getSelected');
							param.slocid = row.slocid;
					} else {
					param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accpenjualanname;
	}},
	{
		field:'accreturpenjualan',
		title:'". GetCatalog('accreturpenjualan') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'600px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocaccounting-slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
						param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accreturpenjualanname;
	}},	
	{
		field:'accdiscpenjualan',
		title:'". GetCatalog('accdiscpenjualan') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'600px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocaccounting-slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
						param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accdiscpenjualanname;
	}},	
	{
		field:'accbiayapenjualan',
		title:'". GetCatalog('accbiayapenjualan') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'600px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocaccounting-slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
						param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accbiayapenjualanname;
	}},
	{
		field:'accexpedisipenjualan',
		title:'". GetCatalog('accexpedisipenjualan') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'600px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocaccounting-slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
						param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accexpedisipenjualanname;
	}},
	{
		field:'hpp',
		title:'". GetCatalog('hpp') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'600px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocaccounting-slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
					 param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.acchppname;
	}},
	{
		field:'accupahlembur',
		title:'". GetCatalog('accupahlembur') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'600px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocaccounting-slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
						param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accupahlemburname;
	}},
	{
		field:'foh',
		title:'". GetCatalog('foh') ."',
		editor:{
			type:'combogrid',
			options:{
				panelWidth:'600px',
				mode : 'remote',
				method:'get',
				pagination:true,
				idField:'accountid',
				textField:'accountname',
				url:'". Yii::app()->createUrl('accounting/account/indexcombosloc',array('grid'=>true)) ."',
				fitColumns:true,
				queryParams:{
					slocid:0
				},              
				onBeforeLoad: function(param) {
					var sloc = $(\"input[name='slocaccounting-slocid']\").val();
					if(sloc==''){
						var row = $('#dg-slocaccounting').edatagrid('getSelected');
						param.slocid = row.slocid;
					} else {
						param.slocid = $(\"input[name='slocaccounting-slocid']\").val(); }
				},
				loadMsg: '". GetCatalog('pleasewait')."',
				columns:[[
					{field:'accountid',title:'". GetCatalog('accountid')."',width:'50px'},
					{field:'accountcode',title:'". GetCatalog('accountcode')."',width:'150px'},
					{field:'accountname',title:'". GetCatalog('accountname')."',width:'200px'},
					{field:'companyname',title:'". GetCatalog('companyname')."',width:'200px'},
				]]
			}	
		},
		width: '250px',
		sortable: true,
		formatter: function(value,row,index){
			return row.accfohname;
	}}",
	'searchfield'=> array ('slocaccid','sloccode','materialgroupcode','materialgroupname')
));