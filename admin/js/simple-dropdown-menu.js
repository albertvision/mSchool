$(document).ready(function () 
	{	
		$('.main li:has(ul) > a').addClass('more'); //Tursi vsqko LI, v koeto ima UL i LI e roditelski element na A. Ako sa izpylneni usloviqta, dobavq na A class "more". Taka ako v LI imame UL, shte stava qsno, che imame submenu i shte podtikvame user-a da click-a.
		
		$('.main li').hover(function () {
			$(this).find('ul:first').stop(true, true).animate({opacity: 'toggle', height: 'toggle'}, 200).addClass('active_list');
		}, function () {
			$(this).children('ul.active_list').stop(true, true).animate({opacity: 'toggle', height: 'toggle'}, 200).removeClass('active_list');
		});	 // Gornite nqkolko reda predstavlqvat effecta na slide up & down. Vajno e da go zadadem za vsqko pyrvo UL. V red 6-ti tyrsim pyrvoto UL, koeto se namira w .MAIN LI i mu puskame animaciq, koqto go slide-va nadolu i mu dobavq class "active_list". V red 8 (koito predstavlqva hover out), tyrsim UL, koeto veche ima class "active_list" i kazvame ako mishoka ne e vyrhu nego da se izpylni animaciqta i da se premahne class-a "active_list". Po tozi nachin ako mrydnem mishkata vstrani menu-to shte se zatvori.
	});