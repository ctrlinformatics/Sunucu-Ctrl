/*
SunucuCtrl Database Oluşturucu Fonksiyon Dosyası

Copyright (C) 2014 SunucuCtrl Projesi

Bu program özgür yazılımdır: Özgür Yazılım Vakfı tarafından yayımlanan GNU Genel Kamu Lisansı’nın sürüm 3 ya da (isteğinize bağlı olarak) daha sonraki sürümlerinin hükümleri altında yeniden dağıtabilir ve/veya değiştirebilirsiniz.

Bu program, yararlı olması umuduyla dağıtılmış olup, programın BİR TEMİNATI YOKTUR; TİCARETİNİN YAPILABİLİRLİĞİNE VE ÖZEL BİR AMAÇ İÇİN UYGUNLUĞUNA dair bir teminat da vermez. Ayrıntılar için GNU Genel Kamu Lisansı’na göz atınız.

Bu programla birlikte GNU Genel Kamu Lisansı’nın bir kopyasını elde etmiş olmanız gerekir. Eğer elinize ulaşmadıysa <http://www.gnu.org/licenses/> adresine bakınız.

Proje ile ilgili herşeye http//sunucuctrl.com/ adresinden ulaşılabilir.
*/

//TOOLTIP FONKSİYONU
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

//BAĞLI FONKSİYONLAR
$(document).ready(function(){
	//SMS API SAĞLAYICIYA BAĞLI FONKSİYONLAR
	$('#SMSOwner').change(function(){
		if ($(this).val()==('CLOCKWORKS')){
			$('#SMS_UCode').show();
			$('#SMS_UName').hide();
			$('#SMS_UPwd').hide();
			document.getElementById("SMS_UCodeLbl").innerHTML  = "Kullanıcı Kodu";
		} else if ($(this).val()==('CLICKATELL') || $(this).val()==('OZTEK') || $(this).val()==('CMFCELL')){
			$('#SMS_UCode').show();
			$('#SMS_UName').show();
			$('#SMS_UPwd').show();
			if ($(this).val()==('OZTEK') || $(this).val()==('CMFCELL')){
				document.getElementById("SMS_UCodeLbl").innerHTML  = "Bayi Kodu";
			} else {
				document.getElementById("SMS_UCodeLbl").innerHTML  = "Kullanıcı Kodu";
			}
		} else {
			$('#SMS_UCode').hide();
			$('#SMS_UName').show();
			$('#SMS_UPwd').show();
		}
		
		if ($(this).val()==('CLICKATELL')){
			document.getElementById("SMS_APIAdresi").value = "https://api.clickatell.com/xml/xml";
		} else if ($(this).val()==('CLOCKWORKS')){
			document.getElementById("SMS_APIAdresi").value = "https://api.clockworksms.com/xml/send.aspx";
		} else if ($(this).val()==('DAKIKSMS')){
			document.getElementById("SMS_APIAdresi").value = "http://www.dakiksms.com//api/xml_api.php";
		} else if ($(this).val()==('EMARKA')){
			document.getElementById("SMS_APIAdresi").value = "http://api.iletimerkezi.com/v1/send-sms";
		} else if ($(this).val()==('INFOBIP')){
			document.getElementById("SMS_APIAdresi").value = "http://api.infobip.com/api/v3/sendsms/xml";
		} else if ($(this).val()==('KURECELL')){
			document.getElementById("SMS_APIAdresi").value = "http://kurecell.com.tr/kurecellapiV2/api-center/";
		} else if ($(this).val()==('NETGSM')){
			document.getElementById("SMS_APIAdresi").value = "http://api.netgsm.com.tr/xmlbulkhttppost.asp";
		} else if ($(this).val()==('OZTEK')){
			document.getElementById("SMS_APIAdresi").value = "http://www.ozteksms.com/panel/smsgonder1Npost.php";
		} else if ($(this).val()==('AVRASYA')){
			document.getElementById("SMS_APIAdresi").value = "https://www.avrasyatelekom.com/avrasyasmsapi.php";
		} else if ($(this).val()==('CMFCELL')){
			document.getElementById("SMS_APIAdresi").value = "http://smsc.cmfcell.com/api/xml/default.aspx";
		} else {
			document.getElementById("SMS_APIAdresi").value = "";
		}
	
	});
	
	//MAIL PORTUNA BAĞLI FONKSİYONLAR
	$('#Mail_SSL').change(function(){
		if (document.getElementById("Mail_SSL").checked){
			document.getElementById("Mail_Port").value = "465";
		} else {
			document.getElementById("Mail_Port").value = "587";
		}
	
	});
	
	//FTP MODUNA BAĞLI FONKSİYONLAR
	$('#FTP_Mode').change(function(){
		if ($(this).val()==('LOCAL')){
			$('#FTP_Srv').hide();
			$('#FTP_UName').hide();
			$('#FTP_UPwd').hide();
			$('#FTP_Prt').hide();
		} else if ($(this).val()==('FTP')){
			$('#FTP_Srv').show();
			$('#FTP_UName').show();
			$('#FTP_UPwd').show();
			$('#FTP_Prt').show();					
			document.getElementById("FTP_Port").value = "21";
		} else if ($(this).val()==('SFTP')){
			$('#FTP_Srv').show();
			$('#FTP_UName').show();
			$('#FTP_UPwd').show();
			$('#FTP_Prt').show();
			document.getElementById("FTP_Port").value = "22";
		} else {
			$('#FTP_Srv').show();
			$('#FTP_UName').show();
			$('#FTP_UPwd').show();
			$('#FTP_Prt').show();
			document.getElementById("FTP_Port").value = "";
		}
	});

	//TWEET MODUNA BAĞLI FONKSİYONLAR
	$('#Tweet_Mode').change(function(){
		if ($(this).val()==('LOCAL')){
			$('#Tweet_ServURL').hide();			
		} else if ($(this).val()==('REMOTE')){
			$('#Tweet_ServURL').show();
		} else {
			$('#Tweet_ServURL').show();
		}
	});
	
});

//KRİPTO İŞLEMLERİ İÇİN VERİYİ PHP SAYFAYA POST EDEN ve CEVABI DÖNDÜREN FONKSİYON
function crypt(f_data, islem){
	var hr = new XMLHttpRequest();
	var url = "cryptor.php";	
	var vars = "islem="+islem+"&pss="+encodeURIComponent(f_data.trim());
	hr.open("POST", url, false);
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	hr.setRequestHeader("Content-length", vars.length);
	hr.setRequestHeader("Connection", "close");
	hr.send(vars);
	var hash = hr.responseText.split('<body>').pop().split('</body>')[0];
	return hash.trim();
}

//ALICI LİSTELERİNE VERİ EKLEYEN FONKSİYON
function Add_Recipient(source, target){
	var data = document.getElementById(source).value;
	if (data != "") {			
		if (source == "SMS_Recipient"){
			data = data.substr(1); 
			data = data.replace(/\s/g, "");
		}
		if (source == "Tweet_Recipient_Pwd"){
			data = crypt(data, "encrypt");
		}
		if ((document.getElementById(target).value == "") || (document.getElementById(target).value == " ")) {
			document.getElementById(target).value = data;
		} else {
			document.getElementById(target).value += ', ' + data;
		}
		document.getElementById(source).value = "";	
		document.getElementById(source).focus();
	}
}

//ALICI LİSTELERİNDEN VERİ SİLEN FONKSİYON
function Del_Recipient(source, target){
	var data = document.getElementById(source).value;
	if (data != "") {
		if (source == "SMS_Recipient"){
			data = data.substr(1); 
			data = data.replace(/\s/g, "");
		}
		if (source == "Tweet_Recipient_Pwd"){
			data = crypt(data, "encrypt");
		}
		var target_data = document.getElementById(target).value.replace(data, '');
		target_data = target_data.replace(' , ',' '); 
		if (target_data[0] == ","){
			target_data = target_data.substr(1);
		} else if (target_data[target_data.length-2] == ","){
			target_data = target_data.substr(0, target_data.length-2);
		}
		document.getElementById(target).value = target_data;
		document.getElementById(source).value = "";		
		document.getElementById(source).focus();		
	}
}

//ŞİFRE GİRİLEN INPUTBOXLARDAN BOŞLUKLARI, TÜRKÇE KARAKTERLERİ VE ÖZEL KARAKTERLERİ TEMİZLEYEN FONKSİYON
function check_pwds(target) {

	var data = document.getElementById(target).value;
	
	if (/[\`\~\,\.\<\>\;\'\:\"\!\/\[\#\$\½\é\^\*\%\&\?\@\Ğ\ğ\Ü\ü\İ\ı\Ş\ş\Ö\ö\Ç\ç\]\|\{\}\(\)\=\_\+\-\\ ]/.test(data)){    
		 document.getElementById(target).value = data.replace(/[\`\~\,\.\<\>\;\'\:\"\!\/\[\#\$\½\é\^\*\%\&\?\@\Ğ\ğ\Ü\ü\İ\ı\Ş\ş\Ö\ö\Ç\ç\]\|\{\}\(\)\=\_\+\-\\ ]/g, "");
	}
}

//ŞİFRE GİRİLEN INPUTBOXLARI KRİPTOLAYAN FONKSİYON
function crypt_pwd(source, islem){
	var data = document.getElementById(source).value.trim();
	if (data != ""){
		data = crypt(data, islem);
		document.getElementById(source).value = data.trim();	
	}
}

//MASK FONKSİYONU
jQuery(function($){
	$("#SMS_Recipient").mask("+90 sss sss ssss");
});

//SUBMIT BUTONUNUN DEFAULT ÖZELLİĞİNİ DEVREDIŞI BIRAKAN FONKSİYON
$(function() {
	$("form input").keypress(function (e) {
	if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
		$('input[type=submit].default').click();
		return false;
	} else {
		return true;
	}
	});
});

//ALICI LİSTELERİNE VERİ EKLEME BUTONLARINA DEFAULT ÖZELLİĞİ VEREN FONKSİYON
function Default_Add(e, source, target) {
	if (e.keyCode == 13) {
		document.getElementById(source).blur();
		Add_Recipient(source, target);
		return false;
	}
}

//PORT KUTUCUKLARINA SADECE NUMERİK KARAKTER GİRİLMESİNİ SAĞLAYAN FONKSİYON
function validate_number(e) {
    var key = window.event ? e.keyCode : e.which;
	if (e.keyCode == 8 || e.keyCode == 46
	 || e.keyCode == 37 || e.keyCode == 39) {
		return true;
	}
	else if ( key < 48 || key > 57 ) {
		return false;
	}
	else return true;
}

//E POSTA ADRESLERİNİ KONTROL EDEN FONKSİYON
function validate_email(emailbox) { 
	var data = document.getElementById(emailbox).value.trim();
	if (data != "") {
		var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		
		if (!(regex.test(data))) {
			alert(data + " adresi geçerli bir e-posta adresi değildir. Gönderimlerin sorunsuz gerçekleşebilmesi için lütfen geçerli bir mail adresi giriniz!");
			document.getElementById(emailbox).value = "";	
			document.getElementById(emailbox).focus();
		}
	} 
}
