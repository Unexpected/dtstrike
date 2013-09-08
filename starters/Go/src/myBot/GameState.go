package myBot

import (
	"bufio"
	"fmt"
	"log"
	"math"
	"time"
)

/******************************************************************************
World State, keep track of things and abstract access to input/output
******************************************************************************/
type GameState struct {
	listID    Planets   // direct access by ID, sure to get all planets
	listOwner []Planets // direct access by owner of their planets

	listFleet []Fleet // all "in flight" fleets

	// Internal book-keeping
	logger log.Logger
	debug  bool
	bout   *bufio.Writer
	//initFlag     bool
	nbPlanet int

	// params
	loadtime int
	turntime int
	turns    int

	// time related
	starttime   int
	currentturn int
}

/******************************************************************************
Get remaining time until end of turn
******************************************************************************/
func (l GameState) RemainingTime() int {

	return time.Now().Nanosecond() - l.starttime
}

/******************************************************************************
Get my planets that can send ships
******************************************************************************/
func (l *GameState) GetMyMilitary() (MyMilitary Planets) {
	// get planets for user 1, me :-)
	for key := range l.listOwner[1] {
		if !l.listOwner[1][key].Type {
			//filter on military only
			MyMilitary = append(MyMilitary, l.listOwner[1][key])
		}
	}
	return
}

/******************************************************************************
Get planets of others
******************************************************************************/
func (l *GameState) GetOtherPlanets() (potentielTargets Planets) {
	// targets are concat of planet that are not mine
	for key := range l.listOwner {
		if key != 1 {
			potentielTargets = append(potentielTargets, l.listOwner[key]...)
		}
	}
	return
}

/******************************************************************************
Get all planets
******************************************************************************/
func (l *GameState) GetAllPlanets() Planets {
	return l.listID
}

/******************************************************************************
Print my view of the world
******************************************************************************/
func (l GameState) String() string {
	//test := fmt.Sprintf("GameState ID:\n%s\nMili:\n%s\nEco:\n%s\nOwners:\n%s\nFleets:\n%s\n", l.listID, l.listMili, l.listEco, l.listOwner, l.listFleet)
	test := fmt.Sprintf("GameState ID:\n%s\nFleets:\n%s\n", l.listID, l.listFleet)
	return test
}

/******************************************************************************
Start timer & turn number
******************************************************************************/
func (l *GameState) StartTurnTimer() {
	l.currentturn++
	l.Log("Start turn : %o", l.currentturn)
	l.starttime = time.Now().Nanosecond()
}

/******************************************************************************
Start timer & turn number
******************************************************************************/
func (l GameState) CurrentTurn() int {
	return l.currentturn
}

/******************************************************************************
clean data struct for next turn
******************************************************************************/
func (l *GameState) Reinit() {

	l.listFleet = make([]Fleet, 0)

	for key := range l.listOwner {
		length := len(l.listOwner[key])
		l.listOwner[key] = make(Planets, length)[0:0]
	}

	l.nbPlanet = 0
	//l.initFlag = false
}

// Returns the distance between two planets, rounded up to the next highest
// integer. This is the number of discrete time steps it takes to get
// between the two planets.
func (l GameState) Distance(sourceID, targetID int) int {
	//l.Log("CalculateDistance : %d", sourceID, targetID)
	source := l.listID[sourceID]
	destination := l.listID[targetID]
	dx := source.X - destination.X
	dy := source.Y - destination.Y
	return int(math.Ceil(math.Sqrt(dx*dx + dy*dy)))
}

// Sends an order to the game engine. An order is composed of a source
// planet number, a destination planet number, and a number of ships. A
// few things to keep in mind:
// * you can issue many orders per turn if you like.
// * the planets are numbered starting at zero, not one.
// * you must own the source planet. If you break this rule, the game
// engine kicks your bot out of the game instantly.
// * you can't move more ships than are currently on the source planet.
// * the ships will take a few turns to reach their destination. Travel
// is not instant. See the Distance() function for more info.
func (l *GameState) IssueOrder(source, target, numShips int) {
	//l.Log("IssueOrder : |" + fmt.Sprintf("%d %d %d\n", source, target, numShips) + "|\n")
	l.bout.WriteString(fmt.Sprintf("%d %d %d\n", source, target, numShips))
}

// log if debug is activated
func (l GameState) Log(message string, v ...interface{}) {
	if l.debug {
		l.logger.Printf(message, v...)
	}
}

/******************************************************************************
private methods
******************************************************************************/

/******************************************************************************
create or update a planet from parsing input
******************************************************************************/
func (l *GameState) updatePlanet(Type bool, x, y float64, Owner, NumShips, Income int) (tempPlanet *Planet) {
	if l.currentturn < 2 { // i'm in first turn so i create the planet
		tempCoord := new(coord)
		tempCoord.X = x
		tempCoord.Y = y
		tempPlanet = &Planet{Type: Type, Id: l.nbPlanet, coord: tempCoord, Owner: Owner, NumShips: NumShips, Income: Income}
		l.listID = append(l.listID, tempPlanet)

	} else { // so I already Created it
		tempPlanet = l.listID[l.nbPlanet]
		tempPlanet.Owner = Owner
		tempPlanet.NumShips = NumShips
		tempPlanet.Income = Income
	}

	//update owner list
	for len(l.listOwner) <= Owner {
		l.listOwner = append(l.listOwner, make([]*Planet, 0))
	}

	l.listOwner[Owner] = append(l.listOwner[Owner], tempPlanet)
	l.nbPlanet++
	return
}

/******************************************************************************
create a fleet from parsing input
******************************************************************************/
func (l *GameState) updateFleet(Type bool, owner, numShips, source, target, time, remainingtime int) *Fleet {
	fleet := Fleet{Type, owner, numShips, source, target, time, remainingtime}
	l.listFleet = append(l.listFleet, fleet)
	return &fleet
}

/******************************************************************************
CreateDataStruct
******************************************************************************/
func NewGameState(debug bool, debugLogger log.Logger, bout *bufio.Writer) *GameState {
	t := new(GameState)
	t.nbPlanet = 0
	t.logger = debugLogger
	t.debug = debug
	t.bout = bout
	t.listFleet = make([]Fleet, 0)
	//t.initFlag = true
	return t
}

/******************************************************************************
Init things, maybe init fully datastruct ?
******************************************************************************/
func (l *GameState) Init(params map[string]int) {

	l.loadtime = params["loadtime"]
	l.turntime = params["turntime"]
	l.turns = params["turns"]

}


