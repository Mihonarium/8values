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
	
	
	//Не получилось сделать вывод кириллицы, поэтому пока вот так
$ideologies_json = file_get_contents('ideologies_en.json');
	$ideologies = json_decode($ideologies_json)->ideologies;
	/*
	$econArray = ["Коммунистическая", "Социалистическая", "Социальная", "Центристская", "Рыночная", "Капиталистическая", "Laissez-Faire"];
	$diplArray = ["Космополитическая", "Интернациональная", "Мирная", "Сбалансированная", "Патриотическая", "Националистическая", "Шовинистская"];
	$govtArray = ["Анархистская", "Либертарианская", "Либеральная", "Модернистская", "Статистская", "Авторитарная", "Тоталитарная"];
	$sctyArray = ["Революционная", "Крайне прогрессивная", "Прогрессивная", "Нейтральная", "Традиционная", "Крайне традиционная", "Реакционная"];
    	*/
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
