Flight Delays and Holiday/Weather Statistics Analyis Scripts
------------------------------------------------------------

This is a mash-up of Airline On-Time Statistics and Delay Causes
http://www.transtats.bts.gov/OT_Delay/OT_DelayCause1.asp
http://www.transtats.bts.gov/DL_SelectFields.asp?Table_ID=236&DB_Short_Name=On-Time

and NOAA NNDC Global Summary of the Day weather reports
http://www7.ncdc.noaa.gov/CDO/cdoselect.cmd?datasetabbv=GSOD

Preparation
-----------
Make a "data/ncdc-noaa/gsod_2009" directory and download into it:
ftp://ftp.ncdc.noaa.gov/pub/data/gsod/2009/gsod_2009.tar
and extract the contents and unzip all the files within, i.e.:
	mkdir -p data/ncdc-noaa/gsod_2009
	cd data/ncdc-noaa/gsod_2009
	wget ftp://ftp.ncdc.noaa.gov/pub/data/gsod/2009/gsod_2009.tar
	tar -xf gsod_2009.tar
	rm gsod_2009.tar
	gunzip *.gz

Also put ftp://ftp.ncdc.noaa.gov/pub/data/gsod/ish-history.csv
into the "data/ncdc-noaa" directory, i.e.:
	cd data/ncdc-noaa
	wget ftp://ftp.ncdc.noaa.gov/pub/data/gsod/ish-history.csv

Make a "data/ontime/stats" directory and download into it monthly data 
tables that you can get from the transstats link above. Choose the 
fields to include: DayOfWeek, FlightDate, UniqueCarrier, FlightNum, Origin,
Dest, CRSDepTime, ArrDelay, Cancelled, CancellationCode, Diverted, CRSElapsedTime.
Unzip and save the files as 0901.csv (Jan 2009) etc. You can get as many files
as you want.

Also get from that page the data keys L_AIRPORTS.csv and L_UNIQUE_CARRIERS.csv
and put them in a "data/ontime/meta" directory.

Run
---

First match up the airport codes with weather station codes:

	python weather_stations.py

This writes out data/airport_weather_stations.csv. But, it's not
perfect and it is incomplete. We've added some hand corrections.

Then extract out the weather observation data by airport:

	python get_airport_weather.py

This writes data/airport_weather.csv wihich has the weather data
sorted by airport and date.

Then split up the on-time data into segments:

	python splitdata.py
	
This creates a data/tmp directory and writes out a few gigabytes
of the data in thousands of files. If you re-run it, you have to
remove the data/tmp directory first. It takes around an hour to
process a year's worth of FAA data.

Then run a data analysis which dumps a CSV file data/ontime.csv
with records for each data slice of interest.
	
	python analysis.py

The fields of the output file are documented below. The first few fields 
are primary keys. The remaining are statistics. It takes an hour or two.

Primary Keys
------------

The primary keys determine which set of flights the statistics apply to. 
There are eight fields that make up the primary keys: origin and 
destination airport, carrier and flight number, day of week and hour of 
departure, a holiday code, and a weather condition. However, not all 
combinations of fields are in use because there would be too much data 
to process and we don't care. Seven slices of the data are computed. A 
record falls into one of the slices:

	Origin Airport: Only the origin airport field is in use. All others
	are blank. This covers flights from that airport. It happes to
	exclude all holiday-coded days, but that probably doesn't matter.
	
	Origin & Day of Week: These two fields are in use, meaning we can
	see how individual airports vary by day of week. Other fields are
	blank.
	
	Origin & Hour: These two fields are in use, meaning we can see how
	individual airports vary in delays by departure time.
	
	Destination Airport: Only this field is in use, to get delays
	by destination. (Unlike the Origin Airport slice, this includes
	the holiday-coded days.)
	
	Flight Path: The origin and destination fields are in use; the others
	are blank. This gives delays by route.
	
	Flight: Origin, destination, carrier, and flight number fields are
	in use and give delays for a particular flight. Hopefully for any
	given carrier and flight number the origin and destination is fixed,
	but that may not be actually the case. By searching all of these
	records holding origin and destination fixed, we can also find the
	best and worst flights or carriers for a flight path.
	
	Carrier: The carrier field is in use; the other fields are blank. This
	lets us see world-wide delays by carrier.

Here is documentation for each primary key field:

origin:
The origin airport, or blank if this row is a summary across all origins. It is
a three-letter call sign. Full names are in the L_AIRPORTS.csv file.

dest:
The destination airport, or blank if this row is a summary across all destinations.

carrier:
The carrier, or blank if this row is a summary across all carriers. Present either
if origin and dest are both blank or both filled in. The carrier
follows the UniqueCarrier field, described by L_UNIQUE_CARRIERS.csv.

flightnum:
The flight number, or blank if this row is a summary across all flights. Only present
if all other fields are filled in.

dayofweek:
The day of the week, 1 for Monday and so on until 7 for Sunday. 9 is "Unknown"!

hour:
The flight departure time hour (rounded down), in two-digit 24 hour format.

holiday:
A name of a federal holiday or day near a federal holiday. Blank
indicates either all other days or all days, depending on which
other primary key fields are in use. But there are only a few
holidays extracted that there should be no major difference.
	memorial-1		The day before Memorial Day.
	memorial		Memorial Day.
	memorial+1		The day after Memorial Day.
	labor			Labor Day.
	thanksgiving-1	The day before Thanksgiving Day (i.e. Wednesday).
	thanksgiving	Thanksgiving Day (i.e. Thursday).
	thanksgiving+1	The day after Thanksgiving Day (i.e. Friday).
	thanksgiving+2	Two days after Thanksgiving Day (i.e. Saturday).
	thanksgiving+3	Three days after Thanksgiving Day (i.e. Sunday).
	christmas-1		The day before Christmas Day.
	christmas		Christmas Day.
	christmas+1		The day after Christmas Day.

condition:
The weather condition, or "all" meaning all flights regardless of
weather condition.

The weather conditions are

	"origin_" or "dest_" indicating whether the conditions
	are relative to the origin or destination airport
	
	plus 
	
	"any", "fog", "rain", "snow", "hail", "thunder", "tornado"
	"any" means any of the other six conditions.

	plus
	
	"_yes" or "_no".

For instance:
	'origin_fog_yes' means any flights for which the origin
	airport had fog. The origin may have also had any other
	weather condition. And the conditions may be correlated,
	so thunder probably entails rain, for instance.

	'dest_rain_no' means any flights for which the destination
	airport did not have rain, though it may have had any other
	condition.

	'origin_any_no' means the flights where the origin had
	none of the indicated weather conditions, i.e. it was a clear day

	'origin_any_yes' means the flights where the origin had
	at least one of the indicated weather conditions.


Statistics Fields
-----------------

firstdate, lastdate:
The first and last date of a matching flight, in YYYY-MM-DD format.
(For the weather condition rows, the date range is for all flights
regardless of weather condition. That is, they all of the conditions
have the same firstdate and lastdate values.)

count:
Number of occurrences of flights. The weather conditions are not 
mutually exclusive. A day with rain could also have been a day with 
thunder. So, don't add up or compare the counts. But you can divide the 
counts by the "all" count to get the rate at which each weather condition 
occurs (except some days may have no data, so this may over/under- 
count), or compare the yes to the no days, which is probably the most 
accurate (i.e. yes/(yes+no) or no/(yes+no)).

pct_cancel:
Overall proportion of flights cancelled or diverted (0.0 to 1.0) for this
airport or route or flight.

pct_20mindelay:
Overall proportion of flights with a 20 minute or more arrival delay.

pct_ontime:
Overall proportion of flights with a 5 minute or less arrival delay.

delay_{15,85}thpctile, delay_median:
Overall arrival delay, in minutes, at three percentiles in the distribution
(15%, 50%=median, 85%). The median is a good representation
of the most common delay passengers will face. The 15th percentile is for
the luckiest passengers, that is, the 15% that arrive the fastest. The 85th
percentile is for the unluckiest passengers, that is, the 15% that arrive
the latest.

Loading Into MySQL
------------------

This data can be loaded into a MySQL table with the following schema:

CREATE TABLE `ontime` (
  `origin` char(3) collate ascii_bin,
  `dest` char(3) collate ascii_bin,
  `carrier` varchar(3) collate ascii_bin,
  `flightnum` int(11),
  `dayofweek` tinyint(4),
  `hour` char(2) collate ascii_bin,
  `holiday` enum('memorial-1','memorial','memorial+1','labor','thanksgiving-1','thanksgiving','thanksgiving+1','thanksgiving+1','thanksgiving+2','thanksgiving+3','christmas-1','christmas','christmas+1') collate ascii_bin,
  `firstdate` char(10) COLLATE ascii_bin NOT NULL,
  `lastdate` char(10) COLLATE ascii_bin NOT NULL,
  `condition` enum('all','origin_any_no','origin_any_yes','origin_fog_no','origin_fog_yes','origin_snow_no','origin_snow_yes','origin_thunder_no','origin_thunder_yes','origin_hail_no','origin_hail_yes','origin_tornado_no','origin_tornado_yes','dest_any_no','dest_any_yes','dest_fog_no','dest_fog_yes','dest_snow_no','dest_snow_yes','dest_thunder_no','dest_thunder_yes','dest_hail_no','dest_hail_yes','dest_tornado_no','dest_tornado_yes','origin_rain_no','origin_rain_yes','dest_rain_no','dest_rain_yes') collate ascii_bin NOT NULL,
  `count` int(11) NOT NULL,
  `pct_cancel` float default NULL,
  `pct_20mindelay` float default NULL,
  `pct_ontime` float default NULL,
  `delay_15thpctile` int(11) default NULL,
  `delay_median` int(11) default NULL,
  `delay_85thpctile` int(11) default NULL,
  PRIMARY KEY(`origin`,`dest`,`carrier`,`flightnum`,`dayofweek`,`hour`,`holiday`,`condition`),
  KEY `carrier` (`carrier`,`flightnum`,`dest`,`condition`),
  KEY `carrier_2` (`carrier`,`origin`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii COLLATE=ascii_bin

And loaded with:

mysqlimport dbname --delete --ignore-lines 1 --fields-terminated-by=, \
	/full/path/to/ontime.csv

mysqlimport will load this into the table named "ontime", so that better
be the name of the table you used.

You can issue a SELECT statement and get back a single row for any
of the statistics you need. Just, look back above and see which
slice of the data you want to get a statistic for, i.e. for all
flights between two airports, or for all flights of a carrier, etc.

The blank cells come in as blanks or zeros, so querying is a bit
weird. How to query the data depends on the slice. For the fields
not of interest, you must still match the column to "" or 0 or you
may get multiple rows, from other slices. Here are some examples.
(The condition column gets backquoted because MySQL interprets it as a
keyword.)

	By Origin Airport: Data for Philadelphia International Airport
	SELECT pct_ontime FROM ontime WHERE origin="PHL" and dest="" and
	carrier="" and flightnum=0 and dayofweek=0 and hour=0 and holiday=""
	and `condition`="all";
	
	Origin & Day of Week: Data for PHL on Mondays
	SELECT pct_ontime FROM ontime WHERE origin="PHL" and dest="" and
	carrier="" and flightnum=0 and dayofweek=1 and hour=0 and holiday=""
	and `condition`="all";
	
	Origin & Hour: Data for PHL 2 pm flights
	SELECT pct_ontime FROM ontime WHERE origin="PHL" and dest="" and
	carrier="" and flightnum=0 and dayofweek=0 and hour=14 and holiday=""
	and `condition`="all";
	
	Destination Airport: Data for San Francisco arrivals
	SELECT pct_ontime FROM ontime WHERE origin="" and dest="SFO" and
	carrier="" and flightnum=0 and dayofweek=0 and hour=0 and holiday=""
	and `condition`="all";
	
	Flight Path: Data for PHL to SFO flights
	SELECT pct_ontime FROM ontime WHERE origin="PHL" and dest="SFO" and
	carrier="" and flightnum=0 and dayofweek=0 and hour=0 and holiday=""
	and `condition`="all";
	
	Flight: Data for US Airways #949 from PHL to SFO
	SELECT pct_ontime FROM ontime WHERE origin="PHL" and dest="SFO" and
	carrier="US" and flightnum=949 and dayofweek=0 and hour=0 and holiday=""
	and `condition`="all";
	
	Carrier: Data for US Airways flights
	SELECT pct_ontime FROM ontime WHERE origin="" and dest="" and
	carrier="US" and flightnum=0 and dayofweek=0 and hour=0 and holiday=""
	and `condition`="all";
	
The weather.csv file can be loaded into a table
with this structure:

CREATE TABLE `weather` (
 `airport` varchar(3) NOT NULL,
 `station` varchar(4) NOT NULL,
 `airport_descr` text NOT NULL,
 `station_descr` text NOT NULL,
 `wban` text NOT NULL,
 PRIMARY KEY (`airport`)
)

and

mysqlimport dbname --delete --ignore-lines 1 --fields-terminated-by=, \
	--fields-optionally-enclosed-by="\"" \
	weather.csv

Data Dump
---------

Currently you can find dumps of the latest files at:
http://razor.occams.info/code/flyontime
