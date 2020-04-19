<?php if (CheckAccess($this->menuname, $this->iswrite) == 1) {  ?>

<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'formtype'=>'report',
	'isxls'=>0,
	'ispdf'=>0,
	'downloadbuttons'=> "
	<a href=\"javascript:void(0)\" title=\"Generate\" class=\"easyui-linkbutton\" iconCls=\"icon-bom\" plain=\"true\" onclick=\"recalljurnal()\"></a>
	",
	'headerform'=>"
		<table>
			<tr>
				<td>".getCatalog('company')."</td>
				<td><select class=\"easyui-combogrid\" id=\"dlg_search_recalljurnal_companyid\" name=\"dlg_search_recalljurnal_companyid\" style=\"width:250px\" data-options=\"
				panelWidth: 500,
				required: true,
				idField: 'companyid',
				textField: 'companyname',
				pagination:true,
				mode:'remote',
				url: '".Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true))."',
				method: 'get',
				columns: [[
						{field:'companyid',title:'".GetCatalog('companyid')."'},
						{field:'companyname',title:'".GetCatalog('companyname')."'},
				]],
				fitColumns: true,
				prompt:'Company'
			\">
			</select></td>
			</tr>
			<tr>
				<td>".getCatalog('recalloption')."</td>
				<td><select class=\"easyui-combobox\" id=\"dlg_search_recalljurnal_transtype\" name=\"dlg_search_recalljurnal_transtype\" style=\"width:250px\">
				<option value=\"0\">Hapus Semua</option>
				<option value=\"1\">Lanjut</option>
			</select>
				</td>
			</tr>
			<tr>
				<td>".getCatalog('startdate')."</td>
				<td><input class=\"easyui-datebox\" type=\"text\" id=\"dlg_search_recalljurnal_startdate\" name=\"dlg_search_recalljurnal_startdate\" data-options=\"formatter:dateformatter,required:true,parser:dateparser,prompt:'As of Date'\"></input>
				</td>
			</tr>
			<tr>
				<td>".getCatalog('result')."</td>
				<td><input class=\"easyui-textbox\" style=\"width:500px\" id=\"dlg_search_recalljurnal_memo\" name=\"dlg_search_recalljurnal_memo\" data-options=\"multiline:true,height:150\" >
				</td>
			</tr>
    </table>
	",
));
?>
<?php }?>
<script type="text/javascript">
function recalljurnal() {
	$('#dlg_search_recalljurnal_memo').textbox('setValue','Start .....');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/run') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
		if (data.isError == 0) {
			$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Stock Opname');
			jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/runstockopname') ?>',
				'data':{			
					'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
					'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
					'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
				},
				'type':'post','dataType':'json',
				'success':function(data)
				{
					$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
					
				} ,
			'cache':false});
		}
	} ,
	'cache':false});

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall LPB');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/rungr') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
		'cache':false});
	}

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Retur Pembelian');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/rungrretur') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);		
	} ,
	'cache':false});			

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Transfer Stock');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/runtransstock') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
	} ,
	'cache':false});

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Surat Jalan');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/rungiheader') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
	} ,
	'cache':false});

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Retur Penjualan');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/rungiretur') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
	} ,
	'cache':false});

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Invoice AP');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/runinvoiceap') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
	} ,
	'cache':false});

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Pengeluaran Kas/Bank');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/runcashbankout') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
	} ,
	'cache':false});

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Hasil Produksi');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/runproduction') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
	} ,
	'cache':false});

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Penerimaan Kas/Bank');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/runcashbankin') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
	} ,
	'cache':false});

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Kas/Bank');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/runcashbank') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
	} ,
	'cache':false});

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Invoice AR');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/runinvoicear') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
	} ,
	'cache':false});

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Fix Asset');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/runfixasset') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
	} ,
	'cache':false});

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Nota Retur Pembelian');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/runnotagrr') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
	} ,
	'cache':false});

	$('#dlg_search_recalljurnal_memo').textbox('setValue','Recall Nota Retur Penjualan');
	jQuery.ajax({'url':'<?php echo $this->createUrl('recalljurnal/runnotagir') ?>',
	'data':{			
		'companyid':$('#dlg_search_recalljurnal_companyid').combogrid('getValue'),
		'transtype':$('#dlg_search_recalljurnal_transtype').combobox('getValue'),
		'startdate':$('#dlg_search_recalljurnal_startdate').datebox('getValue'),
	},
	'type':'post','dataType':'json',
	'success':function(data)
	{
		$('#dlg_search_recalljurnal_memo').textbox('setValue',data.msg);
	} ,
	'cache':false});
}
</script>