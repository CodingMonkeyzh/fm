<?php
header("Content-Type:text/html; charset=utf-8");
define('APP_ROOT', str_replace('\\', '/', dirname(__FILE__)));

$con = '出卖我的爱 逼着我离开
最后知道真相的我眼泪掉下来
出卖我的爱 你背了良心债
就算付出再多感情 也再买不回来
当初是你要分开 分开就分开
爱情买卖歌谱
爱情买卖歌谱
现在又要用真爱把我哄回来
爱情不是你想卖 想买就能卖
让我挣开 让我明白 放手你的爱
说唱：出卖你的爱 逼着你离开
看到痛苦的你我的眼泪也掉下来
出卖你的爱 我背了良心债
就算付出再多感情也再买不回来
虽然当初是我要分开 后来才明白
现在我用我的真爱希望把你哄回来
我明白是我错了 爱情像你说的
它不是买卖就算千金来买都不卖
He~he...he~he...he……
出卖我的爱 逼着我离开
最后知道真相的我眼泪掉下来
出卖我的爱 你背了良心债
就算付出再多感情也再买不回来
当初是你要分开 分开就分开
现在又要用真爱把我哄回来
爱情不是你想卖 想买就能卖
让我挣开 让我明白 放手你的爱
狠心把我来伤害 爱这么意外
用心浇灌的真爱 枯萎才明白
爱情不是你想卖 想买就能卖
让我看透 痴心的人 不配有真爱
当初是你要分开 分开就分开
现在又要用真爱把我哄回来
爱情不是你想卖 想买就能卖
让我挣开 让我明白 放手你的爱';

function get_tags_arr($title)
    {
		require(APP_ROOT.'/pscws4.class.php');
        $pscws = new PSCWS4();
		$pscws->set_dict(APP_ROOT.'/scws/dict.utf8.xdb');
		$pscws->set_rule(APP_ROOT.'/scws/rules.utf8.ini');
		$pscws->set_ignore(true);
		$pscws->send_text($title);
		$words = $pscws->get_tops(10);
		$tags = array();
		foreach ($words as $val) {
		    $tags[] = $val['word'];
		}
		$pscws->close();
		return $tags;
}

print_r(get_tags_arr($con));

/*function get_keywords_str($content){
	require(APP_ROOT.'/phpanalysis.class.php');
	PhpAnalysis::$loadInit = false;
	$pa = new PhpAnalysis('utf-8', 'utf-8', false);
	$pa->LoadDict();
	$pa->SetSource($content);
	$pa->StartAnalysis( false );
	$tags = $pa->GetFinallyResult();
	return $tags;
}

print(get_keywords_str($con));*/