/*
	Basic example Bot.
	This bot :
		- Get your strongest military planet (Source)
		- Get the weakest other planet (Destination)
		- Send of source fleet to destination, only if fleet have ten ship
*/

class StarterBotGroovy extends Bot {

	def doTurn(data) {
        game.start_turn(data)
        def strongest = getMyStrongestPlanet()
        def target = getOpposantWeakestPlanet()
        sendHalfShipInPlanet(strongest, target)
        game.finish_turn()
    }

}