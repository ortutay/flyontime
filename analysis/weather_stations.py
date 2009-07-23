# Match up three-letter airport codes to three-letter weather
# station call signs, as best we can.

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

# Read manually set station codes.
airport_station_codes = { }
airport_manually_set = { }
if os.path.exists('additional_airport_weather_stations.txt') :
	with open('additional_airport_weather_stations.txt') as f :
		for line in f :
			fields = line.strip().split()
			if len(fields) == 2 :
				airport_station_codes[fields[1]] = fields[0]

# Scan weather station list to find WBAN codes. Some airports
# seem to have more than one entry, and I'm not sure what governs
# which has a corresponding weather data file. Collect all of
# the codes that match.
wban_call = { }
wban_descr = { }
wban_state = { }
with open('data/ncdc-noaa/ish-history.csv') as f :
	data = csv.DictReader(f, delimiter=',', quotechar='"')
	for line in data :
		if line["WBAN"] == "99999" :
			continue
		wban = line["USAF"]+"-"+line["WBAN"]
		wban_call[wban] = line["CALL"]
		wban_descr[wban] = line["STATION NAME"] + ", " + line["CTRY"] + ", " + line["STATE"]
		wban_state[wban] = line["STATE"]
with open('data/ncdc-noaa/ish-history.csv') as f :
	data = csv.DictReader(f, delimiter=',', quotechar='"')
	for line in data :
		if line["WBAN"] == "99999" :
			continue
		wban = line["USAF"]+"-"+line["WBAN"]
		cx = line["CALL"]
		if len(cx) < 3 :
			continue
		if cx in airports :
			airports[cx].append(wban)
		if cx[0] == "K" and cx[1:] in airports :
			airports[cx[1:]].append(wban)
		if cx[0] == "P" and cx[1:] in airports :
			airports[cx[1:]].append(wban)
with open('data/ncdc-noaa/ish-history.csv') as f :
	data = csv.DictReader(f, delimiter=',', quotechar='"')
	for line in data :
		if line["WBAN"] == "99999" :
			continue
		wban = line["USAF"]+"-"+line["WBAN"]
		cx = line["CALL"]
		if cx in airport_station_codes :
			a = airport_station_codes[cx]
			if not a in airport_manually_set :
				airports[a] = []
			airports[a].append(wban)
			airport_manually_set[a] = True


# Prepare output of the matching between airports and station.
output = csv.writer(open('data/airport_weather_stations.csv', 'w'))
output.writerow(('airport', 'station', 'airport_descr', 'station_descr', 'wban'))

# Scan weather data to pick out the first matching WBAN code for
# an airport that actually has data.
airportnames = airports.keys()
airportnames.sort()
for a in airportnames :
	found_wban = None
	for year in weather_years :
		for wban in airports[a] :
			fn = 'data/ncdc-noaa/gsod_' + str(year) + '/' + wban + '-' + str(year) + '.op'
			if not os.path.exists(fn) :
				continue
			
			if not a in airport_manually_set :
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
			
			found_wban = wban
			break # take weather from first matching data file for the airport
	if found_wban != None :
		output.writerow((a, wban_call[found_wban], airport_descr[a], wban_descr[found_wban], found_wban))
	#else :
	#	print a, airport_descr[a]

