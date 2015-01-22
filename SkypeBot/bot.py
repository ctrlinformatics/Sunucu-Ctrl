# -*- coding: utf-8 -*-

import Skype4Py, sys

reload(sys)  
sys.setdefaultencoding('utf8')

skype = Skype4Py.Skype();

if skype.Client.IsRunning == False:
    skype.Client.Start()
skype.Attach();

def UserAuthorize(user):
    if not user.IsAuthorized:
        user.IsAuthorized = True
        skype.CreateChatWith(user.Handle).SendMessage('''Merhaba %s;
Ben SunucuCtrl yazılımının bot hesabıyım. Öncelikle programı kullandığınız için size geliştirici ekibim adına teşekkür ederim.
Kullanım süreniz boyunca skype modülünü aktifleştirdiğiniz sunucuların durumlarını size ileteceğim. Lütfen bana cevap vermeye çalışmayınız. Ben bir botum ve size yanıt veremem.
Tekrardan teşekkür eder ve iyi çalışmalar dilerim...''' % user.FullName)
    
def command(Message, Status):
    if Status == 'RECEIVED':
        if Message.Body == '!botstatus':
            Message.Chat.SendMessage('HEYO! Çalışıyorum (happy)')
            Message.MarkAsSeen()
        elif Message.Body == '!botname':
            Message.Chat.SendMessage('Benim adımı sen koy :).')
            Message.MarkAsSeen()
        elif Message.Body == '!whoami':
            Message.Chat.SendMessage('Senin görünen adın %s.' % Message.FromDisplayName)
            Message.MarkAsSeen()
        else:
            Message.Chat.SendMessage(('''Merhaba %s;
Ben bir botum ve size yanıt veremem :x. Bu konuda üzgünüm :(.
Belki ileride benimde gelişmiş bir yapay zekam olur (waiting) ve sizinle sohbet edebilirim (think).''' % Message.FromDisplayName))
            Message.MarkAsSeen()

skype.OnMessageStatus = command
skype.OnUserAuthorizationRequestReceived = UserAuthorize

while True:
    raw_input('') 
