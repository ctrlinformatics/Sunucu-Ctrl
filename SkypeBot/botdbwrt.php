<?php
/*
Proje Adı: Sunucu Ctrl (ServCtrl)
Modül Adı: ServCtrl Skype Bot Database Writer
Modül Tamamlanma Tarihi: 08.09.2014
Revizyon: 1

Copyright (C) 2014 SunucuCtrl Projesi

Bu program özgür yazılımdır: Özgür Yazılım Vakfı tarafından yayımlanan GNU Genel Kamu Lisansı’nın sürüm 3 ya da (isteğinize bağlı olarak) daha sonraki sürümlerinin hükümleri altında yeniden dağıtabilir ve/veya değiştirebilirsiniz.

Bu program, yararlı olması umuduyla dağıtılmış olup, programın BİR TEMİNATI YOKTUR; TİCARETİNİN YAPILABİLİRLİĞİNE VE ÖZEL BİR AMAÇ İÇİN UYGUNLUĞUNA dair bir teminat da vermez. Ayrıntılar için GNU Genel Kamu Lisansı’na göz atınız.

Bu programla birlikte GNU Genel Kamu Lisansı’nın bir kopyasını elde etmiş olmanız gerekir. Eğer elinize ulaşmadıysa <http://www.gnu.org/licenses/> adresine bakınız.

Proje ile ilgili herşeye http//sunucuctrl.com/ adresinden ulaşılabilir.
*/

try
{
	header("Content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
	
	if (!($_REQUEST['alici']) &&  !($_REQUEST['mesaj'])) {
		echo "<hata>\n\tDeğerler belirtilmemiş\n</hata>";
	} else if (!($_REQUEST['alici'])) {
		echo "<hata>\n\tAlıcı belirtilmemiş\n</hata>";
	} else if (!($_REQUEST['mesaj'])) {
		echo "<hata>\n\tMesaj belirtilmemiş\n</hata>";
	} else {	
		$conn = new PDO("sqlite:/opt/sc_skypebot/Mesajlar.db")  or die("<hata>\n\tVeritabanı açılamadı\</hata>"); 
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		
		$sql = "INSERT INTO Mesajlar (alici, mesaj) VALUES (:alici, :mesaj);";
		$sql_query = $conn->prepare($sql);
		$sql_query->bindParam(':alici', $_REQUEST['alici'], SQLITE3_TEXT);
		$sql_query->bindParam(':mesaj', $_REQUEST['mesaj'], SQLITE3_TEXT);
		$sql_query->execute() or die("<hata>\n\t" . $conn->errorInfo() . "\n</hata>");
		
		echo "<basarili>\n\tID: " . $conn->lastInsertId() . "\n</basarili>";
		$conn = null;
	}
}
catch(PDOException $e)
{
	echo "<hata>\n\t" . $e->getMessage() . "\n</hata>";
}
?>