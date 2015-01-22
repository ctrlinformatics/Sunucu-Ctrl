/*
Proje Adı: Sunucu Ctrl (ServCtrl)
Modül Adı: ServCtrl Tweeter
Modül Tamamlanma Tarihi: 18.01.2015
Revizyon: 5

Copyright (C) 2014 SunucuCtrl Projesi

Bu program özgür yazılımdır: Özgür Yazılım Vakfı tarafından yayımlanan GNU Genel Kamu Lisansı’nın sürüm 3 ya da (isteğinize bağlı olarak) daha sonraki sürümlerinin hükümleri altında yeniden dağıtabilir ve/veya değiştirebilirsiniz.

Bu program, yararlı olması umuduyla dağıtılmış olup, programın BİR TEMİNATI YOKTUR; TİCARETİNİN YAPILABİLİRLİĞİNE VE ÖZEL BİR AMAÇ İÇİN UYGUNLUĞUNA dair bir teminat da vermez. Ayrıntılar için GNU Genel Kamu Lisansı’na göz atınız.

Bu programla birlikte GNU Genel Kamu Lisansı’nın bir kopyasını elde etmiş olmanız gerekir. Eğer elinize ulaşmadıysa <http://www.gnu.org/licenses/> adresine bakınız.

Proje ile ilgili herşeye http//sunucuctrl.com/ adresinden ulaşılabilir.
*/

#include <iostream>
#include <string>
#include <fstream>

#include "decoder.h"
#include "functions.h"

using namespace std;

class twctrl {
	
	string stat_log;
	
	public:
	
		twctrl();
		~twctrl();
		void post_tweet();

};

twctrl::twctrl() {

	ifstream log_file("/opt/sunucuctrl/.twstatus.log");
	if (!log_file){
		stat_log = time_now() + " Tarihli İşlemlerin Durumları\n";
	} else {
		stat_log = "\n\n" + time_now() +" Tarihli İşlemlerin Durumları\n";
	}

	post_tweet();
}


twctrl::~twctrl() {
	
	stat_log += "İşlem Sonu: " + time_now() + "\n";
	ofstream log_file("/opt/sunucuctrl/.twstatus.log", ios::app);
	log_file << stat_log.c_str() << endl;
	log_file.close();

}

void twctrl::post_tweet() {

	string* data = sql_get("SELECT * FROM tbl_tweet", 6);
	string active = data[0];
	string mode = data[1];
	string apikey = decoder(data[2]);
	string apisec = decoder(data[3]);
	string token = decoder(data[4]);
	string tokensec = decoder(data[5]);
	string server_url = data[6];
	
	if (trim(active) == "1") {
		
		if (trim(mode) == "LOCAL") {
			string localtweet = exec_cmd("/usr/bin/php /opt/sunucuctrl/.tweetbot/scltweeter.php \"" + trim(message("tweet")) + "\"");
			stat_log += localtweet + "\n";
		} else if (trim(mode) == "REMOTE") {
			string rmttweet = exec_cmd("/usr/local/bin/curl -sS -X POST --data-urlencode 'tweet=" + trim(message("tweet")) + "' --data-urlencode 'akey=" + trim(apikey) + "' --data-urlencode 'asec=" + trim(apisec) + "' --data-urlencode 'actoken=" + trim(token) + "' --data-urlencode 'tsec=" + trim(tokensec) + "' " + trim(server_url));
			stat_log += rmttweet + "\n";
		} else {
			stat_log += "Tanınmayan mod belirtilmiş.\n";
		} 
		
	} else {
		stat_log += "Tweet gönderimi veritabanından devredışı bırakılmış.\n";
	}


}

main() {

	twctrl obj;

	return 0;
	
}