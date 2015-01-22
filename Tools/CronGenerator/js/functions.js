/*
SunucuCtrl CronTab Oluşturucu Fonksiyon Dosyası

Copyright (C) 2014 İbrahim Filazi

Bu program özgür yazılımdır: Özgür Yazılım Vakfı tarafından yayımlanan GNU Genel Kamu Lisansı’nın sürüm 3 ya da (isteğinize bağlı olarak) daha sonraki sürümlerinin hükümleri altında yeniden dağıtabilir ve/veya değiştirebilirsiniz.

Bu program, yararlı olması umuduyla dağıtılmış olup, programın BİR TEMİNATI YOKTUR; TİCARETİNİN YAPILABİLİRLİĞİNE VE ÖZEL BİR AMAÇ İÇİN UYGUNLUĞUNA dair bir teminat da vermez. Ayrıntılar için GNU Genel Kamu Lisansı’na göz atınız.

Bu programla birlikte GNU Genel Kamu Lisansı’nın bir kopyasını elde etmiş olmanız gerekir. Eğer elinize ulaşmadıysa <http://www.gnu.org/licenses/> adresine bakınız.

Proje ile ilgili herşeye http//sunucuctrl.com/ adresinden ulaşılabilir.
*/

function check(radio){
	document.getElementById(radio).checked = true;
}

function kontrol(sel1, sel2){
	var v1 = parseInt(document.getElementById(sel1).value.trim());
	var v2 = parseInt(document.getElementById(sel2).value.trim());
	if (v1>=v2){
		document.getElementById(sel2).value = parseInt(v1) + 1;
	}
}