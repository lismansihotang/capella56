<table id="gridtodo" style="width:auto;height:285px"></table>
<script type="text/javascript">
	$('#gridtodo').datagrid({
		iconCls: 'icon-edit',	
		singleSelect: true,
		idField:'usertodoid',
		toolbar:'#tb-todo',
		editing: true,
		pagination:true,
		fitColumn: true,
		url: '<?php echo Yii::app()->createUrl('admin/dashboard/usertodo',array('grid'=>true)) ?>',
		columns:[[
			{
					field:'usertodoid',
					title:'<?php echo getCatalog('usertodoid') ?>',
					sortable: true,
					width:'50px',
					formatter: function(value,row,index){
						return value;
					},
			},
			{
					field:'tododate',
					title:'<?php echo getCatalog('date') ?>',
					sortable: true,
					width:'100px',
					formatter: function(value,row,index){
						return value;
					},
			},
			{
					field:'menuname',
					title:'<?php echo getCatalog('menuname') ?>',
					sortable: true,
					width:'200px',
					formatter: function(value,row,index){
						return value;
					},
			},
			{
					field:'docno',
					title:'<?php echo getCatalog('docno') ?>',
					sortable: true,
					width:'150px',
					formatter: function(value,row,index){
						return value;
					},
			},
			{
					field:'description',
					title:'<?php echo getCatalog('description') ?>',
					sortable: true,
					width:'450px',
					formatter: function(value,row,index){
						return value;
					},
			}
		]]
	});
</script>