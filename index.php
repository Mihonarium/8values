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
	$ideologies_json = '{"ideologies":[{"name":"Анархо-Коммунизм","stats":{"econ":100,"dipl":50,"govt":100,"scty":90}},{"name":"Либертарный Коммунизм","stats":{"econ":100,"dipl":70,"govt":80,"scty":80}},{"name":"Троцкизм","stats":{"econ":100,"dipl":100,"govt":60,"scty":80}},{"name":"Марксизм","stats":{"econ":100,"dipl":70,"govt":40,"scty":80}},{"name":"Де Леонизм","stats":{"econ":100,"dipl":30,"govt":30,"scty":80}},{"name":"Ленинизм","stats":{"econ":100,"dipl":40,"govt":20,"scty":70}},{"name":"Сталинизм/Маоизм","stats":{"econ":100,"dipl":20,"govt":0,"scty":60}},{"name":"Религиозный Коммунизм","stats":{"econ":100,"dipl":50,"govt":30,"scty":30}},{"name":"Государственный Социализм","stats":{"econ":80,"dipl":30,"govt":30,"scty":70}},{"name":"Теократический Социализм","stats":{"econ":80,"dipl":50,"govt":30,"scty":20}},{"name":"Религиозный Социализм","stats":{"econ":80,"dipl":50,"govt":70,"scty":20}},{"name":"Демократический Социализм","stats":{"econ":80,"dipl":50,"govt":50,"scty":80}},{"name":"Революционный Социализм","stats":{"econ":80,"dipl":20,"govt":50,"scty":70}},{"name":"Либертарный Социализм","stats":{"econ":80,"dipl":80,"govt":80,"scty":80}},{"name":"Анархо-Синдикализм","stats":{"econ":80,"dipl":50,"govt":100,"scty":80}},{"name":"Популизм Левого крыла","stats":{"econ":60,"dipl":40,"govt":30,"scty":70}},{"name":"Теократический Дистрибутизм","stats":{"econ":60,"dipl":40,"govt":30,"scty":20}},{"name":"Дистрибутизм","stats":{"econ":60,"dipl":50,"govt":50,"scty":20}},{"name":"Социал-Либерализм","stats":{"econ":60,"dipl":60,"govt":60,"scty":80}},{"name":"Христианская Демократия","stats":{"econ":60,"dipl":60,"govt":40,"scty":30}},{"name":"Социальная Демократия","stats":{"econ":60,"dipl":70,"govt":40,"scty":80}},{"name":"Прогрессивизм","stats":{"econ":60,"dipl":80,"govt":60,"scty":100}},{"name":"Анархо-Мютюэлизм","stats":{"econ":60,"dipl":50,"govt":100,"scty":70}},{"name":"Национальный Тоталитаризм","stats":{"econ":50,"dipl":20,"govt":0,"scty":50}},{"name":"Глобальный Тоталитаризм","stats":{"econ":50,"dipl":80,"govt":0,"scty":50}},{"name":"Технократия","stats":{"econ":60,"dipl":60,"govt":20,"scty":70}},{"name":"Центризм","stats":{"econ":50,"dipl":50,"govt":50,"scty":50}},{"name":"Либерализм","stats":{"econ":50,"dipl":60,"govt":60,"scty":60}},{"name":"Религиозный Анархизм","stats":{"econ":50,"dipl":50,"govt":0,"scty":20}},{"name":"Популизм Правого крыла","stats":{"econ":40,"dipl":30,"govt":30,"scty":30}},{"name":"Современный Консерватизм","stats":{"econ":40,"dipl":40,"govt":50,"scty":30}},{"name":"Реакционизм","stats":{"econ":40,"dipl":40,"govt":40,"scty":10}},{"name":"Социал-Либертарианство","stats":{"econ":60,"dipl":70,"govt":80,"scty":70}},{"name":"Либертарианство","stats":{"econ":40,"dipl":60,"govt":80,"scty":60}},{"name":"Анархо-Эгоизм","stats":{"econ":40,"dipl":50,"govt":100,"scty":50}},{"name":"Нацизм","stats":{"econ":40,"dipl":0,"govt":0,"scty":5}},{"name":"Автократия","stats":{"econ":50,"dipl":20,"govt":20,"scty":50}},{"name":"Фашизм","stats":{"econ":40,"dipl":20,"govt":20,"scty":20}},{"name":"Капиталистический Фашизм","stats":{"econ":20,"dipl":20,"govt":20,"scty":20}},{"name":"Консерватизм","stats":{"econ":30,"dipl":40,"govt":40,"scty":20}},{"name":"Нео-Либерализм","stats":{"econ":30,"dipl":30,"govt":50,"scty":60}},{"name":"Классический Либерализм","stats":{"econ":30,"dipl":60,"govt":60,"scty":80}},{"name":"Авторитарный Капитализм","stats":{"econ":20,"dipl":30,"govt":20,"scty":40}},{"name":"Государственный Капитализм","stats":{"econ":20,"dipl":50,"govt":30,"scty":50}},{"name":"Нео-Консерватизм","stats":{"econ":20,"dipl":20,"govt":40,"scty":20}},{"name":"Фундаментализм","stats":{"econ":20,"dipl":30,"govt":30,"scty":5}},{"name":"Либертарный Капитализм","stats":{"econ":20,"dipl":50,"govt":80,"scty":60}},{"name":"Анархизм Свободного Рынка","stats":{"econ":20,"dipl":50,"govt":100,"scty":50}},{"name":"Тоталитарный Капитализм","stats":{"econ":0,"dipl":30,"govt":0,"scty":50}},{"name":"Ультра-Капитализм","stats":{"econ":0,"dipl":40,"govt":50,"scty":50}},{"name":"Анархо-Капитализм","stats":{"econ":0,"dipl":50,"govt":100,"scty":50}}]}';
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

$preview_link = "http://ПУТЬ/results.php.php?e={$e}&d={$d}&g={$g}&s={$s}";
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
2 — «Скорее несогласен»;
1 — «Полностью несогласен».

Чтобы заново ответить на предыдущий вопрос, напиши "0".
Чтобы я напомнил, какие цифры что означают, напиши мне что-нибудь, кроме них.

Ответь цифрами, что скорее согласен или что полностью согласен (то есть понял, как этим пользоваться)', $token);
		$_SESSION['qn'] = -1;
	}
	else
	{
		$questions_json = '{"questions":[{"question":"Притеснение со стороны корпораций вызывает большее беспокойство, чем со стороны государства.","effect":{"econ":10,"dipl":0,"govt":0,"scty":0}},{"question":"Правительству необходимо вмешиваться в экономику для защиты потребителей.","effect":{"econ":10,"dipl":0,"govt":0,"scty":0}},{"question":"Чем свободнее рынок, тем свободнее люди.","effect":{"econ":-10,"dipl":0,"govt":0,"scty":0}},{"question":"Лучше поддерживать сбалансированный бюджет, чем обеспечивать благосостояние всех граждан.","effect":{"econ":-10,"dipl":0,"govt":0,"scty":0}},{"question":"Публичное финансирование исследований более выгодно людям, чем рыночное финансирование.","effect":{"econ":10,"dipl":0,"govt":0,"scty":10}},{"question":"Международная торговля выгодна.","effect":{"econ":-5,"dipl":0,"govt":10,"scty":0}},{"question":"От каждого по способностям, каждому по потребностям","effect":{"econ":10,"dipl":0,"govt":0,"scty":5}},{"question":"Было бы лучше, если бы социальные программы были упразднены в пользу частной благотворительности.","effect":{"econ":-10,"dipl":0,"govt":0,"scty":-5}},{"question":"Налоги на богатых должны быть увеличены для обеспечения бедных.","effect":{"econ":10,"dipl":0,"govt":0,"scty":0}},{"question":"Наследование — законная форма богатства.","effect":{"econ":-10,"dipl":0,"govt":0,"scty":-5}},{"question":"Коммунальные услуги, такие как дороги и электричество, должны находиться в общественной собственности.","effect":{"econ":10,"dipl":0,"govt":0,"scty":0}},{"question":"Чрезмерное вмешательство правительства — угроза для экономики.","effect":{"econ":-10,"dipl":0,"govt":0,"scty":0}},{"question":"Те, кто могут платить больше, должны получать лучшее медицинское обслуживание.","effect":{"econ":-10,"dipl":0,"govt":0,"scty":0}},{"question":"Качественное образование — право всех людей.","effect":{"econ":10,"dipl":0,"govt":0,"scty":10}},{"question":"Средства производства должны принадлежать рабочим, использующим их.","effect":{"econ":10,"dipl":0,"govt":0,"scty":0}},{"question":"ООН должна быть распущена.","effect":{"econ":0,"dipl":-10,"govt":-5,"scty":0}},{"question":"Зачастую необходимы военные действия, чтобы защитить нашу нацию.","effect":{"econ":0,"dipl":-10,"govt":-10,"scty":0}},{"question":"Я поддерживаю региональные объединения, такие как Евросоюз.","effect":{"econ":-5,"dipl":10,"govt":10,"scty":5}},{"question":"Важно сохранить наш национальный суверенитет.","effect":{"econ":0,"dipl":-10,"govt":-5,"scty":0}},{"question":"Единое мировое правительство принесёт человечеству пользу.","effect":{"econ":0,"dipl":10,"govt":0,"scty":0}},{"question":"Важнее сохранять мирные отношения, чем наращивать силу.","effect":{"econ":0,"dipl":10,"govt":0,"scty":0}},{"question":"Наша страна не должна оправдывать войны.","effect":{"econ":0,"dipl":-10,"govt":-10,"scty":0}},{"question":"Военные расходы - пустая трата денег.","effect":{"econ":0,"dipl":10,"govt":10,"scty":0}},{"question":"Международная помощь - пустая трата денег.","effect":{"econ":-5,"dipl":-10,"govt":0,"scty":0}},{"question":"У меня великая нация.","effect":{"econ":0,"dipl":-10,"govt":0,"scty":0}},{"question":"Исследования должны проводиться в международном масштабе.","effect":{"econ":0,"dipl":-10,"govt":0,"scty":10}},{"question":"Правительства должны отвечать перед международным сообществом.","effect":{"econ":0,"dipl":10,"govt":5,"scty":0}},{"question":"Даже в протесте против авторитарных режимов насилие недопустимо.","effect":{"econ":0,"dipl":5,"govt":-5,"scty":0}},{"question":"Мои религиозные ценности должны быть распространены как можно шире.","effect":{"econ":0,"dipl":-5,"govt":-10,"scty":-10}},{"question":"Наши национальные ценности должны быть распространены как можно шире.","effect":{"econ":0,"dipl":-10,"govt":-10,"scty":0}},{"question":"Очень важно сохранять закон и порядок.","effect":{"econ":0,"dipl":-5,"govt":-10,"scty":-5}},{"question":"Большинство населения принимает неверные решения.","effect":{"econ":0,"dipl":0,"govt":-10,"scty":0}},{"question":"Преступления без жертв (как употребление наркотиков) вообще не должны считаться преступлениями.","effect":{"econ":0,"dipl":0,"govt":10,"scty":0}},{"question":"Ради защиты от терроризма можно пожертвовать некоторыми гражданскими свободами.","effect":{"econ":0,"dipl":0,"govt":-10,"scty":0}},{"question":"Слежка со стороны правительств необходима в современном обществе.","effect":{"econ":0,"dipl":0,"govt":-10,"scty":0}},{"question":"Само существование государства - угроза нашей свободе.","effect":{"econ":0,"dipl":0,"govt":10,"scty":0}},{"question":"Независимо от политических взглядов, важно быть на стороне своей страны.","effect":{"econ":0,"dipl":-10,"govt":-10,"scty":-5}},{"question":"Любая власть должна быть оспариваема.","effect":{"econ":0,"dipl":0,"govt":10,"scty":5}},{"question":"Иерархическое устройство - лучшее.","effect":{"econ":0,"dipl":0,"govt":-10,"scty":0}},{"question":"Важно, чтобы государство придерживалось мнения большинства, даже если оно неверное.","effect":{"econ":0,"dipl":0,"govt":10,"scty":0}},{"question":"Чем сильнее руководство, тем лучше.","effect":{"econ":0,"dipl":-10,"govt":-10,"scty":0}},{"question":"Демократия - это большее, чем просто процесс принятия решений.","effect":{"econ":0,"dipl":0,"govt":10,"scty":0}},{"question":"Экологические нормы необходимы.","effect":{"econ":5,"dipl":0,"govt":0,"scty":10}},{"question":"Лучший мир придёт путём автоматизации, научного и технологического прогресса.","effect":{"econ":0,"dipl":0,"govt":0,"scty":10}},{"question":"Дети должны воспитываться в традиционных или религиозных ценностях.","effect":{"econ":0,"dipl":0,"govt":-10,"scty":-10}},{"question":"Сами по себе традиции не имеют ценности.","effect":{"econ":0,"dipl":0,"govt":0,"scty":10}},{"question":"Религия должна играть роль в государственном управлении.","effect":{"econ":0,"dipl":0,"govt":-10,"scty":-10}},{"question":"Церкви должны облагаться такими же налогами, как и другие институты.","effect":{"econ":5,"dipl":0,"govt":0,"scty":10}},{"question":"Климатические изменения - одна из самых больших угроз нашему образу жизни.","effect":{"econ":0,"dipl":0,"govt":0,"scty":10}},{"question":"Мир должен объединиться в борьбе с климатическими изменениями.","effect":{"econ":0,"dipl":10,"govt":0,"scty":10}},{"question":"Общество было лучше много лет назад.","effect":{"econ":0,"dipl":0,"govt":0,"scty":-10}},{"question":"Важно сохранить традиции прошлого.","effect":{"econ":0,"dipl":0,"govt":0,"scty":-10}},{"question":"Важно думать о будущем после наших жизней.","effect":{"econ":0,"dipl":0,"govt":0,"scty":10}},{"question":"Ради реализации своих целей можно пожертвовать частью культуры.","effect":{"econ":0,"dipl":0,"govt":0,"scty":10}},{"question":"Употребление наркотиков должно быть легализовано или декриминализовано.","effect":{"econ":0,"dipl":0,"govt":10,"scty":2}},{"question":"Однополые браки должны быть легализованы.","effect":{"econ":0,"dipl":0,"govt":10,"scty":10}},{"question":"Никакая культура не превосходит другую.","effect":{"econ":0,"dipl":10,"govt":5,"scty":10}},{"question":"Секс вне брака аморален.","effect":{"econ":0,"dipl":0,"govt":-5,"scty":-10}},{"question":"Если мы вообще принимаем иммигрантов, важно их ассимилировать в нашу культуру.","effect":{"econ":0,"dipl":0,"govt":-5,"scty":-10}},{"question":"Аборты должны быть запрещены в большинстве или во всех случаях.","effect":{"econ":0,"dipl":0,"govt":-10,"scty":-10}},{"question":"Владение оружием должно быть запрещено тем, у кого нет веской причины.","effect":{"econ":0,"dipl":10,"govt":-10,"scty":0}},{"question":"Я поддерживаю всеобщую государственную систему здравоохранения.","effect":{"econ":10,"dipl":0,"govt":0,"scty":0}},{"question":"Проституция должна быть нелегальной.","effect":{"econ":0,"dipl":0,"govt":-10,"scty":-10}},{"question":"Сохранения семейных ценностей имеет большое значение.","effect":{"econ":0,"dipl":0,"govt":0,"scty":-10}},{"question":"Преследовать прогресс любой ценой опасно.","effect":{"econ":0,"dipl":0,"govt":0,"scty":-10}},{"question":"Генетические модификации - добро, даже если производятся на людях.","effect":{"econ":0,"dipl":0,"govt":0,"scty":10}},{"question":"Мы должны открыть наши границы иммигрантам.","effect":{"econ":0,"dipl":10,"govt":10,"scty":0}},{"question":"Правительства должны быть так же обеспокоены иностранными гражданами, как и теми, кто находится в их границах.","effect":{"econ":10,"dipl":10,"govt":0,"scty":0}},{"question":"Все люди - независимо от таких факторов, как культура или секусальная ориентация - должны рассматриваться как равные.","effect":{"econ":10,"dipl":10,"govt":10,"scty":10}},{"question":"Важно ставить цели моей группы ваше всех остальных.","effect":{"econ":-10,"dipl":-10,"govt":-10,"scty":-10}}]}';
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
2 — «Скорее несогласен»;
1 — «Полностью несогласен».

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
				message_send($user_id, calc_max($questions), $token);
				break;
			case "test_result":
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
2 — «Скорее несогласен»;
1 — «Полностью несогласен».

Чтобы заново ответить на предыдущий вопрос, напиши "0".
Чтобы я напомнил, какие цифры что означают, напиши мне что-нибудь, кроме них (как сейчас).

Утверждение:
'.$questions[$_SESSION['qn']]->question, $token);
				break;
		}
	}

?>
