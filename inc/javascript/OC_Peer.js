// -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -

function Omnichat_Peer(host, key)
{
	console.log(`Omnichat_Peer(${host}, ${key})`);
	this.key = key;
	this.host = host;
	this.chat_history = [];
	this.connection = null;
	this.connect();
}

Omnichat_Peer.prototype.connect = function()
{
	console.log(`Omnichat_Peer.connect(${this.host.peer.id}, ${this.key})`);
	this.connection = this.host.peer.connect(this.key, {
		reliable: true,
	});
}

function Omnichat_Chat_Entry(peer_key, timestamp, content)
{
	this.peer_key = peer_key;
	this.timestamp = timestamp;
	this.content = content;
}

Omnichat_Peer.prototype.connect = function()
{
	console.log(`Omnichat_Peer.connect(${this.host.peer.id}, ${this.key})`);
	this.connection = this.host.peer.connect(this.key);
}

