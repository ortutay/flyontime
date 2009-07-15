import csv
import sys
import glob
import os
import os.path

holidays = {
	"2008-05-25": "memorial-1",
	"2008-05-26": "memorial",
	"2008-05-27": "memorial+1",
	"2008-09-01": "labor",
	"2008-11-26": "thanksgiving-1",
	"2008-11-27": "thanksgiving",
	"2008-11-28": "thanksgiving+1",
	"2008-11-29": "thanksgiving+2",
	"2008-11-30": "thanksgiving+3",
	"2008-12-24": "christmas-1",
	"2008-12-25": "christmas",
	"2008-12-26": "christmas+1",
}	

# This fails if the dir already exists, which is good because
# we need to start with an empty directory.
os.mkdir("data/tmp")

filecache = []
def openfile(fn, fieldnames) :
	for cfn, cfo, cfw in filecache :
		if cfn == fn :
			return cfw
	
	if len(filecache) == 15 :
		cfn, cfo, cfw = filecache[0]
		cfo.close()
		filecache.pop(0)
		
	newfile = not os.path.exists(fn)
	
	fo = open(fn, "a")
	
	if newfile :
		fo.write(",".join(fieldnames) + "\n")
		
	fw = csv.DictWriter(fo, fieldnames)
					
	filecache.append( (fn, fo, fw) )
	
	return fw

# Load in the on-time records one by one and split it out into temporary
# files according to how we want to splice the data.
for fn in sorted(glob.glob('data/ontime/stats/*.csv')) :
	print fn + "..."
	with open(fn) as f :
		data = csv.DictReader(f, delimiter=',', quotechar='"')
		for line in data :

			hour = line["CRS_DEP_TIME"][0:2]
			holiday = ""
			if line["FL_DATE"] in holidays :
				holiday = holidays[line["FL_DATE"]]
				
			# Split data by:
			#   origin, destination, carrier, flight#, day of week, hour, holiday
			for key in (
				(line["ORIGIN"], "", "", "", "", "", holiday),
				(line["ORIGIN"], "", "", "", line["DAY_OF_WEEK"], "", ""),
				(line["ORIGIN"], "", "", "", "", hour, ""),
				("", line["DEST"], "", "", "", "", ""),
				(line["ORIGIN"], line["DEST"], "", "", "", "", ""),
				(line["ORIGIN"], line["DEST"], line["UNIQUE_CARRIER"], line["FL_NUM"], "", "", ""),
				("", "", line["UNIQUE_CARRIER"], "", "", "", ""),
				) :
			
				keyfn = "data/tmp/" + ",".join(key) + ".csv"
				wr = openfile(keyfn, data.fieldnames)
				wr.writerow(line)

for cfn, cfo, cfw in filecache :
	cfo.close()

