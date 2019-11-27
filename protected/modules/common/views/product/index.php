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
	'columns'=>"
		{
			field:'productid',
			title:'". GetCatalog('productid') ."',
			sortable: true,
			width:'50px',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'materialtypeid',
			title:'". GetCatalog('materialtype') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.description;
		}},		
		{
			field:'materialgroupid',
			title:'". GetCatalog('materialgroup') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.materialgroupcode;
		}},
		{
			field:'productcode',
			title:'". GetCatalog('productcode') ."',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productname',
			title:'". GetCatalog('productname') ."',
			width:'500px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'productpic',
			title:'". GetCatalog('productpic') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'thickness',
			title:'". GetCatalog('thickness') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber(value);
		}},
		{
			field:'width',
			title:'". GetCatalog('width') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber(value);
		}},
		{
			field:'length',
			title:'". GetCatalog('length') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber(value);
		}},
		{
			field:'qty1',
			title:'". GetCatalog('qty1') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'uom1',
			title:'". GetCatalog('uom1') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom1code;
		}},
		{
			field:'qty2',
			title:'". GetCatalog('qty2') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'uom2',
			title:'". GetCatalog('uom2') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom2code;
		}},
		{
			field:'qty3',
			title:'". GetCatalog('qty3') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return formatnumber('',value);
		}},
		{
			field:'uom3',
			title:'". GetCatalog('uom3') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.uom3code;
		}},
		{
			field:'sled',
			title:'". GetCatalog('sled') ."',
			width:'100px',
			sortable: true,
			formatter: function(value,row,index){
				return row.sled;
		}},
		{
			field:'isautolot',
			title:'". GetCatalog('isautolot') ."',
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
			title:'". GetCatalog('isstock') ."',
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
			title:'". GetCatalog('isasset') ."',
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
			title:'". GetCatalog('barcode') ."',
			editor:'text',
			width:'120px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'recordstatus',
			title:'". GetCatalog('recordstatus') ."',
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
				<td>".GetCatalog('materialtype')."</td>
				<td><select class='easyui-combogrid' id='product-materialtypeid' name='product-materialtypeid' style='width:300px' data-options=\"
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'materialtypeid',
						textField:'description',
						url:'". Yii::app()->createUrl('common/materialtype/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'materialtypeid',title:'". GetCatalog('materialtypeid')."',width:'50px'},
							{field:'description',title:'". GetCatalog('description')."',width:'200px'},
						]]
					\">
			</select></td>
      </tr>
      <tr>
				<td>".GetCatalog('materialgroup')."</td>
				<td><select class='easyui-combogrid' id='product-materialgroupid' name='product-materialgroupid' style='width:300px' data-options=\"
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
							{field:'materialgroupid',title:'". GetCatalog('materialgroupid')."',width:'50px'},
							{field:'materialgroupcode',title:'". GetCatalog('materialgroupcode')."',width:'200px'},
							{field:'description',title:'". GetCatalog('description')."',width:'200px'},
						]]
					\">
			</select></td>
			</tr>
			<tr>
				<td>".GetCatalog('productcode')."</td>
				<td><input class='easyui-textbox' id='product-productcode' name='product-productcode' data-options=\"width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('productname')."</td>
				<td><input class='easyui-textbox' id='product-productname' name='product-productname' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>".GetCatalog('thickness')."</td>
				<td><input class='easyui-numberbox' id='product-thickness' name='product-thickness' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>			
			<tr>
				<td>".GetCatalog('width')."</td>
				<td><input class='easyui-numberbox' id='product-width' name='product-width' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>			
			<tr>
				<td>".GetCatalog('length')."</td>
				<td><input class='easyui-numberbox' id='product-length' name='product-length' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>			
			<tr>
				<td>".GetCatalog('productpic')."</td>
				<td><input class='easyui-textbox' id='product-productpic' name='product-productpic' data-options=\"required:true,width:'300px'\"></input></td>
			</tr>			
			<tr>
				<td>".GetCatalog('barcode')."</td>
				<td><input class='easyui-textbox' id='product-barcode' name='product-barcode' data-options=\"width:'300px'\"></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('isstock')."</td>
				<td><input id='product-isstock' name='product-isstock' type='checkbox'></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('isasset')."</td>
				<td><input id='product-isasset' name='product-isasset' type='checkbox'></input></td>
      </tr>
      <tr>
        <td>".GetCatalog('qty1')."</td>
        <td><input class='easyui-numberbox' id='product-qty1' name='product-qty1' data-options=\"required:true,width:'300px'\"></input></td>
      </tr>			
      <tr>
				<td>".GetCatalog('uom1')."</td>
				<td><select class='easyui-combogrid' id='product-uom1' name='product-uom1' style='width:300px' data-options=\"
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'unitofmeasureid',
						textField:'uomcode',
						url:'". Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						required:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'unitofmeasureid',title:'". GetCatalog('unitofmeasureid')."',width:'50px'},
							{field:'uomcode',title:'". GetCatalog('uomcode')."',width:'200px'},
						]]
					\">
			</select></td>
			</tr>
      <tr>
        <td>".GetCatalog('qty2')."</td>
        <td><input class='easyui-numberbox' id='product-qty2' name='product-qty2' data-options=\"required:true,width:'300px'\"></input></td>
      </tr>			
      <tr>
				<td>".GetCatalog('uom1')."</td>
				<td><select class='easyui-combogrid' id='product-uom2' name='product-uom2' style='width:300px' data-options=\"
						panelWidth:'500px',
						mode : 'remote',
						method:'get',
						idField:'unitofmeasureid',
						textField:'uomcode',
						url:'". Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
						fitColumns:true,
						loadMsg: '". GetCatalog('pleasewait')."',
						columns:[[
							{field:'unitofmeasureid',title:'". GetCatalog('unitofmeasureid')."',width:'50px'},
							{field:'uomcode',title:'". GetCatalog('uomcode')."',width:'200px'},
						]]
					\">
			</select></td>
      </tr>
      <tr>
        <td>".GetCatalog('qty3')."</td>
        <td><input class='easyui-numberbox' id='product-qty3' name='product-qty3' data-options=\"required:true,width:'300px'\"></input></td>
      </tr>			
      <tr>
        <td>".GetCatalog('uom3')."</td>
        <td><select class='easyui-combogrid' id='product-uom3' name='product-uom3' style='width:300px' data-options=\"
            panelWidth:'500px',
            mode : 'remote',
            method:'get',
            idField:'unitofmeasureid',
            textField:'uomcode',
            url:'". Yii::app()->createUrl('common/unitofmeasure/index',array('grid'=>true,'combo'=>true)) ."',
            fitColumns:true,
            loadMsg: '". GetCatalog('pleasewait')."',
            columns:[[
              {field:'unitofmeasureid',title:'". GetCatalog('unitofmeasureid')."',width:'50px'},
              {field:'uomcode',title:'". GetCatalog('uomcode')."',width:'200px'},
            ]]
          \">
      </select></td>
      </tr>
      <tr>
        <td>".GetCatalog('sled')."</td>
        <td><input class='easyui-numberbox' id='product-sled' name='product-sled' data-options=\"required:true,width:'300px'\"></input></td>
      </tr>			
			<tr>
				<td>". GetCatalog('isautolot')."</td>
				<td><input id='product-isautolot' name='product-isautolot' type='checkbox'></input></td>
			</tr>
			<tr>
				<td>". GetCatalog('recordstatus')."</td>
				<td><input id='product-recordstatus' name='product-recordstatus' type='checkbox'></input></td>
			</tr>
		</table>
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
				{field:'sloccode',title:'".GetCatalog('sloc')."',width:'200px'},
				{field:'issource',title:'".GetCatalog('issource')."',width:'200px',formatter: function(value,row,index){
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
					title:'". GetCatalog('productplantid') ."',
					sortable: true,
					width:'50px',
					formatter: function(value,row,index){
						return value;
        }},
				{
					field:'productid',
					title:'". GetCatalog('productid') ."',
					sortable: true,
					hidden: true,
					width:'50px',
					formatter: function(value,row,index){
						return value;
        }},
				{
					field:'productid',
					title:'". GetCatalog('productname') ."',
					width:'350px',
					hidden: true,
					sortable: true,
					formatter: function(value,row,index){
						return row.productid;
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
								idField:'slocid',
								textField:'sloccode',
								url:'". $this->createUrl('sloc/indexcombo',array('grid'=>true,'combo'=>true)) ."',
								fitColumns:true,
								required:true,
								loadMsg: '". GetCatalog('pleasewait')."',
								columns:[[
									{field:'slocid',title:'". GetCatalog('slocid')."',width:'80px'},
									{field:'sloccode',title:'". GetCatalog('sloccode')."',width:'80px'},
									{field:'description',title:'". GetCatalog('description')."',width:'200px'},
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
					title:'". GetCatalog('source') ."',
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
	)
));
