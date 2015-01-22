/*
Proje Adı: Sunucu Ctrl (ServCtrl)
Modül Adı: ServCtrl Facebook Status Updater
Modül Tamamlanma Tarihi: 21.07.2014
Revizyon: 4

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

class fbctrl {
	
	string stat_log;
	
	public:
	
		fbctrl();
		~fbctrl();
		void post_fbstatus();

};

fbctrl::fbctrl() {

	ifstream log_file("/opt/sunucuctrl/.fbstatus.log");
	if (!log_file){
		stat_log = time_now() + " Tarihli İşlemlerin Durumları\n";
	} else {
		stat_log = "\n\n" + time_now() +" Tarihli İşlemlerin Durumları\n";
	}

	post_fbstatus();
}


fbctrl::~fbctrl() {
	
	stat_log += "İşlem Sonu: " + time_now() + "\n";
	ofstream log_file("/opt/sunucuctrl/.fbstatus.log", ios::app);
	log_file << stat_log.c_str() << endl;
	log_file.close();

}

void fbctrl::post_fbstatus() {

	string* data = sql_get("SELECT * FROM tbl_fbstatus", 2);
	string active = data[0];
	string user_list = data[1];
	string token = decoder(data[2]);
	
	if (trim(active) == "1") {

		string recipient, rec_user;
		
		while(recipient != user_list) {
		
			recipient = user_list.substr(0,user_list.find_first_of(","));
			user_list = user_list.substr(user_list.find_first_of(",") + 1);
			rec_user = trim(recipient);

			string send_data = "\"message=" + trim(message("status")) + "\" -d \"&access_token=" + trim(token) + "\"";
			string send_cmd = "/usr/local/bin/curl -sS -X POST --data-urlencode " + trim(send_data) + " https://graph.facebook.com/" + trim(rec_user) + "/feed";
			string send_sta = exec_cmd(send_cmd);
			
			stat_log += send_sta + "\n";	

		}
		
	} else {
		stat_log += "Facebook status gönderimi veritabanından devredışı bırakılmış.\n";
	}


}

main() {

	fbctrl obj;

	return 0;
	
}