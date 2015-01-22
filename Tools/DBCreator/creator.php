<?php
/*
SunucuCtrl Database Oluşturucu

Copyright (C) 2014 SunucuCtrl Projesi

Bu program özgür yazılımdır: Özgür Yazılım Vakfı tarafından yayımlanan GNU Genel Kamu Lisansı’nın sürüm 3 ya da (isteğinize bağlı olarak) daha sonraki sürümlerinin hükümleri altında yeniden dağıtabilir ve/veya değiştirebilirsiniz.

Bu program, yararlı olması umuduyla dağıtılmış olup, programın BİR TEMİNATI YOKTUR; TİCARETİNİN YAPILABİLİRLİĞİNE VE ÖZEL BİR AMAÇ İÇİN UYGUNLUĞUNA dair bir teminat da vermez. Ayrıntılar için GNU Genel Kamu Lisansı’na göz atınız.

Bu programla birlikte GNU Genel Kamu Lisansı’nın bir kopyasını elde etmiş olmanız gerekir. Eğer elinize ulaşmadıysa <http://www.gnu.org/licenses/> adresine bakınız.

Proje ile ilgili herşeye http//sunucuctrl.com/ adresinden ulaşılabilir.
*/
try
{
	$SMSAktif = $_REQUEST['SMSAktif'];
	$SMS_Owner = $_REQUEST['SMS_Owner'];
	$SMS_APIAdresi = $_REQUEST['SMS_APIAdresi'];
	$SMS_UserName = $_REQUEST['SMS_UserName'];
	$SMS_Password = $_REQUEST['SMS_Password'];
	$SMS_UserCode = $_REQUEST['SMS_UserCode'];
	$SMS_Originator = $_REQUEST['SMS_Originator'];
	$SMS_Recipients = $_REQUEST['SMS_Recipients'];
	$MailAktif = $_REQUEST['MailAktif'];
	$Mail_ServerAdresi = $_REQUEST['Mail_ServerAdresi'];
	$Mail_UserName = $_REQUEST['Mail_UserName'];
	$Mail_Password = $_REQUEST['Mail_Password'];
	$Mail_SSL = $_REQUEST['Mail_SSL'];
	$Mail_Port = $_REQUEST['Mail_Port'];
	$Mail_Sender = $_REQUEST['Mail_Sender'];
	$Mail_SenderName = $_REQUEST['Mail_SenderName'];
	$Mail_Recipients = $_REQUEST['Mail_Recipients'];
	$FTPAktif = $_REQUEST['FTPAktif'];
	$FTP_Mode = $_REQUEST['FTP_Mode'];
	$FTP_ServerAdresi = $_REQUEST['FTP_ServerAdresi'];
	$FTP_UserName = $_REQUEST['FTP_UserName'];
	$FTP_Password = $_REQUEST['FTP_Password'];
	$FTP_Port = $_REQUEST['FTP_Port'];
	$FTP_Directory = $_REQUEST['FTP_Directory'];
	$SkypeAktif = $_REQUEST['SkypeAktif'];
	$Skype_ServerAdresi = $_REQUEST['Skype_ServerAdresi'];
	$Skype_Recipients = $_REQUEST['Skype_Recipients'];
	$FaceAktif = $_REQUEST['FaceAktif'];
	$Face_Token = $_REQUEST['Face_Token'];
	$Face_Recipients = $_REQUEST['Face_Recipients'];
	$TweetAktif = $_REQUEST['TweetAktif'];
	$Tweet_Mode = $_REQUEST['Tweet_Mode'];
	$Tweet_ApiKey = $_REQUEST['Tweet_ApiKey'];
	$Tweet_ApiSecret = $_REQUEST['Tweet_ApiSecret'];
	$Tweet_AccessToken = $_REQUEST['Tweet_AccessToken'];
	$Tweet_AccessTokenSecret = $_REQUEST['Tweet_TokenSecret'];
	$Tweet_ServerUrl = $_REQUEST['Tweet_ServerUrl'];
	
	$dirname = "tmp/" . uniqid();
	mkdir($dirname);
	
	$filename = $dirname . "/configs.db";
	
	$conn = new PDO("sqlite:" . $filename)  or die("Veritabanı açılamadı"); 
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
	$cr_sql[1] = "CREATE TABLE 'tbl_sms' ('active' BOOLEAN, 'owner' TEXT, 'username' TEXT, 'password' TEXT, 'originator' TEXT, 'recipients' TEXT, 'usercode' TEXT, 'api_url' TEXT);";
	$sql_query = $conn->prepare($cr_sql[1]);
	$sql_query->execute() or die($conn->errorInfo());	
	
	$cr_sql[2] = "CREATE TABLE 'tbl_mail' ('active' BOOLEAN, 'server_url' TEXT, 'username' TEXT, 'password' TEXT, 'port' INTEGER, 'ssl_active' BOOLEAN, 'sender_email' TEXT, 'sender_name' TEXT, 'recipients' TEXT);";
	$sql_query = $conn->prepare($cr_sql[2]);
	$sql_query->execute() or die($conn->errorInfo());
	
	$cr_sql[3] = "CREATE TABLE 'tbl_ftptxt' ('active' BOOLEAN, 'mode' TEXT, 'username' TEXT, 'password' TEXT, 'server_url' TEXT, 'port' INTEGER, 'directory' TEXT);";
	$sql_query = $conn->prepare($cr_sql[3]);
	$sql_query->execute() or die($conn->errorInfo());	
	
	$cr_sql[4] = "CREATE TABLE 'tbl_skype' ('active' BOOLEAN, 'recipients' TEXT, 'boturl' TEXT);";
	$sql_query = $conn->prepare($cr_sql[4]);
	$sql_query->execute() or die($conn->errorInfo());
	
	$cr_sql[5] = "CREATE TABLE 'tbl_fbstatus' ('active' BOOLEAN, 'userid' TEXT, 'token' TEXT);";
	$sql_query = $conn->prepare($cr_sql[5]);
	$sql_query->execute() or die($conn->errorInfo());
	
	$cr_sql[6] = "CREATE TABLE 'tbl_tweet' ('active' BOOLEAN, 'mode' TEXT, 'apikey' TEXT, 'apisecret' TEXT, 'accesstoken' TEXT, 'accesstokensecret' TEXT, 'server_url' TEXT);";
	$sql_query = $conn->prepare($cr_sql[6]);
	$sql_query->execute() or die($conn->errorInfo());
	
	
	$sql[1] = "INSERT INTO tbl_sms (active, owner, username, password, originator, recipients, usercode, api_url) VALUES (:SMSAktif, :SMS_Owner, :SMS_UserName, :SMS_Password, :SMS_Originator, :SMS_Recipients, :SMS_UserCode, :SMS_APIAdresi);";
	$sql_query = $conn->prepare($sql[1]);
	$sql_query->bindParam(':SMSAktif', $SMSAktif, SQLITE3_INTEGER);
	$sql_query->bindParam(':SMS_Owner', $SMS_Owner, SQLITE3_TEXT);
	$sql_query->bindParam(':SMS_UserName', $SMS_UserName, SQLITE3_TEXT);
	$sql_query->bindParam(':SMS_Password', $SMS_Password, SQLITE3_TEXT);
	$sql_query->bindParam(':SMS_Originator', $SMS_Originator, SQLITE3_TEXT);
	$sql_query->bindParam(':SMS_Recipients', $SMS_Recipients, SQLITE3_TEXT);
	$sql_query->bindParam(':SMS_UserCode', $SMS_UserCode, SQLITE3_TEXT);
	$sql_query->bindParam(':SMS_APIAdresi', $SMS_APIAdresi, SQLITE3_TEXT);	
	$sql_query->execute() or die($conn->errorInfo());
	
	$sql[2] = "INSERT INTO tbl_mail (active, server_url, username, password, port, ssl_active, sender_email, sender_name, recipients) VALUES (:MailAktif, :Mail_ServerAdresi, :Mail_UserName, :Mail_Password, :Mail_Port, :Mail_SSL, :Mail_Sender, :Mail_SenderName, :Mail_Recipients);";
	$sql_query = $conn->prepare($sql[2]);
	$sql_query->bindParam(':MailAktif', $MailAktif, SQLITE3_INTEGER);
	$sql_query->bindParam(':Mail_ServerAdresi', $Mail_ServerAdresi, SQLITE3_TEXT);
	$sql_query->bindParam(':Mail_UserName', $Mail_UserName, SQLITE3_TEXT);
	$sql_query->bindParam(':Mail_Password', $Mail_Password, SQLITE3_TEXT);
	$sql_query->bindParam(':Mail_Port', $Mail_Port, SQLITE3_INTEGER);
	$sql_query->bindParam(':Mail_SSL', $Mail_SSL, SQLITE3_INTEGER);
	$sql_query->bindParam(':Mail_Sender', $Mail_Sender, SQLITE3_TEXT);
	$sql_query->bindParam(':Mail_SenderName', $Mail_SenderName, SQLITE3_TEXT);	
	$sql_query->bindParam(':Mail_Recipients', $Mail_Recipients, SQLITE3_TEXT);	
	$sql_query->execute() or die($conn->errorInfo());
	
	$sql[3] = "INSERT INTO tbl_ftptxt (active, mode, username, password, server_url, port, directory) VALUES (:FTPAktif, :FTP_Mode, :FTP_UserName, :FTP_Password, :FTP_Port, :FTP_ServerAdresi, :FTP_Directory);";
	$sql_query = $conn->prepare($sql[3]);
	$sql_query->bindParam(':FTPAktif', $FTPAktif, SQLITE3_INTEGER);
	$sql_query->bindParam(':FTP_Mode', $FTP_Mode, SQLITE3_TEXT);
	$sql_query->bindParam(':FTP_UserName', $FTP_UserName, SQLITE3_TEXT);
	$sql_query->bindParam(':FTP_Password', $FTP_Password, SQLITE3_TEXT);
	$sql_query->bindParam(':FTP_Port', $FTP_Port, SQLITE3_INTEGER);
	$sql_query->bindParam(':FTP_ServerAdresi', $FTP_ServerAdresi, SQLITE3_TEXT);
	$sql_query->bindParam(':FTP_Directory', $FTP_Directory, SQLITE3_TEXT);
	$sql_query->execute() or die($conn->errorInfo());

	$sql[4] = "INSERT INTO tbl_skype (active, recipients, boturl) VALUES (:SkypeAktif, :Skype_Recipients, :Skype_ServerAdresi);";
	$sql_query = $conn->prepare($sql[4]);
	$sql_query->bindParam(':SkypeAktif', $SkypeAktif, SQLITE3_INTEGER);
	$sql_query->bindParam(':Skype_Recipients', $Skype_Recipients, SQLITE3_TEXT);
	$sql_query->bindParam(':Skype_ServerAdresi', $Skype_ServerAdresi, SQLITE3_TEXT);
	$sql_query->execute() or die($conn->errorInfo());

	$sql[5] = "INSERT INTO tbl_fbstatus (active, userid, token) VALUES (:FaceAktif, :Face_Recipients, :Face_Token);";
	$sql_query = $conn->prepare($sql[5]);
	$sql_query->bindParam(':FaceAktif', $FaceAktif, SQLITE3_INTEGER);
	$sql_query->bindParam(':Face_Recipients', $Face_Recipients, SQLITE3_TEXT);	
	$sql_query->bindParam(':Face_Token', $Face_Token, SQLITE3_TEXT);
	$sql_query->execute() or die($conn->errorInfo());

	$sql[6] = "INSERT INTO tbl_tweet (active, mode, apikey, apisecret, accesstoken, accesstokensecret, server_url) VALUES (:TweetAktif, :Tweet_Mode, :Tweet_ApiKey, :Tweet_ApiSecret, :Tweet_AccessToken, :Tweet_AccessTokenSecret, :Tweet_ServerUrl);";
	$sql_query = $conn->prepare($sql[6]);
	$sql_query->bindParam(':TweetAktif', $TweetAktif, SQLITE3_INTEGER);
	$sql_query->bindParam(':Tweet_Mode', $Tweet_Mode, SQLITE3_TEXT);	
	$sql_query->bindParam(':Tweet_ApiKey', $Tweet_ApiKey, SQLITE3_TEXT);
	$sql_query->bindParam(':Tweet_ApiSecret', $Tweet_ApiSecret, SQLITE3_TEXT);
	$sql_query->bindParam(':Tweet_AccessToken', $Tweet_AccessToken, SQLITE3_TEXT);
	$sql_query->bindParam(':Tweet_AccessTokenSecret', $Tweet_AccessTokenSecret, SQLITE3_TEXT);
	$sql_query->bindParam(':Tweet_ServerUrl', $Tweet_ServerUrl, SQLITE3_TEXT);
	$sql_query->execute() or die($conn->errorInfo());
	
	if (file_exists($filename)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($filename));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filename));
		readfile($filename);
		exit;
	}
	
}
catch(PDOException $e)
{
	echo $e->getMessage();
}


?>