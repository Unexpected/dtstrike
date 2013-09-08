package myBot

/*
Written by Kevin Lansard for the SIX IT Challenge
to be used either
- as a example of unit test
- or in conjunction with the full goeclipse package where the test maps are available in ../../maps/
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

// simple test with map1
func TestAutoProcessInputLoopMap1(t *testing.T) {
	rand.Seed(time.Now().UnixNano())
	bout := bufio.NewWriter(os.Stdout)
	debugLogger := *log.New(bout, "\n", log.Lmicroseconds)

	fmt.Println("Init Done")

	filename := "../../maps/map1.txt"
	testFile, _ := os.Open(filename)
	defer testFile.Close()
	bio := bufio.NewReader(testFile)

	bout.WriteString("Test with File :" + filename + "\n")

	//--------------
	{
		game := NewGameState(false, debugLogger, bout)
		ProcessInputLoop(bio, bout, game)
	}
	//--------------

	_ = bout.Flush()
	

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
		game := NewGameState(false, debugLogger, bout)
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
		game := NewGameState(true, debugLogger, bout)
		ProcessInputLoop(bio, bout, game)
	}
	//--------------

	_ = bout.Flush()
	testFile.Close()

}

// benchmark it thousand of time the map3 file.
// it could be better to use a huge replay file ask me for one if you need it.
// with go test myBot -bench . -benchtime 10s -cpuprofile prof.out -memprofile mem.out -benchmem
// it's arount 10000 replay of the 3 turns

// use : go tool pprof ./myBot.test src/myBot/prof.out
// then type web to get a graphical display of where the time is spent
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

		filename := "../../../maps/map3.txt"
		testFile, _ := os.Open(filename)
		bio := bufio.NewReader(testFile)

		fmt.Printf("Start Iter : %d\n", i)
		b.StartTimer()
		//--------------
		{
			game := NewGameState(true, debugLogger, bout)
			ProcessInputLoop(bio, bout, game)
		}
		//--------------
		b.StopTimer()

		testFile.Close()
	}

}
