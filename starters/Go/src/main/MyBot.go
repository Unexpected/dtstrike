package main

import (
	"math"
)

/******************************************************************************
DoInit : do the init of your bot
******************************************************************************/
func DoInit(world *GameState, params map[string]int) {
	world.Log("init do nothing")
}

/******************************************************************************
Main method, you should do the main algo here.
******************************************************************************/
func DoTurn(world *GameState) {

	world.Log("My view of the world is : %s\n", world)


	// (0) Send my reinforcements to a target planet
	myEconomicPlanets := world.GetMyEconomics()
	// i get my non military planets
	for inc := range myEconomicPlanets {// for each
		if myEconomicPlanets[inc].NumShips > 50 { // if it's big enough
				target:=world.GetMyNearestMilitary(myEconomicPlanets[inc]) 
				if (target!=nil){
					world.IssueOrder(myEconomicPlanets[inc].Id, target.Id, myEconomicPlanets[inc].NumShips-50)
				}
			}
		}


	// (1) If we currently have a fleet in flight, just do nothing.
	for key := range world.listFleet {
		if world.listFleet[key].Owner == 1 { // if it's mine
			if world.GetAllPlanets()[world.listFleet[key].Source].Owner == 1 {
				// and it's from my planet
				if !world.GetAllPlanets()[world.listFleet[key].Source].Type {
					// and is a military
					return // do nothing
				}
			}
		}
	}

	// (2) Find my strongest military planet.
	var source *Planet
	var target *Planet
	sourceShips := 0
	myMilitaryPlanets := world.GetMyMilitary()

	//world.Log("My Military : %s", myMilitaryPlanets)
	if len(myMilitaryPlanets) > 0 {
		for key := range myMilitaryPlanets {
			score := myMilitaryPlanets[key].NumShips
			if score > sourceShips {
				sourceShips = score
				source = myMilitaryPlanets[key]
			}
		}
		world.Log("Source Planet will be : %s", source)

		if source != nil {
			// (2) Find the weakest enemy or neutral planet
			// the "best" between those is the target
			targetScore := math.MaxInt32
			targetPlanets := world.GetOtherPlanets()
			//world.Log("Potentials Target : ", targetPlanets)
			for key := range targetPlanets {
				score := targetPlanets[key].NumShips
				if score < targetScore {
					targetScore = score
					target = targetPlanets[key]
				}
			}
			world.Log("Target Planet will be : %s", target)
		} else {
			world.Log("no more military planet, i'm dead...")
		}
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

/******************************************************************************
DoBetweenTurn : do some cleaning, the world itself will be cleaned up jute afte,
it's out of the timer, so all non critical path calculations should be done here
******************************************************************************/
func DoBetweenTurn(world *GameState, id int) {

}

/******************************************************************************
DoEnd : do wining dance :-)
******************************************************************************/
func DoEnd(world *GameState, players, score, status, playerturns string) {
	//world.Log("World has ended : ", *world)
	world.Log("players : %s", players)
	world.Log("score : %s", score)
	world.Log("status : %s", status)
	world.Log("playerturns : %s", playerturns)
}
