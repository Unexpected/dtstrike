package gameState

import (
	"bufio"
	"fmt"
	"log"
	"math"
	"strconv"
	"strings"
)

/******************************************************************************
World State, keep track of things and abstract access to input/output
******************************************************************************/
type GameState struct {
	listID    Planets   // direct access by ID, sure to get all planets
	listMili  Planets   // direct access to Mili planets
	listEco   Planets   // direct access to Eco planets
	listOwner []Planets // direct access by owner of their planets

	listFleet []Fleet // all in flight fleets

	// internal bookkeeping
	logger   log.Logger
	debug    bool
	bout     *bufio.Writer
	init     bool
	nbPlanet int
}

func New(debug bool, debugLogger log.Logger, bout *bufio.Writer) *GameState {
	t := new(GameState)

	t.nbPlanet = 0
	t.logger = debugLogger
	t.debug = debug
	t.bout = bout
	t.listFleet = make([]Fleet, 0)
	t.init = true
	return t
}

func (l *GameState) Reinit() {

	l.listFleet = make([]Fleet, 0)

	for key := range l.listOwner {
		length := len(l.listOwner[key])
		l.listOwner[key] = make(Planets, length)[0:0]
	}

	l.nbPlanet = 0
	l.init = false
}

func (l GameState) String() string {
	//test := fmt.Sprintf("GameState ID:\n%s\nMili:\n%s\nEco:\n%s\nOwners:\n%s\nFleets:\n%s\n", l.listID, l.listMili, l.listEco, l.listOwner, l.listFleet)
	test := fmt.Sprintf("GameState ID:\n%s\nFleets:\n%s\n", l.listID, l.listFleet)
	return test
}

func (l *GameState) updatePlanet(Type bool, x, y float64, Owner, NumShips, Income int) (tempPlanet *Planet) {
	if l.init { // i'm in first turn so i create the planet
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

func (l *GameState) updateMilitaryPlanet(x, y float64, Owner, NumShips int) {
	tempPlanet := l.updatePlanet(false, x, y, Owner, NumShips, 0)
	if l.init {
		l.listMili = append(l.listMili, tempPlanet)
	} // so I already Created it, and as it's references only, no need to change anything
}

func (l *GameState) updateEcoPlanet(x, y float64, Owner, NumShips, Income int) {
	tempPlanet := l.updatePlanet(true, x, y, Owner, NumShips, Income)
	if l.init {
		l.listEco = append(l.listEco, tempPlanet)
	} // so I already Created it, and as it's references only, no need to change anything
}

func (l *GameState) UpdateFleet(owner, numShips, source, target, time, remainingtime int) *Fleet {
	fleet := Fleet{owner, numShips, source, target, time, remainingtime}
	l.listFleet = append(l.listFleet, fleet)
	return &fleet
}

func (l *GameState) GetMyMilitary(id int) (MyMilitary Planets) {
	if len(l.listMili) > len(l.listOwner[id]) {
		// i have less planet than existing military, so this is more efficient
		for key := range l.listOwner[id] {
			if !l.listOwner[id][key].Type {
				MyMilitary = append(MyMilitary, l.listOwner[id][key])
			}
		}
		return
	}

	for key := range l.listMili {
		if l.listMili[key].Owner == id {
			MyMilitary = append(MyMilitary, l.listMili[key])
		}
	}
	return
}

func (l *GameState) GetOtherPlanets(id int) (potentielTargets Planets) {
	// targets are concat of planet that are not mine
	for key := range l.listOwner {
		if key != id {
			potentielTargets = append(potentielTargets, l.listOwner[key]...)
		}
	}
	return
}

func (l *GameState) GetAllPlanets() Planets {

	return l.listID
}

/*
Military Fleet, with a owner, a number of Ship, a source, a target and a time to destination (with distance)
*/
type Fleet struct {
	// F for Fleet, Owner, Number of Ships, ID source Planet, ID Target Planet,
	// total lenght (in nb of turns), Remaining nb of turns before impact
	Owner                               int
	NumShips                            int
	Source, Target, Time, Remainingtime int
}

func (b Fleet) String() string {
	return fmt.Sprintf("Fleet Owner=%d Power=%d [%d=>%d] time=%d remaining=%d\n", b.Owner, b.NumShips, b.Source, b.Target, b.Time, b.Remainingtime)
}

/*
This parse the input line correclty
*/
func (l *GameState) ParseMapLine(line string) {

	line = strings.Replace(strings.Replace(line, "\r", "", 1), "\n", "", 1)
	lineTokens := strings.Split(line, " ")
	switch lineTokens[0] {
	case "E": //E pour le Type de planète économique,
		//2 coordonnées X et Y sous forme de float,
		//ID du propriétaire,
		//Nb de vaisseaux actuellement sur la planète,
		//Income de la planète.
		X, err1 := strconv.ParseFloat(lineTokens[1], 32)
		Y, err2 := strconv.ParseFloat(lineTokens[2], 32)
		Owner, err3 := strconv.Atoi(lineTokens[3])
		NumShips, err4 := strconv.Atoi(lineTokens[4])
		Income, err5 := strconv.Atoi(lineTokens[5])
		if err1 != nil || err2 != nil || err3 != nil || err4 != nil || err5 != nil {
			l.Log("error while parsing Economic : %s : %s|%s|%s|%s|%s", line, err1, err2, err3, err4, err5)
		}
		l.updateEcoPlanet(X, Y, Owner, NumShips, Income)
		//fmt.Println("parsing done for : ", eco)
		break

	case "M":
		//M pour le type Militaire, coordonnées X et Y, ID du propriétaire, Nb de vaisseaux actuellement sur la planète
		X, err1 := strconv.ParseFloat(lineTokens[1], 32)
		Y, err2 := strconv.ParseFloat(lineTokens[2], 32)
		Owner, err3 := strconv.Atoi(lineTokens[3])
		NumShips, err4 := strconv.Atoi(lineTokens[4])
		if err1 != nil || err2 != nil || err3 != nil || err4 != nil {
			l.Log("error while parsing Military : %s : %s|%s|%s|%s", line, err1, err2, err3, err4)
		}
		l.updateMilitaryPlanet(X, Y, Owner, NumShips)
		//fmt.Println("parsing done for : ", mili)
		break

	case "F":
		// F pour Fleet, propriétaire, nombre de vaisseaux, ID planète source, ID planète destination,
		// longueur totale du voyage (en nb de tours), nb de tours restants avant arrivée
		owner, err1 := strconv.Atoi(lineTokens[1])
		numShips, err2 := strconv.Atoi(lineTokens[2])
		source, err3 := strconv.Atoi(lineTokens[3])
		target, err4 := strconv.Atoi(lineTokens[4])
		time, err5 := strconv.Atoi(lineTokens[5])
		remainingtime, err6 := strconv.Atoi(lineTokens[6])
		if err1 != nil || err2 != nil || err3 != nil || err4 != nil || err5 != nil || err6 != nil {
			l.Log("error while parsing Fleet : %s : %s|%s|%s|%s|%s|%s", line, err1, err2, err3, err4, err5, err6)
		}
		l.UpdateFleet(owner, numShips, source, target, time, remainingtime)

		break

	default:
		l.Log("error while parsing ?:  %s : %e", line)
	}
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

// Returns the distance between two planets, rounded up to the next highest
// integer. This is the number of discrete time steps it takes to get
// between the two planets.
func Distance(source, destination *Planet) int {
	//l.Log("CalculateDistance : %d", sourceID, targetID)
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
