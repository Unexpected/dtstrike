package goAndStart

import (
	//"fmt"
	"math"
)
import state "gameState"

func DoTurn(world *state.GameState, myId int) {
	world.Log("doTurn My id is : %d", myId)
	world.Log("doTurn world : \n", *world)

	// (1) Find my strongest military planet.
	var source *state.Planet
	sourceShips := 0
	myMilitaryPlanets := world.GetMyMilitary(myId)
	for key := range myMilitaryPlanets {
		score := myMilitaryPlanets[key].NumShips
		if score > sourceShips {
			sourceShips = score
			source = myMilitaryPlanets[key]
		}
	}
	world.Log("Source Planet will be : ", source)

	// (2) Find the weakest enemy or neutral planet.
	// and the closest also
	// the "best" between those two is the target
	var target *state.Planet
	targetScore := math.MaxInt32
	targetPlanets := world.GetOtherPlanet(myId)
	world.Log("Potentials Target : ", targetPlanets)
	for key := range targetPlanets {
		world.Log("Trying : ", key)
		world.Log("Trying : ", targetPlanets[key])
		score := targetPlanets[key].NumShips - world.Distance(source.Id, targetPlanets[key].Id)
		if score < targetScore {
			targetScore = score
			target = targetPlanets[key]
		}
	}
	world.Log("Target Planet will be : ", target)

	// (3) Send half the ships from my strongest planet to the weakest
	// planet that I do not own.
	if source != nil && target != nil {
		numShips := source.NumShips / 2
		world.IssueOrder(source.Id, target.Id, numShips)

		// (4) update my world
		// decrease ships in planet
		source.NumShips -= numShips
		distance := world.Distance(source.Id, target.Id)
		// add a fleet
		world.UpdateFleet(myId, numShips, source.Id, target.Id, distance, distance)

	}
	world.Log("Potential world after work : \n", *world)
}

func DoEnd(world *state.GameState, data string) {
	// do wining dance :-)
	world.Log("World has ended : ", *world)
	world.Log("finish : " + data)
}

func DoBetweenTurn(world *state.GameState, id int) {

	// do some cleaning on the world (cleaning previous state)
	world.Reinit()

}
