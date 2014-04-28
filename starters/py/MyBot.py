'''
Created on 28 juil. 2013
    The do_turn function is where your code goes. The Game object
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

      
def do_turn(game):
    my_mp = game.my_military_planets()
    
    if len(my_mp) == 0: 
        return
        
    for p in game.my_economic_planets(): 
        if p.num_ships > 50: 
            closest_mp = game.closest_planet(p, my_mp)
            game.issue_order(p.id, closest_mp.id, 50)
    
    # (1) If we currently have a fleet in flight, just do nothing.
    if len(game.my_military_fleets()) > 2:
        return

    if len(game.not_my_planets()) == 0: 
        return

    # (2) Find my strongest planet.
    source = None
    for p in my_mp:
        if source is None or p.num_ships > source.num_ships:
            source = p

    # (3) Find the closest enemy or neutral planet.
    closest_target = game.closest_planet(source, game.not_my_planets())

    # Send all ships from my strongest military planet to closest enemy or neutral planet    
    game.issue_order(source.id, closest_target.id, source.num_ships)

def main():
    try:
        game_running = False
        data = []
        while(True):
            current_line = input()
            if current_line == "ready": # ending loadturn
                game = Game(data)
                data = []
                game_running = True
            elif current_line == "end": # end game data incoming
                game_running = False
            elif current_line == "go":
                if not game_running: #parse end game data
                    game.end_game(data)
                    return
                else:
                    game.start_turn(data) # start turn
                    do_turn(game)
                    game.finish_turn()
                    data = []
            else:
                data.append(current_line) # receiving data
    except EOFError:
        print("End of transmission. Shutting down...")
            
if __name__ == "__main__":
    main()