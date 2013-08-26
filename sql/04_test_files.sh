#!/bin/bash

echo Nettoyage des logs
rm -f /root/dtstrike/logs/api.log

echo Supppression des replays
cd /root/dtstrike/replays
rm -rf 0/

echo Suppression des uploads
cd /root/dtstrike/uploads
rm -rf 0/

echo Creation des soumissions
for i in {1..28}; do
	mkdir -p 0/$i/
done
for i in {1..27..2}; do
	cp /root/entry_js.zip 0/$i/entry.zip
done
for i in {2..28..2}; do
	cp /root/entry_java.zip 0/$i/entry.zip
done
chown -R www-data:www-data *
find -name "*.zip" | wc -l

echo Ended !
