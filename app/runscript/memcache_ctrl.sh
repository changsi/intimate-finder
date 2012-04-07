#!/bin/sh
# SysV script for control of Spiral Applications
#	****MEMCACHED ****
#
# chkconfig: 345 95 05
# description: Run startup and shutdown the memcached as part of normal linux 
#   startup/shutdown.
#
# To be executed by ROOT user
#
#set -x

#to start please type: (example)
#sudo /bin/sh memcache_ctrl.sh SPIRAL WORD_TO_CATEGORY_IDS start

# Source function library.
. /etc/rc.d/init.d/functions

#params
PROJECT_ID=$1
MEMCACHE_TYPE=$2
MEMCACHE_ACTION=$3

if [ "$PROJECT_ID" = "" -o "$MEMCACHE_TYPE" = "" -o "$MEMCACHE_ACTION" = "" ]
then
	echo "Product type must be defined.";
	echo "Usage: memcache_ctrl.sh FACEBOOK_APP NETWORKUSERID_TO_TRENDING_OBJECT_IDS <memcache action>";
	echo "Usage: memcache_ctrl.sh FACEBOOK_APP NETWORKUSERID_TO_TOP_TRENDING_OBJECT_ID <memcache action>";
	echo "Usage: memcache_ctrl.sh FACEBOOK_APP NETWORKUSERID_TO_FOLKS <memcache action>";
	echo "Usage: memcache_ctrl.sh FACEBOOK_APP NETWORKUSERID_TO_TRIBE_FRIENDS <memcache action>";
	exit 0;
fi


# Source Function
FACEBOOK_APP_HOME="/var/www/html/facebook_app/"
MEMCACHE_CONFIG_FILE_PATH="$FACEBOOK_APP_HOME/app/config/memcache_config.php"
prog="memcached"
LOCKFILE="/var/lock/subsys/${prog}_${PROJECT_ID}_${MEMCACHE_TYPE}"
user="envio"
pid=0
newpid=0
RETVAL=0

[ -f /usr/bin/$prog ] || exit 0

function getMemcacheSettings {
    file="$1"
    array_name="$2"
    field_name="$3"

    settings=`grep -A 4 -P "$array_name([ ]*)=([ ]*)array([ ]*)\(" "$file"`

    field_value_raw=`echo "$settings" | grep -P "('|\")$field_name('|\")" | awk -F "=>" '{print $2}'`
    field_value=`echo "$field_value_raw" | awk -F "\"" '{print $2}'`
    if [ "$field_value" = "" ]
    then
        field_value=`echo "$field_value_raw" | awk -F "'" '{print $2}'`

        if [ "$field_value" = "" ]
        then
            field_value=`echo "$field_value_raw" | awk -F " " '{print $1}' | awk -F "," '{print $1}'`
        fi
    fi

    echo $field_value
}

MEMCACHE_PORT=`getMemcacheSettings "$MEMCACHE_CONFIG_FILE_PATH" "$MEMCACHE_TYPE" "PORT"`

#in the future we can create a external config file with this options
#option "m": Use <num> MB memory max to use for object storage; the default is 64 megabytes.
#option "v": verbose
#option "p": Listen on TCP port <num>, the default is port 11211.
#option "f": Use <factor> as the multiplier for computing the sizes of memory chunks that items are stored in. A lower value may  result  in less  wasted memory depending on the total amount of memory available and the distribution of item sizes.  The default is 1.25.
#option "n": Allocate a minimum of <size> bytes for the item key, value, and flags. The default is 48. If you have a lot of small  keys  and values, you can get a significant memory efficiency gain with a lower value. If you use a high chunk growth factor (-f option), on the other hand, you may want to increase the size to allow a bigger percentage of your items to  fit  in  the  most  densely packed (smallest) chunks.
MEMCACHE_OPTIONS="-m 1024 -v -p $MEMCACHE_PORT -f 1.1 -n 24" 

if [ "$MEMCACHE_PORT" = "" ]
then
    echo "Invalid memcache type"
    exit 1
fi

start() {
    	echo -n $"Attempting to start Application $prog on port $MEMCACHE_PORT: "
    	pid=`ps -eafl | grep $prog | grep -v grep | grep $MEMCACHE_PORT | awk {'print $4'}`
    
	if [ -z $pid ]; then
	    	su - $user -c "/usr/bin/$prog $MEMCACHE_OPTIONS > /dev/null 2>&1 &"
	    	RETVAL=$?
		sleep 3
		newpid=`ps -eafl | grep $prog | grep -v grep | grep $MEMCACHE_PORT| awk {'print $4'}`

		if [ $newpid ]; then
			touch $LOCKFILE
        		success	
        		echo
			sleep 3
			echo
		else
			failure
			echo
		fi
		
		return $RETVAL
	elif [ $pid ]; then
		echo
		echo -n $"$prog is already running, cannot start a duplicate."
		warning "$prog is already running, cannot start a duplicate."
		echo
	else
		failure
		echo
	fi

	return $RETVAL
}

stop() {
    pid=`ps -eafl | grep $prog | grep -v grep | grep $MEMCACHE_PORT| awk {'print $4'}`
    
    if test "x$pid" != x; then
        echo -n $"Stopping Application $prog: "
        kill -9 $pid
        echo_success
        echo
    else
        echo -n $"Nothing to stop, $prog already stopped."
        warning "$prog is already stopped."
        echo
    fi
    RETVAL=$?
    rm -f $LOCKFILE
    return $RETVAL
}

case "$MEMCACHE_ACTION" in
	start)
	    start
	    ;;
	
	stop)
	    stop
	    ;;
	
	status)
		pid=`ps -eafl | grep $prog | grep -v grep | grep $MEMCACHE_PORT| awk {'print $4'}`
		if [ -z $pid ]; then
			 echo "memcached is stopped"
		else
			echo "memcached (pid $pid) is running..."
		fi
	    ;;
	exists)
		pid=`ps -eafl | grep $prog | grep -v grep | grep $MEMCACHE_PORT| awk {'print $4'}`
		if [ -z $pid ]; then
			 echo "0"
		else
			echo "1"
		fi
	    ;;    
	restart)
	    stop
	    start
	    ;;
	condrestart)
	    if [ -f $LOCKFILE ]; then
			stop
			start
	    fi
	    ;;
	
	*)
	    echo $"Usage: $0 {start|stop|restart|condrestart|status}"
	    exit 1

esac

exit $RETVAL
