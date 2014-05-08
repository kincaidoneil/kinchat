var info_pane = new Object();

info_pane.set_info = function(username) {
	if (username == user.username) {
		// Change Username & Status Message Text
		$('#kin_username').html('@' + username);
		$('#kin_status_msg').html(user.status_msg);
	} else {
		// Change Username & Status Message Text
		$('#kin_username').html('@' + username);
		$('#kin_status_msg').html(kin_list[username].status_msg);
	}
};

var kin_pane = new Object();

kin_pane.animation_running = false;
kin_pane.open = false;

kin_pane.hide = function() {
	if (kin_pane.animation_running == false) {
		kin_pane.animation_running = true;
		$('#kin_pane_container').animate({bottom: -($('#kin_pane_container').height() - $('#tool_bar').height())}, 1500, function() {
			$('#hide_button').html('Show');
			kin_pane.open = false;
			kin_pane.animation_running = false;
		});
	}
}

kin_pane.show = function() {
	if (kin_pane.animation_running == false) {
		kin_pane.animation_running = true;
		$('#kin_pane_container').animate({bottom: 0}, 1500, function() {
			$('#hide_button').html('Hide');
			kin_pane.open = true;
			kin_pane.animation_running = false;
		});
	}
}

kin_pane.toggle = function() {
	if (kin_pane.open == true) {
		kin_pane.hide();
	} else {
		kin_pane.show();
	}
};

var kin = new Object();

kin.tile_id = new Object();

kin.load = function() {
	$.ajax({
		cache: false,
		dataType: 'json',
		error: function() {
			alert('We\'re sorry, kinchat was unable to load your kin. The page may not function correctly.');
			var reload_page = confirm('To attempt reloading them, please click OK.');
			if (reload_page == true) {
				window.location = window.location;
			}
		},
		success: function(json) {
			user = json.user;
			ordered_kin = new Array(new Array(), new Array());
			kin_list = new Object();
			// If user has kin, load them.
			if (json.kin != null) {
				for (var i = 0; i < json.kin.length; i++) {
					if (json.kin[i].active == 1) {
						ordered_kin[0].push(json.kin[i].username);
					} else {
						ordered_kin[1].push(json.kin[i].username);
					}
					// Let kin be accessable by username.
					kin_list[json.kin[i].username] = json.kin[i];
				}
				// Sort kin by activity level, alphabetically.
				ordered_kin[0].sort();
				ordered_kin[1].sort();
				ordered_kin = ordered_kin[0].concat(ordered_kin[1]);
				for (var i = 0; i < ordered_kin.length; i++) {
					// Add kin info to ordered kin array.
					ordered_kin[i] = kin_list[ordered_kin[i]];
					ordered_kin[i].id = 'b' + (i + 1);
					// Let tile id be accessable by username.
					kin_list[ordered_kin[i].username].id = ordered_kin[i].id;
					// Let kin username be accessable by tile id.
					kin.tile_id[ordered_kin[i].id] = ordered_kin[i].username;
				}
				for (var i = 0; i < ordered_kin.length; i++) {
					// Add tile to kin list.
					$('#kin_list').append('<div class="tile_container" data-username="' + ordered_kin[i].username +'" id="' + ordered_kin[i].id + '" title="Click to Chat"><img id="' + ordered_kin[i].id + '_1" class="tile_background" src="../images/default.png" /><div id="' + ordered_kin[i].id + '_2" class="tile"><div id="' + ordered_kin[i].id + '_2_1" class="tile_username_background"><\/div><div id="' + ordered_kin[i].id + '_2_2" class="tile_username">@' + ordered_kin[i].username + '<\/div><\/div><\/div>');	
					(ordered_kin[i].active == 1) ? $('#' + ordered_kin[i].id + '_2_1').css('border-bottom', '5px solid limegreen') : $('#' + ordered_kin[i].id + '_2_1').css('border-bottom', '5px solid darkgray');
				}
				// Add tile events after tiles have loaded.
				add_events();
				// Reload kin data.
				setTimeout('kin.update()', 2500);
			} else {
				$('#kin_list').append('<span style="font-family: Colaborate Light; color: dimgray;">Your kin are people you can chat with. Click \'Add kin\' to add someone.</span>');
			}
			info_pane.set_info(user.username);
			kin_pane.toggle();
		},
		timeout: 2500,
		type: 'POST',
		url: 'load_data.php'
	});
};

kin.update = function() {
	$.ajax({
		cache: false,
		data: 'status_msg=' + encodeURIComponent(user.status_msg),
		dataType: 'json',
		error: function() {
			// ERROR???
		},
		success: function(json) {
			if (json.kin != null) {
				var arr = new Array(new Array(), new Array());	
				// Sort through kin, removing kin data from kin that have been deleted.
				for (var i = 0; i < ordered_kin.length; i++) {
					// If kin exists, append it's new data to the array.
					if (json.kin[ordered_kin[i].username]) {
						if (json.kin[ordered_kin[i].username].active == 1) {
							arr[0].push(json.kin[ordered_kin[i].username].username);
						} else {
							arr[1].push(json.kin[ordered_kin[i].username].username);
						}
						// Let kin be accessable by username.
						kin_list[ordered_kin[i].username] = json.kin[ordered_kin[i].username];
					} else {
						$('#' + ordered_kin[i].id).css('display', 'none');
					}
				}
				// Sort kin by activity level, alphabetically.
				arr[0].sort();
				arr[1].sort();
				// Combine the arrays.
				ordered_kin = arr[0].concat(arr[1]);
				// Update UI to match new data.
				for (var i = 0; i < ordered_kin.length; i++) {
					// Add kin info to ordered kin array.
					ordered_kin[i] = kin_list[ordered_kin[i]];
					ordered_kin[i].id = 'b' + (i + 1);
					// Let tile id be accessable by username.
					kin_list[ordered_kin[i].username].id = ordered_kin[i].id;
					// Let kin username be accessable by tile id.
					kin.tile_id[ordered_kin[i].id] = ordered_kin[i].username;
					// Change content of tiles.
					$('#' + ordered_kin[i].id + '_2_2').html('@' + ordered_kin[i].username);
					(ordered_kin[i].active == 1) ? $('#' + ordered_kin[i].id + '_2_1').css('border-bottom', '5px solid limegreen') : $('#' + ordered_kin[i].id + '_2_1').css('border-bottom', '5px solid darkgray');
				}
			}
		},
		timeout: 2500,
		type: 'POST',
		url: 'update_data.php',
	});
	setTimeout('kin.update()', 1000);
};

kin.add = function() {
	var email_address = prompt('Please enter the e-mail address of whom you would like to add as your kin.', '');
	if (email_address != '' && email_address != null) {
		$.ajax({
			cache: false,
			data: 'email=' + email_address,
			dataType: 'json',
			error: function() {
				alert('We\'re sorry, an error occured when kinchat tried to send the invitation.');
			},
			success: function(json) {
				alert(json.msg);
			},
			timeout: 10000,
			type: 'POST',
			url: '../kin/add_kin.php',
		});
	}
}

var messages = new Object();

messages.update = function(username) {
	if (chat_pane.instance_num > 0) {
		$.ajax({
			cache: false,
			data: 'username=' + username + '&id=' + chat_pane.instances[username].msg_num,
			dataType: 'json',
			error: function(xhr, status, error) {
				if (error = 'timeout') {
					setTimeout(function() {
						messages.update(username);
					}, 500);
				} else {
					alert('Error fetching new chats.');
					setTimeout(function() {
						messages.update(username);
					}, 5000);
				}
			},
			success: function(json) {
				if (json.sender == 0 && json.id == (chat_pane.instances[username].msg_num + 1)) {
					setTimeout(function() {
						chat_pane.instances[username].msg_num++;
						chat_pane.append_msg(username, json);
						$('#' + chat_pane.instances[username].id + '_3').tinyscrollbar_update();
						setTimeout(function() {
							messages.update(username);
						}, 500);
					}, 10000);
				} else if (json.sender == 1 && json.id == (chat_pane.instances[username].msg_num + 1)) {
					chat_pane.instances[username].msg_num++;
					chat_pane.append_msg(username, json);
					$('#' + chat_pane.instances[username].id + '_3').tinyscrollbar_update();
					setTimeout(function() {
						messages.update(username);
					}, 500);
				}
			},
			timeout: 30000,
			type: 'POST',
			url: '../chat/get_msg.php',
		});
	} else {
		setTimeout(function() {
			messages.update(username);
		}, 2000);
	}
};

messages.load = function(username) {
	$.ajax({
		async: false,
		cache: false,
		data: 'username=' + username,
		dataType: 'json',
		error: function() {
			alert('kinchat was unable to load you and @' + username + '\'s chats.');
		},
		success: function(json) {
			if (json != null) {
				for (var i = 0; i < json.length; i++) {
					// Update message number (id).
					chat_pane.instances[username].msg_num = parseInt(json[i].id);
					if (json[i].sender == 1) {
						// If sender of chat is the user...
						$('#' + chat_pane.instances[username].id + '_3_2_1').prepend('<div id="c' + chat_pane.instances[username].msg_num +'" class="message_container"><div class="spike_left"><\/div><div class="message_info">' + json[i].hour + ':' + json[i].minute + ' ' + json[i].half + ', ' + json[i].month + '\/' + json[i].date + '\/' + json[i].year + '<\/div><div class="message_body">' + json[i].msg + '<\/div><\/div>');
					} else if (json[i].sender == 0) {
						// If sender of chat is user's kin...
						$('#' + chat_pane.instances[username].id + '_3_2_1').prepend('<div id="c' + chat_pane.instances[username].msg_num +'" class="message_container"><div class="spike_right"><\/div><div class="message_info">' + json[i].hour + ':' + json[i].minute + ' ' + json[i].half + ', ' + json[i].month + '\/' + json[i].date + '\/' + json[i].year + '<\/div><div class="message_body" style="background: dodgerblue">' + json[i].msg + '<\/div><\/div>');
					}
				}
			}
		},
		timeout: 500,
		type: 'POST',
		url: '../chat/load_msgs.php',
	});
};

messages.submit = function(username, json) {
	$.ajax({
		cache: false,
		data: 'hour=' + json.hour + '&minute=' + json.minute + '&half=' + json.half + '&month=' + json.month + '&date=' + json.date + '&year=' + json.year + '&username=' + username + '&msg=' + encodeURIComponent(json.msg),
		error: function(xhr, status, error) {
			alert('@' + username + ' did not receive your chat.');
		},
		type: 'POST',
		url: '../chat/submit_msg.php',
	});
};

var chat_pane = new Object();

// Number of chat pane instances EVER open.
chat_pane.total_instances = 0;

// Number of chat pane instances open.
chat_pane.instance_num = 0;

chat_pane.instances = new Object();

/*

*** EXAMPLE ***

"test_user932" : {
	visible: true,
	msg_num: 12,
	id: 'a1',
},

*/

chat_pane.open = function(username) {
	// If user isn't attempting to open chat with themself.
	if (username != user.username) {
		// If there are less than 5 instances open, open new instance.
		if (chat_pane.instance_num < 5) {
			// Increment the number of instances EVER open.
			chat_pane.total_instances++;
			// Increment the number of instances open.
			chat_pane.instance_num++;
			// Save instance info to JSON object.
			chat_pane.instances[username] = {
				visible: true,
				msg_num: 0,
				id: 'a' + chat_pane.total_instances,
			};
			// Append new chat pane to content area.
			$('#content').append('<div id="' + chat_pane.instances[username].id + '" class="chat_pane_container"><div class="chat_pane_background"><\/div><div id="' + chat_pane.instances[username].id + '_4" class="chat_pane"><\/div><\/div>');
			// Add content to chat pane.
			var html = '<img class="delete_button" src="../images/delete_button.png" title="Close Chat Pane" /><div id="' + chat_pane.instances[username].id + '_1" class="label_bar" data-username="' + username +'"><div id="' + chat_pane.instances[username].id + '_1_1" class="label left_label">@' + user.username + '<\/div><div id="' + chat_pane.instances[username].id + '_1_2" class="label right_label">@' + username + '<\/div><\/div><div id="' + chat_pane.instances[username].id + '_2" class="text_box_tray"><div id="' + chat_pane.instances[username].id + '_2_1" class="text_box_spike"><\/div><div id="' + chat_pane.instances[username].id + '_2_2" class="text_box_container"><div id="' + chat_pane.instances[username].id + '_2_2_1" class="formatting_ribbon"><ul id="' + chat_pane.instances[username].id + '_2_2_1_1" class="button_list"><li id="' + chat_pane.instances[username].id + '_2_2_1_1_1" class="button_container"><a id="' + chat_pane.instances[username].id + '_2_2_1_1_1_1" class="button bold_button" href="#"><img id="' + chat_pane.instances[username].id + '_2_2_1_1_1_1_1" class="button_background" src="../images/buttons/static/bold.png" /><\/a><\/li><li id="' + chat_pane.instances[username].id + '_2_2_1_1_2" class="button_container"><a id="' + chat_pane.instances[username].id + '_2_2_1_1_2_1" class="button italic_button" href="#"><img id="' + chat_pane.instances[username].id + '_2_2_1_1_2_1_1" class="button_background" src="../images/buttons/static/italic.png" /><\/a><\/li><li id="' + chat_pane.instances[username].id + '_2_2_1_1_3" class="button_container"><a id="' + chat_pane.instances[username].id + '_2_2_1_1_3_1" class="button underline_button" href="#"><img id="' + chat_pane.instances[username].id + '_2_2_1_1_3_1_1" class="button_background" src="../images/buttons/static/underline.png" /><\/a><\/li><li id="' + chat_pane.instances[username].id + '_2_2_1_1_4" class="button_container"><a id="' + chat_pane.instances[username].id + '_2_2_1_1_4_1" class="button strikethrough_button" href="#"><img id="' + chat_pane.instances[username].id + '_2_2_1_1_4_1_1" class="button_background" src="../images/buttons/static/strikethrough.png" /><\/a><\/li><\/ul><\/div><iframe id="' + chat_pane.instances[username].id + '_2_2_2" class="text_box"><\/iframe><\/div><\/div><div id="' + chat_pane.instances[username].id + '_3" class="message_tray"><div class="scrollbar"><div class="track"><div class="thumb"><\/div><\/div><\/div><div class="viewport"><div id="' + chat_pane.instances[username].id  + '_3_2_1" class="overview"><\/div><\/div><\/div><\/div>';
			$('#' + chat_pane.instances[username].id + '_4').html(html);
			// Load messages.
			messages.load(username);
			// Let user close chat pane.
			$('.delete_button').click(function() {
				chat_pane.hide(username);
			});
			// Let user format their chat.
			document.getElementById(chat_pane.instances[username].id + '_2_2_1_1_1_1').addEventListener('click', function() {
				chat_pane.text_box.format_text('bold', null, chat_pane.instances[username].id + '_2_2_2');
			}, false);
			document.getElementById(chat_pane.instances[username].id + '_2_2_1_1_2_1').addEventListener('click', function() {
				chat_pane.text_box.format_text('italic', null, chat_pane.instances[username].id + '_2_2_2');
			}, false);
			document.getElementById(chat_pane.instances[username].id + '_2_2_1_1_3_1').addEventListener('click', function() {
				chat_pane.text_box.format_text('underline', null, chat_pane.instances[username].id + '_2_2_2');
			}, false);
			document.getElementById(chat_pane.instances[username].id + '_2_2_1_1_4_1').addEventListener('click', function() {
				chat_pane.text_box.format_text('strikethrough', null, chat_pane.instances[username].id + '_2_2_2');
			}, false);
			// Ready text area.
			chat_pane.text_box.write_content(chat_pane.instances[username].id + '_2_2_2');
			chat_pane.text_box.editable(chat_pane.instances[username].id + '_2_2_2', false);
			chat_pane.text_box.focus(chat_pane.instances[username].id + '_2_2_2');
			// Let user scroll.
			$('#' + chat_pane.instances[username].id + '_3').tinyscrollbar();
			// Let user submit their chat.
			document.getElementById(chat_pane.instances[username].id + '_2_2_2').contentWindow.addEventListener('keypress', function(event) {
				if (event.keyCode == 13) {
					chat_pane.text_box.editable(chat_pane.instances[username].id + '_2_2_2', true);
					// Delete Break Lines; Fixes Firefox Bug
					html = this.document.body.innerHTML.replace(/<br \/>/gim, '').replace(/<br\/>/gim, '').replace(/<br>/gim, '');
					// Insert Smiley Faces
					// html = html.replace(/:\)/, '<img src="../images/smilies/smiley.png" />');
					if (html != '') {
						var right_now = new Date();
						var json = {
							'hour' : (right_now.getHours() >= 13) ? (right_now.getHours() - 12) : right_now.getHours(),
							'minute' : (right_now.getMinutes() < 10) ? ('0' + right_now.getMinutes()) : right_now.getMinutes(),
							'half' : (right_now.getHours() >= 12) ? 'PM' : 'AM',
							'month' : (right_now.getMonth() + 1),
							'date' : right_now.getDate(),
							'year' : right_now.getFullYear().toString().substring(2),
							'sender' : 1,
							'msg' : html,
						}
						messages.submit(username, json);
					}
				}
			}, true);
			document.getElementById(chat_pane.instances[username].id + '_2_2_2').contentWindow.addEventListener('keyup', function(event) {
				if (event.keyCode == 13) {
					document.getElementById(chat_pane.instances[username].id + '_2_2_2').contentWindow.document.body.innerHTML = '';
					chat_pane.text_box.editable(chat_pane.instances[username].id + '_2_2_2', false);
					window.focus();
					chat_pane.text_box.focus(chat_pane.instances[username].id + '_2_2_2');
				}
			}, true);
			// Make sure pane is draggable.
			$('.chat_pane_container').draggable({scroll: false, handle: '.label_bar', containment: '#content'});
			// Fade chat pane in.
			$('#' + chat_pane.instances[username].id).animate({opacity: 1}, 400, function() {
				messages.update(username);
			});
		} else {
			// If there are five or over instances open, alert user.
			alert('You have reached the limit of open chat instances.');	
		}
	}
};

chat_pane.show = function(username) {
	$('#' + chat_pane.instances[username].id).animate({opacity: 1}, 400, function() {
		chat_pane.text_box.focus(chat_pane.instances[username].id + '_2_2_2');
		chat_pane.instances[username].visible = true;
		chat_pane.instance_num++;
	});
};

chat_pane.hide = function(username) {
	$('#' + chat_pane.instances[username].id).animate({opacity: 0}, 400, function() {
		chat_pane.instances[username].visible = false;
		chat_pane.instance_num--;
	});
};

chat_pane.update_button_status = function() {
	if (chat_pane.instance_num >= 1) {
		var chat_pane_num = 1;
		var commands = ['bold', 'italic', 'underline', 'strikethrough'];
		while (chat_pane_num <= chat_pane.instance_num) {
			var text_box_doc = 'a' + chat_pane_num + '_2_2_2';
			if (document.getElementById(text_box_doc).contentWindow.document) {
				for (var i = 0; i <= 3; i++) {
					var command_state = document.getElementById(text_box_doc).contentWindow.document.queryCommandState(commands[i]);
					var element_id = 'a' + chat_pane_num + '_2_2_1_1_' + (i + 1) + '_1_1';
					if (command_state == true) {
						$('#' + element_id).attr('src', $('#' + element_id).attr('src').replace('static', 'active'));
					} else {
						$('#' + element_id).attr('src', $('#' + element_id).attr('src').replace('active', 'static'));	
					}
				}	
			}
			chat_pane_num++;
		}	
	}
	setTimeout('chat_pane.update_button_status()', 50);
};

chat_pane.append_msg = function(username, json) {
	if (json.sender == 1) {
		// If sender of chat is the user...
		$('#' + chat_pane.instances[username].id + '_3_2_1').prepend('<div id="c' + chat_pane.instances[username].msg_num +'" class="message_container" style="opacity: 0; position: absolute"><div class="spike_left"><\/div><div class="message_info">' + json.hour + ':' + json.minute + ' ' + json.half + ', ' + json.month + '\/' + json.date + '\/' + json.year + '<\/div><div class="message_body">' + json.msg + '<\/div><\/div>');
	} else if (json.sender == 0) {
		// If sender of chat is user's kin...
		$('#' + chat_pane.instances[username].id + '_3_2_1').prepend('<div id="c' + chat_pane.instances[username].msg_num +'" class="message_container" style="opacity: 0; position: absolute"><div class="spike_right"><\/div><div class="message_info">' + json.hour + ':' + json.minute + ' ' + json.half + ', ' + json.month + '\/' + json.date + '\/' + json.year + '<\/div><div class="message_body" style="background: dodgerblue">' + json.msg + '<\/div><\/div>');
	}
	var height = $('#c' + chat_pane.instances[username].msg_num).height();
	$('#c' + chat_pane.instances[username].msg_num).css('position', 'static').css('height', 0);
	$('#c' + chat_pane.instances[username].msg_num).animate({height: height}, 300, function() {
		$('#c' + chat_pane.instances[username].msg_num).animate({opacity: 1}, 300);
	});
};

chat_pane.text_box = new Object();

chat_pane.text_box.get_doc = function(id) {
	if (document.getElementById(id).contentDocument) {
		return document.getElementById(id).contentDocument;
	} else {
		return document.getElementById(id).contentWindow.document;
	}
};

chat_pane.text_box.write_content = function(id) {
	chat_pane.text_box.get_doc(id).open();
	chat_pane.text_box.get_doc(id).write('<!DOCTYPE html><html><head><style type="text/css">body{margin:0;font-family:"Arial";font-size:10pt;color:white} img{display:none}<\/style><\/head><body><\/body><\/html>');
	chat_pane.text_box.get_doc(id).close();
};

chat_pane.text_box.editable = function(id, disabled) {
	if (disabled == true) {
		chat_pane.text_box.get_doc(id).designMode = 'off';
	} else {
		chat_pane.text_box.get_doc(id).designMode = 'on';
	}
};

chat_pane.text_box.format_text = function(command, value, id) {
	chat_pane.text_box.focus(id);
	chat_pane.text_box.get_doc(id).execCommand(command, false, value);
	chat_pane.text_box.focus(id);
};

chat_pane.text_box.focus = function(id) {
	if (document.getElementById(id).contentWindow) {
		document.getElementById(id).contentWindow.focus();
	}
};

function add_events() {
	
	$('.tile_container').mouseover(function() {
		var username = kin.tile_id[$(this).attr('id')];
		info_pane.set_info(username);
	});
	
	$('.tile_container').mouseout(function() {
		info_pane.set_info(user.username);
	});
	
	$('.tile_container').click(function() {
		// If chat pane has been opened, re-open it.
		if (chat_pane.instances[$(this).attr('data-username')]) {
			// If chat pane is visible, hide it.
			if (chat_pane.instances[$(this).attr('data-username')].visible == true) {
				chat_pane.hide($(this).attr('data-username'));
			} else {
				chat_pane.show($(this).attr('data-username'));
			}
		} else {
			chat_pane.open($(this).attr('data-username'));
		}
	});
	
	$('#kin_status_msg').click(function() {
		var new_status_msg = prompt('Please enter your new status message:', $('<div/>').html(user.status_msg).text());
		if (new_status_msg != null) {
			if (new_status_msg.length > 150) {
				alert('Your status message must be less than 150 characters.');
			} else {
				user.status_msg = $('<div/>').text(new_status_msg).html();
				info_pane.set_info(user.username);
			}
		}
	});
	
}

$(document).ready(function() {
	
	// Set dimensions of chatting area.
	$('#content').css('height', Math.round($(document).height() - $('#nav_bar').height() - 50));
	$('#content').css('width', Math.round($(document).width() - 50));
	
	// Set dimensions of kin pane.
	$('#kin_pane_container').css('height', $(document).height() - $('#nav_bar').height() - 50);
	$('#kin_pane_container').css('bottom', -($('#kin_pane_container').height() - $('#tool_bar').height()));
	$('#tool_bar').css('width', $('#kin_pane_container').width() - 40);
	$('#kin_list').css('height', $('#kin_pane_container').height() - 212);
	
	// Make kin pane draggable.
	$('#kin_pane_container').draggable({scroll: false, handle: '#tool_bar', containment: '#content', axis: 'x'});
	
	// Load all kin.
	kin.load();
	
	// Update state of text box buttons depending apon how current caret text is formated.
	chat_pane.update_button_status();
	
});