'''
Created on 28 juil. 2013

@author: Mike
'''
from math import ceil, sqrt
from sys import stdout

class Game(object):
    '''
    Class handling communication to the DTStrike server ans providing the basics methods to play.
    '''


    def __init__(self, gameState, myID):
        '''
        Constructor
        '''
        self._planets = []
        self._fleets = []
        self._myID = myID
        if (self.parseGameState(gameState)) > 0:
            raise Exception("Error parsing game state")

    def numPlanets(self):
        '''
        Returns the number of planets. Planets are numbered starting with 0
        '''
        return len(self._planets)

    def getPlanet(self, planetID):
        '''
        Returns the planet with the given planet_id. There are NumPlanets() planets. They are numbered starting at 0.
        '''
        return self._planet[planetID]

    def numFleets(self):
        '''
        Returns the number of fleets.
        '''
        return len(self._fleets)
    
    def numShips(self, playerID):
        '''
        Returns the number of ships that the playerID has, either located
        on planets or in flight.
        '''
        numShips = 0
        for planet in self._planets:
            if planet.Owner() == playerID:
                numShips += planet.NumShips()            
        for fleet in self._fleets:
            if fleet.Owner() == playerID:
                numShips += fleet.NumShips()
        return numShips
    
    def getFleet(self, fleetID):
        '''
        Returns the fleet with the given fleet_id. Fleets are numbered starting
        with 0. There are NumFleets() fleets. fleet_id's are not consistent from
        one turn to the next.
        '''
        return self._fleets[fleetID]

    def getPlanets(self):
        '''
        Returns a list of all the planets.
        '''
        return self._planets     
 
    def isAlive(self, ID):
        '''
        A player is alive if he owns at least one military planet or one fleet.
        '''
        for planet in self._planets:
            if(isinstance(planet, MilitaryPlanet) & planet.Owner() == ID):
                return True
        for fleet in self._fleets:
            if fleet.Owner() == ID:
                return True
        return False

    def dropPlayer(self, ID):
        '''
        All player planets belong to neutral player. All fleets are destroyed.
        '''
        for planet in self._planets:
            if planet.Owner() == ID:
                planet.Owner(0)
        for fleet in self._fleets:
            if fleet.Owner() == ID:
                fleet.destroy()

    def getMyPlanets(self):
        '''
        Return a list of all the planets owned by the current player. By
        convention, the current player is always player number 1.
        '''
        r=[]
        for planet in self._planets:
            if planet.Owner() == self._myID:
                r.append(planet)
        return r
    
    
    def getMyEconomicPlanets(self):
        '''
        Return a list of all the economic planets owned by the current player. By
        convention, the current player is always player number 1.
        '''        
        r=[]
        for planet in self._planets:
            if (planet.Owner() == self._myID & isinstance(planet, EconomicPlanet)):
                r.append(planet)
        return r

    def getNeutralEconomicPlanets(self):
        '''
        Return a list of all neutral economic planets.
        '''
        r=[]
        for planet in self._planets:
            if (planet.Owner() == 0 & isinstance(planet, EconomicPlanet)):
                r.append(planet)
        return r

    def getEnemyEconomicPlanets(self):
        '''
        Return a list of all the economic planets owned by rival players. This excludes
        economic planets owned by the current player, as well as neutral economic planets.
        '''
        r=[]
        for planet in self._planets:
            if (planet.Owner() != 0 & planet.Owner() != self._myID & isinstance(planet, EconomicPlanet)):
                r.append(planet)
        return r

    def getMyMilitaryPlanets(self):
        '''
        Return a list of all the military planets owned by the current player. By
        convention, the current player is always player number 1.
        '''
        r=[]
        for planet in self._planets:
            if (planet.Owner() == self._myID & isinstance(planet, MilitaryPlanet)):
                r.append(planet)
        return r
           
    def getMilitaryPlanets(self):
        '''
        Return a list of all the military planets.
        '''
        r=[]
        for planet in self._planets:
            if (isinstance(planet, MilitaryPlanet)):
                r.append(planet)
        return r

    def getNeutralMilitaryPlanets(self):
        '''
        Return a list of all neutral military planets.
        '''
        r=[]
        for planet in self._planets:
            if (planet.Owner() == 0 & isinstance(planet, MilitaryPlanet)):
                r.append(planet)
        return r
    
    def getEnemyMilitaryPlanets(self):
        '''
        Return a list of all the military planets owned by rival players. This excludes
        military planets owned by the current player, as well as neutral military planets.
        '''        
        r=[]
        for planet in self._planets:
            if (planet.Owner() != 0 & planet.Owner() != self._myID & isinstance(planet, MilitaryPlanet)):
                r.append(planet)
        return r

    def getNeutralPlanets(self):
        '''
        Return a list of all neutral planets.
        '''
        r=[]
        for planet in self._planets:
            if (planet.Owner() == 0):
                r.append(planet)
        return r

    def getEnemyPlanets(self):
        '''
        Return a list of all the planets owned by rival players. This excludes
        planets owned by the current player, as well as neutral planets.
        '''
        r=[]
        for planet in self._planets:
            if (planet.Owner() != 0 & planet.Owner() != self._myID):
                r.append(planet)
        return r
    
    def getNotMyPlanets(self):
        '''
        Return a list of all the planets that are not owned by the current
        player. This includes all enemy planets and neutral planets.
        '''
        r=[]
        for planet in self._planets:
            if (planet.Owner() != self._myID):
                r.append(planet)
        return r

    def getFleets(self):
        '''
        Return a list of all the fleets.
        '''
        return self._fleets

    def getMyFleets(self):
        '''
        Return a list of all the fleets owned by the current player.
        '''
        r=[]
        for fleet in self._fleets:
            if (fleet.Owner() == self._myID):
                r.append(fleet)
        return r
    
    def getEnemyFleets(self):
        '''
        Return a list of all the fleets owned by enemy players.
        '''
        r=[]
        for fleet in self._fleets:
            if (fleet.Owner() != self._myID):
                r.append(fleet)
        return r

    def distance(self, sourcePlanet, destinationPlanet):
        source = self._planets[sourcePlanet]
        destination = self._planets[destinationPlanet]
        dx = source.X() - destination.X()
        dy = source.Y() - destination.Y()
        return ceil(sqrt(dx * dx + dy * dy))
    
    def issueOrder(self, sourcePlanet, destinationPlanet, numShips):
        '''
        Sends an order to the game engine. An order is composed of a source
        planet number, a destination planet number, and a number of ships. A
        few things to keep in mind:
        * you can issue many orders per turn if you like.
        * the planets are numbered starting at zero, not one.
        * you must own the source planet. If you break this rule, the game
        engine kicks your bot out of the game instantly.
        * you can't move more ships than are currently on the source planet.
        * the ships will take a few turns to reach their destination. Travel
        is not instant. See the distance() function for more info.
        '''
        stdout.write("{:d} {:d} {:d}\n".format(sourcePlanet, destinationPlanet, numShips))
        stdout.flush()
        
    def FinishTurn(self):
        '''
        Sends the game engine a message to let it know that we're done sending
         orders. This means the end of our turn.
        '''
        stdout.write("go\n")
        stdout.flush()
    
    def parseGameState(self, s):
        self._planets = []
        self._fleets = []
        lines = s.split("\n")
        planet_id = 0

        for line in lines:
            line = line.split("#")[0] # remove comments?!
            tokens = line.split(" ")
            if (len(tokens) > 1): #remove empty strings
                if (tokens[0] == "M"):
                    if (len(tokens) != 5):
                        return 1
                    p = MilitaryPlanet(planet_id, # ID of this planet
                       int(tokens[3]), # Owner
                       int(tokens[4]), # Num ships
                       float(tokens[1]), # X
                       float(tokens[2])) # Y
                    planet_id += 1
                    self._planets.append(p)
                elif tokens[0] == "E":
                    if len(tokens) != 6:
                        return 1
                    p = EconomicPlanet(planet_id, # ID of this planet
                       int(tokens[3]), # Owner
                       int(tokens[4]), # Num ships
                       float(tokens[1]), # X
                       float(tokens[2]), # Y
                       int(tokens[5])) # Income
                    planet_id += 1
                    self._planets.append(p)
                elif tokens[0] == "F":
                    if len(tokens) != 7:
                        return 1
                    f = Fleet(int(tokens[1]), # Owner
                      int(tokens[2]), # Num ships
                      int(tokens[3]), # Source
                      int(tokens[4]), # Destination
                      int(tokens[5]), # Total trip length
                      int(tokens[6])) # Turns remaining
                    self._fleets.append(f)
                else:
                    return 1
        return 0


class Fleet(object):
    '''
    classdocs
    '''


    def __init__(self, owner, power, sourceDept = -1, destDept = -1, tripLength = -1, turnsRemaining = -1):
        '''
        Constructor
        '''
        self._owner = owner
        self._numShips = power
        self._sourcePlanet = sourceDept
        self._destinationPlanet = destDept
        self._totalTripLength = tripLength
        self._turnsRemaining = turnsRemaining

    def Owner(self):
        return self._owner

    def NumShips(self):
        return self._num_ships

    def SourcePlanet(self):
        return self._source_planet

    def DestinationPlanet(self):
        return self._destination_planet

    def TotalTripLength(self):
        return self._total_trip_length

    def TurnsRemaining(self):
        return self._turns_remaining
        
    def destroy(self):
        '''
        Called when the fleet is annihilated
        '''
        self._owner = 0
        self._numShips = 0
        self._turnsRemaining = 0
    
    def doTimeStep(self):
        self._turnsRemaining -=1
        if (self._turnsRemaining < 0):
            self._turnsRemaining = 0
            

class Planet(object):
    '''
    A Planet, mother class.
    '''

    def __init__(self, ID, owner, numShips, x, y):
        '''
        Constructor
        '''
        self._planet_id = ID
        self._owner = owner
        self._num_ships = numShips
        self._x = x
        self._y = y
        
    def PlanetID(self):
        return self._planet_id

    def Owner(self, newOwner=None):
        if newOwner == None:
            return self._owner
        self._owner = newOwner

    def NumShips(self, numShips=None):
        if numShips == None:
            return self._num_ships
        self._num_ships = numShips

    def X(self):
        '''
        X coordinate
        '''
        return self._x

    def Y(self):
        '''
        Y coordinate
        '''
        return self._y
        
class EconomicPlanet(Planet):
    '''
    Eco planet with income
    '''

    def __init__(self, ID, owner, numShips, x, y, income):
        '''
        Constructor
        '''
        Planet.__init__(self, ID, owner, numShips, x, y)
        self._income = income
    
    def Income(self):
        return self._income
        
        
class MilitaryPlanet(Planet):
    '''
    Military Planet, which can handle ships.
    '''

    def __init__(self, ID, owner, numShips, x, y):
        '''
        Constructor
        '''
        Planet.__init__(self, ID, owner, numShips, x, y)
