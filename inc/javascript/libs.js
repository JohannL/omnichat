function ajax(url, post_parameters, callback, data)
{
	var http = new XMLHttpRequest();
	if (http != undefined)
	{
		http.open(post_parameters == '' ? 'GET' : 'POST', url, true);
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http.onreadystatechange = function()
		{
			if (callback != null)
			{
				callback(this.readyState, this.status, this.responseText, data);
			}
		};
		http.send(post_parameters);
		return true;
	}
	else
	{
		return false;
	}
}