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

	launcher.ProcessInputLoop(bio, bout, game)

}