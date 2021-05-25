'use strict';

// -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
function Omnichat()
{
	this.init();
}

Omnichat.prototype.init = function()
{
	this.chat_logs = {};
	this.unread_counts = {};
	this.profile_id = 0;
	this.profile_key = null;
	this.peer = null;
	this.connection = null;
	this.selected_peer = null;
	this.chat_log = '';
	this.el_chat_log = document.getElementById('chat_log');
}

function rand_id()
{
	return (Math.random() + 10000000000).toString(36).replace(/[^a-z]+/g, '').substr(6, 20)
}

Omnichat.prototype.reset_profile = function()
{
	store.setItem('OC_profile_key', '');
	store.setItem('OC_profile_id', 0);
	window.location.reload(true);
}

Omnichat.prototype.init_main = function()
{
	console.log('OC init main');
	store = window.localStorage;
	if (store.getItem('OC_chat_logs'))
	{
		// this.chat_logs = store.getItem('OC_chat_logs');
	}
	if (store.getItem('OC_profile_id'))
	{
		this.profile_id = store.getItem('OC_profile_id');
	}
	if (store.getItem('OC_profile_key'))
	{
		this.profile_key = store.getItem('OC_profile_key');
	}
	if (this.profile_id == 0)
	{
		// create a new, hopefully unique ID
		let a_random_id = rand_id() + rand_id() + rand_id() + rand_id() + rand_id() + rand_id() + rand_id() + rand_id();
		// create new profile in the DB and receive the profile ID
		ajax(window.location + '_request_profile_id/', 'key=' + a_random_id, function(readyState, status, responseText, data)
		{
			if (readyState == 4)
			{
				if (status = 200)
				{
					console.log('browser profile ID:', responseText);
					OC.profile_id = responseText;
					OC.profile_key = a_random_id;
					// save profile ID and key i browser
					store.setItem('OC_profile_key', OC.profile_key);
					store.setItem('OC_profile_id', OC.profile_id);
					// reload page to show up in the list
					window.location.reload(true);
					OC.init_host(this.profile_key);
				}
				else
				{
					alert('error requesting profile id');
				}
			}
		}, null);
	}
	// profil ID exist, create host
	else
	{
		console.log(`Profile ID: ${this.profile_id}	Key: ${this.profile_key}`);
		this.init_host(this.profile_key);
	}
}

Omnichat.prototype.init_host = function(profile_key)
{
	this.host = new Omnichat_Host(profile_key);
}

Omnichat.prototype.send_chat = function()
{
	if (this.selected_peer == null || this.host.peers[this.selected_peer] == undefined)
	{
		return;
	}
	el_input = document.getElementById('chat_input');
	let peer = this.host.peers[this.selected_peer];
	let connection = this.host.connections[this.selected_peer];
	// console.log('sending chat', el_input.value, this.selected_peer);
	peer.connection.send({
		chat: el_input.value,
	});
	el_input.value = '';
}

Omnichat.prototype.add_to_chatlog = function(peer_key, chat)
{
	if (this.unread_counts[peer_key] == undefined)
	{
		this.unread_counts[peer_key] = [];
	}
	if (this.selected_peer != peer_key)
	{
		this.unread_counts[peer_key]++;
		let el_profile_unread = document.getElementById('profile_unread_' + peer_key);
		el_profile_unread.innerHTML = this.unread_counts[peer_key];
	}

	if (this.chat_logs[peer_key] == undefined)
	{
		// console.log(`add_to_chatlog this.chat_logs[${peer_key}] == undefined`);
		this.chat_logs[peer_key] = [];
	}
	// console.log(this.chat_logs, peer_key);
	let
		formatted_chat = '<div class=entry>'+chat+'</div>';
	this.chat_logs[peer_key] += formatted_chat;
	if (this.selected_peer === peer_key)
	{
		this.add_to_chat(formatted_chat)
	}
	store.setItem('OC_chat_logs', this.chat_logs);
}

Omnichat.prototype.add_to_chat = function(formatted_chat)
{
	this.chat_log += formatted_chat;
	this.el_chat_log.innerHTML += formatted_chat;
}


Omnichat.prototype.clear_chat = function()
{
	this.chat_log = '';
	if (this.selected_peer !== undefined)
	{
		this.chat_logs[this.selected_peer] = [];
	}
	this.el_chat_log.innerHTML = '';
	store.setItem('OC_chat_logs', this.chat_logs);
}

Omnichat.prototype.refresh_chat = function(peer_key)
{
	// console.log(`refresh_chat ${peer_key} ${this.chat_logs[peer_key]}`);

	this.unread_counts[peer_key] = 0;
	let el_profile_unread = document.getElementById('profile_unread_' + peer_key);
	el_profile_unread.innerHTML = '&nbsp;';

	this.chat_log = this.chat_logs[peer_key];
	this.el_chat_log.innerHTML = this.chat_logs[peer_key];
	// console.log(this.chat_logs[peer_key]);
}

Omnichat.prototype.select_profile = function(peer_key)
{
	// console.log(`selecting profile ${peer_key}`);
	// hmmpf
	this.host.add_peer(peer_key);
	if (this.selected_peer)
	{
		let el_profile = document.getElementById('profile_' + this.selected_peer);
		if (el_profile)
		{
			if (this.host.peers[this.selected_peer] === undefined)
			{
				el_profile.setAttribute('class', 'profile');
			}
			else
			{
				el_profile.setAttribute('class', 'profile connected');
			}
		}
	}
	if (this.chat_logs[peer_key] == undefined)
	{
		// console.log(`this.chat_logs[${peer_key}] == undefined`);
		this.chat_logs[peer_key] = [];
	}
	this.selected_peer = peer_key;
	this.refresh_chat(peer_key);
	let
		el_profile = document.getElementById('profile_' + peer_key);
	if (el_profile)
	{
		el_profile.setAttribute('class', 'profile connected selected');
	}
}

let OC = new Omnichat();

OC.init_main();

