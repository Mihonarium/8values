<?php


$verify_token = ""; // Verify token 
if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token) { 
echo $_REQUEST['hub_challenge']; 
}
include('bot_functions.php');

function init_question($questions, $token)
{
	return array("message" => 'Вопрос '.($_SESSION['qn']+1) . ' из 70.
'.$questions[$_SESSION['qn']]->question);
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
	$govtArray = ["Анархистская", "Либертарная", "Либеральная", "Модернистская", "Статистская", "Авторитарная", "Тоталитарная"];
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
	
$return['message'] = "Ближайшая идеология: {$ideology}.
	
Экономическая ось: {$economicLabel}
Равенство {$equality}% - {$wealth}% Рынок

Дипломатическая ось: {$diplomaticLabel}
Нация {$might}% - {$peace}% Мир

Гражданская ось: {$stateLabel}
Свобода {$liberty}% - {$authority}% Авторитарность

Социальная ось: {$societyLabel}
Традиции {$tradition}% - {$progress}% Прогресс

Чтобы пройти тест заново, напишите «Заново».";

//message_send($_SESSION['user_id'], "Не забудь поделиться результатами с друзьями и рассказать им об этом тесте!", $token);

$preview_link = "http://bot.rtmp.ru/new_bots/8values_image.php?e={$e}&d={$d}&g={$g}&s={$s}";
		/*$tmpfname = tempnam("/var/www/html/new_bots/tmp", "FOO");
			$content = file_get_contents($preview_link);
			file_put_contents($tmpfname, $content);
			$img = json_decode(uploadImg($tmpfname, $token, $_SESSION['user_id']));
			unlink($tmpfname);
			$doc = $img->response[0]->id;
			message_send($_SESSION['user_id'], '', $token, $doc);*/
			$return['img'] = $preview_link;
	return $return;
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
	return init_question($questions, $token);
}

function next_question($mult, $questions, $token)
{
	if($_SESSION['qn'] >= 70)
	{
		return test_results($token);
	}
	
	$_SESSION['econ'] += $mult * $questions[$_SESSION['qn']]->effect->econ;
	$_SESSION['dipl'] += $mult * $questions[$_SESSION['qn']]->effect->dipl;
	$_SESSION['govt'] += $mult * $questions[$_SESSION['qn']]->effect->govt;
	$_SESSION['scty'] += $mult * $questions[$_SESSION['qn']]->effect->scty;
	$_SESSION['prev_answer'][$_SESSION['qn']] = $mult;
	++$_SESSION['qn'];
	if($_SESSION['qn'] < 70)
		return init_question($questions, $token);
	else
		return test_results($token);
}
$facebook_token = ''; 
$token = '';

$data = json_decode(file_get_contents('php://input')); 

require_once(dirname(__FILE__) . '/vendor/autoload.php');
use pimax\FbBotApp;
use pimax\Menu\MenuItem;
use pimax\Menu\LocalizedMenu;
use pimax\Messages\Message;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageElement;
use pimax\Messages\MessageReceiptElement;
use pimax\Messages\Address;
use pimax\Messages\Summary;
use pimax\Messages\Adjustment;
use pimax\Messages\AccountLink;
use pimax\Messages\ImageMessage;
use pimax\Messages\QuickReply;
use pimax\Messages\QuickReplyButton;
use pimax\Messages\SenderAction;

$bot = new FbBotApp($facebook_token);

if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token)
{
     // Webhook setup request
    echo $_REQUEST['hub_challenge'];
} else {

     $data = json_decode(file_get_contents("php://input"));
	 //message_send('149192198', json_encode($data), $token);
     if (!empty($data->entry[0]->messaging))
     {
            foreach ($data->entry[0]->messaging as $cur_message)
            {
				$user_id = $cur_message->sender->id;
				if(isset($cur_message->message->text)) $message = $cur_message->message->text;
				else $message = $cur_message->postback->payload;
				switch($message)
				{
					case "Полностью согласен":
						$message = "5";
						break;
					case "Скорее согласен":
						$message = "4";
						break;
					case "Не знаю/Не уверен":
						$message = "3";
						break;
					case "Скорее несогласен":
						$message = "2";
						break;
					case "Полностью несогласен":
						$message = "1";
						break;
					case "Назад":
						$message = "0";
						break;
				}
				//message_send('149192198', $user_id . " " . $message, $token);
				//$bot->send(new Message($user_id, 'Hi there'));
				session_id("fb-8values{$user_id}");
					session_start();
				similar_text($message, 'заново', $percent);
				if((!isset($_SESSION['qn']))  || ($percent >70))
		{
		$_SESSION['user_id'] = $user_id;
		$bot->send(new StructuredMessage($user_id, StructuredMessage::TYPE_BUTTON,
					[
						'text' => 'Привет!
Я буду писать тебе утверждения. Выбирай степень согласия с ними.

Чтобы заново ответить на предыдущий вопрос, выбери "Назад".

Ответь, что скорее согласен или что полностью согласен (то есть понял, как этим пользоваться)',
						'buttons' => [
							new MessageButton(MessageButton::TYPE_POSTBACK, 'Полностью согласен'),
							new MessageButton(MessageButton::TYPE_POSTBACK, 'Скорее согласен'),
							new MessageButton(MessageButton::TYPE_POSTBACK, 'Не знаю/Не уверен')
						]
					]
				));
			$bot->send(new StructuredMessage($user_id,
				StructuredMessage::TYPE_BUTTON,
				[
					'text' => '📎',
					'buttons' => [
						new MessageButton(MessageButton::TYPE_POSTBACK, 'Скорее несогласен'),
						new MessageButton(MessageButton::TYPE_POSTBACK, 'Полностью несогласен'),
						new MessageButton(MessageButton::TYPE_POSTBACK, 'Назад')
					]
				]
			));
		$_SESSION['qn'] = -1;
	}
	else
	{
		$questions_json = file_get_contents('8values_questions.json');
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
					$reply = init_question($questions, $token);			
				$bot->send(new StructuredMessage($user_id, StructuredMessage::TYPE_BUTTON,
					[
						'text' => $reply['message'],
						'buttons' => [
							new MessageButton(MessageButton::TYPE_POSTBACK, 'Полностью согласен'),
							new MessageButton(MessageButton::TYPE_POSTBACK, 'Скорее согласен'),
							new MessageButton(MessageButton::TYPE_POSTBACK, 'Не знаю/Не уверен')
						]
					]
				));
				$bot->send(new StructuredMessage($user_id,
					StructuredMessage::TYPE_BUTTON,
					[
						'text' => '📎',
						'buttons' => [
							new MessageButton(MessageButton::TYPE_POSTBACK, 'Скорее несогласен'),
							new MessageButton(MessageButton::TYPE_POSTBACK, 'Полностью несогласен'),
							new MessageButton(MessageButton::TYPE_POSTBACK, 'Назад')
						]
					]
				));
				return;
			}
			else
			{
				$bot->send(new StructuredMessage($user_id, StructuredMessage::TYPE_BUTTON,
					[
						'text' => 'Ответь, что скорее согласен или что полностью согласен (то есть понял, как этим пользоваться)',
						'buttons' => [
							new MessageButton(MessageButton::TYPE_POSTBACK, 'Полностью согласен'),
							new MessageButton(MessageButton::TYPE_POSTBACK, 'Скорее согласен'),
							new MessageButton(MessageButton::TYPE_POSTBACK, 'Не знаю/Не уверен')
						]
					]
				));
			$bot->send(new StructuredMessage($user_id,
				StructuredMessage::TYPE_BUTTON,
				[
					'text' => '📎',
					'buttons' => [
						new MessageButton(MessageButton::TYPE_POSTBACK, 'Скорее несогласен'),
						new MessageButton(MessageButton::TYPE_POSTBACK, 'Полностью несогласен'),
						new MessageButton(MessageButton::TYPE_POSTBACK, 'Назад')
					]
				]
			));
				return;
			}
		}
		$reply = array();
		switch($message)
		{
			case "5":
				$reply = next_question(1.0, $questions, $token);
				break;
			case "4":
				$reply = next_question(0.5, $questions, $token);
				break;
			case "3":
				$reply = next_question(0.0, $questions, $token);
				break;
			case "2":
				$reply = next_question(-0.5, $questions, $token);
				break;
			case "1":
				$reply = next_question(-1.0, $questions, $token);
				break;
			case "0":
				$reply = prev_question($mult, $questions, $token);
				break;
			case "calc_max":
				$reply = array("message" => calc_max($questions));
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
				$reply = next_question(0, $questions, $token);
				break;
			default:
				$reply = array("message" => 'Выбирай, насколько согласен с утверждениями.

Чтобы заново ответить на предыдущий вопрос, выбери "Назад".

Утверждение:
'.$questions[$_SESSION['qn']]->question);
				break;
		}
		//message_send('149192198', json_encode($reply), $token);
		if(!isset($reply['img']))
		{
			
			$bot->send(new StructuredMessage($user_id, StructuredMessage::TYPE_BUTTON,
				[
					'text' => $reply['message'],
					'buttons' => [
						new MessageButton(MessageButton::TYPE_POSTBACK, 'Полностью согласен'),
						new MessageButton(MessageButton::TYPE_POSTBACK, 'Скорее согласен'),
						new MessageButton(MessageButton::TYPE_POSTBACK, 'Не знаю/Не уверен')
					]
				]
			));
			$bot->send(new StructuredMessage($user_id,
				StructuredMessage::TYPE_BUTTON,
				[
					'text' => '📎',
					'buttons' => [
						new MessageButton(MessageButton::TYPE_POSTBACK, 'Скорее несогласен'),
						new MessageButton(MessageButton::TYPE_POSTBACK, 'Полностью несогласен'),
						new MessageButton(MessageButton::TYPE_POSTBACK, 'Назад')
					]
				]
			));
			
		}
		else
		{
			$bot->send(new Message($user_id, $reply['message']));
			$bot->send(new ImageMessage($user_id, $reply['img']));
		}
	}
	
	
            }
   }
}
