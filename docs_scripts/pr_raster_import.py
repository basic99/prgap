
#import os, commands
import glob
import string
import os
import _pg

con2 = _pg.connect(dbname='prgap', host='localhost', user='postgres')

os.putenv("GISBASE", "/usr/local/grass-6.2.1")
os.putenv("GISRC", "/data2/grassrc")
os.putenv("PATH", "/usr/local/grass-6.2.1/bin:/usr/local/grass-6.2.1/scripts:/usr/local/bin:/usr/bin:/bin")

mylist = glob.glob('/data/NewData/corrupted/PRGAP_Species_Grids/*.tif')

#species_name = 'sphaerodactylus gaigeae'
#print species_name
#querystr = 'select sppcode from pr_infospp where gname like ' + '\'' + species_name + '\''
#print querystr
#result = con2.query(querystr)
#res_1 = result.getresult()
#if (res_1):
#   print species_name, sppcode
#else: 
 #  print 'error reading                           ' + species_name
 #  print querystr

command_start = "g.region -d"
os.system(command_start)


for i in mylist:
   #print i
   parts = i.split('/')
   #print parts[5]
   filename = parts[5].split('.')
   sppcode = filename[0]
   
   command1 = "r.in.gdal input=%s output=i_%s" % (parts[5], sppcode)
   print command1
   os.system(command1)  
   command2 = "cat /home/grassmonkey/sw_misc/pr_pd_recl | r.reclass input=i_%s output=r_%s" % (sppcode, sppcode)
   print command2
   os.system(command2)
   command3 = "r.mapcalc 'pd_%s=r_%s'" % (sppcode, sppcode)
   print command3
   os.system(command3)
   command4 = "g.remove rast=r_%s" % sppcode
   print command4
   os.system(command4)
   command5 = "g.remove rast=i_%s" % sppcode
   print command5
   os.system(command5)
   command6 = "cat /home/grassmonkey/pr_misc/pd_color | r.colors map=pd_%s  color=rules" % sppcode
   print command6
   os.system(command6)