#!/bin/bash

echo "Building engine"
cd server\ITWarsEngine\ant
ant -f engine.xml
echo ".. OK"
echo

echo "Building sample bots"
cd ..\..\..\sample_bots
ant
echo ".. OK"
echo

echo "Building LocalTester"
cd ..\LocalTester\ant
ant
echo ".. OK"
echo

echo "Building & Coping starter kits"
cd ..\..\starters
ant copy_to_site
echo ".. OK"
echo
