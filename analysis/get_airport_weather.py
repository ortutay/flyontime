import os.path
import csv
import glob

weather_years = [2008, 2009]

# Get list of airport codes in the data.
airports = { }
airport_descr = { }
with open('data/ontime/meta/L_AIRPORTS.csv') as f :
	data = csv.DictReader(f, delimiter=',', quotechar='"')
	for line in data :
		airports[line["Code"]] = []
		airport_descr[line["Code"]] = line["Description"]
		
# Scan weather station list to find the WBAN code. Some airports
# seem to have more than one entry, and I'm not sure what governs
# which has a corresponding weather data file.
wban_call = { }
wban_descr = { }
wban_state = { }
with open('data/ncdc-noaa/ish-history.csv') as f :
	data = csv.DictReader(f, delimiter=',', quotechar='"')
	for line in data :
		if line["WBAN"] == "99999" :
			continue
		cx = line["CALL"]
		if len(cx) < 3 :
			continue
		wban = line["USAF"]+"-"+line["WBAN"]
		wban_call[wban] = line["CALL"]
		wban_descr[wban] = line["STATION NAME"] + ", " + line["CTRY"] + ", " + line["STATE"]
		wban_state[wban] = line["STATE"]
		if cx in airports :
			airports[cx].append(wban)
		if cx[0] == "K" and cx[1:] in airports :
			airports[cx[1:]].append(wban)


# Prepare output of weather data by airport and date
output = csv.writer(open('../data/airport_weather.csv', 'w'))
output.writerow(('airport', 'date', 'temp', 'precip', 'snowamt', 'fog', 'rain', 'snow', 'hail', 'thunder', 'tornado'))

output2 = csv.writer(open('../data/weather.csv', 'w'))
output2.writerow(('airport', 'station', 'airport_descr', 'station_descr', 'wban'))

# Scan weather data
airportnames = airports.keys()
airportnames.sort()
for a in airportnames :
	lastwban = None
	for year in weather_years :
		for wban in airports[a] :
			fn = 'data/ncdc-noaa/gsod_' + str(year) + '/' + wban + '-' + str(year) + '.op'
			if not os.path.exists(fn) :
				continue
				
			# Check that airport state matches station state to catch most
			# of the errors of identifying an international airport code
			# with a domestic weather station call sign.
			try :
				a_loc, a_nam = airport_descr[a].split(": ")
				a_city, a_state = a_loc.split(", ")
				if a_state != wban_state[wban] :
					#print a_state, wban_state[wban], a_nam, wban_descr[wban]
					continue
			except :
				continue
				
			lastwban = wban
			with open(fn) as f :
				f.readline()
				for line in f :
					date = line[14:22]
					
					temp = line[24:30]
					if temp == "9999.9" :
						temp = None
					else :
						temp = float(temp)
						
					precip = line[118:123]
					if precip == "99.99" :
						precip = None # maybe zero
					else :
						precip = float(precip)
						
					snowamt = line[125:130]
					if snowamt == "999.9" :
						snowamt = None # maybe zero
					else :
						snowamt = float(snowamt)
						
					fog = (line[132] == "1")
					rain = (line[133] == "1")
					snow = (line[134] == "1")
					hail = (line[135] == "1")
					thunder = (line[136] == "1")
					tornado = (line[137] == "1")
						
					output.writerow((a, date, temp, precip, snowamt, fog, rain, snow, hail, thunder, tornado))
			break # take weather from first matching data file for the airport
	if lastwban != None :
		output2.writerow((a, wban_call[lastwban], airport_descr[a], wban_descr[lastwban], lastwban))


