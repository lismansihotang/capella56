<script type="text/javascript">
$(document).ready(function() {
	$('#rawschedulecalendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay,listWeek'
		},
		defaultView: 'listWeek',
		businessHours: false,
		events: "<?php echo Yii::app()->createUrl('purchasing/poheader/rawschedule') ?>"
	});
});
</script>
<div id='rawschedulecalendar'></div>