#!/bin/sh

ORIG_DIR=`pwd`
cd `dirname $0`
# While 'stop_worker' does not exists, loop on worker
while [ ! -f "stop_worker" ]; do
  echo "======================================================================="
  echo "======================================================================="
  echo "==========                  STARTING WORKER                  =========="
  echo "======================================================================="
  echo "======================================================================="
  python release_stale_jails.py
  python worker.py -t -n -1
done
# 'stop_worker' file found, exit loop
cd $ORIG_DIR
