<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl;?>/js/fullcalendar/fullcalendar.min.css"/>	
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl?>/js/fullcalendar/lib/moment.min.js"></script>	
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl?>/js/fullcalendar/fullcalendar.min.js"></script>	
<script type="text/javascript">
$(document).ready(function() {
	$('#deliveryschedulecalendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay,listWeek'
		},
		defaultView: 'listWeek',
		businessHours: false,
		events: "<?php echo Yii::app()->createUrl('order/soheader/deliveryschedule') ?>"
	});
});
</script>
<div id='deliveryschedulecalendar'></div>