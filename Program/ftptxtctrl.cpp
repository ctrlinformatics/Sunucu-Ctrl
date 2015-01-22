/*
Proje Adı: Sunucu Ctrl (ServCtrl)
Modül Adı: ServCtrl ServCtrl Txt Log Creator & Ftp Uploader
Modül Tamamlanma Tarihi: 09.09.2014
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

class ftptxtctrl {
	
	string stat_log;
	
	public:
	
		ftptxtctrl();
		~ftptxtctrl();
		void load_ftptxt();

};

ftptxtctrl::ftptxtctrl() {

	ifstream log_file("/opt/sunucuctrl/.ftptxtstatus.log");
	if (!log_file){
		stat_log = time_now() + " Tarihli İşlemlerin Durumları\n";
	} else {
		stat_log = "\n\n" + time_now() +" Tarihli İşlemlerin Durumları\n";
	}

	load_ftptxt();
}


ftptxtctrl::~ftptxtctrl() {
	
	stat_log += "İşlem Sonu: " + time_now() + "\n";
	ofstream log_file("/opt/sunucuctrl/.ftptxtstatus.log", ios::app);
	log_file << stat_log.c_str() << endl;
	log_file.close();

}

void ftptxtctrl::load_ftptxt() {

	string* data = sql_get("SELECT * FROM tbl_ftptxt", 6);
	string active = data[0];
	string mode = data[1];
	string username = data[2];
	string passwd = decoder(data[3]);
	string server_url = data[4];
	string port = data[5];
	string directory = data[6];
	
	if (trim(active) == "1") {
		
		string file_name = "/tmp/" + trim(exec_cmd("/bin/hostname -s")) + "-" + time_now("file") + ".log";
		string status = "Log Tarihi: " + time_now() + "\n" + trim(message("status"));
		ofstream serv_stat_file(file_name.c_str(), ios::app);
		serv_stat_file << status.c_str() << endl;
		serv_stat_file.close();
		
		string load_sta;
		if (trim(mode) == "LOCAL") {
		
			load_sta = exec_cmd("/bin/cp " + file_name + " " + trim(directory) + file_name);
			
			if (trim(load_sta) == ""){
				stat_log += file_name + " dosyası " + trim(exec_cmd("/bin/hostname -f")) + " adresli lokal sunucunun " + trim(directory) + " dizinine program tarafından yüklendi.\n";
			} else {
				stat_log += load_sta + "\n";
			}
			
		} else if (trim(mode) == "FTP") {

			load_sta = exec_cmd("/usr/local/bin/curl -sS --user \"" + trim(username) + ":" + trim(passwd) + "\" -T " + trim(file_name) + " --insecure ftp://" + trim(server_url) + ":" + trim(port) + trim(directory));
			
			if (trim(load_sta) == ""){
				stat_log += file_name + " dosyası FTP ile " + trim(server_url) + " adresli sunucunun " + trim(directory) + " dizinine " + trim(username) + " kullanıcısı tarafından yüklendi.\n";
			} else {
				stat_log += load_sta + "\n";
			}
			
		} else if (trim(mode) == "SFTP") {
		
			load_sta = exec_cmd("/usr/local/bin/curl -sS --user \"" + trim(username) + ":" + trim(passwd) + "\" -T " + trim(file_name) + " --insecure sftp://" + trim(server_url) + ":" + trim(port) + trim(directory));
			
			if (trim(load_sta) == ""){
				stat_log += file_name + " dosyası SFTP ile " + trim(server_url) + " adresli sunucunun " + trim(directory) + " dizinine " + trim(username) + " kullanıcısı tarafından yüklendi.\n";
			} else {
				stat_log += load_sta + "\n";
			}
			
		} else {
			stat_log += "Tanınmayan mod belirtilmiş.\n";
		}
		
		string rm_logfile = "/bin/rm -f " + file_name;
		exec_cmd(rm_logfile);
		
	} else {
		stat_log += "TXT log oluşturma ve FTP log yükleme özelliği veritabanından devredışı bırakılmış.\n";
	}

}

main() {

	ftptxtctrl obj;

	return 0;
	
}