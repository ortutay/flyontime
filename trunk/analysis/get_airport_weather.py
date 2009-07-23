import os.path
import csv
import glob

weather_years = [2008, 2009]

# Load the mapping from airports to weather station WBAN codes.
airports = { }
with open('data/airport_weather_stations.csv') as f :
	data = csv.DictReader(f, delimiter=',', quotechar='"')
	for line in data :
		airports[line["airport"]] = line["wban"]
		
# Prepare output of weather data by airport and date
output = csv.writer(open('data/airport_weather.csv', 'w'))
output.writerow(('airport', 'date', 'temp', 'precip', 'snowamt', 'fog', 'rain', 'snow', 'hail', 'thunder', 'tornado'))

# Scan weather data
airportnames = airports.keys()
airportnames.sort()
for a in airportnames :
	for year in weather_years :
		fn = 'data/ncdc-noaa/gsod_' + str(year) + '/' + airports[a] + '-' + str(year) + '.op'
		if not os.path.exists(fn) :
			continue
			
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

