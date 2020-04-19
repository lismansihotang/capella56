<script type="text/javascript">
$(document).ready(function() {
	$('#productionschedulecalendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay,listWeek'
		},
		defaultView: 'listWeek',
		businessHours: false,
		events: "<?php echo Yii::app()->createUrl('production/productplan/productionfg') ?>"
	});
});
</script>
<div id='productionschedulecalendar'></div>