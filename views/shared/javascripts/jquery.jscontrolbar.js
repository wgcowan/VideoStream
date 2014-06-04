
playerReady = function(obj) {
	try {
		var player = $("#"+obj['id']);
		player.each(function (){
			this.addModelListener("TIME","timeTracker");
			this.addModelListener("STATE","playTracker");
			this.addControllerListener("MUTE","muteTracker");
			this.addControllerListener("VOLUME","volumeTracker");
		});
		player.addControlbar();
	} catch (err){
	}
	controlbarReady(obj);
}

jQuery.fn.addControlbar = function (options){
	var settings = jQuery.extend({
		'width': this.width(),
		'prefix': 'jw_controlbar',
		'elements': {
			'play': {
				'width': '16px',
				'height': '16px',
				'background-image': 'url("http://www.longtailvideo.com/jw/images/controlbar/control_play_blue.png")',
				'float': 'left',
				'display': 'block'
			},
			'pause': {
				'width': '16px',
				'height': '16px',
				'background-image': 'url("http://www.longtailvideo.com/jw/images/controlbar/control_pause_blue.png")',
				'float': 'left',
				'display': 'none'
			},
			'stop': {
				'width': '16px',
				'height': '16px',
				'background-image': 'url("http://www.longtailvideo.com/jw/images/controlbar/control_stop_blue.png")',
				'float': 'left',
				'display': 'block'
			},
			'currenttime': {
				'width': '12%',
				'float': 'left',
				'display': 'block',
				'text-align': 'right',
				'font-family': 'monospace',
				'font-weight': '900',
				'font-size': '11px'
			},
			'scrubber': {
				'width': '45%',
				'float': 'left',
				'display': 'block',
				'margin': '5px'
			},
			'totaltime': {
				'width': '12%',
				'float': 'left',
				'display': 'block',
				'text-align': 'left',
				'font-family': 'monospace',
				'font-weight': '900',
				'font-size': '11px'
			},
			'fullscreen': {
				'width': '5%',
				'background-color': 'black',
				'float': 'left',
				'display': 'none'
			},
			'mute': {
				'width': '16px',
				'height': '16px',
				'float': 'left',
				'display': 'block',
				'background-image': 'url("http://www.longtailvideo.com/jw/images/controlbar/sound_none.png")'
			},
			'unmute': {
				'width': '16px',
				'height': '16px',
				'float': 'left',
				'display': 'none',
				'background-image': 'url("http://www.longtailvideo.com/jw/images/controlbar/sound_mute.png")'

			},
			'volume': {
				'width': '5%',
				'float': 'left',
				'display': 'block',
				'margin': '5px'

			}
		}
	}, options);
	
	this.after(buildControlbar(settings));
	var player = this[0];
	var playerSiblings = this.siblings();
	var play = $("."+settings.prefix+".play", playerSiblings);
	var pause = $("."+settings.prefix+".pause", playerSiblings);
	var stop = $("."+settings.prefix+".stop", playerSiblings);
	var scrubber = $("."+settings.prefix+".scrubber", playerSiblings);
	var currenttime = $("."+settings.prefix+".currenttime", playerSiblings);
	var totaltime = $("."+settings.prefix+".totaltime", playerSiblings);
	var fullscreen = $("."+settings.prefix+".fullscreen", playerSiblings);
	var mute = $("."+settings.prefix+".mute", playerSiblings);
	var unmute = $("."+settings.prefix+".unmute", playerSiblings);
	var volume = $("."+settings.prefix+".volume", playerSiblings);

	play.click(function() {
		player.sendEvent("PLAY", true);
	});


	pause.click(function() {
		player.sendEvent("PLAY", false);
	});

	stop.click(function() {
		player.sendEvent("STOP");
	});

	currenttime.html("00:00");

	scrubber.slider({
			range: "min",
			min: 0,
			max: 100000,
			slide: function(event, ui) {
				var duration = player.getPlaylist()[player.getConfig()['item']].duration;
				var seekTime = Math.round(duration * ui.value / 100000);
				player.sendEvent("SEEK", seekTime);
			}
	});

	totaltime.html("00:00");

	fullscreen.click(function() {
		player.sendEvent("FULLSCREEN", true);
	});

	mute.click(function() {
		player.sendEvent("MUTE", true);
	});

	unmute.click(function() {
		player.sendEvent("MUTE", false);
	});

	volume.slider({
			range: "min",
			min: 0,
			max: 100,
			value: player.getConfig()['volume'],
			slide: function(event, ui) {
				player.sendEvent("VOLUME", ui.value);
			}
		});
}

function buildControlbar(settings){
	var result = "";
	result += "<div class='"+settings.prefix+"' style='width:"+settings.width+"px'>";
	for (element in settings.elements){
		var style = "";
		for (styleElement in settings.elements[element]){
			style += styleElement+":"+settings.elements[element][styleElement]+";";
		}
		result += "<div class='"+settings.prefix+" "+element+"' style='"+style+"'>&nbsp;</div>";
	}
	result += "</div>";
	return result;
}

function pad(s,l) {
	return( l.substr(0, (l.length-s.length) )+s );
}

function formatTime(seconds){
	var result = "";
	var remaining = Math.floor(seconds);
	
	if (seconds > 3600){
		result += pad((Math.floor(remaining/3600)).toString(),"00")+":";
		remaining = remaining % 3600;
	}
	
	result += pad((Math.floor(remaining/60)).toString(),"00")+":";
	remaining = remaining % 60;

	result += pad(remaining.toString(),"00")+"";
	
	return result;
}

function play(player){
	player.sendEvent("PLAY");
	return false;
}

function stop(player){
	player.sendEvent("STOP");
	return false;
}

function seek(player){
	player.sendEvent("STOP");
	return false;
}

function timeTracker(obj){
	var percentComplete = Math.round(100000 * obj.position / obj.duration);
	var player = $("#"+obj['id']);
	var playerSiblings = player.siblings();
	$(".jw_controlbar.scrubber", playerSiblings).slider('option', 'value', percentComplete);
	$(".jw_controlbar.currenttime", playerSiblings).html(formatTime(obj.position));
	$(".jw_controlbar.totaltime", playerSiblings).html(formatTime(obj.duration));
}

function playTracker(obj){
	var player = $("#"+obj['id']);
	var playerSiblings = player.siblings();
	if (obj.newstate == "PLAYING"){
		$(".jw_controlbar.pause", playerSiblings).css("display","block");
		$(".jw_controlbar.play", playerSiblings).css("display","none");
	} else {
		$(".jw_controlbar.pause", playerSiblings).css("display","none");
		$(".jw_controlbar.play", playerSiblings).css("display","block");
	}
}

function muteTracker(obj){
	var player = $("#"+obj['id']);
	var playerSiblings = player.siblings();
	if (!obj.state){
		$(".jw_controlbar.mute", playerSiblings).css("display","block");
		$(".jw_controlbar.unmute", playerSiblings).css("display","none");
		setVolume($(".jw_controlbar.volume", playerSiblings), player[0].getConfig()['volume']);
	} else {
		$(".jw_controlbar.mute", playerSiblings).css("display","none");
		$(".jw_controlbar.unmute", playerSiblings).css("display","block");
		setVolume($(".jw_controlbar.volume", playerSiblings), 0);
	}
}

function volumeTracker(obj){
	var player = $("#"+obj['id']);
	var playerSiblings = player.siblings();
	setVolume($(".jw_controlbar.volume", playerSiblings),obj.percentage);
}

function setVolume(slider, value){
	slider.slider('option', 'value', value);
}
