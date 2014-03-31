#!/bin/bash

rm *.class 2>>/dev/null
javac *.java
jar -cf Bot.jar *.class
rm *.class 2>>/dev/null
