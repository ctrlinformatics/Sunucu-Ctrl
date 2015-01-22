<?php
/*
SunucuCtrl Şifreli Veri Oluşturucu / Çözücü

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

error_reporting(0);
?>
<!DOCTYPE html>
<html lang="tr">

	<head>
		<!-- Meta Bilgileri -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="SunucuCtrl Şifreli Veri Oluşturucu / Çözücü">
		<title>SunucuCtrl Şifreli Veri Oluşturucu / Çözücü</title>
	</head>
	
	<body>
		<?php
		function XOREncryption($InputString){
			
			$KeyPhrase = "486Gt2m+329376/XA4+c71G9YVc5z8/V199LzJO";
			
			for ($i = 0; $i < strlen($InputString); $i++){
				$rPos = $i % strlen($KeyPhrase);
				$r = ord($InputString[$i]) ^ ord($KeyPhrase[$rPos]) & 10;
				$InputString[$i] = chr($r);
			}
			
			return $InputString;
			
		}

		function encoder($hash) {
			$hsh = base64_encode(XOREncryption($hash));
			return $hsh;	
		}

		function decoder($hash) {
			$hsh = XOREncryption(base64_decode($hash));
			return $hsh;
		}

		if ($_REQUEST['islem'] == "encrypt") {
			echo encoder($_REQUEST['pss']);	
		} else if ($_REQUEST['islem'] == "decrypt") {
			echo decoder($_REQUEST['pss']);
		} else {
			echo "Eksik Bilgi";
		}

		?>
	</body>
	
</html>
