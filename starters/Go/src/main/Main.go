package main

import (
	"bufio"
	"flag"
	"fmt"
	"log"
	"math/rand"
	"os"
	"time"
)
import state "gameState"
import launcher "launcher"

const APP_VERSION = "0.1"

// The flag package provides a default help printer via -h switch
var versionFlag *bool = flag.Bool("v", false, "Print the version number.")
var debugFlag *bool = flag.Bool("d", false, "log detail info in current directory with file name = GoAndStartBotxxx.log")
var debugLogger log.Logger

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

	bio := bufio.NewReader(os.Stdin)
	bout := bufio.NewWriter(os.Stdout)
	// setup the world
	game := *state.New(*debugFlag, debugLogger, bout)

	launcher.ProcessInputLoop(bio, bout, game, debugLogger)

}

/*
func ProcessInputLoop(bio *bufio.Reader, bout *bufio.Writer, game state.GameState) {

	for !stop {
		line, err := bio.ReadString('\n')
		if err != nil || strings.HasPrefix(line, "stop") {
			// end of the game , say goodbye
			bot.DoEnd(&game, line)
			stop = true
		} else if strings.HasPrefix(line, "go") {
			id, err := strconv.Atoi(strings.Replace(strings.Replace(strings.Split(line, " ")[1], "\r", "", 1), "\n", "", 1)) // parse ID
			handleError("Parse UserID", err)

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
*/
