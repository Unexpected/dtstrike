package gameState

import (
	"bufio"
	"fmt"
	"log"
	"math"
	"strconv"
	"strings"
)

/*
Etat du monde, résultat du parsing de l'entré + autre chose ?
*/
type GameState struct {

	// multimaps to direct acces to most infomations

	listID    []*Planet   // direct access by ID
	listMili  []*Planet   // direct access to Mili planets
	listEco   []*Planet   // direct access to Eco planets
	listOwner [][]*Planet // direct access by owner

	listFleet []Flotte
	nbPlanet  int
	distances [][]int // pre calculated distances

	logger log.Logger
	debug  bool
	bout   *bufio.Writer
}

func New(debug bool, debugLogger log.Logger, bout *bufio.Writer) *GameState {
	t := new(GameState)

	t.nbPlanet = 0
	t.logger = debugLogger
	t.debug = debug
	t.bout = bout
	t.listFleet = make([]Flotte, 0)

	return t
}

func (l *GameState) Reinit() {

	l.listFleet = make([]Flotte, 0)

	l.listID = make([]*Planet, len(l.listID))[0:0]     // direct access by ID
	l.listMili = make([]*Planet, len(l.listMili))[0:0] // direct access to Mili planets
	l.listEco = make([]*Planet, len(l.listEco))[0:0]   // direct access to Eco planets

	for key := range l.listOwner {
		length := len(l.listOwner[key])
		l.Log("for key : %d SubSlice size %d", l.listOwner[key], length)

	}

	for key := range l.listOwner {
		length := len(l.listOwner[key])

		l.Log("for key : %d SubSlice size %d", key, length)
		l.listOwner[key] = make([]*Planet, length)[0:0]
	}

	l.nbPlanet = 0
}

func (l GameState) String() string {
	test := fmt.Sprintf("GameState ID:\n%s\nMili:\n%s\nEco:\n%s\nOwners:\n%s\nFleets:\n%s\n", l.listID, l.listMili, l.listEco, l.listOwner, l.listFleet)
	return test
}

func (l *GameState) updatePlanet(Type bool, x, y float64, Owner, NumShips, Income int) *Planet {

	tempCoord := new(coord)
	tempCoord.X = x
	tempCoord.Y = y

	tempPlanet := Planet{Type: Type, Id: l.nbPlanet, coord: tempCoord, Owner: Owner, NumShips: NumShips, Income: Income}

	l.listID = append(l.listID, &tempPlanet)

	for len(l.listOwner) <= Owner {
		l.listOwner = append(l.listOwner, make([]*Planet, 0))
		l.Log("New Owner : ", Owner)
	}

	l.listOwner[Owner] = append(l.listOwner[Owner], &tempPlanet)
	l.nbPlanet++
	return &tempPlanet
}

func (l *GameState) UpdateMilitaryPlanet(x, y float64, Owner, NumShips int) {
	tempPlanet := l.updatePlanet(false, x, y, Owner, NumShips, 0)
	l.listMili = append(l.listMili, tempPlanet)
}

func (l *GameState) UpdateEcoPlanet(x, y float64, Owner, NumShips, Income int) {
	tempPlanet := l.updatePlanet(true, x, y, Owner, NumShips, Income)
	l.listEco = append(l.listEco, tempPlanet)
}

func (l *GameState) UpdateFleet(owner, numShips, source, target, time, remainingtime int) *Flotte {
	fleet := Flotte{owner, numShips, source, target, time, remainingtime}
	l.listFleet = append(l.listFleet, fleet)
	return &fleet
}

func (l *GameState) GetMyMilitary(id int) (MyMilitary []*Planet) {
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

func (l *GameState) GetOtherPlanet(id int) (potentielTargets []*Planet) {
	// targets are concat of planet that are not mine
	for key := range l.listOwner {
		if key != id {
			potentielTargets = append(potentielTargets, l.listOwner[key]...)
		}
	}
	return
}

/*

type de base pour une coordonnée

*/
type coord struct {
	X float64
	Y float64
}

func (b coord) String() string {
	return fmt.Sprintf("[%f:%f]", b.X, b.Y)
}

/*
@TODO : planette simple est auj militaire... a confirmer avec les regles finales

*/
type Planet struct {
	Type bool // true if economic
	Id   int
	*coord
	Owner    int
	NumShips int
	Income   int
}

func (b Planet) String() string {

	if b.Type {
		return fmt.Sprintf("Economic %d %s Owner=%d Power=%d Income=%d\n", b.Id, b.coord, b.Owner, b.NumShips, b.Income)
	}
	return fmt.Sprintf("Military %d %s Owner=%d Power=%d\n", b.Id, b.coord, b.Owner, b.NumShips)
}

func NewPlanet() *Planet {
	return &Planet{Type: false, Id: 0, coord: new(coord), Owner: 0, NumShips: 0, Income: 0}
}

/*

Flotte militaire, la mienne ou celle d'un adversaire

*/
type Flotte struct {
	// F pour flotte, propriétaire, nombre de vaisseaux, ID planète source, ID planète destination,
	// longueur totale du voyage (en nb de tours), nb de tours restants avant arrivée
	Owner                               int
	NumShips                            int
	Source, Target, Time, Remainingtime int
}

func (b Flotte) String() string {
	return fmt.Sprintf("Flotte Owner=%d Power=%d [%d=>%d] time=%d remaining=%d\n", b.Owner, b.NumShips, b.Source, b.Target, b.Time, b.Remainingtime)
}

// vérifie une erreur
// @TODO : refaire en closure ?
func (l GameState) handleError(where string, err error) {

}

func (l *GameState) ParseMapLine(line string) {
	//fmt.Println("try to decode : " + line)
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
		l.UpdateEcoPlanet(X, Y, Owner, NumShips, Income)
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
		l.UpdateMilitaryPlanet(X, Y, Owner, NumShips)
		//fmt.Println("parsing done for : ", mili)
		break

	case "F":
		// F pour flotte, propriétaire, nombre de vaisseaux, ID planète source, ID planète destination,
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
	l.Log("CalculateDistance : %d", sourceID, targetID)
	source := l.listID[sourceID]
	destination := l.listID[targetID]
	dx := source.X - destination.X
	dy := source.Y - destination.Y

	return int(math.Ceil(math.Sqrt(dx*dx + dy*dy)))

}

// Returns the distance between two planets, rounded up to the next highest
// integer. This is the number of discrete time steps it takes to get
// between the two planets.
func (l GameState) DistancePlanet(sourcePlanet, destinationPlanet Planet) int {
	dx := sourcePlanet.X - destinationPlanet.X
	dy := sourcePlanet.Y - destinationPlanet.Y
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

	l.bout.WriteString(fmt.Sprintf("%d %d %d\n", source, target, numShips))

}

// log if debug is activated
func (l GameState) Log(message string, v ...interface{}) {

	if l.debug {
		l.logger.Printf(message, v)
	}

}
