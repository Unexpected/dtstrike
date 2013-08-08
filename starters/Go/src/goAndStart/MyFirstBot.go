package goAndStart

import (
	"math"
)
import state "gameState"

func DoTurn(world *state.GameState, myId int) {
	world.Log("My id is : %d", myId)
	world.Log("My view of the world is %s: \n", world)

	// (1) Find my strongest military planet.
	var source *state.Planet
	var target *state.Planet
	sourceShips := 0
	myMilitaryPlanets := world.GetMyMilitary(myId)

	if len(myMilitaryPlanets) > 0 {
		for key := range myMilitaryPlanets {
			score := myMilitaryPlanets[key].NumShips
			if score > sourceShips {
				sourceShips = score
				source = myMilitaryPlanets[key]
			}
		}

		world.Log("Source Planet will be : %s", source)

		// (2) Find the weakest enemy or neutral planet.
		// and the closest also
		// the "best" between those is the target
		targetScore := math.MaxInt32
		targetPlanets := world.GetOtherPlanets(myId)
		// world.Log("Potentials Target : ", targetPlanets)
		for key := range targetPlanets {
			score := targetPlanets[key].NumShips + world.Distance(source.Id, targetPlanets[key].Id)
			if score < targetScore {
				targetScore = score
				target = targetPlanets[key]
			}
		}
		world.Log("Target Planet will be : %s", target)
	} else {
		world.Log("no more military planet, i'm dead...")
	}

	// (3) Send half the ships from my strongest planet to the weakest
	// planet that I do not own.
	if source != nil && target != nil {
		numShips := source.NumShips / 2

		// here we issue the real order to the game system
		world.IssueOrder(source.Id, target.Id, numShips)
	}
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
