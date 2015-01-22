<?php
/*
Proje Adı: Sunucu Ctrl (ServCtrl)
Modül Adı: ServCtrl Local Tweeter
Modül Tamamlanma Tarihi: 18.01.2015
Revizyon: 1

Copyright (C) 2014 SunucuCtrl Projesi

Bu program özgür yazılımdır: Özgür Yazılım Vakfı tarafından yayımlanan GNU Genel Kamu Lisansı’nın sürüm 3 ya da (isteğinize bağlı olarak) daha sonraki sürümlerinin hükümleri altında yeniden dağıtabilir ve/veya değiştirebilirsiniz.

Bu program, yararlı olması umuduyla dağıtılmış olup, programın BİR TEMİNATI YOKTUR; TİCARETİNİN YAPILABİLİRLİĞİNE VE ÖZEL BİR AMAÇ İÇİN UYGUNLUĞUNA dair bir teminat da vermez. Ayrıntılar için GNU Genel Kamu Lisansı’na göz atınız.

Bu programla birlikte GNU Genel Kamu Lisansı’nın bir kopyasını elde etmiş olmanız gerekir. Eğer elinize ulaşmadıysa <http://www.gnu.org/licenses/> adresine bakınız.

Proje ile ilgili herşeye http//sunucuctrl.com/ adresinden ulaşılabilir.

DEĞİŞTİRİLEN XOR ŞİFRELEME FONKSİYONUNUN ORJİNAL LİSANSI
Basic XOR Encryption Function

Copyright 2010 Serverboy Software; Matt Basta
(https://github.com/serverboy/interchange-singularity/blob/master/libraries/encrypt_xor.php)

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0
*/

function XOREncryption($InputString){
	
	$KeyPhrase = "486Gt2m+329376/XA4+c71G9YVc5z8/V199LzJO";
	
	for ($i = 0; $i < strlen($InputString); $i++){
		$rPos = $i % strlen($KeyPhrase);
		$r = ord($InputString[$i]) ^ ord($KeyPhrase[$rPos]) & 10;
		$InputString[$i] = chr($r);
	}
	
	return $InputString;
	
}

function decoder($hash) {
	$hsh = XOREncryption(base64_decode($hash));
	return $hsh;
}

try
{
	header("Content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

	require_once('codebird.php');
	$tweet = $argv[1];
	
	if (!($tweet)) {
		echo "<hata>\n\tGönderilecek tweet belirtilmemiş\n</hata>";
	} else {

		$conn = new PDO("sqlite:/opt/sunucuctrl/configs.db")  or die("<hata>\n\tVeritabanı açılamadı\n</hata>"); 

		$sql = "SELECT * FROM tbl_tweet";
		foreach ($conn->query($sql) as $row) {
			$apikey = decoder($row['apikey']); //Consumer Key (API Key)
			$apisecret = decoder($row['apisecret']); //Consumer Secret (API Secret)
			$accesstoken = decoder($row['accesstoken']); //Access Token
			$accesstokensecret = decoder($row['accesstokensecret']); //Access Token Secret
		}
		if (!($apikey) || !($apisecret) || !($accesstoken) || !($accesstokensecret)){
			echo "<hata>\n\tAPI bilgilerinde boş değer var\n</hata>";
		} else {
			\Codebird\Codebird::setConsumerKey($apikey, $apisecret);
			$cb = \Codebird\Codebird::getInstance();
			$cb->setToken($accesstoken, $accesstokensecret);
			 
			$params = array(
			  'status' => $tweet
			);
			$reply = $cb->statuses_update($params);

			if($reply->httpstatus == '200'){
				echo "<basarili>\n\tTweet Atıldı\n</basarili>";
			} else {
				echo "<hata>\n\tTweet atılırken " . $reply->httpstatus . " kodlu hata oluştu\n</hata>";
			}
		}
	}
	
}
catch(PDOException $e)
{
	echo "<hata>\n\t" . $e->getMessage() . "\n</hata>";;
}
?>