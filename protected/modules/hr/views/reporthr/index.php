<div id="tb-reporthr">
	<?php
 if (CheckAccess($this->menuname, $this->isdownload) == 1) {  ?>
 <table>
 <tr>
 <td><?php echo GetCatalog('reporttype')?></td>
 <td><select class="easyui-combobox" id="listreporthr" name="listreporthr" data-options="required:true" style="width:450px;">
		<option value="1">1.Laporan Evaluasi karyawan</option>
		<option value="1">(1).Laporan Evaluasi Karyawan</option>
		<option value="2">(2).Rekap Laporan Evaluasi Karyawan Per Dokumen</option>
		<option value="3">(3).Laporan Kontrak Yang Sudah di Perpanjang</option>
		<option value="4">(4).Laporan Kontrak Yang Akan Berakhir</option>
		<option value="5">(5).Laporan Kejadian Karyawan</option>
		<option value="6">(6).Rekap Data Karyawan</option>
        <option value="7">(7).Rekap Jenis Karyawan</option>
        <option value="8">(8).Rekap THR Karyawan</option>
        <option value="9">(9).Rekap Ulang Tahun Karyawan</option>
        <option value="10">(10).Rincian Struktur Karyawan</option>
    </select>		<br/></td>
 </tr>

  <tr>
 <td><?php echo GetCatalog('company')?></td>
 <td><select class="easyui-combogrid" id="reporthr_companyid" name="reporthr_companyid" style="width:450px" data-options="
								panelWidth: 500,
								required: true,
								idField: 'companyid',
								textField: 'companyname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('admin/company/indexauth',array('grid'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'companyid',title:'<?php echo GetCatalog('companyid') ?>'},
										{field:'companyname',title:'<?php echo GetCatalog('companyname') ?>'},
								]],
								fitColumns: true
						">
				</select><br/></td>
 </tr>

  <tr>
 <td><?php echo GetCatalog('employee')?></td>
 <td><select class="easyui-combogrid" id="reporthr_employeeid" name="reporthr_employeeid" style="width:450px" data-options="
								panelWidth: 500,
								idField: 'fullname',
								textField: 'fullname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('hr/employee/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'employeeid',title:'<?php echo GetCatalog('employeeid') ?>'},
										{field:'fullname',title:'<?php echo GetCatalog('fullname') ?>'},
								]],
								fitColumns: true
						">
						</select><br/></td>
 </tr>

  <tr>
 <td><?php echo GetCatalog('religion')?></td>
 <td><select class="easyui-combogrid" id="reporthr_religionid" name="reporthr_religionid" style="width:450px" data-options="
								panelWidth: 500,
								idField: 'religionname',
								textField: 'religionname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('hr/religion/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns:[[
									{field:'religionid',title:'<?php echo GetCatalog('religionid')?>'},
									{field:'religionname',title:'<?php echo GetCatalog('religionname')?>',width:50},
								]],
								fitColumns: true
						">
				</select><br/></td>
 </tr>

<tr>
 <td><?php echo GetCatalog('employeetype')?></td>
 <td><select class="easyui-combogrid" id="reporthr_employeetypeid" name="reporthr_employeetypeid" style="width:450px" data-options="
								panelWidth: 500,
								idField: 'employeetypename',
								textField: 'employeetypename',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('hr/employeetype/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'employeetypeid',title:'<?php echo GetCatalog('employeetypeid') ?>'},
										{field:'employeetypename',title:'<?php echo GetCatalog('employeetypename') ?>'},
								]],
								fitColumns: true
						">
				</select><br/></td>
 </tr>
 <tr>
 <td><?php echo GetCatalog('empplan')?></td>
 <td><select class="easyui-combogrid" id="reporthr_empplanid" name="reporthr_empplanid" style="width:450px" data-options="
								panelWidth: 500,
								idField: 'empplanname',
								textField: 'empplanname',
								pagination:true,
								mode:'remote',
								url: '<?php echo Yii::app()->createUrl('hr/empplan/index',array('grid'=>true,'combo'=>true)) ?>',
								method: 'get',
								columns: [[
										{field:'empplanid',title:'<?php echo GetCatalog('empplanid') ?>'},
										{field:'empplanname',title:'<?php echo GetCatalog('empplanname') ?>'},
								]],
								fitColumns: true
						">
				</select><br/></td>
 </tr>
 
<tr>
 <td><?php echo GetCatalog('date')?></td>
 <td><input class="easyui-datebox" id="reporthr_startdate" name="reporthr_startdate" data-options="required:true" size="31"></input>
		-
		<input class="easyui-datebox" id="reporthr_enddate" name="reporthr_enddate" data-options="required:true" size="32"></input>
		<a href="javascript:void(0)" title="Export Ke PDF"class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="downpdfreporthr()"></a>
		<a href="javascript:void(0)" title="Export Ke EXCEL" class="easyui-linkbutton" iconCls="icon-xls" plain="true" onclick="downxlsreporthr()"></a></td>
 </tr>

 
 

 </table>	
		
<?php }?>
</div>

<script type="text/javascript">
function downpdfreporthr() {
	window.open('<?php echo $this->createUrl('reporthr/downpdf') ?>?lro='+
		$('#listreporthr').combobox('getValue')
		+ '&company='+$("input[name='reporthr_companyid']").val()
        + '&employeeid='+$("input[name='reporthr_employeeid']").val()
        + '&religionid='+$("input[name='reporthr_religionid']").val()
        + '&employeetypeid='+$("input[name='reporthr_employeetypeid']").val()
        + '&empplanid='+$("input[name='reporthr_empplanid']").val()
        + '&startdate='+$("input[name='reporthr_startdate']").val()
        + '&enddate='+$("input[name='reporthr_enddate']").val()
        + '&per=10');
    };

function downxlsreporthr() {
	window.open('<?php echo $this->createUrl('reporthr/downxls') ?>?lro='+
		$('#listreporthr').combobox('getValue')
		+ '&company='+$("input[name='reporthr_companyid']").val()
        + '&employeeid='+$("input[name='reporthr_employeeid']").val()
        + '&religionid='+$("input[name='reporthr_religionid']").val()
        + '&employeetypeid='+$("input[name='reporthr_employeetypeid']").val()
        + '&empplanid='+$("input[name='reporthr_empplanid']").val()
        + '&startdate='+$("input[name='reporthr_startdate']").val()
        + '&enddate='+$("input[name='reporthr_enddate']").val()
        + '&per=10');
};
</script>
