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
    # (1) If we currently have a fleet in flight, just do nothing.
    if len(game.my_military_fleets()) >= 3:
        return

    # (2) Find my strongest planet.
    source = -1
    source_score = -1
    source_num_ships = 0
    my_planets = game.my_military_planets()
    for p in my_planets:
        score = p.num_ships
        if score > source_score:
            source_score = score
            source = p.id
            source_num_ships = p.num_ships
    # (3) Find the weakest enemy or neutral planet.
    dest = -1
    dest_score = -1.0
    not_my_planets = game.not_my_planets()
    for p in not_my_planets:
        score = 1.0 / (1 + p.num_ships)
        if score > dest_score:
            dest_score = score
            dest = p.id
    # (4) Send half the ships from my strongest planet to the weakest
    # planet that I do not own.
    if source >= 0 and dest >= 0:
        num_ships = source_num_ships / 2
        game.issue_order(source, dest, num_ships)

def main():
    try:
        game_running = False
        data = []
        while(True):
            current_line = raw_input()
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