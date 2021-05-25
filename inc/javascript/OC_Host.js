// -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -

function Omnichat_Host(key)
{
	
	this.peers = {};
	this.connections = {};
	this.key = key;
	this.init();
}

Omnichat_Host.prototype.receive_data = function(peer_key, data)
{
	if (data.chat !== undefined)
	{
		OC.add_to_chatlog(peer_key, data.chat);
	}
}

Omnichat_Host.prototype.init = function()
{
	this.peer = new Peer(this.key);
	// console.log(`Host {${this.key}}`);
	// on incoming connection
	var that = this;

	this.peer.on('connection', function(connection) {
		// console.log(`Incoming connection from {${connection.peer}}`);
		that.add_peer(connection.peer);
		that.connections[connection.peer] = connection;
		connection.on('open', function() {
			// console.log(`Connection opened from {${connection.peer}}`);
			// receive data
			connection.on('data', function(data) {
				// console.log('Received data from ' + connection.peer, data);
				that.receive_data(connection.peer, data);
			});
		});
	});

	// mark own profile in list
	// console.log('profile_' + this.key);
	// only exists after the page refreshed once, so don't assume
	let el_profile = document.getElementById('profile_' + this.key);
	if (el_profile)
	{
		// disable clicking own profile
		el_profile.onclick = null;
		el_profile.setAttribute('class', 'profile own');
	}
}

Omnichat_Host.prototype.add_peer = function(profile_key)
{
	if (this.peers[profile_key] === undefined)
	{
		this.peers[profile_key] = new Omnichat_Peer(this, profile_key);
	}
	let
		el_profile = document.getElementById('profile_' + profile_key);
	if (el_profile)
	{
		if (OC.selected_peer === profile_key)
		{
			el_profile.setAttribute('class', 'profile connected selected');
		}
		else
		{
			el_profile.setAttribute('class', 'profile connected');
		}
	}

}
