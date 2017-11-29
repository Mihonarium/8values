<?php

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
	$ideologies_json = file_get_contents('8values_ideologies.json');
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
	$govtArray = ["Анархистская", "Либертарная", "Либеральная", "Модернистская", "Этатистская", "Авторитарная", "Тоталитарная"];
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

message_send($_SESSION['user_id'], "Поделись результатами с друзьями и расскажи им о тесте, пусть тоже пройдут!

Кстати, в самом паблике (vk.com/8values) мы будем писать о разных политических взглядах и, может быть, даже устраивать дебаты. Подписывайся!)", $token);

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
