/*
 Created on 31 juil. 2013
     The do_turn function is where your code goes. The Game object
     contains the state of the game, including information about all planets
     and fleets that currently exist. Inside this function, you issue orders
     using the Game.issueOrder function. For example, to send 10 ships from
     planet 3 to planet 8, you would say Game.issueOrder(3, 8, 10).
     
     There is already a basic strategy in place here. You can use it as a
     starting point, or you can throw it out entirely and replace it with
     your own.
 @author: Zuberl
*/

class Bot {

    def game

    def initGame(game_data) {
        game = game_data
    }

    def doTurn(data) {
        game.start_turn(data)
        game.finish_turn()
    }

    def getMyStrongestPlanet() {
        return game.planets.military.my().max({ a,b ->
            a.num_ships <=> b.num_ships
        })
    } 

    def getMyWeakestPlanet() {
        return game.planets.my().min({ a,b ->
            a.num_ships <=> b.num_ships
        })
    }

    def getOpposantWeakestPlanet() {
        return game.planets.others().min({ a,b ->
            a.num_ships <=> b.num_ships
        })
    }

    def sendHalfShipInPlanet(source, dest) {
        if ((source != null) && (dest != null)) {
            def num_ships = (source.num_ships / 2).toInteger()
            if(num_ships > 10) {
                game.issue_order(source.id, dest.id, num_ships)
            }
        }
    }

}
