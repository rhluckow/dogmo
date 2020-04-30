<?php
function enviasms ($para, $msg)
{
	echo '<script>
		var xhr = new XMLHttpRequest(),
		body = JSON.stringify(
			{
				"messages": [
					{
						"channel": "sms",
						"to": "55'.$para.'",
						"content": "'.$msg.'"
					}
				]
			}
		);
		xhr.open("POST", "https://platform.clickatell.com/v1/message", true);
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Authorization", "Xh7VrBUNQKeEZF-YFZAPMg==");
		xhr.onreadystatechange = function(){
			if (xhr.readyState == 4 && xhr.status == 200) {
				console.log("success");
			}
		};
		xhr.send(body);
	</script>';
	return true;
}
