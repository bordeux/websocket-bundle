#!/bin/bash
### BEGIN INIT INFO
# Provides:          sf-websocket
# Required-Start:    $local_fs $network
# Required-Stop:     $local_fs
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: Symfony Websocket server
# Description:       Symfony2 Websocket server
# Processname:       sf-websocket
# Author:			 Krzysztof Bednarczyk <krzysztof@bednarczyk.me>
### END INIT INFO



DAEMON_PATH="/usr/local/bin"

DAEMON=php
DAEMONOPTS="/your/path/to/project/app/console bordeux:websocket:start"

NAME=sf-websocket
DESC="Symfony2 websocket"
PIDFILE=/tmp/$NAME.pid
SCRIPTNAME=/etc/init.d/$NAME
SERVICE_USER=www-data

case "$1" in
start)
	printf "%-50s" "Starting $NAME..."
	cd $DAEMON_PATH

    su -m $SERVICE_USER -s /bin/bash -c "echo 0 >$PIDFILE"
    su -m $SERVICE_USER -s /bin/bash -c "$DAEMON $DAEMONOPTS >/dev/null 2>&1 & echo \$! >$PIDFILE"
    PID=`cat $PIDFILE`

	#echo "Saving PID" $PID " to " $PIDFILE
        if [ -z $PID ]; then
            printf "%s\n" "Fail"
        else
            printf "%s\n" "Ok"
            printf "PID is %s\n" PID
        fi
;;
status)
    printf "%-50s" "Checking $NAME..."
    if [ -f $PIDFILE ]; then
        PID=`cat $PIDFILE`
        if [ -z "`ps axf | grep ${PID} | grep -v grep`" ]; then
            printf "%s\n" "Process dead but pidfile exists"
        else
            echo "Running"
        fi
    else
        printf "%s\n" "Service not running"
    fi
;;
stop)
    printf "%-50s" "Stopping $NAME"
        PID=`cat $PIDFILE`
        cd $DAEMON_PATH
    if [ -f $PIDFILE ]; then
        kill -HUP $PID
        printf "%s\n" "Ok"
        rm -f $PIDFILE
    else
        printf "%s\n" "pidfile not found"
    fi
;;

restart)
  	$0 stop
  	$0 start
;;

*)
    echo "Usage: $0 {status|start|stop|restart}"
    exit 1
esac