TOT=0
NBWIN=0
NBLOST=0

. ./run.sh.conf

for MAP in `find . -name "map*.txt" -type f`
do
  TOT=$(( ${TOT} + 1 ))
  NB=`echo ${MAP} | cut -d '/' -f3`
  rm replay_test.js

  bot1="java -Xms128m - Xmx128m -jar bots/CrazyBot.jar"
  bot2="java -Xms128m - Xmx128m -jar bots/DedicatedBot.jar"
  bot3="java -Xms128m - Xmx128m -jar bots/DefensiveBot.jar"
  bot4="java -Xms128m - Xmx128m -jar bots/DispersionBot.jar"
  bot5="java -Xms128m - Xmx128m -jar bots/FlightyBot.jar"
  bot6="java -Xms128m - Xmx128m -jar bots/LooterRageBot.jar"
  bot7="java -Xms128m - Xmx128m -jar bots/PatientBot.jar"
  bot8="java -Xms128m - Xmx128m -jar bots/WarriorRageBot.jar"

  echo "Nb Engine ${NB} sur la MAP ${MAP}"

  case "$NB" in
  "2")  	
     java -server -Xms128m -Xmx512m -Duser.language=en -jar engine.jar ${MAP} 1000 1000 replay_log.txt "${bot_cmd}" "${bot1}" 1>>replay_test.js 2>replay_err_test.txt
  	;;
  "3")
     java -server -Xms128m -Xmx512m -Duser.language=en -jar engine.jar ${MAP} 1000 1000 replay_log.txt "${bot_cmd}" "${bot1}" "${bot2}" 1>>replay_test.js 2>replay_err_test.txt
  	;;
  "4")
     java -server -Xms128m -Xmx512m -Duser.language=en -jar engine.jar ${MAP} 1000 1000 replay_log.txt "${bot_cmd}" "${bot2}" "${bot3}" "${bot4}" 1>>replay_test.js 2>replay_err_test.txt
      ;;
  "5")
     java -server -Xms128m -Xmx512m -Duser.language=en -jar engine.jar ${MAP} 1000 1000 replay_log.txt "${bot_cmd}" "${bot4}" "${bot5}" "${bot6}" "${bot7}" 1>>replay_test.js 2>replay_err_test.txt
      ;;
  "6")
     java -server -Xms128m -Xmx512m -Duser.language=en -jar engine.jar ${MAP} 1000 1000 replay_log.txt "${bot_cmd}" "${bot4}" "${bot5}" "${bot6}" "${bot7} ${bot8}" 1>>replay_test.js 2>replay_err_test.txt
      ;;
  esac


  result=$(awk -F"rank" '{print $3}' replay_test.js | tail -n1 | cut -d ","  -f1 | cut -d "[" -f2)

  if [[ "x$result" == "x1" ]]
  then
  	NBWIN=$(( ${NBWIN} + 1 ))
  else
  	NBLOST=$(( ${NBLOST} + 1 ))
  fi

  echo "WIN : ${NBWIN} / LOST : ${NBLOST} - TOTAL : ${TOT}"

done

