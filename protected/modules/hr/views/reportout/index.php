<?php $this->widget('Form',	array('menuname'=>$this->menuname,
	'idfield'=>'reportout',
	'formtype'=>'master',
	'url'=>Yii::app()->createUrl('hr/reportout/index',array('grid'=>true)),
	
	'downpdf'=>Yii::app()->createUrl('hr/reportout/downpdf'),
	'downxls'=>Yii::app()->createUrl('hr/reportout/downxls'),
	'columns'=>"
		{
			field:'reportoutid',
			title:'". GetCatalog('reportoutid') ."',
			sortable: true,
			width:'50',
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'employeeid',
			title:'". GetCatalog('employeeid') ."',
			width:'50px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
		{
			field:'fullname',
			title:'". GetCatalog('fullname') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'oldnik',
			title:'". GetCatalog('oldnik') ."',
			editor:'text',
			width:'80px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'fulldivision',
			title:'". GetCatalog('fulldivision') ."',
			editor:'text',
			width:'150px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'month',
			title:'". GetCatalog('month') ."',
			editor:'text',
			width:'25px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'year',
			title:'". GetCatalog('year') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},{
			field:'s1',
			title:'". GetCatalog('s1') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d1',
			title:'". GetCatalog('1') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s2',
			title:'". GetCatalog('s2') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d2',
			title:'". GetCatalog('2') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s3',
			title:'". GetCatalog('s3') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d3',
			title:'". GetCatalog('3') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s4',
			title:'". GetCatalog('s4') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d4',
			title:'". GetCatalog('4') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s5',
			title:'". GetCatalog('s5') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d5',
			title:'". GetCatalog('5') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s6',
			title:'". GetCatalog('s6') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d6',
			title:'". GetCatalog('6') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s7',
			title:'". GetCatalog('s7') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d7',
			title:'". GetCatalog('7') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s8',
			title:'". GetCatalog('s8') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d8',
			title:'". GetCatalog('8') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s9',
			title:'". GetCatalog('s9') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d9',
			title:'". GetCatalog('9') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s10',
			title:'". GetCatalog('s10') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d10',
			title:'". GetCatalog('10') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s11',
			title:'". GetCatalog('s11') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d11',
			title:'". GetCatalog('11') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s12',
			title:'". GetCatalog('s12') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d12',
			title:'". GetCatalog('12') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s13',
			title:'". GetCatalog('s13') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d13',
			title:'". GetCatalog('13') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s14',
			title:'". GetCatalog('s14') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d14',
			title:'". GetCatalog('14') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s15',
			title:'". GetCatalog('s15') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d15',
			title:'". GetCatalog('15') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s16',
			title:'". GetCatalog('s16') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d16',
			title:'". GetCatalog('16') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s17',
			title:'". GetCatalog('s17') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d17',
			title:'". GetCatalog('17') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s18',
			title:'". GetCatalog('s18') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d18',
			title:'". GetCatalog('18') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s19',
			title:'". GetCatalog('s19') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d19',
			title:'". GetCatalog('19') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s20',
			title:'". GetCatalog('s20') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d20',
			title:'". GetCatalog('20') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s21',
			title:'". GetCatalog('s21') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d21',
			title:'". GetCatalog('21') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s22',
			title:'". GetCatalog('s22') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d22',
			title:'". GetCatalog('22') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s23',
			title:'". GetCatalog('s23') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d23',
			title:'". GetCatalog('23') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s24',
			title:'". GetCatalog('s24') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d24',
			title:'". GetCatalog('24') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s25',
			title:'". GetCatalog('s25') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d25',
			title:'". GetCatalog('25') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s26',
			title:'". GetCatalog('s26') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d26',
			title:'". GetCatalog('26') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s27',
			title:'". GetCatalog('s27') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d27',
			title:'". GetCatalog('27') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s28',
			title:'". GetCatalog('s28') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d28',
			title:'". GetCatalog('28') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s29',
			title:'". GetCatalog('s29') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d29',
			title:'". GetCatalog('29') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s30',
			title:'". GetCatalog('s30') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d30',
			title:'". GetCatalog('30') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'s31',
			title:'". GetCatalog('s31') ."',
			editor:'text',
			width:'40px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
        {
			field:'d31',
			title:'". GetCatalog('31') ."',
			editor:'text',
			width:'20px',
			sortable: true,
			formatter: function(value,row,index){
				return value;
		}},
    ",
	'searchfield'=> array('fullname','fulldivision','month','year')
    ));