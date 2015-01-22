<?php
/*
Proje Adı: Sunucu Ctrl (ServCtrl)
Modül Adı: ServCtrl Remote Tweeter
Modül Tamamlanma Tarihi: 18.01.2015
Revizyon: 1

Copyright (C) 2014 SunucuCtrl Projesi

Bu program özgür yazılımdır: Özgür Yazılım Vakfı tarafından yayımlanan GNU Genel Kamu Lisansı’nın sürüm 3 ya da (isteğinize bağlı olarak) daha sonraki sürümlerinin hükümleri altında yeniden dağıtabilir ve/veya değiştirebilirsiniz.

Bu program, yararlı olması umuduyla dağıtılmış olup, programın BİR TEMİNATI YOKTUR; TİCARETİNİN YAPILABİLİRLİĞİNE VE ÖZEL BİR AMAÇ İÇİN UYGUNLUĞUNA dair bir teminat da vermez. Ayrıntılar için GNU Genel Kamu Lisansı’na göz atınız.

Bu programla birlikte GNU Genel Kamu Lisansı’nın bir kopyasını elde etmiş olmanız gerekir. Eğer elinize ulaşmadıysa <http://www.gnu.org/licenses/> adresine bakınız.

Proje ile ilgili herşeye http//sunucuctrl.com/ adresinden ulaşılabilir.
*/

header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

require_once('codebird.php');

if (!($_REQUEST["tweet"]) || !($_REQUEST["akey"]) || !($_REQUEST["asec"]) || !($_REQUEST["actoken"]) || !($_REQUEST["tsec"])) {
	echo "<hata>\n\tEksik bilgi gönderilmiş\n</hata>";
} else {

	\Codebird\Codebird::setConsumerKey($_REQUEST["akey"], $_REQUEST["asec"]);
	$cb = \Codebird\Codebird::getInstance();
	$cb->setToken($_REQUEST["actoken"], $_REQUEST["tsec"]);
	 
	$params = array(
	  'status' => $_REQUEST["tweet"]
	);
	$reply = $cb->statuses_update($params);

	if($reply->httpstatus == '200'){
		echo "<basarili>\n\tTweet Atıldı\n</basarili>";
	} else {
		echo "<hata>\n\tTweet atılırken " . $reply->httpstatus . " kodlu hata oluştu\n</hata>";
	}
}
?>