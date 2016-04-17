$(document).ready(function() {
	var date = new Date();
	var jour = date.getDate();
	var mois = (date.getMonth()+1);
	if(mois < 10) mois = '0' + mois;
	var annee = date.getFullYear();
console.log(annee + '-' + mois + '-' + jour);

	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		lang: 'fr',
		defaultView: 'agendaWeek',
		defaultDate: annee + '-' + mois + '-' + jour, // '2016-01-12'
		editable: true,
		eventLimit: true, // allow "more" link when too many events
		events: [
			{
					title: 'Meeting',
					start: '2016-04-18T10:30:00',
					end: '2016-04-18T12:30:00'
			}
		]
	});
	
});