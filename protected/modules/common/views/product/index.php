<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'productid',
	'formtype'=>'masterdetail',
	'url'=>Yii::app()->createUrl('common/product/index',array('grid'=>true)),
	'urlgetdata'=>Yii::app()->createUrl('common/product/getdata',array('grid'=>true)),
	'saveurl'=>Yii::app()->createUrl('common/product/save',array('grid'=>true)),
	'updateurl'=>Yii::app()->createUrl('common/product/save',array('grid'=>true)),
	'destroyurl'=>Yii::app()->createUrl('common/product/purge',array('grid'=>true)),
	'uploadurl'=>Yii::app()->createUrl('common/product/upload'),
	'downpdf'=>Yii::app()->createUrl('common/product/downpdf'),
	'downxls'=>Yii::app()->createUrl('common/product/downxls'),
	'downdoc'=>Yii::app()->createUrl('common/product/downdoc'),
	'columns'=>"
		{
			field:'productid',
			title: localStorage.getItem('catalogproductid'),
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'materialtypeid',
			title: localStorage.getItem('catalogmaterialtype'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.description;
		}},		
		{
			field:'materialgroupid',
			title: localStorage.getItem('catalogmaterialgroup'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.materialgroupcode;
		}},
		{
			field:'productcode',
			title: localStorage.getItem('catalogproductcode'),
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productname',
			title: localStorage.getItem('catalogproductname'),
			width:'500px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productpic',
			title: localStorage.getItem('catalogproductpic'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'thickness',
			title: localStorage.getItem('catalogthickness'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'width',
			title: localStorage.getItem('catalogwidth'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'length',
			title: localStorage.getItem('cataloglength'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'qty1',
			title: localStorage.getItem('catalogqty1'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'uom1',
			title: localStorage.getItem('cataloguom1'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom1code;
		}},
		{
			field:'qty2',
			title: localStorage.getItem('catalogqty2'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'uom2',
			title: localStorage.getItem('cataloguom2'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom2code;
		}},
		{
			field:'qty3',
			title: localStorage.getItem('catalogqty3'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'uom3',
			title: localStorage.getItem('cataloguom3'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom3code;
		}},
		{
			field:'sled',
			title: localStorage.getItem('catalogsled'),
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.sled;
		}},
		{
			field:'isautolot',
			title: localStorage.getItem('catalogisautolot'),
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'isstock',
			title: localStorage.getItem('catalogisstock'),
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'isasset',
			title: localStorage.getItem('catalogisasset'),
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
		}},
		{
			field:'barcode',
			title: localStorage.getItem('catalogbarcode'),
			editor:'text',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title: localStorage.getItem('catalogrecordstatus'),
			align:'center',
			width:'50px',
			editor:{type:'checkbox',options:{on:'1',off:'0'}},
			sortable: true,
			formatter: function(value,row,index){
				if (value == 1){
					return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
				} else {
					return '';
				}
		}}",
	'searchfield'=> array ('productid','productname','barcode','materialtypecode','materialtypedesc'),
	'headerform'=> "
		<table cellpadding='5'>
			<tr>
				<td id='producttext-materialtype'></td>
				<td><select class='easyui-combogrid' id='product-materialtypeid' name='product-materialtypeid' style='width:300px' data-options=\"
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'materialtypeid',
						textField:'description',
						url:'". Yii::app()->createUrl('common/materialtype/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg:  localStorage.getItem('catalogpleasewait'),
						columns:[[
							{field:'materialtypeid',title: localStorage.getItem('catalogmaterialtypeid'),width:'50px'},
							{field:'description',title: localStorage.getItem('catalogdescription'),width:'200px'},
						]]
					\">
			</select></td>
      </tr>
      <tr>
				<td id='producttext-materialgroup'></td>
				<td><select class='easyui-combogrid' id='product-materialgroupid' name='product-materialgroupid' style='width:300px' data-options=\"
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'materialgroupid',
						textField:'description',
						url:'". Yii::app()->createUrl('common/materialgroup/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg:  localStorage.getItem('catalogpleasewait'),
						columns:[[
							{field:'materialgroupid',title: localStorage.getItem('catalogmaterialgroupid'),width:'50px'},
							{field:'materialgroupcode',title: localStorage.getItem('catalogmaterialgroupcode'),width:'200px'},
							{field:'description',title: localStorage.getItem('catalogdescription'),width:'200px'},
						]]
					\">
			</select></td>
			</tr>
			<tr>
				<td id='producttext-productcode'></td>
				<td><input class='easyui-textbox' id='product-productcode' name='product-productcode' data-options=\"width:'300px'\"></input></td>
			</tr>
			<tr>
				<td id='producttext-productname'></td>
				<td><input class='easyui-textbox' id='product-productname' name='product-productname' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td id='producttext-thickness'></td>
				<td><input class='easyui-numberbox' id='product-thickness' name='product-thickness' data-options=\"required:true,width:'300px',precision:4,
				decimalSeparator:',',
				groupSeparator:'.'\"></input></td>
			</tr>			
			<tr>
				<td id='producttext-width'></td>
				<td><input class='easyui-numberbox' id='product-width' name='product-width' data-options=\"required:true,width:'300px',precision:4,
				decimalSeparator:',',
				groupSeparator:'.'\"></input></td>
			</tr>			
			<tr>
				<td id='producttext-length'></td>
				<td><input class='easyui-numberbox' id='product-length' name='product-length' data-options=\"required:true,width:'300px',precision:4,
				decimalSeparator:',',
				groupSeparator:'.'\"></input></td>
			</tr>			
			<tr>
				<td id='producttext-productpic'></td>
				<td><input class='easyui-textbox' id='product-productpic' name='product-productpic' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>			
			<tr>
				<td id='producttext-barcode'></td>
				<td><input class='easyui-textbox' id='product-barcode' name='product-barcode' data-options=\"width:'300px'\"></input></td>
			</tr>
			<tr>
				<td id='producttext-isstock'></td>
				<td><input id='product-isstock' name='product-isstock' type='checkbox'></input></td>
			</tr>
			<tr>
				<td id='producttext-isasset'></td>
				<td><input id='product-isasset' name='product-isasset' type='checkbox'></input></td>
      </tr>
      <tr>
        <td id='producttext-qty1'></td>
        <td><input class='easyui-numberbox' id='product-qty1' name='product-qty1' data-options=\"required:true,width:'300px',precision:4,
				decimalSeparator:',',
				groupSeparator:'.'\"></input></td>
      </tr>			
      <tr>
				<td id='producttext-uom1'></td>
				<td><select class='easyui-combogrid' id='product-uom1' name='product-uom1' style='width:300px' data-options=\"
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'unitofmeasureid',
						textField:'uomcode',
						url:'". Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg:  localStorage.getItem('catalogpleasewait'),
						columns:[[
							{field:'unitofmeasureid',title: localStorage.getItem('catalogunitofmeasureid'),width:'50px'},
							{field:'uomcode',title: localStorage.getItem('cataloguomcode'),width:'200px'},
						]]
					\">
			</select></td>
			</tr>
      <tr>
        <td id='producttext-qty2'></td>
        <td><input class='easyui-numberbox' id='product-qty2' name='product-qty2' data-options=\"required:true,width:'300px'\"></input></td>
      </tr>			
      <tr>
				<td id='producttext-uom2'></td>
				<td><select class='easyui-combogrid' id='product-uom2' name='product-uom2' style='width:300px' data-options=\"
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'unitofmeasureid',
						textField:'uomcode',
						url:'". Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						loadMsg:  localStorage.getItem('catalogpleasewait'),
						columns:[[
							{field:'unitofmeasureid',title: localStorage.getItem('catalogunitofmeasureid'),width:'50px'},
							{field:'uomcode',title: localStorage.getItem('cataloguomcode'),width:'200px'},
						]]
					\">
			</select></td>
      </tr>
      <tr>
        <td id='producttext-qty3'></td>
        <td><input class='easyui-numberbox' id='product-qty3' name='product-qty3' data-options=\"required:true,width:'300px'\"></input></td>
      </tr>			
      <tr>
        <td id='producttext-uom3'></td>
        <td><select class='easyui-combogrid' id='product-uom3' name='product-uom3' style='width:300px' data-options=\"
            panelWidth:'500px',
            mode : 'remote',
            method:'get',
            idField:'unitofmeasureid',
            textField:'uomcode',
            url:'". Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
            fitColumns:true,
            loadMsg:  localStorage.getItem('catalogpleasewait'),
            columns:[[
              {field:'unitofmeasureid',title: localStorage.getItem('catalogunitofmeasureid'),width:'50px'},
              {field:'uomcode',title: localStorage.getItem('cataloguomcode'),width:'200px'},
            ]]
          \">
      </select></td>
      </tr>
      <tr>
        <td id='producttext-sled'></td>
        <td><input class='easyui-numberbox' id='product-sled' name='product-sled' data-options=\"required:true,width:'300px'\"></input></td>
      </tr>			
			<tr>
				<td id='producttext-isautolot'></td>
				<td><input id='product-isautolot' name='product-isautolot' type='checkbox'></input></td>
			</tr>
			<tr>
				<td id='producttext-recordstatus'></td>
				<td><input id='product-recordstatus' name='product-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
  ",
  'addonscripts'=>"
    $(document).ready(function(){
      var parel = document.getElementById('producttext-materialtype');
      parel.innerHTML = localStorage.getItem('catalogmaterialtype');
      parel = document.getElementById('producttext-materialgroup');
      parel.innerHTML = localStorage.getItem('catalogmaterialgroup');
      parel = document.getElementById('producttext-productcode');
      parel.innerHTML = localStorage.getItem('catalogproductcode');
      parel = document.getElementById('producttext-productname');
      parel.innerHTML = localStorage.getItem('catalogproductname');
      parel = document.getElementById('producttext-thickness');
      parel.innerHTML = localStorage.getItem('catalogthickness');
      parel = document.getElementById('producttext-width');
      parel.innerHTML = localStorage.getItem('catalogwidth');
      parel = document.getElementById('producttext-length');
      parel.innerHTML = localStorage.getItem('cataloglength');
      parel = document.getElementById('producttext-productpic');
      parel.innerHTML = localStorage.getItem('catalogproductpic');
      parel = document.getElementById('producttext-barcode');
      parel.innerHTML = localStorage.getItem('catalogbarcode');
      parel = document.getElementById('producttext-isstock');
      parel.innerHTML = localStorage.getItem('catalogisstock');
      parel = document.getElementById('producttext-isasset');
      parel.innerHTML = localStorage.getItem('catalogisasset');
      parel = document.getElementById('producttext-qty1');
      parel.innerHTML = localStorage.getItem('catalogqty1');
      parel = document.getElementById('producttext-uom1');
      parel.innerHTML = localStorage.getItem('cataloguom1');
      parel = document.getElementById('producttext-qty2');
      parel.innerHTML = localStorage.getItem('catalogqty2');
      parel = document.getElementById('producttext-uom2');
      parel.innerHTML = localStorage.getItem('cataloguom2');
      parel = document.getElementById('producttext-qty3');
      parel.innerHTML = localStorage.getItem('catalogqty3');
      parel = document.getElementById('producttext-uom3');
      parel.innerHTML = localStorage.getItem('cataloguom3');
      parel = document.getElementById('producttext-sled');
      parel.innerHTML = localStorage.getItem('catalogsled');
      parel = document.getElementById('producttext-isautolot');
      parel.innerHTML = localStorage.getItem('catalogisautolot');
      parel = document.getElementById('producttext-recordstatus');
      parel.innerHTML = localStorage.getItem('catalogrecordstatus');
    });
  ",
  'addload' => "
    $('#product-productpic').textbox('setValue','default.jpg');
    $('#product-thickness').numberbox('setValue','0');
    $('#product-width').numberbox('setValue','0');
    $('#product-length').numberbox('setValue','0');
  ",
	'loadsuccess' => "
		if (data.isstock == 1)
		{
			$('#product-isstock').prop('checked', true);
		} else
		{
			$('#product-isstock').prop('checked', false);
		}
		if (data.isasset == 1)
		{
			$('#product-isasset').prop('checked', true);
		} else
		{
			$('#product-isasset').prop('checked', false);
		}
		if (data.isautolot == 1)
		{
			$('#product-isautolot').prop('checked', true);
		} else
		{
			$('#product-isautolot').prop('checked', false);
		}
		if (data.recordstatus == 1)
		{
			$('#product-recordstatus').prop('checked', true);
		} else
		{
			$('#product-recordstatus').prop('checked', false);
		}
	",
	'columndetails'=> array (
		array(
			'id'=>'productplant',
			'idfield'=>'productplantid',
			'urlsub'=>Yii::app()->createUrl('common/product/indexproductplant',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('common/product/indexproductplant',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/product/saveproductplant'),
			'updateurl'=>Yii::app()->createUrl('common/product/saveproductplant'),
			'destroyurl'=>Yii::app()->createUrl('common/product/purgeproductplant'),
			'subs'=>"
				{field:'sloccode',title: localStorage.getItem('catalogsloc'),width:'200px'},
				{field:'issource',title: localStorage.getItem('catalogissource'),width:'200px',formatter: function(value,row,index){
					if (value == 1){
						return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
					} else {
						return '';
					}
				}},
			",
			'columns'=>"
				{
					field:'productplantid',
					title: localStorage.getItem('catalogproductplantid'),
					sortable: true,
					width:'80px',
					formatter: function(value,row,index){
						return value;
        }},
				{
					field:'productid',
					title: localStorage.getItem('catalogproductid'),
					sortable: true,
					hidden: true,
					width:'50px',
					formatter: function(value,row,index){
						return value;
        }},
				{
					field:'productid',
					title: localStorage.getItem('catalogproductname'),
					width:'350px',
					hidden: true,
					sortable: true,
					formatter: function(value,row,index){
						return row.productid;
				}},
				{
					field:'slocid',
					title: localStorage.getItem('catalogsloc'),
					editor:{
						type:'combogrid',
						options:{
								panelWidth:'500px',
								mode : 'remote',
								method:'get',
								idField:'slocid',
								textField:'sloccode',
								url:'". $this->createUrl('sloc/indexcombo',array('grid'=>true,'combo'=>true)) ."',
								fitColumns:true,
								required:true,
								loadMsg:  localStorage.getItem('catalogpleasewait'),
								columns:[[
									{field:'slocid',title: localStorage.getItem('catalogslocid'),width:'80px'},
									{field:'sloccode',title: localStorage.getItem('catalogsloccode'),width:'80px'},
									{field:'description',title: localStorage.getItem('catalogdescription'),width:'200px'},
								]]
						}	
					},
					width:'100px',
					sortable: true,
					formatter: function(value,row,index){
						return row.sloccode;
				}},
				{
					field:'issource',
					title: localStorage.getItem('catalogsource'),
					align:'center',
					width:'50px',
					editor:{type:'checkbox',options:{on:'1',off:'0'}},
					sortable: true,
					formatter: function(value,row,index){
						if (value == 1){
							return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
						} else {
							return '';
						}
				}}
			"
      ),
		array(
			'id'=>'productsales',
			'idfield'=>'productsalesid',
			'urlsub'=>Yii::app()->createUrl('common/product/indexproductsales',array('grid'=>true)),
			'url'=>Yii::app()->createUrl('common/product/indexproductsales',array('grid'=>true)),
			'saveurl'=>Yii::app()->createUrl('common/product/saveproductsales'),
			'updateurl'=>Yii::app()->createUrl('common/product/saveproductsales'),
			'destroyurl'=>Yii::app()->createUrl('common/product/purgeproductsales'),
			'subs'=>"
				{field:'productsalesid',title: localStorage.getItem('catalogproductsalesid'),width:'120px'},
				{field:'currencyid',title: localStorage.getItem('catalogcurrency'),width:'120px'},
				{field:'currencyvalue',title: localStorage.getItem('catalogcurrencyvalue'),width:'150px'},
				{field:'pricecategoryid',title: localStorage.getItem('catalogpricecategory'),width:'200px'},
				{field:'uomid',title: localStorage.getItem('cataloguom'),width:'200px'},
				{field:'issource',title: localStorage.getItem('catalogissource'),width:'200px',formatter: function(value,row,index){
					if (value == 1){
						return '<img src=\"". Yii::app()->request->baseUrl."/images/icons/ok.png"."\"></img>';
					} else {
						return '';
					}
				}},
			",
			'columns'=>"
			{
				field:'productsalesid',
				title: localStorage.getItem('catalogproductsalesid'),
				sortable: true,
				width:'80px',
				formatter: function(value,row,index){
					return value;
			}},
			{
				field:'productid',
				title: localStorage.getItem('catalogproductname'),
				width:'50px',
				hidden:true,
				sortable: true,
				formatter: function(value,row,index){
					return value;
			}},
		{
			field:'currencyid',
			title: localStorage.getItem('catalogcurrency'),
			width:'100px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'currencyid',
					textField:'currencyname',
					url:'". Yii::app()->createUrl('admin/currency/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg:  localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'currencyid',title: localStorage.getItem('catalogcurrencyid'),width:'50px'},
						{field:'currencyname',title: localStorage.getItem('catalogcurrencyname'),width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.currencyname;
		}},
		{
			field:'currencyvalue',
			title: localStorage.getItem('catalogcurrencyvalue'),
			width:'150px',
			editor:{
				type:'numberbox',
				options:{
					precision:2,
					decimalSeparator:',',
					groupSeparator:'.',
					required:true,
				}
			},
			align: 'right',
			sortable: true,
			formatter: function(value,row,index){
			return formatnumber('',value);
		}},
		{
			field:'pricecategoryid',
			title: localStorage.getItem('catalogpricecategory'),
			pagination:true,
			width:'150px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'pricecategoryid',
					textField:'categoryname',
					url:'". Yii::app()->createUrl('common/pricecategory/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg:  localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'pricecategoryid',title: localStorage.getItem('catalogpricecategoryid'),width:'50px'},
						{field:'categoryname',title: localStorage.getItem('catalogcategoryname'),width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.categoryname;
		}},
		{
			field:'uomid',
			title: localStorage.getItem('cataloguom'),
			pagination:true,
			width:'100px',
			editor:{
				type:'combogrid',
				options:{
					panelWidth:'500px',
					mode : 'remote',
					method:'get',
					idField:'unitofmeasureid',
					textField:'uomcode',
					url:'". Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
					fitColumns:true,
					required:true,
					loadMsg:  localStorage.getItem('catalogpleasewait'),
					columns:[[
						{field:'unitofmeasureid',title: localStorage.getItem('catalogunitofmeasureid'),width:'50px'},
						{field:'uomcode',title: localStorage.getItem('cataloguomcode'),width:'200px'},
					]]
				}	
			},
			sortable: true,
			formatter: function(value,row,index){
				return row.uomcode;
		}}
			"
      ),
	)
));