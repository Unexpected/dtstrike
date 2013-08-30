package launcher

import (
	"bufio"
	"fmt"
	"strconv"
	"strings"
)

import bot "goAndStart"
import state "gameState"

func ProcessInputLoop(bio *bufio.Reader, bout *bufio.Writer, game state.GameState) {
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
			bot.DoEnd(&game, players, score, status, playerturns)
			stop = true
			
		} else if strings.HasPrefix(line, "ready") {

			params := make(map[string]int, 3)

			for i := range buf {
				if strings.HasPrefix(buf[i], "*") {
					line2Parse:=strings.Replace(strings.Replace(strings.Replace(buf[i], "*", "", 1), "\r", "", 1), "\n", "", 1)
					lineTokens := strings.Split(line2Parse, ":")
					game.Log("Param %s : %s",lineTokens[0],lineTokens[1])
					params[lineTokens[0]], _ = strconv.Atoi(lineTokens[1])
				} else {
					// ignore
				}
			}

			// do the init
			game.Init(params)
			bot.DoInit(&game, params)

			// send the go message
			bout.WriteString(fmt.Sprintf("go\n"))
			bout.Flush()
			buf = make([]string, 10)[0:0]

		} else if strings.HasPrefix(line, "go") {

			game.StartTurnTimer()
			
			for i := range buf {
				game.ParseMapLine(buf[i])
			}
			game.Log("End Parsing at = %o", game.RemainingTime())

			// here should have GameState ready so we can start
			bot.DoTurn(&game)
			game.Log("End Turn at = %o", game.RemainingTime())

			// send the go message to end turn
			bout.WriteString(fmt.Sprintf("go\n"))
			bout.Flush()

			// do some cleaning
			buf = make([]string, len(buf))[0:0]
			bot.DoBetweenTurn(&game, 1)
		}  else {
		line = strings.Replace(strings.Replace(line, "\r", "", 1), "\n", "", 1)
		buf = append(buf, line)
		}
	}
}
