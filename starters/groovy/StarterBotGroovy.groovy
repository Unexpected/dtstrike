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
        // (1) If an economic planet have more than 50 ships, send 50 ships to the closest military planet and go to the next step.
        def economics = game.planets.economic.my().findAll{it.num_ships > 50}
        def military = game.planets.military.my()
        for(economic in economics) {
              def aMil = military.min({ a,b ->
                  game.distance(economic, a) <=>  game.distance(economic, b)
              })
              if (null != aMil)
                game.issue_order(economic.id, aMil.id,50)
        }

        // (2) If we currently have two fleet in flight, just do nothing.
        def militaryFleet = game.fleets.my().findAll{it.military}
        if (militaryFleet.size() < 3) {
            // (3) Find my strongest military planet.
            def strongest = getMyStrongestPlanet()

            // (4) Find the nearest enemy or neutral planet.
            def target = getOpposantNearestPlanet(strongest)

            // (5) All ships from my strongest planet to the nearest
            if (null != target)
                game.issue_order(strongest.id, target.id,strongest.num_ships)
        }


        game.finish_turn()
    }

}