$("#darkmode").on('click', function() {
	acd()

	// if($('body').hasClass('dark-mode-background')) {
	// 	$(this).html("Light Mode")
	// 	$(this).removeClass("btn-dark")
	// 	$(this).addClass("btn-light")
	// } else {
	// 	$(this).html("Dark Mode")
	// 	$(this).removeClass("btn-light")
	// 	$(this).addClass("btn-dark")
	// }
})

function acd() {
	$('body').toggleClass('dark-mode-background')

	if($('body').hasClass('dark-mode-background')) {
		$("#darkmode").html("Light Mode")
		$("#darkmode").removeClass("btn-dark")
		$("#darkmode").addClass("btn-light")
		$("#container-custom").addClass("dark-mode-divs")
	} else {
		$("#darkmode").html("Dark Mode")
		$("#darkmode").removeClass("btn-light")
		$("#darkmode").addClass("btn-dark")
		$("#darkmode").removeClass("dark-mode-divs")
	}

	$.ajax({
		method: "POST",
		url: "darkmode.php",
		data: {
			darkmode: $('body').hasClass('dark-mode-background')
		}
	}).done(function( response ) {
		//alert(response)
	})
}
