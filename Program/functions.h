/*
SunucuCtrl Ana Fonksiyonları

Copyright (C) 2014 SunucuCtrl Projesi

Bu program özgür yazılımdır: Özgür Yazılım Vakfı tarafından yayımlanan GNU Genel Kamu Lisansı’nın sürüm 3 ya da (isteğinize bağlı olarak) daha sonraki sürümlerinin hükümleri altında yeniden dağıtabilir ve/veya değiştirebilirsiniz.

Bu program, yararlı olması umuduyla dağıtılmış olup, programın BİR TEMİNATI YOKTUR; TİCARETİNİN YAPILABİLİRLİĞİNE VE ÖZEL BİR AMAÇ İÇİN UYGUNLUĞUNA dair bir teminat da vermez. Ayrıntılar için GNU Genel Kamu Lisansı’na göz atınız.

Bu programla birlikte GNU Genel Kamu Lisansı’nın bir kopyasını elde etmiş olmanız gerekir. Eğer elinize ulaşmadıysa <http://www.gnu.org/licenses/> adresine bakınız.
*/

#include <string>
#include <cstring>
#include <time.h>

#include "sqlite3.h" 

using namespace std;

string trim(const string &str0) {

    string str = str0;
    size_t at2 = str.find_last_not_of(" \t\r\n\0\a\b\f\v");
    size_t at1 = str.find_first_not_of(" \t\r\n\0\a\b\f\v");
    if (at2 != string::npos) str.erase(at2+1);
    if (at1 != string::npos) str.erase(0,at1);

    return str;

}

const string time_now(string stil="log") {

	time_t now = time(0);
    struct	tm	tstruct;
    char buffer[BUFSIZ];
    tstruct = *localtime(&now);
	if (trim(stil) == "log") {
		strftime(buffer, BUFSIZ, "%d/%m/%Y %X", &tstruct);
	} else if (trim(stil) == "file") {
		strftime(buffer, BUFSIZ, "%d%m%Y%H%M", &tstruct);
	} else {
		strftime(buffer, BUFSIZ, "%d/%m/%Y %X", &tstruct);
	}
	
	return buffer;

}

string exec_cmd(string cmd) {

	string out_data;
	char buffer[BUFSIZ];
	FILE *stream;
	
	try{
		cmd.append(" 2>&1");
		if (!(stream = popen(cmd.c_str(), "r"))) throw 1;
		while (fgets(buffer, BUFSIZ, stream) != NULL) {
			out_data.append(buffer);
		}
		if ((strstr(out_data.c_str(), "command not found"))) throw 2;
		pclose(stream);	
	} catch(int h) {
		if (h == 2) {
			out_data = "!GECERSIZ KOMUT!";
		} else {
			out_data = "!KOMUT UYGULAMA HATASI!";
		}	
	}
	
	return out_data;
		
}


string* sql_get(const char *sql_cmd, int col) {

	sqlite3 *db;
	int dbos = sqlite3_open("/opt/sunucuctrl/configs.db", &db);
	sqlite3_stmt *stmt;
	int cps = sqlite3_prepare(db, sql_cmd, -1, &stmt, 0);
	col++; //SQLite tabloları alırken NULL tabloyu gördüğü an duruyor. +1 sütün fazla verilmeli.
	string* str = new string[col];
	
	while(sqlite3_step(stmt) == SQLITE_ROW) {
		for (int icol = 0 ; icol <= col ; icol++){
			if (sqlite3_column_text(stmt, icol) == NULL) {
				continue;
			} else {
				str[icol] = string(reinterpret_cast<const char*>(sqlite3_column_text(stmt, icol)));
			}
		}
	};

	return str;
		
	sqlite3_finalize(stmt);
	sqlite3_close(db);
	delete[] str;

}

string message(string type) {

	string host_s = exec_cmd("/bin/hostname -s");
	string host_f = exec_cmd("/bin/hostname -f");
	string load_avs = exec_cmd("/bin/awk '{ printf \"%s %s %s\", $1, $2, $3}' /proc/loadavg ; /usr/bin/nproc | /bin/awk '{printf \" (%s CPU)\", $0}'");
	string total_con = exec_cmd("/bin/netstat -an | /usr/bin/wc -l");
	string syn_packs = exec_cmd("/bin/netstat -n | /bin/grep SYN | /usr/bin/wc -l");
	string mem_usa = exec_cmd("/usr/bin/free -t -m | /bin/grep \"Mem\" | /bin/awk '{ print $3 \" MB RAM kullanılıyor \" $4 \" MB RAM boşta\";}'");
	string cpu_usa = exec_cmd("/usr/bin/top -b -n2 | /bin/grep \"Cpu(s)\" | /bin/awk '{print $5}' | /usr/bin/rev | /usr/bin/cut -c 5- | /usr/bin/rev | /bin/sed '$!d' | /bin/awk '{print 100-$1}'");
	string uptime = exec_cmd("/bin/awk '{ printf $1}' /proc/uptime | /bin/awk '{printf(\"%d gün %02d saat %02d dakika\",  int($0/86400), int(($0%86400)/3600), int(($0%3600)/60))}'");
	string cpu_monster = exec_cmd("/bin/ps -eo %cpu=\"\",user=\"\",pid=\"\",command=\"\"| /bin/sort -k 1 -r | /usr/bin/head -1 | /bin/awk '{print $2 \" kullanıcısı %\" $1 \" oranıyla en yüksek CPU kullanımına sahip (PID: \" $3 \" Komut: \" $4 \").\"}'");
	string free_root_spc = exec_cmd("/bin/df -Ph / | /usr/bin/tail -1 | /bin/awk '{ print $5}' | /usr/bin/rev | /usr/bin/cut -c 2- | /usr/bin/rev");
		
	if (type == trim("sms")) {
		
		string sms_message = "Sunucu adı: " + trim(host_f) + ". Load değerleri: " + trim(load_avs) + ". Sunucuda toplam " + trim(total_con) + " aktif bağlantı var. Sunucuya toplam " + trim(syn_packs) + " SYN paketi geliyor. Sunucuda toplam %" + trim(cpu_usa) + " oranında CPU kullanılıyor. Sunucuda " + trim(mem_usa) + ". Sunucu " + trim(uptime) + "dır açık. " + trim(cpu_monster) + " Root (/) bölütündeki dosyalar %" + trim(free_root_spc) + " disk kullanıyor."; 
		
		return sms_message;
	
	} else if (type == trim("mail")) {
		
		string mail_text = "Load Değerleri: " + trim(load_avs) + "<br><br>Sunucuda toplam " + trim(total_con) + " aktif bağlantı var.<br><br>Sunucuya toplam " + trim(syn_packs) + " SYN paketi geliyor.<br><br>Sunucuda toplam %" + trim(cpu_usa) + " oranında CPU kullanılıyor.<br><br>Sunucuda " + trim(mem_usa) + ".<br><br>Sunucu " + trim(uptime) + "dır açık.<br><br>" + trim(cpu_monster) + "<br><br>Root (/) bölütündeki dosyalar %" + trim(free_root_spc) + " disk kullanıyor."; 
		
		return mail_text;
		
	} else if (type == trim("status")) {

		string status_text = trim(host_f) + " İSİMLİ SUNUCUNUN DURUM RAPORU\n\n" + "Load Değerleri: " + trim(load_avs) + "\nSunucuda toplam " + trim(total_con) + " aktif bağlantı var.\nSunucuya toplam " + trim(syn_packs) + " SYN paketi geliyor.\nSunucuda toplam %" + trim(cpu_usa) + " oranında CPU kullanılıyor.\nSunucuda " + trim(mem_usa) + ".\nSunucu " + trim(uptime) + "dır açık.\n" + trim(cpu_monster) + "\nRoot (/) bölütündeki dosyalar %" + trim(free_root_spc) + " disk kullanıyor."; 
		
		return status_text;	

	} else if (type == trim("tweet")) {
	
		string tweet_text =  "Sunucu: " + trim(host_s) + ". Load: " + trim(exec_cmd("awk '{ printf \"%s\", $1}' /proc/loadavg ; nproc | awk '{printf \" (%s CPU)\", $0}'")) + ". " + trim(cpu_monster);
		
		return tweet_text;	
	
	}
	
}

string rm_lastch(string lcs) {
	lcs = lcs.substr(0,lcs.length()-1);
	return lcs;
}

string rm_cntcode(string cntcs) {
	cntcs.erase(0,2);
	return cntcs;
}

string tr_to_en(string txt) {

	int found;
	string tr_ch[12] = {"ğ","ü","ş","ı","ö","ç","Ğ","Ü","Ş","İ","Ö","Ç"};
	string en_ch[12] = {"g","u","s","i","o","c","G","U","S","I","O","C"};

	for (int i = 0 ; i < 12 ; i++) {
		for(int j = 0 ; j < txt.size() ; j++) {
			found = txt.find(tr_ch[i]);
			if (found >= 0) {
				txt.replace(found, tr_ch[i].size(), en_ch[i]);
			}
		}
	}

	return txt;
}