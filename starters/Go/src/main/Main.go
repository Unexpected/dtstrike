package main

import (
	"bufio"
	"flag"
	"fmt"
	"log"
	"math/rand"
	"os"
	"strconv"
	"strings"
	"time"
)

/******************************************************************************
No need to change this file, it's doing all the boilerplate work for you !
Launch the bot with -d option and it will generate a debug file with everything logged
Launch it without and it will be compliant to rules (no writing files)
******************************************************************************/

const APP_VERSION = "0.1"

// The flag package provides a default help printer via -h switch
var versionFlag *bool = flag.Bool("v", false, "Print the version number.")
var debugFlag *bool = flag.Bool("d", false, "log detail info in current directory with file name = GoAndStartBotxxx.log")
var debugLogger log.Logger

/******************************************************************************
Only parse, flag & Co, setup the data & logger
******************************************************************************/
func main() {
	flag.Parse() // Scan the arguments list

	if *versionFlag {
		fmt.Println("Version:", APP_VERSION)
	}
	rand.Seed(time.Now().UnixNano())

	if *debugFlag { // create the logger
		filename := fmt.Sprintf("GoAndStartBot%d.log", rand.Int31n(999))
		debugFile, _ := os.Create(filename)
		debugFile, _ = os.OpenFile(filename, os.O_WRONLY|os.O_APPEND|os.O_CREATE, 0660)
		debugLogger = *log.New(debugFile, "\n", log.Lmicroseconds)
		defer debugFile.Close()
	}

	// buffer the in/out
	bio := bufio.NewReader(os.Stdin)
	bout := bufio.NewWriter(os.Stdout)
	// setup the world
	game := newGameState(*debugFlag, debugLogger, bout)

	ProcessInputLoop(bio, bout, *game)

}

/******************************************************************************
Main loop, here is done and waited everything
******************************************************************************/
func ProcessInputLoop(bio *bufio.Reader, bout *bufio.Writer, game GameState) {
	var stop bool = false
	buf := make([]string, 3)[0:0]

	for !stop {
		line, err := bio.ReadString('\n')
		if err != nil || strings.HasPrefix(line, "end") {
			players, _ := bio.ReadString('\n')
			score, _ := bio.ReadString('\n')
			status, _ := bio.ReadString('\n')
			playerturns, _ := bio.ReadString('\n')
			_, _ = bio.ReadString('\n')

			// end of the game , say goodbye
			DoEnd(&game, players, score, status, playerturns)
			stop = true

		} else if strings.HasPrefix(line, "ready") {

			params := make(map[string]int, 3)

			for i := range buf {
				if strings.HasPrefix(buf[i], "*") {
					line2Parse := strings.Replace(strings.Replace(strings.Replace(buf[i], "*", "", 1), "\r", "", 1), "\n", "", 1)
					lineTokens := strings.Split(line2Parse, ":")
					game.Log("Param %s : %s", lineTokens[0], lineTokens[1])
					params[lineTokens[0]], _ = strconv.Atoi(lineTokens[1])
				} else {
					// ignore
				}
			}

			// do the init
			game.init(params)
			DoInit(&game, params)

			// send the go message
			bout.WriteString(fmt.Sprintf("go\n"))
			bout.Flush()
			
			// do the cleaning			
			buf = make([]string, 10)[0:0]

		} else if strings.HasPrefix(line, "go") {

			game.startTurnTimer()

			for i := range buf {
				game.ParseMapLine(buf[i])
			}
			game.Log("End Parsing at = %o", game.RemainingTime())

			// here should have GameState ready so we can start
			DoTurn(&game)
			game.Log("End Turn at = %o", game.RemainingTime())

			// send the go message to end turn
			bout.WriteString(fmt.Sprintf("go\n"))
			bout.Flush()

			// do some cleaning
			buf = make([]string, len(buf))[0:0]
			DoBetweenTurn(&game, 1)
			game.reinit()
			
		} else {
			line = strings.Replace(strings.Replace(line, "\r", "", 1), "\n", "", 1)
			buf = append(buf, line)
		}
	}
}

/*
This parse the input line correclty,
*/
func (l *GameState) ParseMapLine(line string) {
	lineTokens := strings.Split(line, " ")
	fleetType:=false
	switch lineTokens[0] {
	case "E": //E pour le Type de planète économique,
		//2 coordonnées X et Y sous forme de float,
		//ID du propriétaire,
		//Nb de vaisseaux actuellement sur la planète,
		//Income de la planète.
		X, err1 := strconv.ParseFloat(lineTokens[1], 32)
		Y, err2 := strconv.ParseFloat(lineTokens[2], 32)
		Owner, err3 := strconv.Atoi(lineTokens[3])
		NumShips, err4 := strconv.Atoi(lineTokens[4])
		Income, err5 := strconv.Atoi(lineTokens[5])
		if err1 != nil || err2 != nil || err3 != nil || err4 != nil || err5 != nil {
			l.Log("error while parsing Economic : %s : %s|%s|%s|%s|%s", line, err1, err2, err3, err4, err5)
		}
		l.updatePlanet(true, X, Y, Owner, NumShips, Income)
		break

	case "M":
		//M pour le type Militaire, coordonnées X et Y, ID du propriétaire, Nb de vaisseaux actuellement sur la planète
		X, err1 := strconv.ParseFloat(lineTokens[1], 32)
		Y, err2 := strconv.ParseFloat(lineTokens[2], 32)
		Owner, err3 := strconv.Atoi(lineTokens[3])
		NumShips, err4 := strconv.Atoi(lineTokens[4])
		if err1 != nil || err2 != nil || err3 != nil || err4 != nil {
			l.Log("error while parsing Military : %s : %s|%s|%s|%s", line, err1, err2, err3, err4)
		}
		l.updatePlanet(false, X, Y, Owner, NumShips, 0)
		break
	case "R":
		fleetType=true
	case "F":
		// R ou F pour Fleet, propriétaire, nombre de vaisseaux, ID planète source, ID planète destination,
		// longueur totale du voyage (en nb de tours), nb de tours restants avant arrivée
		owner, err1 := strconv.Atoi(lineTokens[1])
		numShips, err2 := strconv.Atoi(lineTokens[2])
		source, err3 := strconv.Atoi(lineTokens[3])
		target, err4 := strconv.Atoi(lineTokens[4])
		time, err5 := strconv.Atoi(lineTokens[5])
		remainingtime, err6 := strconv.Atoi(lineTokens[6])
		if err1 != nil || err2 != nil || err3 != nil || err4 != nil || err5 != nil || err6 != nil {
			l.Log("error while parsing Fleet : %s : %s|%s|%s|%s|%s|%s", line, err1, err2, err3, err4, err5, err6)
		}
		l.updateFleet(fleetType,owner, numShips, source, target, time, remainingtime)
		break
	default:
		l.Log("error while parsing ?: %s", line)
	}
}
