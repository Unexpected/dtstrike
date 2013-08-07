'''
Created on 28 juil. 2013
    The DoTurn function is where your code goes. The Game object
    contains the state of the game, including information about all planets
    and fleets that currently exist. Inside this function, you issue orders
    using the Game.issueOrder function. For example, to send 10 ships from
    planet 3 to planet 8, you would say Game.issueOrder(3, 8, 10).
    
    There is already a basic strategy in place here. You can use it as a
    starting point, or you can throw it out entirely and replace it with
    your own.
@author: Mike
'''
from Game import Game

class MyBot(object):
    '''
    classdocs
    '''

    def __init__(self):
        '''
        Constructor
        '''
        
    def doTurn(self, game):
        # (1) If we currently have a fleet in flight, just do nothing.
        if len(game.MyFleets()) >= 1:
            return
        # (2) Find my strongest planet.
        source = -1
        source_score = -1.0
        source_num_ships = 0
        my_planets = game.MyPlanets()
        for p in my_planets:
            score = float(p.NumShips())
            if score > source_score:
                source_score = score
                source = p.PlanetID()
                source_num_ships = p.NumShips()
        # (3) Find the weakest enemy or neutral planet.
        dest = -1
        dest_score = -1.0
        not_my_planets = game.NotMyPlanets()
        for p in not_my_planets:
            score = 1.0 / (1 + p.NumShips())
            if score > dest_score:
                dest_score = score
                dest = p.PlanetID()
        # (4) Send half the ships from my strongest planet to the weakest
        # planet that I do not own.
        if source >= 0 and dest >= 0:
            num_ships = source_num_ships / 2
            game.IssueOrder(source, dest, num_ships)

    def main(self):
        systemMap = ""
        try:
            while(True):
                currentLine = input()
                if len(currentLine) >= 2 and currentLine.startswith("go"):
                    game = Game(systemMap, int(currentLine.split(" ")[1]))
                    self.doTurn(game)
                    game.FinishTurn()
                    systemMap = ""
                else:
                    systemMap += currentLine + '\n'
        except EOFError:
            print("End of transmission. Shutting down...")