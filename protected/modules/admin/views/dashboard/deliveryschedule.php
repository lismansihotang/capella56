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