jQuery(document).ready(function() {

	if (!steam_data.cache_exists) {
		var loading_message = 'Loading & caching Steam Library GT for the first time.<br>Sit tight! This may take a couple of minutes!';
	} else {
		var loading_message = 'Loading Steam Library GT...'
	}

	var div = jQuery('#container');
	var page_load_spinner = '<div id="floatingCirclesG"><div class="f_circleG" id="frotateG_01"></div><div class="f_circleG" id="frotateG_02"></div><div class="f_circleG" id="frotateG_03"></div><div class="f_circleG" id="frotateG_04"></div><div class="f_circleG" id="frotateG_05"></div><div class="f_circleG" id="frotateG_06"></div><div class="f_circleG" id="frotateG_07"></div><div class="f_circleG" id="frotateG_08"></div></div>';

	var loading_content = '<div id="loading_content"><div id="spinner" style="position: relative; margin: auto; margin-bottom: 30px;">' + page_load_spinner + '</div>' + loading_message + '</div>';
	div.append(loading_content);

	jQuery.ajax({
		type: "POST",
		url: steam_data.ajaxurl,
		data: { 'action': 'load_library', 'steam_profile_id' : steam_data.steam_profile_id },
		dataType: "json",
		success: function(data) {

			var div = jQuery('#container');
			destroy('modal');

			jQuery('#loading_content').fadeOut(500, function() {
				jQuery('#loading_content').remove();
			});

			setTimeout(function() {
				for (var key in data) {
					var obj = data[key];
					if (obj.image_hash !== '') {
						if (obj.cached) {
							var game_html = '<img src="' + steam_data.plugin_url + '/steam-library-gt/cache/' + obj.image_hash + '.jpg" id="' + obj.app_id + '" class="grid_image" data-name="' + key + '" data-playtime="' + obj.playtime + '">';
						} else {
							var game_html = '<img src="http://media.steampowered.com/steamcommunity/public/images/apps/' + obj.app_id + '/' + obj.image_hash + '.jpg" id="' + obj.app_id + '" class="grid_image" data-name="' + key + '" data-playtime="' + obj.playtime + '">';
						}					
						div.append(game_html);
					}
				}
			}, 500);
			
		},
		error: function(xhr, textStatus, errorThrown){
			console.log("failed to load steam library");
		}
	});

	var counter = 1;

	function resize_div() {
		var width = jQuery('.entry-content').width();
		var image_width = 184;
		var image_count = (Math.round((width-(image_width/2)) / image_width) !== 0) ? Math.round((width-(image_width/2)) / image_width) : 1;
		var div_width = (image_count * image_width);
		var container = document.getElementById('container');
		container.style.width = div_width+'px';
	}

	function has_class(element, cls) {
		return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
	}

	jQuery('#modal_close').live('click', function(e) {
		e.preventDefault();
		destroy('modal');
	});

	function destroy(element) {
		var existing_element = element_exists(element);
		if (existing_element) document.getElementById("container").removeChild(existing_element);
	}

	function add_modal(element) {
		// remove existing modal if it exists
		destroy('modal');
		add_spinner();

		var overview = 'No description available';

		jQuery.ajax({
			type: "GET",
			url: steam_data.plugin_url+"/steam-library-gt/ajax/game_details.php",
			data: {'name' : element.getAttribute('data-name')},
			dataType: "json",
			success: function(data) {

				// grab the first overview if available
				if (data.Game && data.Game[0] && data.Game[0].Overview) {
					overview = data.Game[0].Overview;
				}

				// now try to refine the overview, in the case that the api returned an incorrect top result
				if (data.Game) {
					for (var i = data.Game.length - 1; i >= 0; i--) {
						if ((data.Game[i].GameTitle === element.getAttribute('data-name')) && data.Game[i].Overview) {
							overview = data.Game[i].Overview;
						}
					};
				}
				
				fill_modal(overview);
			},
			error: function(xhr, textStatus, errorThrown){
				fill_modal();
			}
		});

		function fill_modal() {
			destroy('spinner');
			var modal_html = '<img src="'+element.src+'" id="modal_image">';
			modal_html += '<i class="fa fa-circle-o fa-lg" id="modal_close"></i>';
			modal_html += '<div><h1>'+element.getAttribute('data-name')+'</h1>';
			modal_html += '<p id="overview">'+overview+'</p>';
			modal_html += '<input type="button" value="Launch" onclick="location.href=\'steam://run/'+element.id+'\';"></div>';

			var modal = document.createElement('div');
			modal.setAttribute('id','modal');
			modal.innerHTML = modal_html;
			document.getElementById("container").insertBefore(modal,element);

			place_element('modal');
		}

		function add_spinner() {
			var modal_html = '<div id="floatingCirclesG"><div class="f_circleG" id="frotateG_01"></div><div class="f_circleG" id="frotateG_02"></div><div class="f_circleG" id="frotateG_03"></div><div class="f_circleG" id="frotateG_04"></div><div class="f_circleG" id="frotateG_05"></div><div class="f_circleG" id="frotateG_06"></div><div class="f_circleG" id="frotateG_07"></div><div class="f_circleG" id="frotateG_08"></div></div>';

			var modal = document.createElement('div');
			modal.setAttribute('id','spinner');
			modal.innerHTML = modal_html;
			document.getElementById("container").insertBefore(modal,element);

			place_element('spinner');
		}		
	}

	function place_element(element) {
		if (element_exists(element)) {
			document.getElementById(element).style.left = (window.innerWidth/2) - (document.getElementById(element).getBoundingClientRect().width/2) +"px";
			document.getElementById(element).style.top = (window.innerHeight/2) - (document.getElementById(element).getBoundingClientRect().height/2) +"px";
		}
	}

	function element_exists(element) {
		var existing_element = document.getElementById(element);
		if (existing_element) {
			return existing_element;
		}
		 else {
			return false;
		 }
	}

	function mouseover(e) {
		var element = e.target ? e.target : e.toElement;
		if (element instanceof HTMLImageElement && has_class(element, 'grid_image')) {    		
			element.className = element.className + ' glow'+counter;

			element.onmousedown=function(){
				add_modal(this);
			};

			if (counter === 7) { counter = 1 } else { ++counter	}
		}
	}

	function mouseout(e) {
		var element = e.target ? e.target : e.toElement;
		if (element instanceof HTMLImageElement && has_class(element, 'grid_image')) element.className = 'grid_image';
	}

	// Listeners
	window.addEventListener('mouseover', mouseover);
	window.addEventListener('mouseout', mouseout);

	document.onkeydown = function(evt) {
		evt = evt || window.event;
		if (evt.keyCode == 27) {
			destroy('modal');
		}
	};

	window.onresize = function() {
		resize_div();
		place_element('modal');
	}

	resize_div();
});