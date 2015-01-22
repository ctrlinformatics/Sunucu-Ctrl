/*
Proje Adı: Sunucu Ctrl (ServCtrl)
Modül Adı: ServCtrl Mailer
Modül Tamamlanma Tarihi: 22.07.2014
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

class mailctrl {
	
	string stat_log;
	
	public:
		mailctrl();
		~mailctrl();
		void post_mail();

};

mailctrl::mailctrl() {

	ifstream log_file("/opt/sunucuctrl/.mailstatus.log");
	if (!log_file){
		stat_log = time_now() + " Tarihli İşlemlerin Durumları\n";
	} else {
		stat_log = "\n\n" + time_now() +" Tarihli İşlemlerin Durumları\n";
	}

	post_mail();
}


mailctrl::~mailctrl() {
	
	stat_log += "İşlem Sonu: " + time_now() + "\n";
	ofstream log_file("/opt/sunucuctrl/.mailstatus.log", ios::app);
	log_file << stat_log.c_str() << endl;
	log_file.close();

}

void mailctrl::post_mail() {

	string* data = sql_get("SELECT * FROM tbl_mail", 8);
	string active = data[0];
	string server_url = data[1];
	string username = data[2];
	string passwd = decoder(data[3]);
	string port = data[4];
	string ssl_active = data[5];
	string sender_email = data[6];
	string sender_name = data[7];
	string recipient_list = data[8];
	
	if (trim(active) == "1") {

		string recipient, recipients, rec_cmd, log_rec, send_sta, secure_sta, server_addr;
		
		while(recipient != recipient_list) {
		
			recipient = recipient_list.substr(0,recipient_list.find_first_of(","));
			recipient_list = recipient_list.substr(recipient_list.find_first_of(",") + 1);
			rec_cmd += "--mail-rcpt \"" + trim(recipient) + "\" ";
			recipients += "BCC: " + trim(recipient) + " <" + trim(recipient) + "> \n"; 
			log_rec += trim(recipient) + "; "; 
		}		
		
		string filebody = "From: " + trim(sender_name) + " <" + trim(sender_email) + ">\n" + "TO: \"Undisclosed recipients\" <" + trim(sender_email) + ">\n" + recipients + "Subject: " + trim(exec_cmd("/bin/hostname -f")) + " Sunucusunun Durum Raporu\nMime-Version: 1.0;\nContent-Type: text/html; charset=\"utf-8\";\nContent-Transfer-Encoding: 7bit;\n<html>\n<body>\n" + trim(message("mail")) + "\n</body>\n</html>\n.\nQUIT";
			
		string tmp_mailfile = trim(exec_cmd("/bin/mktemp"));
		ofstream mailfile;
		mailfile.open (tmp_mailfile.c_str());
		mailfile << filebody.c_str();
		mailfile.close();
			
		if (trim(ssl_active) == "1") {
			
			server_addr = "smtps://" + trim(server_url) + ":" + trim(port);	
			secure_sta = " --ssl-reqd";
			
		} else {
				
			server_addr = "smtp://" + trim(server_url) + ":" + trim(port);	
			secure_sta = "";
				
		}
			
		string auth = trim(username) + ":" + trim(passwd);
			
		string send_mail_cmd = "/usr/local/bin/curl -sS --url \"" + server_addr + "\"" + secure_sta + " --mail-from \"" +  trim(sender_email) + "\" " + rec_cmd + "--upload-file " + trim(tmp_mailfile) + " --user \"" + auth + "\" --insecure";
		send_sta = exec_cmd(send_mail_cmd);
			
		string rmtmpmailfilecmd = "/bin/rm -f " + tmp_mailfile;
		exec_cmd(rmtmpmailfilecmd.c_str());
		
		if (trim(send_sta) == "") {
			stat_log += rm_lastch(trim(log_rec)) + " adreslerine mail gönderildi.\n";
		} else {
			stat_log += send_sta + "\n";
		}
				
	} else {
		stat_log += "Mail gönderimi veritabanından devredışı bırakılmış.\n";
	}

}

main() {

	mailctrl obj;
	
	return 0;

}