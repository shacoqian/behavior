#-*- coding:utf-8 -*-

"""
@desc 流水
@author 河边的老牛
@email qianfeng5511@163.com
"""

import wx
import os
import urllib
import urllib2
import cookielib
import json
import sys
reload(sys)


sys.setdefaultencoding('utf-8')
        
def getData(path, param):
    cj = cookielib.CookieJar()
    post_data = urllib.urlencode(param)
    opener = urllib2.build_opener(urllib2.HTTPCookieProcessor(cj))
    urllib2.install_opener(opener)
    req = urllib2.Request(path,post_data)
    try:      
        response = urllib2.urlopen(req).read()
        datas = json.loads(response)
        if int(datas[0]) == 300:
            return False
        else:
            return datas
    except:
        return False 
        
def getStatus(path, param):       
    cj = cookielib.CookieJar()
    post_data = urllib.urlencode(param)
    opener = urllib2.build_opener(urllib2.HTTPCookieProcessor(cj))
    urllib2.install_opener(opener)
    req = urllib2.Request(path,post_data)
    try:
        response = urllib2.urlopen(req).read()
        datas = json.loads(response)
        if int(datas[0]) == 300:
            return False
        else:
            return True
    except:
        return False 
        
def isfloat(string):
    try:
        float(string)
    except:
        return False
    return True    
    
class ExamplePanel(wx.Frame):
    
    def OnInit(self):
        
        #单选按钮
        param = {"act":"category","gid":self.config[1]}
        datas = getData(self.path, param)
        if not datas[2]:
            self.content = wx.StaticText(self, label=u"无法连接服务器，请查看网络！", pos=(100, 100))
        else:
            radiolist = []
            self.datas = datas[2]    
            for item in self.datas:
                radiolist.append(item['cname'])    
        self.radio = wx.RadioBox(self, -1, u"选择", (20, 10), wx.DefaultSize,radiolist, 1, wx.RA_SPECIFY_COLS)
        
        self.quote = wx.StaticText(self, label=u"金额：", pos=(150,20))
        self.quote = wx.StaticText(self, label="for what:", pos=(150,50))
        self.quote = wx.StaticText(self, label=u"@for "+self.config[2], pos=(150,200))
                
        self.quote = wx.StaticText(self, label=u"元", pos=(340,20))
        self.money = wx.TextCtrl(self, pos=(190, 20), size=(140, -1))
        
        self.description = wx.TextCtrl(self, pos=(150,70), size=(200,90), style=wx.TE_MULTILINE)
        # A button
        self.button =wx.Button(self, label=u"发送", pos=(150, 165))
        self.Bind(wx.EVT_BUTTON, self.OnClick,self.button)

        self.button1 =wx.Button(self, label=u"查看消费", pos=(250, 165))
        self.Bind(wx.EVT_BUTTON, self.OpenUrl,self.button1)        
        
    def __init__(self,parent):
        wx.Frame.__init__(self, parent)
        self.width = 400
        self.height = 320
        self.SetSize((self.width,self.height))
        # set color
        self.SetBackgroundColour('white')        
        self.SetTitle(u"河边的老牛 for life V0.3")
        self.Center()

        self.quote = wx.StaticText(self, label=u"@个人流水账统计，版本持续更新中", pos=(150,220))
        self.quote = wx.StaticText(self, label=u"@遇到垃圾360阻止，请全部允许", pos=(150,240)) 
    
        #------------------应用配置---------------------
        self.path = 'http://1.qianfeng.duapp.com/app.php' 
        #------------------配置结束---------------------
        
        self.InitConfig()
        
        #self.InitUI()
        #self.OnInit()    
    
    def InitConfig(self):
        if os.path.isfile('./config.ini'):
            #try:
            file = open('./config.ini','r')
            string =  file.readline()
            file.close()
            self.config = string.split(',')
            print self.config
            if len(self.config) == 4:
                self.InitUI()
                self.OnInit()
            else:
                print 2
        else:
            self.SetUI()        
    
    
    def SetUI(self):
        self.radio1 = wx.RadioButton(self, -1, u"第一次使用，没有自己的用户？", pos=(60,50), style=wx.RB_GROUP )
        self.radio2 = wx.RadioButton(self, -1, u"我以前使用过！", pos=(60,100) )
        self.next = wx.Button(self, label=u"下一步", pos=(100,150))
        self.Bind(wx.EVT_BUTTON, self.Onnext, self.next)
        
    def Onnext(self,e): 
        #self.Text = wx.StaticText(self, size=(self.height, self.width))
        if self.radio1.GetValue():
            print 1
            wx.MessageBox(u'此路不同！还未开发！', 'faild', wx.OK|wx.ICON_INFORMATION)
            #first time use
        elif self.radio2.GetValue():
            #even use
            self.firstClear()
            self.EvenUse()
    
    #first time clear    
    def firstClear(self):
        self.radio1.Hide()
        self.radio2.Hide()
        #self.next.Hide()
    
    #even use
    def EvenUse(self):
        self.Bind(wx.EVT_BUTTON, self.OnEvenUse, self.next)
        self.pre = wx.Button(self, label=u"上一步", pos=(200,150))
        self.Bind(wx.EVT_BUTTON, self.OnPre, self.pre)
        
        self.text1 = wx.StaticText(self, label=u"请输入你的姓名:", pos=(60,50))
        self.text2 = wx.StaticText(self, label=u"请输入你的密码:", pos=(60,100))
        self.name = wx.TextCtrl(self,pos=(150,50),size=(175,-1))
        self.passwd = wx.TextCtrl(self,pos=(150,100),size=(175,-1))
    def OnEvenUse(self, e):
        #check
        name = self.name.GetValue()
        passwd = self.passwd.GetValue()
        param = {'act':'who', 'name':name, 'passwd':passwd}
        data = getData(self.path,param)
        if data:
            try:
                configString =  data[2]['id']+','+data[2]['gid']+','+data[2]['name']+','+data[2]['password']
                f = open('./config.ini','w')
                f.write(configString)
                f.close()
                wx.MessageBox(u'配置成功', 'success', wx.OK|wx.ICON_INFORMATION)
                self.text1.Hide()
                self.text2.Hide()
                self.name.Hide()
                self.passwd.Hide()
                self.next.Hide()
                self.pre.Hide()
                self.InitConfig()
            except:
                wx.MessageBox(u'有可能是权限不够导致程序出错\n请联系河边的老牛！', 'faild', wx.OK|wx.ICON_INFORMATION)
                self.Close()    
        else:
            wx.MessageBox(u'为查到你的信息\n请确定输入信息的正确性！！！', 'faild', wx.OK|wx.ICON_INFORMATION)
            self.name.Value = ''
            self.passwd.Value = ''
    # pre step
    def OnPre(self,e):
        self.radio1.Show()
        self.radio2.Show()
        self.text1.Hide()
        self.text2.Hide()
        self.name.Hide()
        self.passwd.Hide()
        self.pre.Hide()
        self.Bind(wx.EVT_BUTTON, self.Onnext, self.next)
        
    def InitUI(self):  
        menuBar = wx.MenuBar()  
        filemenu = wx.Menu()  
          
        fitem = filemenu.Append(wx.ID_EXIT,"Quit","Quit Applications")  
        menuBar.Append(filemenu,"&File")  
        self.SetMenuBar(menuBar)  
          
        self.Bind(wx.EVT_MENU, self.OnQuit, fitem)            
        self.Show()  
    def OnQuit(self,e):  
        self.Close()  
    def OnClick(self,event):
        #单选按钮
        text = self.radio.GetStringSelection()
        id = ''
        for data in self.datas:
            if text == data['cname']:
                id = data['id']
                break
        
        money = self.money.GetValue()
        if not isfloat(money):
            wx.MessageBox(u'金额必须为数字', u'填错了', wx.OK|wx.ICON_INFORMATION)
        else:
            description = self.description.GetValue()
            param = {'act':'insert', 'id':id, 'money':money, 'description':description, 'who':self.config[0]}
            if getStatus(self.path, param):
                wx.MessageBox(u'成功了！', 'success', wx.OK|wx.ICON_INFORMATION)
                self.money.Value = ''
                self.description.Value = ''
            else:
                wx.MessageBox(u'谁知道哪里出错了！！！', 'faild', wx.OK|wx.ICON_INFORMATION)

    def OpenUrl(self,event):
        os.startfile('http://1.qianfeng.duapp.com/?gid='+self.config[1]+'&passwd='+self.config[3])
    
    
app = wx.App(False)
frame = ExamplePanel(None)
frame.Show(True)
app.MainLoop()
