<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'addressbookid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('common/supplier/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('common/supplier/getData'),
	'saveurl'=>Yii::app()->createUrl('common/supplier/save'),
	'destroyurl'=>Yii::app()->createUrl('common/supplier/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/supplier/upload'),
	'downpdf'=>Yii::app()->createUrl('common/supplier/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/supplier/downxls'),
	'columns'=>"
		{
			field:'addressbookid',
			title:'".GetCatalog('addressbookid')."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title:'".GetCatalog('fullname')."',
			sortable: true,
			width:'300px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'taxno',
			title:'".GetCatalog('taxno')."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return value;
		}}, 
		{
			field:'paymentmethodid',
			title:'".GetCatalog('paymentmethod')."',
			sortable: true,
			width:'100px',
			formatter: function(value,row,index){
				return row.paycode;
		}}, 
		{
			field:'bankaccountno',
			title:'".GetCatalog('bankaccountno')."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'bankname',
			title:'".GetCatalog('bankname')."',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'accountowner',
			title:'".GetCatalog('accountowner')."',
			width:'250px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'".GetCatalog('recordstatus')."',
			align:'center',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('addressbookid','fullname','bankname','accountowner'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td>".GetCatalog('fullname')."</td>
				<td><input class='easyui-textbox' id='supplier-fullname' name='supplier-fullname' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('taxno')."</td>
				<td><input class='easyui-textbox' id='supplier-taxno' name='supplier-taxno' ></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('bankaccountno')."</td>
				<td><input class='easyui-textbox' id='supplier-bankaccountno' name='supplier-bankaccountno' ></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('bankname')."</td>
				<td><input class='easyui-textbox' id='supplier-bankname' name='supplier-bankname' ></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('accountowner')."</td>
				<td><input class='easyui-textbox' id='supplier-accountowner' name='supplier-accountowner' ></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('paymentmethod')."</td>
				<td>
					<select class='easyui-combogrid' id='supplier-paymentmethodid' name='supplier-paymentmethodid' style='width:250px' data-options=\"
						panelWidth: '500px',
						required: true,
						idField: 'paymentmethodid',
						textField: 'paycode',
						mode:'remote',
						url: '".Yii::app()->createUrl('accounting/paymentmethod/index',array('grid'=>true,'combo'=>true)) ."',
						method: 'get',
						columns: [[
							{field:'paymentmethodid',title:'".GetCatalog('paymentmethodid') ."',width:'50px'},
							{field:'paycode',title:'".GetCatalog('paycode') ."',width:'120px'},
							{field:'paydays',title:'".GetCatalog('paydays') ."',width:'120px'},
						]],
						fitColumns: true\">
					</select>
				</td>
			</tr>
			<tr>
				<td>".GetCatalog('recordstatus')."</td>
				<td><input id='supplier-recordstatus' name='supplier-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
	",
	'loadsuccess' => "
		$('#supplier-fullname').textbox('setValue',data.fullname);
		$('#supplier-taxno').textbox('setValue',data.taxno);
		$('#supplier-bankaccountno').textbox('setValue',data.bankaccountno);
		$('#supplier-bankname').textbox('setValue',data.bankname);
		$('#supplier-accountowner').textbox('setValue',data.accountowner);
		$('#supplier-paymentmethodid').combogrid('setValue',data.paymentmethodid);
		if (data.recordstatus == 1) {
			$('#supplier-recordstatus').prop('checked', true);
		} else {
			$('#supplier-recordstatus').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'address',
			'idfield'=>'addressid',
			'urlsub'=>Yii::app()->createUrl('common/supplier/indexaddress',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('common/supplier/searchaddress',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/supplier/saveaddress',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('common/supplier/saveaddress',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('common/supplier/purgeaddress',array('grid'=>true)),
			'subs'=>"
				{field:'addresstypename',title:'".GetCatalog('addresstypename')."',width:'200px'},
				{field:'addressname',title:'".GetCatalog('addressname')."',width:'200px'},
				{field:'rt',title:'".GetCatalog('rt')."',width:'200px'},
				{field:'rw',title:'".GetCatalog('rw')."',width:'200px'},
				{field:'cityname',title:'".GetCatalog('cityname')."',width:'200px'},
				{field:'phoneno',title:'".GetCatalog('phoneno')."',width:'200px'},
				{field:'faxno',title:'".GetCatalog('faxno')."',width:'200px'}
			",
			'columns'=>"
				{
					field:'addressbookid',
					title:'".GetCatalog('addressbookid')."',
					width:'80px',
					hidden:true,
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'addressid',
					title:'".GetCatalog('addressid')."',
					width:'80px',
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'addresstypeid',
					title:'".GetCatalog('addresstype')."',
					width:'200px',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'addresstypeid',
							textField:'addresstypename',
							url:'". Yii::app()->createUrl('common/addresstype/index',array('grid'=>true,'combo'=>true))."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'addresstypeid',title:'".GetCatalog('addresstypeid')."',width:'80px'},
								{field:'addresstypename',title:'".GetCatalog('addresstypename')."',width:'200px'}
							]]
						}	
					},
					sortable: true,
					formatter: function(value,row,index){
						return row.addresstypename;
					}
				},
				{
					field:'addressname',
					title:'".GetCatalog('addressname')."',
					width:'200px',
					editor:{
						type: 'textbox',
						options: {
							required:true,
							multiline:true
						}
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'rt',
					title:'".GetCatalog('rt')."',
					width:'50px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'rw',
					title:'".GetCatalog('rw')."',
					width:'50px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'cityid',
					title:'".GetCatalog('city')."',
					width:'150px',
					editor:{
						type:'combogrid',
						options:{
							panelWidth:'500px',
							mode : 'remote',
							method:'get',
							idField:'cityid',
							textField:'cityname',
							url:'". Yii::app()->createUrl('admin/city/index',array('grid'=>true,'combo'=>true))."',
							fitColumns:true,
							required:true,
							loadMsg: '".GetCatalog('pleasewait')."',
							columns:[[
								{field:'cityid',title:'".GetCatalog('cityid')."',width:'50px'},
								{field:'cityname',title:'".GetCatalog('cityname')."',width:'200px'}
							]]
						}	
					},
					sortable: true,
					formatter: function(value,row,index){
						return row.cityname;
					}
				},
				{
					field:'phoneno',
					title:'".GetCatalog('phoneno')."',
					width:'150px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'faxno',
					title:'".GetCatalog('faxno')."',
					width:'150px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'lat',
					title:'".GetCatalog('lat')."',
					width:'150px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				},
				{
					field:'lng',
					title:'".GetCatalog('lng')."',
					width:'150px',
					editor:{
						type: 'textbox',
					},
					sortable: true,
					formatter: function(value,row,index){
						return value;
					}
				}
			"
		),
		array(
			'id'=>'addresscontact',
			'idfield'=>'addresscontactid',
			'urlsub'=>Yii::app()->createUrl('common/supplier/indexcontact',array('grid'=>true)),
			'url'=> Yii::app()->createUrl('common/supplier/searchcontact',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/supplier/savecontact',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('common/supplier/savecontact',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('common/supplier/purgecontact',array('grid'=>true)),
			'subs'=>"
				{field:'contacttypename',title:'".GetCatalog('contacttypename')."',width:'200px'},
				{field:'addresscontactname',title:'".GetCatalog('addresscontactname')."',width:'200px'},
				{field:'phoneno',title:'".GetCatalog('phoneno')."',width:'200px'},
				{field:'mobilephone',title:'".GetCatalog('mobilephone')."',width:'200px'},
				{field:'emailaddress',title:'".GetCatalog('emailaddress')."',width:'200px'}
			",
			'columns'=>"
			{
				field:'addressbookid',
				width:'50px',
				hidden:true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'addresscontactid',
				title:'".GetCatalog('addresscontactid')."',
				width:'50px',
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'contacttypeid',
				title:'".GetCatalog('contacttype')."',
				width:'150px',
				editor:{
					type:'combogrid',
					options:{
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'contacttypeid',
						textField:'contacttypename',
						url:'". Yii::app()->createUrl('common/contacttype/index',array('grid'=>true,'combo'=>true))."',
						fitColumns:true,
						required:true,
						loadMsg: '".GetCatalog('pleasewait')."',
						columns:[[
							{field:'contacttypeid',title:'".GetCatalog('contacttypeid')."',width:'80px'},
							{field:'contacttypename',title:'".GetCatalog('contacttypename')."',width:'200px'}
						]]
					}	
				},
				sortable: true,
				formatter: function(value,row,index){
					return row.contacttypename;
				}
			},
			{
				field:'addresscontactname',
				title:'".GetCatalog('addresscontactname')."',
				width:'150px',
				editor:{
					type: 'textbox',
					options:{
						required:true,
					}
				},
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'phoneno',
				title:'".GetCatalog('phoneno')."',
				width:'150px',
				editor:{
					type: 'textbox',
				},
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'mobilephone',
				title:'".GetCatalog('mobilephone')."',
				width:'150px',
				editor:{
					type: 'textbox',
				},
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			},
			{
				field:'emailaddress',
				title:'".GetCatalog('emailaddress')."',
				width:'150px',
				editor:{
					type: 'textbox',
				},
				sortable: true,
				formatter: function(value,row,index){
					return value;
				}
			}	
			"
		),
		array(
			'id'=>'supplierproduct',
			'idfield'=>'purchinforecid',
			'urlsub'=>Yii::app()->createUrl('common/supplier/indexpurchinforec',array('grid'=>true)),
			'url'=> Yii::app()->createUrl('common/supplier/searchpurchinforec',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/supplier/savepurchinforec',array('grid'=>true)),
			'updateurl'=>Yii::app()->createUrl('common/supplier/savepurchinforec',array('grid'=>true)),
			'destroyurl'=>Yii::app()->createUrl('common/supplier/purgeinforec',array('grid'=>true)),
			'subs'=>"
				{field:'productcode',title:'".GetCatalog('productcode')."',width:'150px'},
				{field:'productname',title:'".GetCatalog('productname')."',width:'250px'},
				{field:'price',title:'".GetCatalog('price')."',width:'200px'},
				{field:'currencyname',title:'".GetCatalog('currencyname')."',width:'200px'},
				{field:'toleransidown',title:'".GetCatalog('toleransidown')."',width:'200px'},
				{field:'toleransiup',title:'".GetCatalog('toleransiup')."',width:'200px'}
			",
			'columns'=>"
			{
        field:'purchinforecid',
        title:'".GetCatalog('purchinforecid') ."',
        width:'50px',
        sortable: true,
        formatter: function(value,row,index){
          return value;
      }},
      {
        field:'addressbookid',
        title:'".GetCatalog('supplier') ."',
        width:'250px',
        hidden: true,
        sortable: true,
        formatter: function(value,row,index){
          return value;
      }},
      {
        field:'productid',
        title:'".GetCatalog('product') ."',
        width:'450px',
        editor:{
          type:'combogrid',
          options:{
            panelWidth:'600px',
            mode : 'remote',
            method:'get',
            idField:'productid',
            textField:'productname',
            url:'".Yii::app()->createUrl('common/product/index',array('grid'=>true,'combo'=>true)) ."',
            fitColumns:true,
            required:true,
            loadMsg: '".GetCatalog('pleasewait')."',
            columns:[[
              {field:'productid',title:'".GetCatalog('productid')."',width:'50px'},
              {field:'productcode',title:'".GetCatalog('productcode')."',width:'150px'},
              {field:'productname',title:'".GetCatalog('productname')."',width:'300px'},
            ]]
          }	
        },
        sortable: true,
        formatter: function(value,row,index){
          return row.productname;
      }},
      {
        field:'price',
        title:'".GetCatalog('price') ."',
        width:'120px',
        editor:{
          type:'numberbox',
          options:{
            precision:2,
            required:true,
            decimalSeparator:',',
            groupSeparator:'.'
          }
        },
        sortable: true,
        formatter: function(value,row,index){
          return formatnumber('',value);
      }},
      {
        field:'currencyid',
        title:'".GetCatalog('currency') ."',
        width:'100px',
        editor:{
          type:'combogrid',
          options:{
            panelWidth:'500px',
            mode : 'remote',
            method:'get',
            idField:'currencyid',
            textField:'currencyname',
            required:true,
            url:'".Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
            fitColumns:true,
            loadMsg: '".GetCatalog('pleasewait')."',
            columns:[[
              {field:'currencyid',title:'".GetCatalog('currencyid')."',width:'50px'},
              {field:'symbol',title:'".GetCatalog('symbol')."',width:'100px'},
              {field:'currencyname',title:'".GetCatalog('currencyname')."',width:'200px'},
            ]]
          }	
        },
        sortable: true,
        formatter: function(value,row,index){
          return row.currencyname;
      }},
      {
        field:'biddate',
        title:'".GetCatalog('biddate') ."',
        width:'120px',
        editor:{
          type:'datebox',
          options:{
            required:true,
          },
        },
        sortable: true,
        formatter: function(value,row,index){
          return value;
      }},
      {
        field:'toleransidown',
        title:'".GetCatalog('toleransidown') ."',
        width:'150px',
        editor:{
          type:'numberbox',
          options:{
            precision:2,
            required:true,
            decimalSeparator:',',
            groupSeparator:'.'
          }
        },
        sortable: true,
        formatter: function(value,row,index){
          return formatnumber('',value);
      }},
      {
        field:'toleransiup',
        title:'".GetCatalog('toleransiup') ."',
        width:'150px',
        editor:{
          type:'numberbox',
          options:{
            precision:2,
            required:true,
            decimalSeparator:',',
            groupSeparator:'.'
          }
        },
        sortable: true,
        formatter: function(value,row,index){
          return formatnumber('',value);
      }},	
			"
		),
		array(
			'id'=>'supplierpo',
			'idfield'=>'purchinforecid',
			'urlsub'=>Yii::app()->createUrl('common/supplier/indexsupplierpo',array('grid'=>true)),
      'url'=> Yii::app()->createUrl('common/supplier/searchsupplierpo',array('grid'=>true)),
      'isnew'=>0,
      'iswrite'=>0,
      'ispurge'=>0,
			'subs'=>"
        {field:'pono',title:'".GetCatalog('pono')."',width:'150px'},
        {field:'productcode',title:'".GetCatalog('productcode')."',width:'150px'},
        {field:'productname',title:'".GetCatalog('productname')."',width:'250px'},
        {field:'price',title:'".GetCatalog('price')."',width:'200px',
          formatter: function(value,row,index){
            return formatnumber('',value);
        }},
        {field:'currencyname',title:'".GetCatalog('currencyname')."',width:'200px'},
        {field:'toleransidown',title:'".GetCatalog('toleransidown')."',width:'200px'},
        {field:'toleransiup',title:'".GetCatalog('toleransiup')."',width:'200px'}
      ",
			'columns'=>"
			{
        field:'purchinforecid',
        title:'".GetCatalog('purchinforecid') ."',
        width:'50px',
        sortable: true,
        formatter: function(value,row,index){
          return value;
      }},
      {
        field:'addressbookid',
        title:'".GetCatalog('supplier') ."',
        width:'250px',
        hidden: true,
        sortable: true,
        formatter: function(value,row,index){
          return value;
      }},
      {
        field:'pono',
        title:'".GetCatalog('pono') ."',
        width:'150px',
        sortable: true,
        formatter: function(value,row,index){
          return value;
      }},
      {
        field:'productid',
        title:'".GetCatalog('product') ."',
        width:'450px',
        sortable: true,
        formatter: function(value,row,index){
          return row.productname;
      }},
      {
        field:'price',
        title:'".GetCatalog('price') ."',
        width:'120px',
        sortable: true,
        formatter: function(value,row,index){
          return formatnumber('',value);
      }},
      {
        field:'currencyid',
        title:'".GetCatalog('currency') ."',
        width:'100px',
        sortable: true,
        formatter: function(value,row,index){
          return row.currencyname;
      }},
      {
        field:'biddate',
        title:'".GetCatalog('buydate') ."',
        width:'120px',
        sortable: true,
        formatter: function(value,row,index){
          return value;
      }},
      {
        field:'toleransidown',
        title:'".GetCatalog('toleransidown') ."',
        width:'150px',
        sortable: true,
        formatter: function(value,row,index){
          return formatnumber('',value);
      }},
      {
        field:'toleransiup',
        title:'".GetCatalog('toleransiup') ."',
        width:'150px',
        sortable: true,
        formatter: function(value,row,index){
          return formatnumber('',value);
      }},	
			"
		),
		array(
			'id'=>'invoiceap',
			'idfield'=>'invoiceapid',
			'urlsub'=>Yii::app()->createUrl('common/supplier/indexinvoiceap',array('grid'=>true)),
      'url'=> Yii::app()->createUrl('common/supplier/searchinvoiceap',array('grid'=>true)),
      'isnew'=>0,
      'iswrite'=>0,
      'ispurge'=>0,
			'subs'=>"
        {field:'plantcode',title:'".GetCatalog('plantcode')."',width:'150px'},
        {field:'invoiceapno',title:'".GetCatalog('invoiceapno')."',width:'150px'},
        {field:'pono',title:'".GetCatalog('pono')."',width:'150px'},
        {field:'contractno',title:'".GetCatalog('contractno')."',width:'250px'},
        {field:'amount',title:'".GetCatalog('amount')."',width:'200px',
          formatter: function(value,row,index){
            return formatnumber('',value);
        }},
      ",
			'columns'=>"
			{
        field:'invoiceapid',
        title:'".GetCatalog('invoiceapid') ."',
        width:'50px',
        sortable: true,
        formatter: function(value,row,index){
          return value;
      }},
      {
        field:'addressbookid',
        title:'".GetCatalog('supplier') ."',
        width:'250px',
        hidden: true,
        sortable: true,
        formatter: function(value,row,index){
          return value;
      }},
      {
        field:'pono',
        title:'".GetCatalog('pono') ."',
        width:'150px',
        sortable: true,
        formatter: function(value,row,index){
          return value;
      }},
      {
        field:'invoiceapno',
        title:'".GetCatalog('invoiceapno') ."',
        width:'150px',
        sortable: true,
        formatter: function(value,row,index){
          return value;
      }},
      {
        field:'contractno',
        title:'".GetCatalog('contractno') ."',
        width:'450px',
        sortable: true,
        formatter: function(value,row,index){
          return row.contractno;
      }},
      {
        field:'amount',
        title:'".GetCatalog('amount') ."',
        width:'120px',
        sortable: true,
        formatter: function(value,row,index){
          return formatnumber('',value);
      }},
			"
		)
	),	
));
