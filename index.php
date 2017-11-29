<?php

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
Я буду писать тебе утверждения. Напиши цифрами, насколько согласен с ними:
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
				message_send($user_id, 'Напиши цифрами, насколько согласен с утверждениями:
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
				message_send($user_id, 'Напиши цифрами, насколько согласен с утверждениями:
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
