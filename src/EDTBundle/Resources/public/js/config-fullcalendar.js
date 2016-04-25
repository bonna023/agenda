$(document).ready(function() {
	var date = new Date();
	var jour = date.getDate();
	var mois = (date.getMonth()+1);
	if(mois < 10) mois = '0' + mois;
	var annee = date.getFullYear();
console.log(annee + '-' + mois + '-' + jour);

	$('#calendar-holder').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		lang: 'fr',
		defaultView: 'agendaWeek',
		defaultDate: annee + '-' + mois + '-' + jour, // '2016-01-12'

		selectable: true,
		selectHelper: true,
		select: function(start, end) {
			var title = prompt('Nom de l\'évènement: ');
			var eventData;
			if (title) {
				eventData = {
					title: title,
					start: start,
					end: end
				};
				$('#calendar-holder').fullCalendar('renderEvent', eventData, true); // stick? = true
			}
			$('#calendar-holder').fullCalendar('unselect');
		},

		editable: true,
		eventLimit: true, // allow "more" link when too many events
		events: [
			{
					title: 'Test...',
					start: '2016-04-18T10:30:00',
					end: '2016-04-18T12:30:00'
			}
		]
	});
	
});