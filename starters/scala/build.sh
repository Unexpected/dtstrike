#!/bin/bash

rm *.class 2>/dev/null
scalac *.scala
jar -cf MyBot.jar *.scala *.class
rm *.class 2>/dev/null

