<?php
/*
SunucuCtrl CronTab Oluşturucu

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

$execr = ">>/dev/null 2>&1";

//SMS
//DAKİKA CRONU	
if ($_REQUEST["SMS_Min_Options"] == "*") {
	$sms_min_cron = "*";
} else if ($_REQUEST["SMS_Min_Options"] == "*/") {
	$sms_min_cron = "*/" . $_REQUEST["SMS_MinuteR"];
} else if ($_REQUEST["SMS_Min_Options"] == "Minute") {
	$sms_min_cron = $_REQUEST["SMS_Minute"];
} else if ($_REQUEST["SMS_Min_Options"] == "MinuteSL") {
	$sms_min_cron = $_REQUEST["SMS_MinuteAS"] . "-" . $_REQUEST["SMS_MinuteAL"]; 
} else if ($_REQUEST["SMS_Min_Options"] == "Minutes") {
	$sms_min_cron = "";
	foreach ($_REQUEST["SMS_Minutes"] as $SMS_SelMinutes)
	{
		$sms_min_cron .= $SMS_SelMinutes.",";
	}
	$sms_min_cron = substr($sms_min_cron, 0, -1);
} else {
	$sms_min_cron = "00";
}

//SAAT CRONU	
if ($_REQUEST["SMS_Hour_Options"] == "*") {
	$sms_hour_cron = "*";
} else if ($_REQUEST["SMS_Hour_Options"] == "*/") {
	$sms_hour_cron = "*/" . $_REQUEST["SMS_HourR"];
} else if ($_REQUEST["SMS_Hour_Options"] == "Hour") {
	$sms_hour_cron = $_REQUEST["SMS_Hour"];
} else if ($_REQUEST["SMS_Hour_Options"] == "HourSL") {
	$sms_hour_cron = $_REQUEST["SMS_HourAS"] . "-" . $_REQUEST["SMS_HourAL"]; 
} else if ($_REQUEST["SMS_Hour_Options"] == "Hours") {
	$sms_hour_cron = "";
	foreach ($_REQUEST["SMS_Hours"] as $SMS_SelHours)
	{
		$sms_hour_cron .= $SMS_SelHours.",";
	}
	$sms_hour_cron = substr($sms_hour_cron, 0, -1);
} else {
	$sms_hour_cron = "*/6";
}

//AYIN GÜNLERİ CRONU	
if ($_REQUEST["SMS_Day_Options"] == "*") {
	$sms_day_cron = "*";
} else if ($_REQUEST["SMS_Day_Options"] == "*/") {
	$sms_day_cron = "*/" . $_REQUEST["SMS_DayR"];
} else if ($_REQUEST["SMS_Day_Options"] == "Day") {
	$sms_day_cron = $_REQUEST["SMS_Day"];
} else if ($_REQUEST["SMS_Day_Options"] == "DaySL") {
	$sms_day_cron = $_REQUEST["SMS_DayAS"] . "-" . $_REQUEST["SMS_DayAL"]; 
} else if ($_REQUEST["SMS_Day_Options"] == "Days") {
	$sms_day_cron = "";
	foreach ($_REQUEST["SMS_Days"] as $SMS_SelDays)
	{
		$sms_day_cron .= $SMS_SelDays.",";
	}
	$sms_day_cron = substr($sms_day_cron, 0, -1);
} else {
	$sms_day_cron = "*";
}

//AY CRONU	
if ($_REQUEST["SMS_Month_Options"] == "*") {
	$sms_month_cron = "*";
} else if ($_REQUEST["SMS_Month_Options"] == "*/") {
	$sms_month_cron = "*/" . $_REQUEST["SMS_MonthR"];
} else if ($_REQUEST["SMS_Month_Options"] == "Month") {
	$sms_month_cron = $_REQUEST["SMS_Month"];
} else if ($_REQUEST["SMS_Month_Options"] == "MonthSL") {
	$sms_month_cron = $_REQUEST["SMS_MonthAS"] . "-" . $_REQUEST["SMS_MonthAL"]; 
} else if ($_REQUEST["SMS_Month_Options"] == "Months") {
	$sms_month_cron = "";
	foreach ($_REQUEST["SMS_Months"] as $SMS_SelMonths)
	{
		$sms_month_cron .= $SMS_SelMonths.",";
	}
	$sms_month_cron = substr($sms_month_cron, 0, -1);
} else {
	$sms_month_cron = "*";
}

//HAFTANIN GÜNLERİ CRONU	
if ($_REQUEST["SMS_WDay_Options"] == "*") {
	$sms_wday_cron = "*";
} else if ($_REQUEST["SMS_WDay_Options"] == "1-5") {
	$sms_wday_cron = "1-5";
} else if ($_REQUEST["SMS_WDay_Options"] == "6,7") {
	$sms_wday_cron = "6,7";
} else if ($_REQUEST["SMS_WDay_Options"] == "*/") {
	$sms_wday_cron = "*/" . $_REQUEST["SMS_WDayR"];
} else if ($_REQUEST["SMS_WDay_Options"] == "WDay") {
	$sms_wday_cron = $_REQUEST["SMS_WDay"];
} else if ($_REQUEST["SMS_WDay_Options"] == "WDaySL") {
	$sms_wday_cron = $_REQUEST["SMS_WDayAS"] . "-" . $_REQUEST["SMS_WDayAL"]; 
} else if ($_REQUEST["SMS_WDay_Options"] == "WDays") {
	$sms_wday_cron = "";
	foreach ($_REQUEST["SMS_WDays"] as $SMS_SelWDays)
	{
		$sms_wday_cron .= $SMS_SelWDays.",";
	}
	$sms_wday_cron = substr($sms_wday_cron, 0, -1);
} else {
	$sms_wday_cron = "*";
}

//SMS TOTAL CRON
$sms_cron = $sms_min_cron . " " . $sms_hour_cron . " " . $sms_day_cron . " " . $sms_month_cron . " " . $sms_wday_cron . " /etc/sunucuctrl/scsmsctrl " . $execr;

//MAIL
//DAKİKA CRONU	
if ($_REQUEST["MAIL_Min_Options"] == "*") {
	$mail_min_cron = "*";
} else if ($_REQUEST["MAIL_Min_Options"] == "*/") {
	$mail_min_cron = "*/" . $_REQUEST["MAIL_MinuteR"];
} else if ($_REQUEST["MAIL_Min_Options"] == "Minute") {
	$mail_min_cron = $_REQUEST["MAIL_Minute"];
} else if ($_REQUEST["MAIL_Min_Options"] == "MinuteSL") {
	$mail_min_cron = $_REQUEST["MAIL_MinuteAS"] . "-" . $_REQUEST["MAIL_MinuteAL"]; 
} else if ($_REQUEST["MAIL_Min_Options"] == "Minutes") {
	$mail_min_cron = "";
	foreach ($_REQUEST["MAIL_Minutes"] as $MAIL_SelMinutes)
	{
		$mail_min_cron .= $MAIL_SelMinutes.",";
	}
	$mail_min_cron = substr($mail_min_cron, 0, -1);
} else {
	$mail_min_cron = "00";
}

//SAAT CRONU	
if ($_REQUEST["MAIL_Hour_Options"] == "*") {
	$mail_hour_cron = "*";
} else if ($_REQUEST["MAIL_Hour_Options"] == "*/") {
	$mail_hour_cron = "*/" . $_REQUEST["MAIL_HourR"];
} else if ($_REQUEST["MAIL_Hour_Options"] == "Hour") {
	$mail_hour_cron = $_REQUEST["MAIL_Hour"];
} else if ($_REQUEST["MAIL_Hour_Options"] == "HourSL") {
	$mail_hour_cron = $_REQUEST["MAIL_HourAS"] . "-" . $_REQUEST["MAIL_HourAL"]; 
} else if ($_REQUEST["MAIL_Hour_Options"] == "Hours") {
	$mail_hour_cron = "";
	foreach ($_REQUEST["MAIL_Hours"] as $MAIL_SelHours)
	{
		$mail_hour_cron .= $MAIL_SelHours.",";
	}
	$mail_hour_cron = substr($mail_hour_cron, 0, -1);
} else {
	$mail_hour_cron = "*/4";
}

//AYIN GÜNLERİ CRONU	
if ($_REQUEST["MAIL_Day_Options"] == "*") {
	$mail_day_cron = "*";
} else if ($_REQUEST["MAIL_Day_Options"] == "*/") {
	$mail_day_cron = "*/" . $_REQUEST["MAIL_DayR"];
} else if ($_REQUEST["MAIL_Day_Options"] == "Day") {
	$mail_day_cron = $_REQUEST["MAIL_Day"];
} else if ($_REQUEST["MAIL_Day_Options"] == "DaySL") {
	$mail_day_cron = $_REQUEST["MAIL_DayAS"] . "-" . $_REQUEST["MAIL_DayAL"]; 
} else if ($_REQUEST["MAIL_Day_Options"] == "Days") {
	$mail_day_cron = "";
	foreach ($_REQUEST["MAIL_Days"] as $MAIL_SelDays)
	{
		$mail_day_cron .= $MAIL_SelDays.",";
	}
	$mail_day_cron = substr($mail_day_cron, 0, -1);
} else {
	$mail_day_cron = "*";
}

//AY CRONU	
if ($_REQUEST["MAIL_Month_Options"] == "*") {
	$mail_month_cron = "*";
} else if ($_REQUEST["MAIL_Month_Options"] == "*/") {
	$mail_month_cron = "*/" . $_REQUEST["MAIL_MonthR"];
} else if ($_REQUEST["MAIL_Month_Options"] == "Month") {
	$mail_month_cron = $_REQUEST["MAIL_Month"];
} else if ($_REQUEST["MAIL_Month_Options"] == "MonthSL") {
	$mail_month_cron = $_REQUEST["MAIL_MonthAS"] . "-" . $_REQUEST["MAIL_MonthAL"]; 
} else if ($_REQUEST["MAIL_Month_Options"] == "Months") {
	$mail_month_cron = "";
	foreach ($_REQUEST["MAIL_Months"] as $MAIL_SelMonths)
	{
		$mail_month_cron .= $MAIL_SelMonths.",";
	}
	$mail_month_cron = substr($mail_month_cron, 0, -1);
} else {
	$mail_month_cron = "*";
}

//HAFTANIN GÜNLERİ CRONU	
if ($_REQUEST["MAIL_WDay_Options"] == "*") {
	$mail_wday_cron = "*";
} else if ($_REQUEST["MAIL_WDay_Options"] == "1-5") {
	$mail_wday_cron = "1-5";
} else if ($_REQUEST["MAIL_WDay_Options"] == "6,7") {
	$mail_wday_cron = "6,7";
} else if ($_REQUEST["MAIL_WDay_Options"] == "*/") {
	$mail_wday_cron = "*/" . $_REQUEST["MAIL_WDayR"];
} else if ($_REQUEST["MAIL_WDay_Options"] == "WDay") {
	$mail_wday_cron = $_REQUEST["MAIL_WDay"];
} else if ($_REQUEST["MAIL_WDay_Options"] == "WDaySL") {
	$mail_wday_cron = $_REQUEST["MAIL_WDayAS"] . "-" . $_REQUEST["MAIL_WDayAL"]; 
} else if ($_REQUEST["MAIL_WDay_Options"] == "WDays") {
	$mail_wday_cron = "";
	foreach ($_REQUEST["MAIL_WDays"] as $MAIL_SelWDays)
	{
		$mail_wday_cron .= $MAIL_SelWDays.",";
	}
	$mail_wday_cron = substr($mail_wday_cron, 0, -1);
} else {
	$mail_wday_cron = "*";
}

//MAIL TOTAL CRON
$mail_cron = $mail_min_cron . " " . $mail_hour_cron . " " . $mail_day_cron . " " . $mail_month_cron . " " . $mail_wday_cron . " /etc/sunucuctrl/scmailctrl " . $execr;


//FTP
//DAKİKA CRONU	
if ($_REQUEST["FTP_Min_Options"] == "*") {
	$ftp_min_cron = "*";
} else if ($_REQUEST["FTP_Min_Options"] == "*/") {
	$ftp_min_cron = "*/" . $_REQUEST["FTP_MinuteR"];
} else if ($_REQUEST["FTP_Min_Options"] == "Minute") {
	$ftp_min_cron = $_REQUEST["FTP_Minute"];
} else if ($_REQUEST["FTP_Min_Options"] == "MinuteSL") {
	$ftp_min_cron = $_REQUEST["FTP_MinuteAS"] . "-" . $_REQUEST["FTP_MinuteAL"]; 
} else if ($_REQUEST["FTP_Min_Options"] == "Minutes") {
	$ftp_min_cron = "";
	foreach ($_REQUEST["FTP_Minutes"] as $FTP_SelMinutes)
	{
		$ftp_min_cron .= $FTP_SelMinutes.",";
	}
	$ftp_min_cron = substr($ftp_min_cron, 0, -1);
} else {
	$ftp_min_cron = "00";
}

//SAAT CRONU	
if ($_REQUEST["FTP_Hour_Options"] == "*") {
	$ftp_hour_cron = "*";
} else if ($_REQUEST["FTP_Hour_Options"] == "*/") {
	$ftp_hour_cron = "*/" . $_REQUEST["FTP_HourR"];
} else if ($_REQUEST["FTP_Hour_Options"] == "Hour") {
	$ftp_hour_cron = $_REQUEST["FTP_Hour"];
} else if ($_REQUEST["FTP_Hour_Options"] == "HourSL") {
	$ftp_hour_cron = $_REQUEST["FTP_HourAS"] . "-" . $_REQUEST["FTP_HourAL"]; 
} else if ($_REQUEST["FTP_Hour_Options"] == "Hours") {
	$ftp_hour_cron = "";
	foreach ($_REQUEST["FTP_Hours"] as $FTP_SelHours)
	{
		$ftp_hour_cron .= $FTP_SelHours.",";
	}
	$ftp_hour_cron = substr($ftp_hour_cron, 0, -1);
} else {
	$ftp_hour_cron = "*/2";
}

//AYIN GÜNLERİ CRONU	
if ($_REQUEST["FTP_Day_Options"] == "*") {
	$ftp_day_cron = "*";
} else if ($_REQUEST["FTP_Day_Options"] == "*/") {
	$ftp_day_cron = "*/" . $_REQUEST["FTP_DayR"];
} else if ($_REQUEST["FTP_Day_Options"] == "Day") {
	$ftp_day_cron = $_REQUEST["FTP_Day"];
} else if ($_REQUEST["FTP_Day_Options"] == "DaySL") {
	$ftp_day_cron = $_REQUEST["FTP_DayAS"] . "-" . $_REQUEST["FTP_DayAL"]; 
} else if ($_REQUEST["FTP_Day_Options"] == "Days") {
	$ftp_day_cron = "";
	foreach ($_REQUEST["FTP_Days"] as $FTP_SelDays)
	{
		$ftp_day_cron .= $FTP_SelDays.",";
	}
	$ftp_day_cron = substr($ftp_day_cron, 0, -1);
} else {
	$ftp_day_cron = "*";
}

//AY CRONU	
if ($_REQUEST["FTP_Month_Options"] == "*") {
	$ftp_month_cron = "*";
} else if ($_REQUEST["FTP_Month_Options"] == "*/") {
	$ftp_month_cron = "*/" . $_REQUEST["FTP_MonthR"];
} else if ($_REQUEST["FTP_Month_Options"] == "Month") {
	$ftp_month_cron = $_REQUEST["FTP_Month"];
} else if ($_REQUEST["FTP_Month_Options"] == "MonthSL") {
	$ftp_month_cron = $_REQUEST["FTP_MonthAS"] . "-" . $_REQUEST["FTP_MonthAL"]; 
} else if ($_REQUEST["FTP_Month_Options"] == "Months") {
	$ftp_month_cron = "";
	foreach ($_REQUEST["FTP_Months"] as $FTP_SelMonths)
	{
		$ftp_month_cron .= $FTP_SelMonths.",";
	}
	$ftp_month_cron = substr($ftp_month_cron, 0, -1);
} else {
	$ftp_month_cron = "*";
}

//HAFTANIN GÜNLERİ CRONU	
if ($_REQUEST["FTP_WDay_Options"] == "*") {
	$ftp_wday_cron = "*";
} else if ($_REQUEST["FTP_WDay_Options"] == "1-5") {
	$ftp_wday_cron = "1-5";
} else if ($_REQUEST["FTP_WDay_Options"] == "6,7") {
	$ftp_wday_cron = "6,7";
} else if ($_REQUEST["FTP_WDay_Options"] == "*/") {
	$ftp_wday_cron = "*/" . $_REQUEST["FTP_WDayR"];
} else if ($_REQUEST["FTP_WDay_Options"] == "WDay") {
	$ftp_wday_cron = $_REQUEST["FTP_WDay"];
} else if ($_REQUEST["FTP_WDay_Options"] == "WDaySL") {
	$ftp_wday_cron = $_REQUEST["FTP_WDayAS"] . "-" . $_REQUEST["FTP_WDayAL"]; 
} else if ($_REQUEST["FTP_WDay_Options"] == "WDays") {
	$ftp_wday_cron = "";
	foreach ($_REQUEST["FTP_WDays"] as $FTP_SelWDays)
	{
		$ftp_wday_cron .= $FTP_SelWDays.",";
	}
	$ftp_wday_cron = substr($ftp_wday_cron, 0, -1);
} else {
	$ftp_wday_cron = "*";
}

//FTP TOTAL CRON
$ftp_cron = $ftp_min_cron . " " . $ftp_hour_cron . " " . $ftp_day_cron . " " . $ftp_month_cron . " " . $ftp_wday_cron . " /etc/sunucuctrl/scftptxtctrl " . $execr;

//SKYPE
//DAKİKA CRONU	
if ($_REQUEST["SKYPE_Min_Options"] == "*") {
	$skype_min_cron = "*";
} else if ($_REQUEST["SKYPE_Min_Options"] == "*/") {
	$skype_min_cron = "*/" . $_REQUEST["SKYPE_MinuteR"];
} else if ($_REQUEST["SKYPE_Min_Options"] == "Minute") {
	$skype_min_cron = $_REQUEST["SKYPE_Minute"];
} else if ($_REQUEST["SKYPE_Min_Options"] == "MinuteSL") {
	$skype_min_cron = $_REQUEST["SKYPE_MinuteAS"] . "-" . $_REQUEST["SKYPE_MinuteAL"]; 
} else if ($_REQUEST["SKYPE_Min_Options"] == "Minutes") {
	$skype_min_cron = "";
	foreach ($_REQUEST["SKYPE_Minutes"] as $SKYPE_SelMinutes)
	{
		$skype_min_cron .= $SKYPE_SelMinutes.",";
	}
	$skype_min_cron = substr($skype_min_cron, 0, -1);
} else {
	$skype_min_cron = "*/10";
}

//SAAT CRONU	
if ($_REQUEST["SKYPE_Hour_Options"] == "*") {
	$skype_hour_cron = "*";
} else if ($_REQUEST["SKYPE_Hour_Options"] == "*/") {
	$skype_hour_cron = "*/" . $_REQUEST["SKYPE_HourR"];
} else if ($_REQUEST["SKYPE_Hour_Options"] == "Hour") {
	$skype_hour_cron = $_REQUEST["SKYPE_Hour"];
} else if ($_REQUEST["SKYPE_Hour_Options"] == "HourSL") {
	$skype_hour_cron = $_REQUEST["SKYPE_HourAS"] . "-" . $_REQUEST["SKYPE_HourAL"]; 
} else if ($_REQUEST["SKYPE_Hour_Options"] == "Hours") {
	$skype_hour_cron = "";
	foreach ($_REQUEST["SKYPE_Hours"] as $SKYPE_SelHours)
	{
		$skype_hour_cron .= $SKYPE_SelHours.",";
	}
	$skype_hour_cron = substr($skype_hour_cron, 0, -1);
} else {
	$skype_hour_cron = "*";
}

//AYIN GÜNLERİ CRONU	
if ($_REQUEST["SKYPE_Day_Options"] == "*") {
	$skype_day_cron = "*";
} else if ($_REQUEST["SKYPE_Day_Options"] == "*/") {
	$skype_day_cron = "*/" . $_REQUEST["SKYPE_DayR"];
} else if ($_REQUEST["SKYPE_Day_Options"] == "Day") {
	$skype_day_cron = $_REQUEST["SKYPE_Day"];
} else if ($_REQUEST["SKYPE_Day_Options"] == "DaySL") {
	$skype_day_cron = $_REQUEST["SKYPE_DayAS"] . "-" . $_REQUEST["SKYPE_DayAL"]; 
} else if ($_REQUEST["SKYPE_Day_Options"] == "Days") {
	$skype_day_cron = "";
	foreach ($_REQUEST["SKYPE_Days"] as $SKYPE_SelDays)
	{
		$skype_day_cron .= $SKYPE_SelDays.",";
	}
	$skype_day_cron = substr($skype_day_cron, 0, -1);
} else {
	$skype_day_cron = "*";
}

//AY CRONU	
if ($_REQUEST["SKYPE_Month_Options"] == "*") {
	$skype_month_cron = "*";
} else if ($_REQUEST["SKYPE_Month_Options"] == "*/") {
	$skype_month_cron = "*/" . $_REQUEST["SKYPE_MonthR"];
} else if ($_REQUEST["SKYPE_Month_Options"] == "Month") {
	$skype_month_cron = $_REQUEST["SKYPE_Month"];
} else if ($_REQUEST["SKYPE_Month_Options"] == "MonthSL") {
	$skype_month_cron = $_REQUEST["SKYPE_MonthAS"] . "-" . $_REQUEST["SKYPE_MonthAL"]; 
} else if ($_REQUEST["SKYPE_Month_Options"] == "Months") {
	$skype_month_cron = "";
	foreach ($_REQUEST["SKYPE_Months"] as $SKYPE_SelMonths)
	{
		$skype_month_cron .= $SKYPE_SelMonths.",";
	}
	$skype_month_cron = substr($skype_month_cron, 0, -1);
} else {
	$skype_month_cron = "*";
}

//HAFTANIN GÜNLERİ CRONU	
if ($_REQUEST["SKYPE_WDay_Options"] == "*") {
	$skype_wday_cron = "*";
} else if ($_REQUEST["SKYPE_WDay_Options"] == "1-5") {
	$skype_wday_cron = "1-5";
} else if ($_REQUEST["SKYPE_WDay_Options"] == "6,7") {
	$skype_wday_cron = "6,7";
} else if ($_REQUEST["SKYPE_WDay_Options"] == "*/") {
	$skype_wday_cron = "*/" . $_REQUEST["SKYPE_WDayR"];
} else if ($_REQUEST["SKYPE_WDay_Options"] == "WDay") {
	$skype_wday_cron = $_REQUEST["SKYPE_WDay"];
} else if ($_REQUEST["SKYPE_WDay_Options"] == "WDaySL") {
	$skype_wday_cron = $_REQUEST["SKYPE_WDayAS"] . "-" . $_REQUEST["SKYPE_WDayAL"]; 
} else if ($_REQUEST["SKYPE_WDay_Options"] == "WDays") {
	$skype_wday_cron = "";
	foreach ($_REQUEST["SKYPE_WDays"] as $SKYPE_SelWDays)
	{
		$skype_wday_cron .= $SKYPE_SelWDays.",";
	}
	$skype_wday_cron = substr($skype_wday_cron, 0, -1);
} else {
	$skype_wday_cron = "*";
}

//SKYPE TOTAL CRON
$skype_cron = $skype_min_cron . " " . $skype_hour_cron . " " . $skype_day_cron . " " . $skype_month_cron . " " . $skype_wday_cron . " /etc/sunucuctrl/scskypectrl " . $execr;


//FACE
//DAKİKA CRONU	
if ($_REQUEST["FACE_Min_Options"] == "*") {
	$face_min_cron = "*";
} else if ($_REQUEST["FACE_Min_Options"] == "*/") {
	$face_min_cron = "*/" . $_REQUEST["FACE_MinuteR"];
} else if ($_REQUEST["FACE_Min_Options"] == "Minute") {
	$face_min_cron = $_REQUEST["FACE_Minute"];
} else if ($_REQUEST["FACE_Min_Options"] == "MinuteSL") {
	$face_min_cron = $_REQUEST["FACE_MinuteAS"] . "-" . $_REQUEST["FACE_MinuteAL"]; 
} else if ($_REQUEST["FACE_Min_Options"] == "Minutes") {
	$face_min_cron = "";
	foreach ($_REQUEST["FACE_Minutes"] as $FACE_SelMinutes)
	{
		$face_min_cron .= $FACE_SelMinutes.",";
	}
	$face_min_cron = substr($face_min_cron, 0, -1);
} else {
	$face_min_cron = "00";
}

//SAAT CRONU	
if ($_REQUEST["FACE_Hour_Options"] == "*") {
	$face_hour_cron = "*";
} else if ($_REQUEST["FACE_Hour_Options"] == "*/") {
	$face_hour_cron = "*/" . $_REQUEST["FACE_HourR"];
} else if ($_REQUEST["FACE_Hour_Options"] == "Hour") {
	$face_hour_cron = $_REQUEST["FACE_Hour"];
} else if ($_REQUEST["FACE_Hour_Options"] == "HourSL") {
	$face_hour_cron = $_REQUEST["FACE_HourAS"] . "-" . $_REQUEST["FACE_HourAL"]; 
} else if ($_REQUEST["FACE_Hour_Options"] == "Hours") {
	$face_hour_cron = "";
	foreach ($_REQUEST["FACE_Hours"] as $FACE_SelHours)
	{
		$face_hour_cron .= $FACE_SelHours.",";
	}
	$face_hour_cron = substr($face_hour_cron, 0, -1);
} else {
	$face_hour_cron = "*/6";
}

//AYIN GÜNLERİ CRONU	
if ($_REQUEST["FACE_Day_Options"] == "*") {
	$face_day_cron = "*";
} else if ($_REQUEST["FACE_Day_Options"] == "*/") {
	$face_day_cron = "*/" . $_REQUEST["FACE_DayR"];
} else if ($_REQUEST["FACE_Day_Options"] == "Day") {
	$face_day_cron = $_REQUEST["FACE_Day"];
} else if ($_REQUEST["FACE_Day_Options"] == "DaySL") {
	$face_day_cron = $_REQUEST["FACE_DayAS"] . "-" . $_REQUEST["FACE_DayAL"]; 
} else if ($_REQUEST["FACE_Day_Options"] == "Days") {
	$face_day_cron = "";
	foreach ($_REQUEST["FACE_Days"] as $FACE_SelDays)
	{
		$face_day_cron .= $FACE_SelDays.",";
	}
	$face_day_cron = substr($face_day_cron, 0, -1);
} else {
	$face_day_cron = "*";
}

//AY CRONU	
if ($_REQUEST["FACE_Month_Options"] == "*") {
	$face_month_cron = "*";
} else if ($_REQUEST["FACE_Month_Options"] == "*/") {
	$face_month_cron = "*/" . $_REQUEST["FACE_MonthR"];
} else if ($_REQUEST["FACE_Month_Options"] == "Month") {
	$face_month_cron = $_REQUEST["FACE_Month"];
} else if ($_REQUEST["FACE_Month_Options"] == "MonthSL") {
	$face_month_cron = $_REQUEST["FACE_MonthAS"] . "-" . $_REQUEST["FACE_MonthAL"]; 
} else if ($_REQUEST["FACE_Month_Options"] == "Months") {
	$face_month_cron = "";
	foreach ($_REQUEST["FACE_Months"] as $FACE_SelMonths)
	{
		$face_month_cron .= $FACE_SelMonths.",";
	}
	$face_month_cron = substr($face_month_cron, 0, -1);
} else {
	$face_month_cron = "*";
}

//HAFTANIN GÜNLERİ CRONU	
if ($_REQUEST["FACE_WDay_Options"] == "*") {
	$face_wday_cron = "*";
} else if ($_REQUEST["FACE_WDay_Options"] == "1-5") {
	$face_wday_cron = "1-5";
} else if ($_REQUEST["FACE_WDay_Options"] == "6,7") {
	$face_wday_cron = "6,7";
} else if ($_REQUEST["FACE_WDay_Options"] == "*/") {
	$face_wday_cron = "*/" . $_REQUEST["FACE_WDayR"];
} else if ($_REQUEST["FACE_WDay_Options"] == "WDay") {
	$face_wday_cron = $_REQUEST["FACE_WDay"];
} else if ($_REQUEST["FACE_WDay_Options"] == "WDaySL") {
	$face_wday_cron = $_REQUEST["FACE_WDayAS"] . "-" . $_REQUEST["FACE_WDayAL"]; 
} else if ($_REQUEST["FACE_WDay_Options"] == "WDays") {
	$face_wday_cron = "";
	foreach ($_REQUEST["FACE_WDays"] as $FACE_SelWDays)
	{
		$face_wday_cron .= $FACE_SelWDays.",";
	}
	$face_wday_cron = substr($face_wday_cron, 0, -1);
} else {
	$face_wday_cron = "*";
}

//FACE TOTAL CRON
$face_cron = $face_min_cron . " " . $face_hour_cron . " " . $face_day_cron . " " . $face_month_cron . " " . $face_wday_cron . " /etc/sunucuctrl/scfbctrl " . $execr;


//TWEET
//DAKİKA CRONU	
if ($_REQUEST["TWEET_Min_Options"] == "*") {
	$tweet_min_cron = "*";
} else if ($_REQUEST["TWEET_Min_Options"] == "*/") {
	$tweet_min_cron = "*/" . $_REQUEST["TWEET_MinuteR"];
} else if ($_REQUEST["TWEET_Min_Options"] == "Minute") {
	$tweet_min_cron = $_REQUEST["TWEET_Minute"];
} else if ($_REQUEST["TWEET_Min_Options"] == "MinuteSL") {
	$tweet_min_cron = $_REQUEST["TWEET_MinuteAS"] . "-" . $_REQUEST["TWEET_MinuteAL"]; 
} else if ($_REQUEST["TWEET_Min_Options"] == "Minutes") {
	$tweet_min_cron = "";
	foreach ($_REQUEST["TWEET_Minutes"] as $TWEET_SelMinutes)
	{
		$tweet_min_cron .= $TWEET_SelMinutes.",";
	}
	$tweet_min_cron = substr($tweet_min_cron, 0, -1);
} else {
	$tweet_min_cron = "*/5";
}

//SAAT CRONU	
if ($_REQUEST["TWEET_Hour_Options"] == "*") {
	$tweet_hour_cron = "*";
} else if ($_REQUEST["TWEET_Hour_Options"] == "*/") {
	$tweet_hour_cron = "*/" . $_REQUEST["TWEET_HourR"];
} else if ($_REQUEST["TWEET_Hour_Options"] == "Hour") {
	$tweet_hour_cron = $_REQUEST["TWEET_Hour"];
} else if ($_REQUEST["TWEET_Hour_Options"] == "HourSL") {
	$tweet_hour_cron = $_REQUEST["TWEET_HourAS"] . "-" . $_REQUEST["TWEET_HourAL"]; 
} else if ($_REQUEST["TWEET_Hour_Options"] == "Hours") {
	$tweet_hour_cron = "";
	foreach ($_REQUEST["TWEET_Hours"] as $TWEET_SelHours)
	{
		$tweet_hour_cron .= $TWEET_SelHours.",";
	}
	$tweet_hour_cron = substr($tweet_hour_cron, 0, -1);
} else {
	$tweet_hour_cron = "*";
}

//AYIN GÜNLERİ CRONU	
if ($_REQUEST["TWEET_Day_Options"] == "*") {
	$tweet_day_cron = "*";
} else if ($_REQUEST["TWEET_Day_Options"] == "*/") {
	$tweet_day_cron = "*/" . $_REQUEST["TWEET_DayR"];
} else if ($_REQUEST["TWEET_Day_Options"] == "Day") {
	$tweet_day_cron = $_REQUEST["TWEET_Day"];
} else if ($_REQUEST["TWEET_Day_Options"] == "DaySL") {
	$tweet_day_cron = $_REQUEST["TWEET_DayAS"] . "-" . $_REQUEST["TWEET_DayAL"]; 
} else if ($_REQUEST["TWEET_Day_Options"] == "Days") {
	$tweet_day_cron = "";
	foreach ($_REQUEST["TWEET_Days"] as $TWEET_SelDays)
	{
		$tweet_day_cron .= $TWEET_SelDays.",";
	}
	$tweet_day_cron = substr($tweet_day_cron, 0, -1);
} else {
	$tweet_day_cron = "*";
}

//AY CRONU	
if ($_REQUEST["TWEET_Month_Options"] == "*") {
	$tweet_month_cron = "*";
} else if ($_REQUEST["TWEET_Month_Options"] == "*/") {
	$tweet_month_cron = "*/" . $_REQUEST["TWEET_MonthR"];
} else if ($_REQUEST["TWEET_Month_Options"] == "Month") {
	$tweet_month_cron = $_REQUEST["TWEET_Month"];
} else if ($_REQUEST["TWEET_Month_Options"] == "MonthSL") {
	$tweet_month_cron = $_REQUEST["TWEET_MonthAS"] . "-" . $_REQUEST["TWEET_MonthAL"]; 
} else if ($_REQUEST["TWEET_Month_Options"] == "Months") {
	$tweet_month_cron = "";
	foreach ($_REQUEST["TWEET_Months"] as $TWEET_SelMonths)
	{
		$tweet_month_cron .= $TWEET_SelMonths.",";
	}
	$tweet_month_cron = substr($tweet_month_cron, 0, -1);
} else {
	$tweet_month_cron = "*";
}

//HAFTANIN GÜNLERİ CRONU	
if ($_REQUEST["TWEET_WDay_Options"] == "*") {
	$tweet_wday_cron = "*";
} else if ($_REQUEST["TWEET_WDay_Options"] == "1-5") {
	$tweet_wday_cron = "1-5";
} else if ($_REQUEST["TWEET_WDay_Options"] == "6,7") {
	$tweet_wday_cron = "6,7";
} else if ($_REQUEST["TWEET_WDay_Options"] == "*/") {
	$tweet_wday_cron = "*/" . $_REQUEST["TWEET_WDayR"];
} else if ($_REQUEST["TWEET_WDay_Options"] == "WDay") {
	$tweet_wday_cron = $_REQUEST["TWEET_WDay"];
} else if ($_REQUEST["TWEET_WDay_Options"] == "WDaySL") {
	$tweet_wday_cron = $_REQUEST["TWEET_WDayAS"] . "-" . $_REQUEST["TWEET_WDayAL"]; 
} else if ($_REQUEST["TWEET_WDay_Options"] == "WDays") {
	$tweet_wday_cron = "";
	foreach ($_REQUEST["TWEET_WDays"] as $TWEET_SelWDays)
	{
		$tweet_wday_cron .= $TWEET_SelWDays.",";
	}
	$tweet_wday_cron = substr($tweet_wday_cron, 0, -1);
} else {
	$tweet_wday_cron = "*";
}

//TWEET TOTAL CRON
$tweet_cron = $tweet_min_cron . " " . $tweet_hour_cron . " " . $tweet_day_cron . " " . $tweet_month_cron . " " . $tweet_wday_cron . " /etc/sunucuctrl/sctwctrl " . $execr;


//DOSYA OLUŞTUR ve İNDİRT
$cron_data = $sms_cron . "\n" . $mail_cron . "\n" . $ftp_cron . "\n" . $skype_cron . "\n" . $face_cron . "\n" . $tweet_cron;
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=cron.txt');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' .strlen($cron_data));
echo $cron_data;
exit;

?>