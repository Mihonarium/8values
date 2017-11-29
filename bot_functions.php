<?php
// part of MStreaming bots functions
function echo_ok()
{
	
	ob_start();
	echo 'ok';
	$length = ob_get_length();
	header('Connection: close');
	header("Content-Length: " . $length);
	header("Content-Encoding: none");
	header("Accept-Ranges: bytes");
	ob_end_flush();
	ob_flush();
	flush();
	//fastcgi_finish_request();
}

function vk_message_send($user_id, $reply, $token, $doc='')
{
	$parameters = [
		'message' => $reply, 
		'user_id' => $user_id,
		'attachment' => $doc,
		'access_token' => $token, 
		'v' => '5.63' 
	];
	$curl_result = vk_request('messages.send', $parameters, $token);
	return $curl_result;
}
function vk_start_typing($user_id, $token)
{
	$parameters = [
		'type' => 'typing',
		'user_id' => $user_id,
		'access_token' => $token, 
		'v' => '5.63' 
	];
	$curl_result = vk_request('messages.setActivity', $parameters, $token);
	return $curl_result;
}
function message_send($user_id, $reply, $token, $doc='')
{
		vk_message_send($user_id, $reply, $token, $doc);
	return;
}
function vk_request($method, $parameters, $token='')
{
	$parameters['access_token'] = $token;
	return post_request('https://api.vk.com/method/'.$method, $parameters);
}
function post_request($url, $parameters=[])
{
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
	$curl_result = curl_exec($ch);
	
	curl_close($ch);
	return $curl_result;
}

function vkApi_upload($url, $file_name) { 
  $curl_file = curl_file_create(realpath("$file_name"),'image/jpeg','photo.jpg');
  $curl = curl_init($url); 
  curl_setopt($curl, CURLOPT_POST, true); 
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
  curl_setopt($curl, CURLOPT_POSTFIELDS, array('file' => $curl_file)); 
  $response = curl_exec($curl); 
  curl_close($curl); 
  return $response; 
} 
function uploadImg($filename, $token, $user_id)
{
	$getUploadServer = vk_request('photos.getMessagesUploadServer', array('peer_id' => $user_id), $token);
	$uploadServerUrl = json_decode($getUploadServer)->response->upload_url;
	//return json_encode($uploadServerUrl);
    $upload_result = vkApi_upload($uploadServerUrl, $filename);
	$upRes = json_decode($upload_result);
	//return json_encode($upload_result);
	$save_result = vk_request('photos.saveMessagesPhoto', array('hash' => $upRes->hash, 'photo' => $upRes->photo, 'server' => $upRes->server), $token);
	return $save_result;
}
?>
