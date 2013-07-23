package launcher

import (
	"bufio"
	"fmt"
	"log"
	"math/rand"
	"os"
	//"runtime/pprof"
	"testing"
	"time"
)
import state "gameState"

// @TODO : To be transformed in Benchmark and real "test" when real data will be ready
func TestAutoProcessInputLoop(t *testing.T) {

	rand.Seed(time.Now().UnixNano())
	bout := bufio.NewWriter(os.Stdout)
	debugLogger := *log.New(bout, "\n", log.Lmicroseconds)

	fmt.Println("Init Done")

	/*
		f, err := os.Create("cpuProfile.out")
		if err != nil {
			log.Fatal(err)
		}
		f2, err2 := os.Create("memProfile.out")
		if err2 != nil {
			log.Fatal(err2)
		}
		pprof.StartCPUProfile(f)

		defer pprof.StopCPUProfile()


	*/

	for i := 1; i < 4; i++ {
		filename := fmt.Sprintf("..\\..\\maps\\map%d.txt", i)
		testFile, _ := os.Open(filename)
		bio := bufio.NewReader(testFile)

		bout.WriteString("Test with File :" + filename + "\n")
		game := *state.New(true, debugLogger, bout)
		ProcessInputLoop(bio, bout, game, debugLogger)

		_ = bout.Flush()
		testFile.Close()
	}
	/*
		pprof.WriteHeapProfile(f2)
		f.Close()*/
}
