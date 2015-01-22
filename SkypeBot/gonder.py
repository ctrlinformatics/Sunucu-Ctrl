#!/usr/bin/python
# -*- coding: utf-8 -*-

import time, sqlite3

def main():   
    try:
        from Skype4Py import Skype
        skype = Skype()     
        
        if skype.Client.IsRunning == False:
            skype.Client.Start(True, True)
            time.sleep(45)
   
        skype.Attach()
        
        conn = sqlite3.connect('Mesajlar.db')
        conn.row_factory = sqlite3.Row

        sql = conn.execute('SELECT * FROM Mesajlar')
        data = sql.fetchall()
        for i in data:
            alici = str(i['alici'])
            mesaj = str(i['mesaj'])
            skype.SendMessage(alici, mesaj)
            conn.execute("DELETE FROM Mesajlar WHERE id='%i'" % int(i['id']))

        conn.commit()
        conn.close()
    
    except ImportError as hata:
        print 'Skype4Py modülü tanımlanmamış'
    except ValueError as hata:
        print hata
    except:
        print 'Bilinmeyen bir hata oluştu!'


if __name__ == "__main__":
    main()
