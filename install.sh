#!/bin/bash

KONTROL=$( curl --version | grep smtp | grep sftp)
KONFIGKONTROL=$(curl-config --protocols | grep SMTP)
CRONKONTROL=$(crontab -l | grep sc*)

if [ -f configs.db ]; then
	
	if [ -f cron.txt ]; then
		yum -y install gcc openssl openssl-devel gcc-c++ sqlite-devel make unzip
		if [ -n "$KONTROL" ]; then

				mkdir /opt/sunucuctrl;
				cd /opt/sunucuctrl;
				wget http://depo.sunucuctrl.com/program.zip;
				unzip program.zip;
				make;
				rm -if *.cpp *.h program.zip Makefile;
				crontab -l >> Cron.txt;
				crontab Cron.txt
				if [ -n "$CRONKONTROL" ]; then
					echo -e "Kurulum bitti, fakat cron ayarlariniz yapilamadi. Lütfen cron ayarlarinizi manuel olarak yapiniz.\n"
				else 
					echo -e "SunucuCtrl kurulumu başarıyla tamamlandi.\n"
				fi
				
				read -p "TweetBot bu sunucuya kurulsun mu?  <E/h> " prompt; prompt=${prompt,,}
				if [[ $prompt == "h" || $prompt == "hayir" || $prompt == "hayır" || $prompt == "n" || $prompt == "no" ]]; then
					exit 0
				else
					mkdir /opt/sunucuctrl/.tweetbot;
					cd /opt/sunucuctrl/.tweetbot;
					wget http://depo.sunucuctrl.com/localtweeter.zip;
					unzip localtweeter.zip;
					rm -f localtweeter.zip
					echo -e "Local Tweeter kurulumu başarıyla tamamlandi.\n"
				fi
				
			else

				wget http://www.libssh2.org/download/libssh2-1.4.3.tar.gz -O libssh2.tgz -q;
				tar zxf libssh2.tgz;
				rm -f libssh2.tgz;
				cd libssh2-*; 
				./configure;
				make; 
				make install;
				cd ..;
				rm -rf libssh2-*;
				wget http://curl.haxx.se/download/curl-7.40.0.tar.gz -O curl.tgz -q;
				tar zxf curl.tgz;
				rm -f curl.tgz;
				cd curl-*; 
				./configure --enable-http --enable-pop3 --enable-smtp --enable-libcurl-option --enable-libgcc --with-ssl --with-libssh2; 
				make; 
				make install;
				cd ..;
				rm -rf curl-*

				if [ -n "$KONFIGKONTROL" ]; then
					mkdir /opt/sunucuctrl;
					cd /opt/sunucuctrl;
					wget http://depo.sunucuctrl.com/program.zip;
					unzip program.zip;
					make;
					rm -if *.cpp *.h program.zip Makefile;
					crontab -l >> Cron.txt;
					crontab Cron.txt
					if [ -n "$CRONKONTROL" ]; then
						echo -e "Kurulum bitti, fakat cron ayarlariniz yapilamadi. Lütfen cron ayarlarinizi manuel olarak yapiniz.\n"
					else 
						echo -e "SunucuCtrl kurulumu başarıyla tamamlandi.\n"
					fi
					
					read -p "TweetBot bu sunucuya kurulsun mu?  <E/h> " prompt; prompt=${prompt,,}
					if [[ $prompt == "h" || $prompt == "hayir" || $prompt == "hayır" || $prompt == "n" || $prompt == "no" ]]; then
						exit 0
					else
						mkdir /opt/sunucuctrl/.tweetbot;
						cd /opt/sunucuctrl/.tweetbot;
						wget http://depo.sunucuctrl.com/localtweeter.zip;
						unzip localtweeter.zip;
						rm -f localtweeter.zip
						echo -e "Local TweetBot kurulumu başarıyla tamamlandi.\n"
					fi
				else 
					echo -e "HATA! cURL kurulumu basarisiz yada tamamlanamadi! Sisteminizi reboot edin ve kurmayı tekrar deneyin.\n";
				fi
				
			fi
	else
		echo -e "HATA! Cron ayar dosyasi bulunamadi!\n"
	fi
	
else
    echo -e "HATA! Veritabani dosyasi bulunamadi!\n"
fi
exit 1;

