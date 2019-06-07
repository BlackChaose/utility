# pip install pdfkit
# sudo apt-get install wkhtmltopdf
#import pdfkit
#pdfkit.from_url('http://google.com', 'out.pdf')

import pdfkit
import time
from random import randint
for year in range (2016, 2018):
    for month in range (1, 12):
        fileout = "./out/diary-" + str(month) + "-"  + str(year) +".pdf"
        httppath = "https://www.gismeteo.ru/diary/4368/" + str(year) + "/" + str(month) + "/"
        print("from " + httppath)
        pdfkit.from_url(httppath, fileout)
        print("iter" + str(year) + ":::" + str(month))
        time.sleep(randint(0,9))
        print("->")
