package myBot

import (
	"fmt"
	"sort"
)

/******************************************************************************
Coordinate
******************************************************************************/
type coord struct {
	X float64
	Y float64
}

func (b coord) String() string {
	return fmt.Sprintf("[%f:%f]", b.X, b.Y)
}

/******************************************************************************
Planet structure, a composition of a coordinate and multiples fields
Same fields as game engine
******************************************************************************/
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
		return fmt.Sprintf("Economic %d %s Owner=%d NumShips=%d Income=%d\n", b.Id, b.coord, b.Owner, b.NumShips, b.Income)
	}
	return fmt.Sprintf("Military %d %s Owner=%d NumShips=%d\n", b.Id, b.coord, b.Owner, b.NumShips)
}
func NewPlanet() *Planet {
	return &Planet{Type: false, Id: 0, coord: new(coord), Owner: 0, NumShips: 0, Income: 0}
}

/******************************************************************************
Planets is a simple slice of Planets, but it's defined to be clearer and to define others methods on it.
******************************************************************************/
type Planets []*Planet

// Deep Copy of planet array
func (b Planets) deepCopy() (copyPlanets Planets) {
	copyPlanets = make(Planets, len(b))
	for key := range b {
		copyPlanets[key] = &Planet{Type: b[key].Type, Id: b[key].Id, coord: b[key].coord, Owner: b[key].Owner, NumShips: b[key].NumShips, Income: b[key].Income}
	}
	return
}

// By is the type of a "less" function that defines the ordering of its Planet arguments.
type By func(p1, p2 *Planet) bool

// Sort is a method on the function type, By, that sorts the argument slice according to the function.
func (by By) Sort(planets Planets) {
	ps := &planetSorter{
		planets: planets,
		by:      by, // The Sort method's receiver is the function (closure) that defines the sort order.
	}
	sort.Sort(ps)
}

// planetSorter joins a By function and a slice of Planets to be sorted
type planetSorter struct {
	planets Planets
	by      func(p1, p2 *Planet) bool // Closure used in the Less method.
}

// Len is part of sort.Interface.
func (s *planetSorter) Len() int {
	return len(s.planets)
}

// Swap is part of sort.Interface.
func (s *planetSorter) Swap(i, j int) {
	s.planets[i], s.planets[j] = s.planets[j], s.planets[i]
}

// Less is part of sort.Interface. It is implemented by calling the "by" closure in the sorter.
func (s *planetSorter) Less(i, j int) bool {
	return s.by(s.planets[i], s.planets[j])
}

// to do a sort, juste define a method/Closure of type func(p1, p2 *Planet) bool that is used by Less
// for example, this sort by number of ship (max first)
func byNumship(p1, p2 *Planet) bool {
	return p1.NumShips > p2.NumShips
}

// then just use By(byNumship).Sort(myMilitaryPlanets)

/*
Military Fleet, with a owner, a number of Ship, a source, a target and a time to destination (with distance)
*/
type Fleet struct {
	// F/R for Fleet, Owner, Number of Ships, ID source Planet, ID Target Planet,
	// total lenght (in nb of turns), Remaining nb of turns before impact
	Type                                bool // true if reinforcement
	Owner                               int
	NumShips                            int
	Source, Target, Time, Remainingtime int
}

func (b Fleet) String() string {
	if b.Type {
		return fmt.Sprintf("Reinforcement Owner=%d Power=%d [%d=>%d] time=%d remaining=%d\n", b.Owner, b.NumShips, b.Source, b.Target, b.Time, b.Remainingtime)
	}
	return fmt.Sprintf("Fleet Owner=%d Power=%d [%d=>%d] time=%d remaining=%d\n", b.Owner, b.NumShips, b.Source, b.Target, b.Time, b.Remainingtime)
}
