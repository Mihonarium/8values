<?php
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
{;
	/*if($user_id<0)
	{
		if(strlen($doc)) $reply.='
vk.com/'.$doc;
		$telegram = new Telegram($bot_id);
		$content = array('chat_id' => -1 * $user_id, 'text' => $reply);
	    $telegram->sendMessage($content);
	}
	else
	{*/
		//$token = $vk['token']; 
		vk_message_send($user_id, $reply, $token, $doc);
	//}
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
function tg_message_send($user_id, $reply, $telegram, $doc='', $keyb = false)
{
	if(strlen($doc)) $reply.='
vk.com/'.$doc;
		$content = array('chat_id' => -1 * $user_id, 'text' => $reply);
		if($keyb) $content['reply_markup'] = $keyb;
	    $telegram->sendMessage($content);
		return;
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

function image4post2base64($img_src)
{
	$imgbinary = file_get_contents($img_src);
    $img_str = base64_encode($imgbinary);
	return "data:image/jpeg;base64,{$img_str}";
}
function init_question($questions, $token)
{
	message_send($_SESSION['user_id'], 'Вопрос '.($_SESSION['qn']+1) . ' из 70.
'.$questions[$_SESSION['qn']]->question, $token);
}
function setLabel($val,$ary) {
        if ($val > 100) { return ""; } else
        if ($val > 90) { return $ary[0]; } else
        if ($val > 75) { return $ary[1]; } else
        if ($val > 60) { return $ary[2]; } else
        if ($val >= 40) { return $ary[3]; } else
        if ($val >= 25) { return $ary[4]; } else
        if ($val >= 10) { return $ary[5]; } else
        if ($val >= 0) { return $ary[6]; } else
        	{return "";}
    }
function test_results($token)
{
	$ideologies_json = file_get_contents('ideologies.json');
	$ideologies = json_decode($ideologies_json)->ideologies;
	$max_econ = 205;
	$max_dipl = 235;
	$max_govt = 325;
	$max_scty = 307;
	$e = calc_score($_SESSION['econ'], $max_econ)/10;
	$d = calc_score($_SESSION['dipl'], $max_dipl)/10;
	$g = calc_score($_SESSION['govt'], $max_govt)/10;
	$s = calc_score($_SESSION['scty'], $max_scty)/10;
	$econArray = ["Коммунистическая", "Социалистическая", "Социальная", "Центристская", "Рыночная", "Капиталистическая", "Laissez-Faire"];
	$diplArray = ["Космополитическая", "Интернациональная", "Мирная", "Сбалансированная", "Патриотическая", "Националистическая", "Шовинистская"];
	$govtArray = ["Анархистская", "Либертарианская", "Либеральная", "Модернистская", "Статистская", "Авторитарная", "Тоталитарная"];
	$sctyArray = ["Революционная", "Крайне прогрессивная", "Прогрессивная", "Нейтральная", "Традиционная", "Крайне традиционная", "Реакционная"];
	
    $equality  = $e;
    $peace     = $d;
    $liberty   = $g;
    $progress  = $s;
    $wealth    = ((int) 10*(100 - $equality))/10;
    $might     = ((int) 10*(100 - $peace   ))/10;
    $authority = ((int) 10*(100 - $liberty ))/10;
    $tradition = ((int) 10*(100 - $progress))/10;
	
	$ideology = "";
	$ideodist = -1;
	for ($i = 0; $i < count($ideologies); ++$i)
	{
        $dist = 0;
        $dist += pow(abs($ideologies[$i]->stats->econ - $equality), 2);
        $dist += pow(abs($ideologies[$i]->stats->govt - $liberty), 2);
        $dist += pow(abs($ideologies[$i]->stats->dipl - $peace), 1.73856063);
        $dist += pow(abs($ideologies[$i]->stats->scty - $progress), 1.73856063);
		if($ideodist == -1) $ideodist = $dist+1;
        if ($dist < $ideodist) {
            $ideology = $ideologies[$i]->name;
            $ideodist = $dist;
        }
    }
	$economicLabel = setLabel($equality, $econArray);
	$diplomaticLabel = setLabel($peace, $diplArray);
	$stateLabel = setLabel($liberty, $govtArray);
	$societyLabel = setLabel($progress, $sctyArray);
	
message_send($_SESSION['user_id'], "Ближайшая идеология: {$ideology}.
	
Экономическая ось: {$economicLabel}
Равенство {$equality}% - {$wealth}% Рынок

Дипломатическая ось: {$diplomaticLabel}
Нация {$might}% - {$peace}% Мир

Гражданская ось: {$stateLabel}
Свобода {$liberty}% - {$authority}% Авторитарность

Социальная ось: {$societyLabel}
Традиции {$tradition}% - {$progress}% Прогресс

Чтобы пройти тест заново, напишите «Заново».", $token);

$preview_link = "http://bot.rtmp.ru/new_bots/8values_image.php?e={$e}&d={$d}&g={$g}&s={$s}";
		$tmpfname = tempnam("/var/www/html/new_bots/tmp", "FOO");
			$content = file_get_contents($preview_link);
			file_put_contents($tmpfname, $content);
			$img = json_decode(uploadImg($tmpfname, $token, $_SESSION['user_id']));
			unlink($tmpfname);
			$doc = $img->response[0]->id;
			message_send($_SESSION['user_id'], '', $token, $doc);
}
function calc_max($questions)
{
	for ($i = 0; $i < count($questions); ++$i) {
		$max_econ += abs($questions[$i]->effect->econ);
		$max_dipl += abs($questions[$i]->effect->dipl);
		$max_govt += abs($questions[$i]->effect->govt);
		$max_scty += abs($questions[$i]->effect->scty);
	}
	return "max_econ: {$max_econ}, max_dipl: {$max_dipl}, max_govt: {$max_govt}, max_scty: {$max_scty}";
}
function calc_score($score, $max)
{
	return (int) (1000*($max+$score)/(2*$max));
}
function prev_question($mult, $questions, $token)
{
	if(!isset($_SESSION['prev_answer'][$_SESSION['qn']-1])) return;
	--$_SESSION['qn'];
	$_SESSION['econ'] -= $_SESSION['prev_answer'][$_SESSION['qn']] * $questions[$_SESSION['qn']]->effect->econ;
	$_SESSION['dipl'] -= $_SESSION['prev_answer'][$_SESSION['qn']] * $questions[$_SESSION['qn']]->effect->dipl;
	$_SESSION['govt'] -= $_SESSION['prev_answer'][$_SESSION['qn']] * $questions[$_SESSION['qn']]->effect->govt;
	$_SESSION['scty'] -= $_SESSION['prev_answer'][$_SESSION['qn']] * $questions[$_SESSION['qn']]->effect->scty;
	init_question($questions, $token);
}

function next_question($mult, $questions, $token)
{
	if($_SESSION['qn'] >= 70)
	{
		test_results($token);
		return;
	}
	
	$_SESSION['econ'] += $mult * $questions[$_SESSION['qn']]->effect->econ;
	$_SESSION['dipl'] += $mult * $questions[$_SESSION['qn']]->effect->dipl;
	$_SESSION['govt'] += $mult * $questions[$_SESSION['qn']]->effect->govt;
	$_SESSION['scty'] += $mult * $questions[$_SESSION['qn']]->effect->scty;
	$_SESSION['prev_answer'][$_SESSION['qn']] = $mult;
	++$_SESSION['qn'];
	if($_SESSION['qn'] < 70)
		init_question($questions, $token);
	else
		test_results($token);
}

$confirmation_token = 'СТРОКА_ПОДТВЕРЖДЕНИЯ'; 
$token = 'ТОКЕН';

$data = json_decode(file_get_contents('php://input')); 
$group_id = $data->group_id;

if(($data->type) == 'confirmation')
{
    echo $confirmation_token; 
    return; 
}
if(($data->type) != 'message_new')
{
	echo_ok();
	return;
}
	echo_ok();
	
	$dataobject = $data->object;
    $user_id = (int) $dataobject->user_id;	
	vk_start_typing($user_id, $token);
	$message = $dataobject->body;
	session_id("vk-8values{$user_id}");
		session_start();
	similar_text($message, 'заново', $percent);
	if((!isset($_SESSION['qn']))  || ($percent >70))
	{
		$_SESSION['user_id'] = $user_id;
		message_send($user_id, 'Привет!
Я буду писать тебе утверждения. Выражай степень согласия с ними цифрами:
5 — «Полностью согласен»;
4 — «Скорее согласен»;
3 — «Не знаю»/«Не уверен»;
2 — «Скорее не согласен»;
1 — «Полностью не согласен».

Чтобы заново ответить на предыдущий вопрос, напиши "0".
Чтобы я напомнил, какие цифры что означают, напиши мне что-нибудь, кроме них.

Ответь цифрами, что скорее согласен или что полностью согласен (то есть понял, как этим пользоваться)', $token);
		$_SESSION['qn'] = -1;
	}
	else
	{
		$questions_json = file_get_contents('questions.json');;
		$questions = json_decode($questions_json)->questions;
		if($_SESSION['qn'] == -1)
		{
			$_SESSION['econ'] = 0.0;
			$_SESSION['dipl'] = 0.0;
			$_SESSION['govt'] = 0.0;
			$_SESSION['scty'] = 0.0;
			$_SESSION['prev_answer'] = array();
			if(($message == 4) || ($message == 5))
			{
				$_SESSION['qn'] = 0;
				init_question($questions, $token);
				return;
			}
			else
			{
				message_send($user_id, 'Выражай степень согласия с утверждениями цифрами:
5 — «Полностью согласен»;
4 — «Скорее согласен»;
3 — «Не знаю»/«Не уверен»;
2 — «Скорее не согласен»;
1 — «Полностью не согласен».

Чтобы заново ответить на предыдущий вопрос, напиши "0".
Чтобы я напомнил, какие цифры что означают, напиши мне что-нибудь, кроме них.

Чтобы начать выполнять тест, ответь, что скорее согласен или что полностью согласен.', $token);
				return;
			}
		}
		switch($message)
		{
			case "5":
				next_question(1.0, $questions, $token);
				break;
			case "4":
				next_question(0.5, $questions, $token);
				break;
			case "3":
				next_question(0.0, $questions, $token);
				break;
			case "2":
				next_question(-0.5, $questions, $token);
				break;
			case "1":
				next_question(-1.0, $questions, $token);
				break;
			case "0":
				prev_question($mult, $questions, $token);
				break;
			case "calc_max":
				//для вывода максимальных значений один раз без необходимости делать это при каждом подсчёте результатов
				message_send($user_id, calc_max($questions), $token);
				break;
			case "test_result":
				//Для проверки работы без необходимости отвечать на 70 вопросов
				if($_SESSION['qn'] < 69)
				{
					$_SESSION['econ'] = -30.0;
					$_SESSION['dipl'] = 132.5;
					$_SESSION['govt'] = 235;
					$_SESSION['scty'] = 202;
					$_SESSION['qn'] = 71;
				}
				next_question(0, $questions, $token);
				break;
			default:
				message_send($user_id, 'Выражай степень согласия с утверждениями цифрами:
5 — «Полностью согласен»;
4 — «Скорее согласен»;
3 — «Не знаю»/«Не уверен»;
2 — «Скорее не согласен»;
1 — «Полностью не согласен».

Чтобы заново ответить на предыдущий вопрос, напиши "0".
Чтобы я напомнил, какие цифры что означают, напиши мне что-нибудь, кроме них (как сейчас).

Утверждение:
'.$questions[$_SESSION['qn']]->question, $token);
				break;
		}
	}

?>
