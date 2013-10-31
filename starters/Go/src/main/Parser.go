package main

import (
	"bufio"
	"strconv"
	"strings"
	"fmt"
	)


/******************************************************************************
Main loop, here is done and waited everything
******************************************************************************/
func ProcessInputLoop(bio *bufio.Reader, bout *bufio.Writer, game *GameState) {
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
			DoEnd(game, players, score, status, playerturns)
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
			game.Init(params)
			DoInit(game, params)

			// send the go message
			bout.WriteString(fmt.Sprintf("go\n"))
			bout.Flush()

			// do the cleaning
			buf = make([]string, 10)[0:0]

		} else if strings.HasPrefix(line, "go") {

			game.StartTurnTimer()

			for i := range buf {
				game.ParseMapLine(buf[i])
			}
			game.Log("End Parsing %o at = %o", game.CurrentTurn(), game.RemainingTime())

			// here should have GameState ready so we can start
			DoTurn(game)
			game.Log("End Turn %o at = %o", game.CurrentTurn(), game.RemainingTime())

			// send the go message to end turn
			bout.WriteString(fmt.Sprintf("go\n"))
			bout.Flush()

			// do some cleaning
			buf = make([]string, len(buf))[0:0]
			DoBetweenTurn(game, 1)
			game.Reinit()

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
	switch lineTokens[0] {
	case "E": //E for economic planet type,
		//coordinate X and Y as float,
		//Owner ID
		//Number of Ship on planets
		//Income of the planet (reinforcement that will be sent automatically to nearest military planet of the same owner)
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
		//M for Military planet type,
		//coordinate X and Y as float,
		//Owner ID
		//Number of Ship on planets
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
		// R = Reinforcement Fleet : then Owner, NumShips, ID source Planet, ID destination Planet,
		// trip total nb of turns, remaining nb of turns
		owner, err1 := strconv.Atoi(lineTokens[1])
		numShips, err2 := strconv.Atoi(lineTokens[2])
		source, err3 := strconv.Atoi(lineTokens[3])
		target, err4 := strconv.Atoi(lineTokens[4])
		time, err5 := strconv.Atoi(lineTokens[5])
		remainingtime, err6 := strconv.Atoi(lineTokens[6])
		if err1 != nil || err2 != nil || err3 != nil || err4 != nil || err5 != nil || err6 != nil {
			l.Log("error while parsing Fleet : %s : %s|%s|%s|%s|%s|%s", line, err1, err2, err3, err4, err5, err6)
		}
		l.updateFleet(true, owner, numShips, source, target, time, remainingtime)
		break
	case "F":
		// F = Military Fleet : then Owner, NumShips, ID source Planet, ID destination Planet,
		// trip total nb of turns, remaining nb of turns
		owner, err1 := strconv.Atoi(lineTokens[1])
		numShips, err2 := strconv.Atoi(lineTokens[2])
		source, err3 := strconv.Atoi(lineTokens[3])
		target, err4 := strconv.Atoi(lineTokens[4])
		time, err5 := strconv.Atoi(lineTokens[5])
		remainingtime, err6 := strconv.Atoi(lineTokens[6])
		if err1 != nil || err2 != nil || err3 != nil || err4 != nil || err5 != nil || err6 != nil {
			l.Log("error while parsing Fleet : %s : %s|%s|%s|%s|%s|%s", line, err1, err2, err3, err4, err5, err6)
		}
		l.updateFleet(false, owner, numShips, source, target, time, remainingtime)
		break
	default:
		l.Log("error while parsing ?: %s", line)
	}
}
