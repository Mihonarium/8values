<?php
mb_internal_encoding("UTF-8");
header("Content-type: image/png");
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
	
	
	
$ideologies_json = '{"ideologies":[{"name":"Анархо-Коммунизм","stats":{"econ":100,"dipl":50,"govt":100,"scty":90}},{"name":"Либертарный Коммунизм","stats":{"econ":100,"dipl":70,"govt":80,"scty":80}},{"name":"Троцкизм","stats":{"econ":100,"dipl":100,"govt":60,"scty":80}},{"name":"Марксизм","stats":{"econ":100,"dipl":70,"govt":40,"scty":80}},{"name":"Де Леонизм","stats":{"econ":100,"dipl":30,"govt":30,"scty":80}},{"name":"Ленинизм","stats":{"econ":100,"dipl":40,"govt":20,"scty":70}},{"name":"Сталинизм/Маоизм","stats":{"econ":100,"dipl":20,"govt":0,"scty":60}},{"name":"Религиозный Коммунизм","stats":{"econ":100,"dipl":50,"govt":30,"scty":30}},{"name":"Государственный Социализм","stats":{"econ":80,"dipl":30,"govt":30,"scty":70}},{"name":"Теократический Социализм","stats":{"econ":80,"dipl":50,"govt":30,"scty":20}},{"name":"Религиозный Социализм","stats":{"econ":80,"dipl":50,"govt":70,"scty":20}},{"name":"Демократический Социализм","stats":{"econ":80,"dipl":50,"govt":50,"scty":80}},{"name":"Революционный Социализм","stats":{"econ":80,"dipl":20,"govt":50,"scty":70}},{"name":"Либертарный Социализм","stats":{"econ":80,"dipl":80,"govt":80,"scty":80}},{"name":"Анархо-Синдикализм","stats":{"econ":80,"dipl":50,"govt":100,"scty":80}},{"name":"Популизм Левого крыла","stats":{"econ":60,"dipl":40,"govt":30,"scty":70}},{"name":"Теократический Дистрибутизм","stats":{"econ":60,"dipl":40,"govt":30,"scty":20}},{"name":"Дистрибутизм","stats":{"econ":60,"dipl":50,"govt":50,"scty":20}},{"name":"Социал-Либерализм","stats":{"econ":60,"dipl":60,"govt":60,"scty":80}},{"name":"Христианская Демократия","stats":{"econ":60,"dipl":60,"govt":40,"scty":30}},{"name":"Социальная Демократия","stats":{"econ":60,"dipl":70,"govt":40,"scty":80}},{"name":"Прогрессивизм","stats":{"econ":60,"dipl":80,"govt":60,"scty":100}},{"name":"Анархо-Мютюэлизм","stats":{"econ":60,"dipl":50,"govt":100,"scty":70}},{"name":"Национальный Тоталитаризм","stats":{"econ":50,"dipl":20,"govt":0,"scty":50}},{"name":"Глобальный Тоталитаризм","stats":{"econ":50,"dipl":80,"govt":0,"scty":50}},{"name":"Технократия","stats":{"econ":60,"dipl":60,"govt":20,"scty":70}},{"name":"Центризм","stats":{"econ":50,"dipl":50,"govt":50,"scty":50}},{"name":"Либерализм","stats":{"econ":50,"dipl":60,"govt":60,"scty":60}},{"name":"Религиозный Анархизм","stats":{"econ":50,"dipl":50,"govt":0,"scty":20}},{"name":"Популизм Правого крыла","stats":{"econ":40,"dipl":30,"govt":30,"scty":30}},{"name":"Современный Консерватизм","stats":{"econ":40,"dipl":40,"govt":50,"scty":30}},{"name":"Реакционизм","stats":{"econ":40,"dipl":40,"govt":40,"scty":10}},{"name":"Социал-Либертарианство","stats":{"econ":60,"dipl":70,"govt":80,"scty":70}},{"name":"Либертарианство","stats":{"econ":40,"dipl":60,"govt":80,"scty":60}},{"name":"Анархо-Эгоизм","stats":{"econ":40,"dipl":50,"govt":100,"scty":50}},{"name":"Нацизм","stats":{"econ":40,"dipl":0,"govt":0,"scty":5}},{"name":"Автократия","stats":{"econ":50,"dipl":20,"govt":20,"scty":50}},{"name":"Фашизм","stats":{"econ":40,"dipl":20,"govt":20,"scty":20}},{"name":"Капиталистический Фашизм","stats":{"econ":20,"dipl":20,"govt":20,"scty":20}},{"name":"Консерватизм","stats":{"econ":30,"dipl":40,"govt":40,"scty":20}},{"name":"Нео-Либерализм","stats":{"econ":30,"dipl":30,"govt":50,"scty":60}},{"name":"Классический Либерализм","stats":{"econ":30,"dipl":60,"govt":60,"scty":80}},{"name":"Авторитарный Капитализм","stats":{"econ":20,"dipl":30,"govt":20,"scty":40}},{"name":"Государственный Капитализм","stats":{"econ":20,"dipl":50,"govt":30,"scty":50}},{"name":"Нео-Консерватизм","stats":{"econ":20,"dipl":20,"govt":40,"scty":20}},{"name":"Фундаментализм","stats":{"econ":20,"dipl":30,"govt":30,"scty":5}},{"name":"Либертарный Капитализм","stats":{"econ":20,"dipl":50,"govt":80,"scty":60}},{"name":"Анархизм Свободного Рынка","stats":{"econ":20,"dipl":50,"govt":100,"scty":50}},{"name":"Тоталитарный Капитализм","stats":{"econ":0,"dipl":30,"govt":0,"scty":50}},{"name":"Ультра-Капитализм","stats":{"econ":0,"dipl":40,"govt":50,"scty":50}},{"name":"Анархо-Капитализм","stats":{"econ":0,"dipl":50,"govt":100,"scty":50}}]}';
$ideologies_json = '{"ideologies":[{"name":"Anarcho-Communism","stats":{"econ":100,"dipl":50,"govt":100,"scty":90}},{"name":"Libertarian Communism","stats":{"econ":100,"dipl":70,"govt":80,"scty":80}},{"name":"Trotskyism","stats":{"econ":100,"dipl":100,"govt":60,"scty":80}},{"name":"Marxism","stats":{"econ":100,"dipl":70,"govt":40,"scty":80}},{"name":"De Leonism","stats":{"econ":100,"dipl":30,"govt":30,"scty":80}},{"name":"Leninism","stats":{"econ":100,"dipl":40,"govt":20,"scty":70}},{"name":"Stalinism/Maoism","stats":{"econ":100,"dipl":20,"govt":0,"scty":60}},{"name":"Religious Communism","stats":{"econ":100,"dipl":50,"govt":30,"scty":30}},{"name":"State Socialism","stats":{"econ":80,"dipl":30,"govt":30,"scty":70}},{"name":"Theocratic Socialism","stats":{"econ":80,"dipl":50,"govt":30,"scty":20}},{"name":"Religious Socialism","stats":{"econ":80,"dipl":50,"govt":70,"scty":20}},{"name":"Democratic Socialism","stats":{"econ":80,"dipl":50,"govt":50,"scty":80}},{"name":"Revolutionary Socialism","stats":{"econ":80,"dipl":20,"govt":50,"scty":70}},{"name":"Libertarian Socialism","stats":{"econ":80,"dipl":80,"govt":80,"scty":80}},{"name":"Anarcho-Syndicalism","stats":{"econ":80,"dipl":50,"govt":100,"scty":80}},{"name":"Left-Wing Populism","stats":{"econ":60,"dipl":40,"govt":30,"scty":70}},{"name":"Theocratic Distributism","stats":{"econ":60,"dipl":40,"govt":30,"scty":20}},{"name":"Distributism","stats":{"econ":60,"dipl":50,"govt":50,"scty":20}},{"name":"Social Liberalism","stats":{"econ":60,"dipl":60,"govt":60,"scty":80}},{"name":"Christian Democracy","stats":{"econ":60,"dipl":60,"govt":40,"scty":30}},{"name":"Social Democracy","stats":{"econ":60,"dipl":70,"govt":40,"scty":80}},{"name":"Progressivism","stats":{"econ":60,"dipl":80,"govt":60,"scty":100}},{"name":"Anarcho-Mutualism","stats":{"econ":60,"dipl":50,"govt":100,"scty":70}},{"name":"National Totalitarianism","stats":{"econ":50,"dipl":20,"govt":0,"scty":50}},{"name":"Global Totalitarianism","stats":{"econ":50,"dipl":80,"govt":0,"scty":50}},{"name":"Technocracy","stats":{"econ":60,"dipl":60,"govt":20,"scty":70}},{"name":"Centrist","stats":{"econ":50,"dipl":50,"govt":50,"scty":50}},{"name":"Liberalism","stats":{"econ":50,"dipl":60,"govt":60,"scty":60}},{"name":"Religious Anarchism","stats":{"econ":50,"dipl":50,"govt":0,"scty":20}},{"name":"Right-Wing Populism","stats":{"econ":40,"dipl":30,"govt":30,"scty":30}},{"name":"Moderate Conservatism","stats":{"econ":40,"dipl":40,"govt":50,"scty":30}},{"name":"Reactionary","stats":{"econ":40,"dipl":40,"govt":40,"scty":10}},{"name":"Social Libertarianism","stats":{"econ":60,"dipl":70,"govt":80,"scty":70}},{"name":"Libertarianism","stats":{"econ":40,"dipl":60,"govt":80,"scty":60}},{"name":"Anarcho-Egoism","stats":{"econ":40,"dipl":50,"govt":100,"scty":50}},{"name":"Nazism","stats":{"econ":40,"dipl":0,"govt":0,"scty":5}},{"name":"Autocracy","stats":{"econ":50,"dipl":20,"govt":20,"scty":50}},{"name":"Fascism","stats":{"econ":40,"dipl":20,"govt":20,"scty":20}},{"name":"Capitalist Fascism","stats":{"econ":20,"dipl":20,"govt":20,"scty":20}},{"name":"Conservatism","stats":{"econ":30,"dipl":40,"govt":40,"scty":20}},{"name":"Neo-Liberalism","stats":{"econ":30,"dipl":30,"govt":50,"scty":60}},{"name":"Classical Liberalism","stats":{"econ":30,"dipl":60,"govt":60,"scty":80}},{"name":"Authoritarian Capitalism","stats":{"econ":20,"dipl":30,"govt":20,"scty":40}},{"name":"State Capitalism","stats":{"econ":20,"dipl":50,"govt":30,"scty":50}},{"name":"Neo-Conservatism","stats":{"econ":20,"dipl":20,"govt":40,"scty":20}},{"name":"Fundamentalism","stats":{"econ":20,"dipl":30,"govt":30,"scty":5}},{"name":"Libertarian Capitalism","stats":{"econ":20,"dipl":50,"govt":80,"scty":60}},{"name":"Market Anarchism","stats":{"econ":20,"dipl":50,"govt":100,"scty":50}},{"name":"Totalitarian Capitalism","stats":{"econ":0,"dipl":30,"govt":0,"scty":50}},{"name":"Ultra-Capitalism","stats":{"econ":0,"dipl":40,"govt":50,"scty":50}},{"name":"Anarcho-Capitalism","stats":{"econ":0,"dipl":50,"govt":100,"scty":50}}]}';
	$ideologies = json_decode($ideologies_json)->ideologies;
	$econArray = ["Коммунистическая", "Социалистическая", "Социальная", "Центристская", "Рыночная", "Капиталистическая", "Laissez-Faire"];
	$diplArray = ["Космополитическая", "Интернациональная", "Мирная", "Сбалансированная", "Патриотическая", "Националистическая", "Шовинистская"];
	$govtArray = ["Анархистская", "Либертарианская", "Либеральная", "Модернистская", "Статистская", "Авторитарная", "Тоталитарная"];
	$sctyArray = ["Революционная", "Крайне прогрессивная", "Прогрессивная", "Нейтральная", "Традиционная", "Крайне традиционная", "Реакционная"];
    $econArray = ["Communist","Socialist","Social","Centrist","Market","Capitalist","Laissez-Faire"];
    $diplArray = ["Cosmopolitan","Internationalist","Peaceful","Balanced","Patriotic","Nationalist","Chauvinist"];
    $govtArray = ["Anarchist","Libertarian","Liberal","Moderate","Statist","Authoritarian","Totalitarian"];
    $sctyArray = ["Revolutionary","Very Progressive","Progressive","Neutral","Traditional","Very Traditional","Reactionary"];
	
    $equality  = $_GET['e'];
    $peace     = $_GET['d'];
    $liberty   = $_GET['g'];
    $progress  = $_GET['s'];
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
$im = imagecreatefrompng('8values_base.png');
$style = imagecolorallocate($im, 0x22, 0x22, 0x22);
imagefilledrectangle($im, 120, 130, 120+561, 130+80, $style);
imagefilledrectangle($im, 120, 250, 120+561, 250+80, $style);
imagefilledrectangle($im, 120, 370, 120+561, 370+80, $style);
imagefilledrectangle($im, 120, 490, 120+561, 490+80, $style);

$style = imagecolorallocate($im, 0xf4, 0x43, 0x36);
imagefilledrectangle($im, 120, 134, 120+5.6*$equality-2, 134+72, $style);
$style = imagecolorallocate($im, 0x00, 0x89, 0x7b);
imagefilledrectangle($im, 682-5.6*$wealth, 134, 681-2, 134+72, $style);

$style = imagecolorallocate($im, 0xff, 0x98, 0x00);
imagefilledrectangle($im, 120, 254, 120+5.6*$might-2, 254+72, $style);
$style = imagecolorallocate($im, 0x03, 0xa9, 0xf4);
imagefilledrectangle($im, 682-5.6*$peace, 254, 682-2, 254+72, $style);

$style = imagecolorallocate($im, 0xff, 0xeb, 0x3b);
imagefilledrectangle($im, 120, 374, 120+5.6*$liberty-2, 374+72, $style);
$style = imagecolorallocate($im, 0x3f, 0x51, 0xb5);
imagefilledrectangle($im, 682-5.6*$authority, 374, 682-2, 374+72, $style);

$style = imagecolorallocate($im, 0x8b, 0xc3, 0x4a);
imagefilledrectangle($im, 120, 494, 120+5.6*$tradition-2, 494+72, $style);
$style = imagecolorallocate($im, 0x9c, 0x27, 0xb0);
imagefilledrectangle($im, 682-5.6*$progress, 494, 682-2, 494+72, $style);

putenv('GDFONTPATH=' . realpath('.'));
       // ctx.fillStyle="#222222"
$style = imagecolorallocate($im, 0x22, 0x22, 0x22);
        //ctx.font="40px Montserrat"
        //ctx.textAlign="right"
        //ctx.fillText(ideology, 780, 87.5)
		$dimensions = imagettfbbox(30, 0, 'Montserrat-Regular.ttf', $ideology);
imagettftext($im, 30, 0, 780-abs($dimensions[4] - $dimensions[0]), 87.5, $style, 'Montserrat-Regular', $ideology);

if ($equality > 30) { imagettftext($im, 37.5, 0, 130, 187.5, $style, 'Montserrat-Regular', $equality.'%'); }
if ($might > 30) { imagettftext($im, 37.5, 0, 130, 307.5, $style, 'Montserrat-Regular', $might.'%'); }
if ($liberty > 30) { imagettftext($im, 37.5, 0, 130, 427.5, $style, 'Montserrat-Regular', $liberty.'%'); }
if ($tradition > 30) { imagettftext($im, 37.5, 0, 130, 547.5, $style, 'Montserrat-Regular', $tradition.'%'); }

if ($wealth > 30) {
	$dimensions = imagettfbbox(37.5, 0, 'Montserrat-Regular.ttf', $wealth.'%');
	imagettftext($im, 37.5, 0, 670-abs($dimensions[4] - $dimensions[0]), 187.5, $style, 'Montserrat-Regular', $wealth.'%');
}
if ($peace > 30) {
	$dimensions = imagettfbbox(37.5, 0, 'Montserrat-Regular.ttf', $peace.'%');
	imagettftext($im, 37.5, 0, 670-abs($dimensions[4] - $dimensions[0]), 307.5, $style, 'Montserrat-Regular', $peace.'%');
}
if ($authority > 30) {
	$dimensions = imagettfbbox(37.5, 0, 'Montserrat-Regular.ttf', $authority.'%');
	imagettftext($im, 37.5, 0, 670-abs($dimensions[4] - $dimensions[0]), 427.5, $style, 'Montserrat-Regular', $authority.'%');
}
if ($progress > 30) {
	$dimensions = imagettfbbox(37.5, 0, 'Montserrat-Regular.ttf', $progress.'%');
	imagettftext($im, 37.5, 0, 670-abs($dimensions[4] - $dimensions[0]), 547.5, $style, 'Montserrat-Regular', $progress.'%');
}

	$dimensions = imagettfbbox(22.5, 0, 'Montserrat-Light.ttf', 'vk.com/8values');
	imagettftext($im, 22.5, 0, 780-abs($dimensions[4] - $dimensions[0]), 55, $style, 'Montserrat-Light', 'vk.com/8values');

$dimensions = imagettfbbox(22.5, 0, 'Montserrat-Regular.ttf', "Economic Axis: {$economicLabel}");
imagettftext($im, 22.5, 0, 400-abs($dimensions[4] - $dimensions[0])/2, 125, $style, 'Montserrat-Light', "Economic Axis: {$economicLabel}");

$dimensions = imagettfbbox(22.5, 0, 'Montserrat-Regular.ttf', "Diplomatic Axis: {$diplomaticLabel}");
imagettftext($im, 22.5, 0, 400-abs($dimensions[4] - $dimensions[0])/2, 245, $style, 'Montserrat-Light', "Diplomatic Axis: {$diplomaticLabel}");

$dimensions = imagettfbbox(22.5, 0, 'Montserrat-Regular.ttf', "Civil Axis: {$stateLabel}");
imagettftext($im, 22.5, 0, 400-abs($dimensions[4] - $dimensions[0])/2, 365, $style, 'Montserrat-Light', "Civil Axis: {$stateLabel}");

$dimensions = imagettfbbox(22.5, 0, 'Montserrat-Regular.ttf', "Societal Axis: {$societyLabel}");
imagettftext($im, 22.5, 0, 400-abs($dimensions[4] - $dimensions[0])/2, 485, $style, 'Montserrat-Light', "Societal Axis: {$societyLabel}");

imagepng($im);
?>
