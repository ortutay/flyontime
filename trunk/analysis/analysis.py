import csv
import sys
import glob
import os
import os.path

def mean(data) :
	if len(data) < 10 :
		return None
	m = 0.0
	for x in data :
		if x == True :
			x = 1
		elif x == False :
			x = 0
		m += x
	m /= float(len(data))
	return m

def percentiles(data) :
	if len(data) < 10 :
		return [None,None,None]
	data.sort()
	return (data[int(len(data)*.15)], data[int(len(data)*.50)], data[int(len(data)*.85)])

# Load airport weather CSV file into a hashtable.
Weather = { }
with open('../data/airport_weather.csv') as f :
	data = csv.DictReader(f)
	for line in data :
		if not line["airport"] in Weather :
			Weather[line["airport"]] = { }
		Weather[line["airport"]][line["date"]] = { }
		for w in ('fog', 'rain', 'snow', 'hail', 'thunder', 'tornado') :
			Weather[line["airport"]][line["date"]][w] = (line[w] == 'True')

# The weather segments.
obs_cols = ["all", "origin_any", "origin_fog", "origin_rain", "origin_snow", "origin_hail", "origin_thunder", "origin_tornado", "dest_any", "dest_fog", "dest_rain", "dest_snow", "dest_hail", "dest_thunder", "dest_tornado", "either_any"]

# Initialize the output file. The first set of column headers
# must match the fields stored in the filenames as created by
# splitdata.py.
writer = csv.writer(open('../data/ontime.csv', 'w'), delimiter=',', quotechar='"')
writer.writerow(
	  ['origin', 'dest', 'carrier', 'flightnum', 'dayofweek', 'hour', 'holiday',
	   'firstdate', 'lastdate',
	   'condition',
	   'count', 'pct_cancel', 'pct_20mindelay', 'pct_ontime', 'delay_15thpctile', 'delay_median', 'delay_85thpctile']
	   )

# Go through each of the spliced data files. Each spliced file gets summarized
# as a single row in the output file.
for fn in glob.glob('data/tmp/*.csv') :
	# Extract out the part of the file name that serves as the
	# primary key fields for the statistics we will generate
	# from the file.
	key = fn[len('data/tmp/'):len(fn)-len('.csv')].split(",")
	
	first_flight = None
	last_flight = None
	
	cancelled = [([],[]) for x in obs_cols]
	delayed = [([],[]) for x in obs_cols]
	ontime = [([],[]) for x in obs_cols]
	arrdelay = [([],[]) for x in obs_cols]
	
	with open(fn) as f :
		data = csv.DictReader(f, delimiter=',', quotechar='"')
		for line in data :
			if first_flight == None or line["FL_DATE"] < first_flight :
				first_flight = line["FL_DATE"]
			if last_flight == None or line["FL_DATE"] > last_flight :
				last_flight = line["FL_DATE"]
		
			# If we don't have origin and destination weather data
			# for this flight, we'll just include it in the total
			# column.
			w = [
				True,
				None, None, None, None, None, None, None, None,
				None, None, None, None, None, None, None, None
			]
		
			if line["ORIGIN"] in Weather and line["DEST"] in Weather :
				d = line["FL_DATE"].replace("-", "")
				if d in Weather[line["ORIGIN"]] and d in Weather[line["DEST"]] :
					# Binary weather observations for this day.
					w = [
						True,
						Weather[line["ORIGIN"]][d]["fog"] or Weather[line["ORIGIN"]][d]["rain"] or Weather[line["ORIGIN"]][d]["snow"] or Weather[line["ORIGIN"]][d]["hail"] or Weather[line["ORIGIN"]][d]["thunder"] or Weather[line["ORIGIN"]][d]["tornado"],
						Weather[line["ORIGIN"]][d]["fog"],
						Weather[line["ORIGIN"]][d]["rain"],
						Weather[line["ORIGIN"]][d]["snow"],
						Weather[line["ORIGIN"]][d]["hail"],
						Weather[line["ORIGIN"]][d]["thunder"],
						Weather[line["ORIGIN"]][d]["tornado"],
						Weather[line["DEST"]][d]["fog"] or Weather[line["DEST"]][d]["rain"] or Weather[line["DEST"]][d]["snow"] or Weather[line["DEST"]][d]["hail"] or Weather[line["DEST"]][d]["thunder"] or Weather[line["DEST"]][d]["tornado"],
						Weather[line["DEST"]][d]["fog"],
						Weather[line["DEST"]][d]["rain"],
						Weather[line["DEST"]][d]["snow"],
						Weather[line["DEST"]][d]["hail"],
						Weather[line["DEST"]][d]["thunder"],
						Weather[line["DEST"]][d]["tornado"],
						Weather[line["ORIGIN"]][d]["fog"] or Weather[line["ORIGIN"]][d]["rain"] or Weather[line["ORIGIN"]][d]["snow"] or Weather[line["ORIGIN"]][d]["hail"] or Weather[line["ORIGIN"]][d]["thunder"] or Weather[line["ORIGIN"]][d]["tornado"] \
							or Weather[line["DEST"]][d]["fog"] or Weather[line["DEST"]][d]["rain"] or Weather[line["DEST"]][d]["snow"] or Weather[line["DEST"]][d]["hail"] or Weather[line["DEST"]][d]["thunder"] or Weather[line["DEST"]][d]["tornado"],
						]
			
			# Mark off whether the flight was cancelled/diverted or delayed.
			can = line["CANCELLED"] == "1.00" or line["DIVERTED"] == "1.00"
			isdelayed = not can and (float(line["ARR_DELAY"]) >= 20.0)
			isontime = not can and (float(line["ARR_DELAY"]) <= 5.0)
			for i in range(len(w)) :
				if w[i] == False :
					cancelled[i][0].append(can)
					delayed[i][0].append(isdelayed)
					ontime[i][0].append(isontime)
				elif w[i] == True :
					cancelled[i][1].append(can)
					delayed[i][1].append(isdelayed)
					ontime[i][1].append(isontime)
					
			# Add arrival delay time info.
			if line["ARR_DELAY"] != "" :
				for i in range(len(w)) :
					if w[i] == False :
						arrdelay[i][0].append(float(line["ARR_DELAY"]))
					elif w[i] == True :
						arrdelay[i][1].append(float(line["ARR_DELAY"]))

	for obs in range(len(obs_cols)) :
		for yn in (0, 1) :
			if obs == 0 and yn == 0 : # the "all" condition represents all flights and has no False part
				continue

			row = []
			row.extend(key)
			row.append(first_flight)
			row.append(last_flight)
			
			o = obs_cols[obs]
			if yn == 0 :
				o += "_no"
			elif obs > 0 :
				o += "_yes"
			row.append(o)
			
			# Drop rows where we have insufficient data to
			# do any analysis.
			if len(cancelled[obs][yn]) < 3 :
				continue
			
			row.append(len(cancelled[obs][yn]))
			row.append(mean(cancelled[obs][yn]))
			row.append(mean(delayed[obs][yn]))
			row.append(mean(ontime[obs][yn]))
			row.extend(percentiles(arrdelay[obs][yn]))
			writer.writerow(row)


