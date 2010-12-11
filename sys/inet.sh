#!/bin/bash
# part of miyoko's SystemCP
# inet.sh -- compile a list of interfaces.

interfaces=$(/sbin/ifconfig -s | awk '{ print $1 }' | sed -e 's/Iface//' | awk '{ if($0) { print $0 }}')

for face in $interfaces
	do
		/sbin/ifconfig $face | sed -e 's/'$face'/<strong>'$face'<\/strong>/'
	done
