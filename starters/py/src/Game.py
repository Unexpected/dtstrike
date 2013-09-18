'''
Created on 28 juil. 2013

@author: Mike
'''
from math import ceil, sqrt
from sys import stdout, stderr

class Game(object):
    '''
    Class handling communication to the DTStrike server ans providing the basics methods to play.
    '''

    MY_ID = 1

    def __init__(self, data):
        """ Parse options and send go when ready to rock and roll ! """
        stdout.write("go\n")
        stdout.flush()
    
    def start_turn(self, data):
        self.planets = []
        self.fleets = []
        self.parse_game_state(data)
    
    def num_ships(self, playerID):
        '''
        Returns the number of ships that the playerID has, either located
        on planets or in flight.
        '''
        num_ships = 0
        for planet in self.planets:
            if planet.owner == playerID:
                num_ships += planet.num_ships            
        for fleet in self.fleets:
            if fleet.owner == playerID:
                num_ships += fleet.num_ships()
        return num_ships
    
    def is_alive(self, playerID):
        '''
        A player is alive if he owns at least one military planet or one fleet.
        '''
        for planet in self.planets:
            if(isinstance(planet, MilitaryPlanet) & planet.owner == playerID):
                return True
        for fleet in self.fleets:
            if fleet.owner == playerID:
                return True
        return False

    def my_planets(self):
        '''
        Return a list of all the planets owned by the current player. By
        convention, the current player is always player number 1.
        '''
        return [p for p in self.planets if p.owner == self.MY_ID]
    
    def my_economic_planets(self):
        '''
        Return a list of all the economic planets owned by the current player. By
        convention, the current player is always player number 1.
        '''        
        return [p for p in self.planets if p.owner == self.MY_ID & isinstance(p, EconomicPlanet)]


    def neutral_economic_planets(self):
        '''
        Return a list of all neutral economic planets.
        '''
        return [p for p in self.planets if p.owner == 0 & isinstance(p, EconomicPlanet)]

    def enemy_economic_planets(self):
        '''
        Return a list of all the economic planets owned by rival players. This excludes
        economic planets owned by the current player, as well as neutral economic planets.
        '''
        return [p for p in self.planets if p.owner > self.MY_ID & isinstance(p, EconomicPlanet)]
        
    def my_military_fleets(self):
        '''
        Return a list of all the military fleets owned by the current player. By
        convention, the current player is always player number 1.
        '''
        return [f for f in self.fleets if f.owner == 1 & f.military]

    def my_military_planets(self):
        '''
        Return a list of all the military fleets owned by the current player. By
        convention, the current player is always player number 1.
        '''
        return [p for p in self.planets if p.owner == 1 & isinstance(p, MilitaryPlanet)]
           
    def military_planets(self):
        '''
        Return a list of all the military planets.
        '''
        return [p for p in self.planets if isinstance(p, MilitaryPlanet)]

    def neutral_military_planets(self):
        '''
        Return a list of all neutral military planets.
        '''
        return [p for p in self.planets if p.owner == 0 & isinstance(p, MilitaryPlanet)]
    
    def enemy_military_planets(self):
        '''
        Return a list of all the military planets owned by rival players. This excludes
        military planets owned by the current player, as well as neutral military planets.
        '''        
        return [p for p in self.planets if p.owner > self.MY_ID & isinstance(p, MilitaryPlanet)]

    def neutral_planets(self):
        '''
        Return a list of all neutral planets.
        '''
        return [p for p in self.planets if p.owner == 0]

    def enemy_planets(self):
        '''
        Return a list of all the planets owned by rival players. This excludes
        planets owned by the current player, as well as neutral planets.
        '''
        return [p for p in self.planets if p.owner > self.MY_ID]
    
    def not_my_planets(self):
        '''
        Return a list of all the planets that are not owned by the current
        player. This includes all enemy planets and neutral planets.
        '''
        return [p for p in self.planets if p.owner != self.MY_ID]

    def not_my_fleets(self):
        '''
        Return a list of all the fleets not owned by the current player.
        '''
        return [f for f in self.fleets if f.owner != self.MY_ID]

    def my_fleets(self):
        '''
        Return a list of all the fleets owned by the current player.
        '''
        return [f for f in self.fleets if f.owner == self.MY_ID]
    
    def enemy_fleets(self):
        '''
        Return a list of all the fleets owned by enemy players.
        '''
        return [f for f in self.fleets if f.owner > self.MY_ID]

    def distance(self, source_planet_id, destination_planet_id):
        source = self.planets[source_planet_id]
        destination = self.planets[destination_planet_id]
        dx = source.X() - destination.X()
        dy = source.Y() - destination.Y()
        return ceil(sqrt(dx * dx + dy * dy))
    
    def issue_order(self, source_planet_id, destination_planet_id, num_ships):
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
        stdout.write("%d %d %d\n" % (source_planet_id, destination_planet_id, num_ships))
        stdout.flush()
        
    def finish_turn(self):
        '''
        Sends the game engine a message to let it know that we're done sending
         orders. This means the end of our turn.
        '''
        stdout.write("go\n")
        stdout.flush()
        
    def end_game(self, data):
        ### do nothing
        stderr.write("bye")
    
    def parse_game_state(self, data):
        for line in data:
            line = line.split("#")[0] # remove comments?!
            tokens = line.split(" ")
            if (len(tokens) > 1): #remove empty strings
                if (tokens[0] == "M"):
                    if (len(tokens) != 5):
                        return 1
                    p = MilitaryPlanet(len(self.planets), # ID of this planet
                       int(tokens[3]), # Owner
                       int(tokens[4]), # Num ships
                       float(tokens[1]), # X
                       float(tokens[2])) # Y
                    self.planets.append(p)
                elif tokens[0] == "E":
                    if len(tokens) != 6:
                        return 1
                    p = EconomicPlanet(len(self.planets), # ID of this planet
                       int(tokens[3]), # Owner
                       int(tokens[4]), # Num ships
                       float(tokens[1]), # X
                       float(tokens[2]), # Y
                       int(tokens[5])) # Income
                    self.planets.append(p)
                elif tokens[0] == "F" or tokens[0] == "R":
                    if len(tokens) != 7:
                        return 1
                    f = Fleet(int(tokens[1]), # Owner
                      int(tokens[2]), # Num ships
                      int(tokens[3]), # Source
                      int(tokens[4]), # Destination
                      int(tokens[5]), # Total trip length
                      int(tokens[6]), # Turns remaining
                      tokens[0] == "F") # Military fleet
                    self.fleets.append(f)


class Fleet(object):
    '''
    classdocs
    '''
    def __init__(self, owner, power, sourceDept = -1, destDept = -1, tripLength = -1, turnsRemaining = -1, military = True):
        '''
        Constructor
        '''
        self.owner = owner
        self.num_ships = power
        self.sourcePlanet = sourceDept
        self.destinationPlanet = destDept
        self.totalTripLength = tripLength
        self.turnsRemaining = turnsRemaining
        self.military = military
        
class Planet(object):
    '''
    A Planet, mother class.
    '''
    def __init__(self, id, owner, num_ships, x, y):
        '''
        Constructor
        '''
        self.id = id
        self.owner = owner
        self.num_ships = num_ships
        self.x = x
        self.y = y
        
        
class EconomicPlanet(Planet):
    '''
    Eco planet with income
    '''
    def __init__(self, id, owner, num_ships, x, y, income):
        '''
        Constructor
        '''
        Planet.__init__(self, id, owner, num_ships, x, y)
        self.income = income        
        
class MilitaryPlanet(Planet):
    '''
    Military Planet, which can handle ships.
    '''
    def __init__(self, id, owner, num_ships, x, y):
        '''
        Constructor
        '''
        Planet.__init__(self, id, owner, num_ships, x, y)
