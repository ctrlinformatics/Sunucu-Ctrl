/*
Proje Adı: Sunucu Ctrl (ServCtrl)
Modül Adı: ServCtrl SMS Messager
Modül Tamamlanma Tarihi: 05.08.2014
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

class smsctrl {
	
	string stat_log;
	
	public:
		smsctrl();
		~smsctrl();
		string tr2en(string);
		void send_sms();

};

smsctrl::smsctrl() {

	ifstream log_file("/opt/sunucuctrl/.smsstatus.log");
	if (!log_file){
		stat_log = time_now() + " Tarihli İşlemlerin Durumları\n";
	} else {
		stat_log = "\n\n" + time_now() +" Tarihli İşlemlerin Durumları\n";
	}

	send_sms();
}


smsctrl::~smsctrl() {
	
	stat_log += "İşlem Sonu: " + time_now() + "\n";
	ofstream log_file("/opt/sunucuctrl/.smsstatus.log", ios::app);
	log_file << stat_log.c_str() << endl;
	log_file.close();

}

void smsctrl::send_sms() {

	string* data = sql_get("SELECT * FROM tbl_sms", 7);
	string active = data[0];
	string api_owner = data[1];
	string username = data[2];
	string passwd = decoder(data[3]);
	string originator = data[4];
	string recipient_list = data[5];
	string user_code = data[6];
	string api_url = data[7];
	
	
	if (trim(active) == "1") {
		
		string recipient, rec_users, send_cmd, send_sta;
		
		if (trim(api_owner) == "NETGSM") {
						
			while(recipient != recipient_list) {
						
				recipient = recipient_list.substr(0,recipient_list.find_first_of(","));
				recipient_list = recipient_list.substr(recipient_list.find_first_of(",") + 1);
				rec_users += "<no>" + trim(recipient) + "</no>";
							
			}
							
			send_cmd = "/usr/local/bin/curl -sS -X POST -d '<?xml version=\"1.0\"?><mainbody><header><company>NETGSM</company><usercode>" + trim(username) + "</usercode><password>" + trim(passwd) + "</password><startdate></startdate><stopdate></stopdate><type>1:n</type><msgheader>" + trim(originator) + "</msgheader></header><body><msg><![CDATA[" + tr_to_en(message("sms")) + "]]></msg>" + trim(rec_users) + "</body></mainbody>' --header \"Content-Type:text/xml\" " + trim(api_url);
			
			send_sta = exec_cmd(send_cmd);
			
			stat_log += send_sta + "\n";
		
		} else if (trim(api_owner) == "EMARKA") {
			
			while(recipient != recipient_list) {
						
				recipient = recipient_list.substr(0,recipient_list.find_first_of(","));
				recipient_list = recipient_list.substr(recipient_list.find_first_of(",") + 1);
				rec_users += "<number>" + rm_cntcode(trim(recipient)) + "</number>";
							
			}
			
			send_cmd = "/usr/local/bin/curl -sS -X POST -d '<request><authentication><username>" + trim(username) + "</username><password>" + trim(passwd) + "</password></authentication><order><sender>" + trim(originator) + "</sender><sendDateTime></sendDateTime><message><text><![CDATA[" + tr_to_en(message("sms")) + "]]></text><receipents>" + trim(rec_users) + "</receipents></message></order></request>' --header \"Content-Type:text/xml\" " + trim(api_url);
			
			send_sta = exec_cmd(send_cmd);
			
			stat_log += send_sta + "\n";
			
		} else if (trim(api_owner) == "INFOBIP") {
						
			while(recipient != recipient_list) {
						
				recipient = recipient_list.substr(0,recipient_list.find_first_of(","));
				recipient_list = recipient_list.substr(recipient_list.find_first_of(",") + 1);
				rec_users += "<gsm>" + trim(recipient) + "</gsm>";
							
			}
			
			int found;
			string ns_ch = "%";
			string s_ch = "yüzde ";
			string msg_text = message("sms");
	
			for(int j = 0 ; j < msg_text.size() ; j++) {
				found = msg_text.find(ns_ch);
				if (found >= 0) {
					msg_text.replace(found, ns_ch.size(), s_ch);
				}
			}
							
			send_cmd = "/usr/local/bin/curl -sS -X POST -d 'XML=<SMS><authentication><username>" + trim(username) + "</username><password>" + trim(passwd) + "</password></authentication><message><sender>" + trim(originator) + "</sender><text>" + tr_to_en(msg_text) + "</text><type>longSMS</type><recipients>" + trim(rec_users) + "</recipients></message></SMS>' --header \"Content-Type:text/xml\" " + trim(api_url);
			
			send_sta = exec_cmd(send_cmd);
			
			stat_log += send_sta + "\n";
		
		} else if (trim(api_owner) == "OZTEK") {
			
			while(recipient != recipient_list) {
						
				recipient = recipient_list.substr(0,recipient_list.find_first_of(","));
				recipient_list = recipient_list.substr(recipient_list.find_first_of(",") + 1);
				rec_users += rm_cntcode(trim(recipient)) + ",";
							
			}
			
			send_cmd = "/usr/local/bin/curl -sS -X POST --data-urlencode 'data=<sms><kno>" + trim(user_code) + "</kno><kulad>" + trim(username) + "</kulad><sifre>" + trim(passwd) + "</sifre><gonderen>" + trim(originator) + "</gonderen><mesaj>" + tr_to_en(message("sms")) + "</mesaj><numaralar>" + rm_lastch(trim(rec_users)) + "</numaralar><tur>Normal</tur></sms>' " + trim(api_url);
			
			send_sta = exec_cmd(send_cmd);
			
			stat_log += send_sta + "\n";
			
		} else if (trim(api_owner) == "CLICKATELL") {
			
			while(recipient != recipient_list) {
						
				recipient = recipient_list.substr(0,recipient_list.find_first_of(","));
				recipient_list = recipient_list.substr(recipient_list.find_first_of(",") + 1);
				rec_users += trim(recipient) + ",";
							
			}
			
			send_cmd = "/usr/local/bin/curl -sS -X POST --data-urlencode 'data=<clickAPI><sendMsg><api_id>" + trim(user_code) + "</api_id><user>" + trim(username) + "</user><password>" + trim(passwd) + "</password><to>" + rm_lastch(trim(rec_users)) + "</to><from>" + trim(originator) + "</from><concat>3</concat><text>" + tr_to_en(message("sms")) + "</text></sendMsg></clickAPI>' " + trim(api_url);
			
			send_sta = exec_cmd(send_cmd);
			
			stat_log += send_sta + "\n";
			
		} else if (trim(api_owner) == "CLOCKWORKS") {
		
			string dec_user_code = decoder(user_code);
			string multi_sms_txt = tr_to_en(message("sms"));
			
			while(recipient != recipient_list) {
						
				recipient = recipient_list.substr(0,recipient_list.find_first_of(","));
				recipient_list = recipient_list.substr(recipient_list.find_first_of(",") + 1);
				rec_users += "<SMS><To>" + trim(recipient) + "</To><Content>" + multi_sms_txt + "</Content></SMS>";
							
			}
							
			send_cmd = "/usr/local/bin/curl -sS -X POST -d '<?xml version=\"1.0\" encoding=\"UTF-8\"?><Message><Key>" + trim(dec_user_code) + "</Key>" + rec_users + "<From>" + trim(originator) + "</From><Concat>3</Concat></Message>' --header \"Content-Type:text/xml\" " + trim(api_url);
			
			send_sta = exec_cmd(send_cmd);
			
			stat_log += send_sta + "\n";
		
		
		} else if (trim(api_owner) == "KURECELL") {
			
			while(recipient != recipient_list) {
						
				recipient = recipient_list.substr(0,recipient_list.find_first_of(","));
				recipient_list = recipient_list.substr(recipient_list.find_first_of(",") + 1);
				rec_users += rm_cntcode(trim(recipient)) + ",";
							
			}
			
			send_cmd = "/usr/local/bin/curl -sS -X POST --data-urlencode '&mesaj=" + tr_to_en(message("sms")) + "' -d 'apiNo=1&user=" + trim(username) + "&pass=" + trim(passwd) + "&numaralar=" + rm_lastch(trim(rec_users)) + "&baslik=" + trim(originator) + "' " + trim(api_url);
			
			send_sta = exec_cmd(send_cmd);
			
			stat_log += send_sta + "\n";

		} else if (trim(api_owner) == "AVRASYA") {
			
			while(recipient != recipient_list) {
						
				recipient = recipient_list.substr(0,recipient_list.find_first_of(","));
				recipient_list = recipient_list.substr(recipient_list.find_first_of(",") + 1);
				rec_users += "<gsm>" + rm_cntcode(trim(recipient)) + "</gsm>";
							
			}
			
			send_cmd = "/usr/local/bin/curl -X POST -sS --insecure -d '<?xml version=\"1.0\" encoding=\"UTF-8\"?><root><username>" + trim(username) + "</username><pass>" + trim(passwd) + "</pass><islem>sms</islem><tip>multi</tip><hiz>1</hiz><uzun>1</uzun><originator>" + trim(originator) + "</originator><mesaj>" + tr_to_en(message("sms")) + "</mesaj>" + trim(rec_users) + "</root>' " + trim(api_url);
			
			send_sta = exec_cmd(send_cmd);
			
			stat_log += send_sta + "\n";

		} else if (trim(api_owner) == "CMFCELL") {
			
			while(recipient != recipient_list) {
						
				recipient = recipient_list.substr(0,recipient_list.find_first_of(","));
				recipient_list = recipient_list.substr(recipient_list.find_first_of(",") + 1);
				rec_users += trim(recipient) + ";";
							
			}
			
			send_cmd = "/usr/local/bin/curl -sS -X POST -d '<packet version=\"1.0\"><header><auth userName=\"" + trim(user_code) + "-" + trim(username) + "\" password=\"" + trim(passwd) + "\" /></header><body><sendMessage useGrouping=\"1\"><type>Sms</type><senderid>" + trim(originator) + "</senderid><recipients>" + rm_lastch(trim(rec_users)) + "</recipients><content>" + tr_to_en(message("sms")) + "</content></sendMessage></body></packet>' --header \"Content-Type:text/xml\" " + trim(api_url);
			
			send_sta = exec_cmd(send_cmd);
			
			stat_log += send_sta + "\n";
			
		} else if (trim(api_owner) == "DAKIKSMS") {
			
			while(recipient != recipient_list) {
						
				recipient = recipient_list.substr(0,recipient_list.find_first_of(","));
				recipient_list = recipient_list.substr(recipient_list.find_first_of(",") + 1);
				rec_users += rm_cntcode(trim(recipient)) + ",";
							
			}
			
			send_cmd = "/usr/local/bin/curl -sS -X POST -d '<SMS><oturum><kullanici>" + trim(username) + "</kullanici><sifre>" + trim(passwd) + "</sifre></oturum><mesaj><baslik>" + trim(originator) + "</baslik><metin>" + tr_to_en(message("sms")) + "</metin><alicilar>" + rm_lastch(trim(rec_users)) + "</alicilar></mesaj></SMS>' " + trim(api_url);
			
			send_sta = exec_cmd(send_cmd);
			
			stat_log += send_sta + "\n";
			
		} else {
		
			stat_log += "Geçersiz API sağlayıcı.\n";
		
		}

	} else {
	
		stat_log += "SMS gönderimi veritabanından devredışı bırakılmış.\n";
		
	}

}

main() {

	smsctrl obj;
	
	return 0;

}