MYSQLDB=test
MYSQLOPTS="-u root"
mysqlimport $MYSQLDB $MYSQLOPTS -d --fields-terminated-by=, --fields-optionally-enclosed-by="\"" `pwd`/../data/enums.csv
mysqlimport $MYSQLDB $MYSQLOPTS --delete --ignore-lines 1 --fields-terminated-by=, --fields-optionally-enclosed-by="\"" `pwd`/../data/weather.csv
mysqlimport $MYSQLDB $MYSQLOPTS --ignore-lines 1 -d --fields-terminated-by=, --fields-optionally-enclosed-by="\"" `pwd`/../data/ontime.csv
#mysqlimport $MYSQLDB $MYSQLOPTS --ignore-lines 1 -d --fields-terminated-by=, --fields-optionally-enclosed-by="\"" -c YEAR,QUARTER,MONTH,DAYOFMONTH,DAYOFWEEK,FLightDATE,UNIQUECARRIER,AIRLINEID,CARRIER,TAILNUM,FLightNUM,ORIGIN,ORIGINCITYNAME,ORIGINSTATE,ORIGINWAC,DEST,DESTCITYNAME,DESTSTATE,DESTWAC,CRSDEPTIME,DEPTIME,DEPDELAY,DepDelayMinutes,DEPDEL15,DEPartureDELAYGROUPs,DEPTIMEBLK,TAXIOUT,WHEELSOFF,WHEELSON,TAXIIN,CRSARRTIME,ARRTIME,ARRDELAY,ArrDelayMinutes,ARRDEL15,ARRivalDELAYGROUPs,ARRTIMEBLK,CANCELLED,CANCELLATIONCODE,DIVERTED,CRSELAPSEDTIME,ACTUALELAPSEDTIME,AIRTIME,FLIGHTS,DISTANCE,DISTANCEGROUP,CARRIERDELAY,WEATHERDELAY,NASDELAY,SECURITYDELAY,LATEAIRCRAFTDELAY `pwd`/../data/logs.csv
