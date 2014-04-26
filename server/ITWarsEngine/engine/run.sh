#!/bin/bash

# TODO :
#    - specialiser le run pour chaque StarterKit

map=""
bot_cmd=""
nb_players=""
run_conf="run.sh.conf"
err_file="error.txt"
replay_log="replay_log.txt"
replay_js="visu/replay.js"

function error {
	echo "!!!!!!!!!!!!!!!!! Game crashed !!!!!!!!!!!!!!!!!"
	echo ""
	cat ${err_file}
	echo ""
	echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
	exit
}

function usage {
	echo "Wrong usage"
	echo "Usage: run.bat MapName \"command for My${nb_players}\"command for MySecondBot\""
	exit
}

function config_error {
	echo "!!!!!!!!!!!!!!!!! Error !!!!!!!!!!!!!!!!!!"
	echo ""
	echo "Invalid configuration:"
	echo "$1"
	echo "Check the configuration file '$run_conf'"
	echo ""
	echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
	exit
}

function load_check_config {
	# Load config (map and cmd_bot variables)
	. ${run_conf}

	# Check config
	if [ "x$map" == "x" ]
	then
		config_error "Map file not specified"
	fi
	if [ ! -r $map ]
	then
		config_error "Map file $map not found"
	fi
	if [ "x$bot_cmd" == "x" ]
	then
		config_error "Invalid command bot"
	fi

	# Extract nb players from map
	# remove everything starting at the last '/'
	nb_players=${map%/*}
	# keep everything after the last '/'
	nb_players=${nb_players##*/}

	if [ "x$nb_players" == "x" ]
	then
		config_error "Player number not specified"
	fi

	if [ $(expr ${nb_players} : '^[2-6]$') == "0" ]
	then
		config_error "Invalid player number (${nb_players})"
	fi

	# Define other bots -- you may replace these commands with your own bots
	bot1="java -jar bots/CrazyBot.jar"
    bot2="java -jar bots/DedicatedBot.jar"
    bot3="java -jar bots/DefensiveBot.jar"
    bot4="java -jar bots/DispersionBot.jar"
    bot5="java -jar bots/FlightyBot.jar"
    bot6="java -jar bots/LooterRageBot.jar"
    bot7="java -jar bots/PatientBot.jar"
    bot8="java -jar bots/WarriorRageBot.jar"
}

function start_game {
	echo "Starting game with $nb_players players on map $map"
	echo "In the replay file, your bot is always player 1"
	echo ""

	# runEngine
	set err_file=replay_err.txt

	if [ ${nb_players} == 2 ]
	then
		run_cmd=("${bot_cmd}" "${bot1}")
	fi
	if [ ${nb_players} == 3 ]
	then
		run_cmd=("${bot_cmd}" "${bot1}" "${bot2}")
	fi
	if [ ${nb_players} == 4 ]
	then
		run_cmd=("${bot_cmd}" "${bot1}" "${bot2}" "${bot3}")
	fi
	if [ ${nb_players} == 5 ]
	then
		run_cmd=("${bot_cmd}" "${bot1}" "${bot2}" "${bot3}" "${bot4}")
	fi
	if [ ${nb_players} == 6 ]
	then
		run_cmd=("${bot_cmd}" "${bot1}" "${bot2}" "${bot3}" "${bot4}" "${bot5}")
	fi

	# Cleaning previous game
	rm ${err_file} 2>/dev/null
	rm ${replay_log} 2>/dev/null
	rm ${replay_js} 2>/dev/null

	echo -n "var replayJson=" > $replay_js

    java -Duser.language=en -jar engine.jar ${map} 1000 1000 ${replay_log} "${run_cmd[@]}" 1>>${replay_js} 2>${err_file}

    errorcode=$?
	if [ ${errorcode} -le 0 ]
	then
		error
	else
		winner=${errorcode}
	fi
}

# Launch replay
function replay {
# TODO: Check if replay_err is empty
#for %%A in (%err_file%) do if not %%~zA==0 goto error

	echo "===================================================================="
	echo "===================================================================="
	echo "               Game over - Winner is player $winner"
	echo "You may watch the replay by opening visu/index.html in your browser"
	echo "===================================================================="
	echo "===================================================================="
}

load_check_config
start_game
replay
