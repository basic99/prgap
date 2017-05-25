
#import os, commands
import glob
import string
import os
import _pg

con2 = _pg.connect(dbname='prgap', host='localhost', user='postgres')

os.putenv("GISBASE", "/usr/local/grass-6.2.1")
os.putenv("GISRC", "/data2/grassrc")
os.putenv("PATH", "/usr/local/grass-6.2.1/bin:/usr/local/grass-6.2.1/scripts:/usr/local/bin:/usr/bin:/bin")

querystr = 'select sppcode, gname from pr_infospp'
print querystr
result = con2.query(querystr)
res_1 = result.getresult()  
#print  res_1[8][0]
for i in res_1:
  #print 'pd_' + i[0]   
  print i[1]
  command1 = "ls /data/puerto_rico/PERMANENT/cellhd/pd_%s" % i[0]
  #print command1
  os.system(command1) 
  


  
  