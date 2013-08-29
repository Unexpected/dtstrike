package launcher

/*

Written by Kevin Lansard for the SIX IT Challenge

*/
import (
	"bufio"
	"fmt"
	"log"
	"math/rand"
	"os"
	"testing"
	"time"
)

import state "gameState"

// simple test with map1
func TestAutoProcessInputLoopMap1(t *testing.T) {
	rand.Seed(time.Now().UnixNano())
	bout := bufio.NewWriter(os.Stdout)
	debugLogger := *log.New(bout, "\n", log.Lmicroseconds)

	fmt.Println("Init Done")

	filename := "../../maps/map1.txt"
	testFile, _ := os.Open(filename)
	bio := bufio.NewReader(testFile)

	bout.WriteString("Test with File :" + filename + "\n")

	//--------------
	{
		game := *state.New(false, debugLogger, bout)
		ProcessInputLoop(bio, bout, game)
	}
	//--------------

	_ = bout.Flush()
	testFile.Close()

}

// simple test with map2
func TestAutoProcessInputLoopMap2(t *testing.T) {
	rand.Seed(time.Now().UnixNano())
	bout := bufio.NewWriter(os.Stdout)
	debugLogger := *log.New(bout, "\n", log.Lmicroseconds)

	fmt.Println("Init Done")

	filename := "../../maps/map2.txt"
	testFile, _ := os.Open(filename)
	bio := bufio.NewReader(testFile)

	bout.WriteString("Test with File :" + filename + "\n")

	//--------------
	{
		game := *state.New(false, debugLogger, bout)
		ProcessInputLoop(bio, bout, game)
	}
	//--------------

	_ = bout.Flush()
	testFile.Close()

}

// simple test with map3
func TestAutoProcessInputLoopMap3(t *testing.T) {
	rand.Seed(time.Now().UnixNano())
	bout := bufio.NewWriter(os.Stdout)
	debugLogger := *log.New(bout, "\n", log.Lmicroseconds)

	fmt.Println("Init Done")

	filename := "../../maps/map3.txt"
	testFile, _ := os.Open(filename)
	bio := bufio.NewReader(testFile)

	bout.WriteString("Test with File :" + filename + "\n")

	//--------------
	{
		game := *state.New(false, debugLogger, bout)
		ProcessInputLoop(bio, bout, game)
	}
	//--------------

	_ = bout.Flush()
	testFile.Close()

}

// Huge Test, very complex... benchmark it
// around 4000 turns with multiple 1000 fleets
// if you need the replay file
func BenchmarkProcessInputLoop(b *testing.B) {

	b.StopTimer()
	b.ResetTimer()

	rand.Seed(time.Now().UnixNano())

	// dev null output
	nullFile, _ := os.Open(os.DevNull)
	bout := bufio.NewWriter(nullFile)
	debugLogger := *log.New(bout, "\n", log.Lmicroseconds)

	fmt.Println("Init Done")

	for i := 0; i < b.N; i++ {

		filename := "../../maps/replay.txt"
		testFile, _ := os.Open(filename)
		bio := bufio.NewReader(testFile)

		fmt.Printf("Start Iter : %d\n", i)
		b.StartTimer()
		//--------------
		{
			game := *state.New(false, debugLogger, bout)
			ProcessInputLoop(bio, bout, game)
		}
		//--------------
		b.StopTimer()

		testFile.Close()
	}

}
