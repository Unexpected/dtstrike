package launcher

import (
	"bufio"
	"fmt"
	"log"
	"strconv"
	"strings"
)
import bot "goAndStart"
import state "gameState"

func ProcessInputLoop(bio *bufio.Reader, bout *bufio.Writer, game state.GameState, debugLogger log.Logger) {
	var stop bool = false
	for !stop {
		line, err := bio.ReadString('\n')
		if err != nil || strings.HasPrefix(line, "stop") {
			// end of the game , say goodbye
			bot.DoEnd(&game, line)
			stop = true
		} else if strings.HasPrefix(line, "go") {
			id, err := strconv.Atoi(strings.Replace(strings.Replace(strings.Split(line, " ")[1], "\r", "", 1), "\n", "", 1)) // parse ID
			if err != nil {
				debugLogger.Panicln("error in parsing GO line : ", err)
				stop = true
			}

			// here should have GameState ready so we can start
			bot.DoTurn(&game, id)
			bout.WriteString(fmt.Sprintf("go\n"))
			bout.Flush()

			// do some cleaning
			bot.DoBetweenTurn(&game, id)
		} else {
			// here parse GameState
			game.ParseMapLine(line)
		}
	}
}
