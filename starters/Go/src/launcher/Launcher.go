package launcher

import (
	"bufio"
	"fmt"
	"strconv"
	"strings"
	"time"
)

import bot "goAndStart"
import state "gameState"

func ProcessInputLoop(bio *bufio.Reader, bout *bufio.Writer, game state.GameState) {
	var stop bool = false
	var accuCounter int

	for !stop {
		line, err := bio.ReadString('\n')
		if err != nil || strings.HasPrefix(line, "stop") {
			// end of the game , say goodbye
			bot.DoEnd(&game, line)
			stop = true
		} else if strings.HasPrefix(line, "go") {
			id, err := strconv.Atoi(strings.Replace(strings.Replace(strings.Split(line, " ")[1], "\r", "", 1), "\n", "", 1)) // parse ID
			if err != nil {
				game.Log("error in parsing GO line : %s", err)
				stop = true
			}
			// here should have GameState ready so we can start
			perf1 := time.Now().Nanosecond()
			bot.DoTurn(&game, id)

			// send the go message
			bout.WriteString(fmt.Sprintf("go\n"))
			bout.Flush()
			accuCounter += time.Now().Nanosecond() - perf1
			game.Log("Perf= %o", accuCounter)
			accuCounter = 0
			// do some cleaning
			bot.DoBetweenTurn(&game, id)
		} else {
			perf1 := time.Now().Nanosecond()
			// here parse GameState
			game.ParseMapLine(line)
			accuCounter += time.Now().Nanosecond() - perf1
		}
	}
}
