$(function(){
	$('body > div > div.page > ul > li:not(.description) .delete_button').click(function(event) {
		var button=this;
		if ($(button).hasClass('loading')) return;
		$(button).addClass('loading');
		$.ajax({
			url: 'elimina_serie.htm',
			type: 'POST',
			dataType: 'json',
			data: {
				id : $(this).attr('data-id')
			},
		})
		.done(function(r) {
			if (r.r) {
				console.log()
				$(button).closest('li').fadeOut(400, function() {
					$(this).remove();
				});
			}
			else {
				console.log("Errore nella risposta");
				$(button).removeClass('loading');
			}

		})
		.fail(function() {
			console.log("Errore nella chiamata");
			$(button).removeClass('loading');
		});
		
	});
});