#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo "Building engine"
cd $DIR/server/ITWarsEngine/ant
ant -f engine.xml
echo ".. OK"
echo

echo "Building sample bots"
cd $DIR/sample_bots
ant
echo ".. OK"
echo

# echo "Building LocalTester"
# cd $DIR/LocalTester/ant
# ant
# echo ".. OK"
# echo

echo "Building & Coping starter kits"
cd $DIR/starters
ant copy_to_site
echo ".. OK"
echo
