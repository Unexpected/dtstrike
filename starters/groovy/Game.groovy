/*
 Created on 31 juil. 2013
 @author: Zuberl


 This module contains tools class : 
 - Game : General tools to query game data et send order
 - Fleets : Querys fleet data. Take Game.fleets and use access method
 - Planets : Querys planet data. Take Game.planets and use access method
 - Logger : Tools to print log if bot launch with verbose mode

 This module contains data class
 - Planet : Class to represent standard planets (mother class)
 - MilitaryPlanet : Class to represent planets who can send fleet
 - EconomicPlanet :  Class to represent planets which give food
 - Fleet : Class to represent fleet
*/


/*
    General tools to run game.
    Contains method to :
        - Parse game data
        - Send orders
        - Works with general game data
*/
class Game {

    final MY_ID = Constant.MY_ID
    def running = false
    
    def planets = new PlanetsList()
    def fleets = new FleetList()
    def load_time
    def turn_time
    def turns
    def turn_start_time

    /*
        Initialize game object with startup data
    */
    Game(data) {
        // Add the initialize logic
        data.each{line ->
            def tokens = line.split(":")

            if (tokens.size() > 1) { // remove empty strings
                switch (tokens[0]) {
                    case "loadtime":
                        load_time = tokens[2]
                        break
                    case "turntime":
                        turn_time = tokens[2]
                        break
                    case "turns":
                        turns = tokens[2]
                        break
                }

            }
        }

        // And go when bot is ready
        println "go\n"
    }

    /*
        Begin turn logic : 
        - Parse new turn data
    */
    def start_turn(data) {
        turn_start_time = System.currentTimeMillis()
        Logger.print "Initialize turn"
        planets = new PlanetsList()
        fleets = new FleetList()
        parse_game_state(data)
    }
    
    /*
        Write a fleet order
    */
    def issue_order(source_planet_id, destination_planet_id, num_ships) {
        Logger.print "Launch fleet with $num_ships units form $source_planet_id to $destination_planet_id"
        println "$source_planet_id $destination_planet_id $num_ships\n"
    }

    /*
        End turn logic :
        - Write ready order
    */  
    def finish_turn() {
        Logger.print "Finish turn"
        println "go\n"
    }

    /*
        End game logic : 
        - Actual do nothing
    */
    def end_game(data) {
        Logger.print "End of game"
    }
        

    /*
        Sum all ships of one player (in fleet and planets)
    */
    def num_ships(playerID) {
        num_ships = planets.forId(playerID).sum{ it.num_ships }
        num_ships += fleets.forId(playerID).sum{ it.num_ships }
        return num_ships
    }
    
    /*
        Check if one player is alive.
        Alive : 
            - Have planetary planet
            - Or have in fly fleets
    */
    def is_alive(playerID) {
        return (planets.military.forId(playerID).size() > 0) || (fleets.forId(playerID).size() > 0)
    }

    def time_remaining()  {
        return turn_time  - (int) (System.currentTimeMillis() - turn_start_time);
    }


    /*
        Calcul distance beetwen to planets
    */
    def distance(source, destination) {    
        if (source == null || destination == null) 
            return Integer.MAX_VALUE
        def dx = source.x - destination.x
        def dy = source.y - destination.y
        return (int) Math.floor(Math.sqrt(dx * dx + dy * dy))
    }



    /*
        Parse game data of a turn.
    */
    def parse_game_state(data) {
        data.each{line -> 
            line = line.split("#")[0] //remove comments?!
            def tokens = line.split(" ")
            if (tokens.size() > 1) { // remove empty strings
                if (tokens[0] == "M"){
                    if (tokens.size() == 5){
                        def p = MilitaryPlanet.parse(tokens, planets) 
                        planets.add(p)
                    }     
                } else if (tokens[0] == "E") {
                    if (tokens.size() == 6) {
                        def p = EconomicPlanet.parse(tokens, planets) 
                        planets.add(p)
                    }   
                } else if ((tokens[0] == "F") || (tokens[0] == "R")) {
                    if (tokens.size() == 7) {
                        def f = Fleet.parse(tokens)
                        fleets.add(f)
                    }
                }
            }
        }
    }
}


/*
    Planet list.
    Contains all access method to extract planets
*/
class PlanetsList extends ArrayList {

    final NEUTRAL_ID = Constant.NEUTRAL_ID
    final MY_ID = Constant.MY_ID


    def all() { this }

    private def isMy = { it.owner == MY_ID }
    private def isNeutral = { it.owner == NEUTRAL_ID }
    private def isOthers = { it.owner != MY_ID }
    private def isEnnemy = { it.owner > MY_ID }
    private def isForId = { id -> it.owner = id  }

    def military = [
        all: { this.findAll{ it.isMilitary() } },
        my : { this.military.all().findAll(isMy) },
        neutral : { this.military.all().findAll(isNeutral) },
        others : { this.military.all().findAll(isOthers) },
        ennemy : { this.military.all().findAll(isEnnemy) },
        forId : { id -> this.military.all().findAll(isForId(id))}
    ]

    def economic = [
        all: { this.findAll{ it.isEconomic() } },
        my : { this.economic.all().findAll(isMy) },
        neutral : { this.economic.all().findAll(isNeutral) },
        others : { this.economic.all().findAll(isOthers) },
        ennemy : { this.military.all().findAll(isEnnemy) },
        forId : { id -> this.military.all().findAll(isForId(id))}
    ]

    def my() { this.all().findAll(isMy) }
    def neutral() { this.all().findAll(isNeutral) }
    def ennemy() { this.all().findAll(isEnnemy) }
    def others() { this.all().findAll(isOthers) }
    def forId(id) { this.all().findAll(isForId(id))}

}

/*
    Fleet list.
    Contains all access method to extract fleets
*/
class FleetList extends ArrayList {

    final MY_ID = Constant.MY_ID

    def all() { this }
    def my() { this.all().findAll{ it.owner == MY_ID } }
    def ennemy() { this.all().findAll{ it.owner > MY_ID } }

    def forId(id) { this.all().findAll{ it.owner > id } }
    def toId(id) { this.all().findAll{ it.destinationPlanet == id}; }
    
}

/**
*  A Planet, mother class.
**/ 
class Planet {
    def id 
    def owner
    def num_ships
    def x
    def y

    def isMilitary() { this instanceof MilitaryPlanet }
    def isEconomic() { this instanceof EconomicPlanet }
}

/*
 Economic Planet, which can handle ships.
*/
class EconomicPlanet extends Planet {
    def income

    static parse(tokens, planets) {
        new EconomicPlanet(
           id: planets.size(), 
           owner: tokens[3].toInteger(), 
           num_ships: tokens[4].toInteger(), 
           x: tokens[1].toFloat(), 
           y: tokens[2].toFloat(), 
           income: tokens[5].toInteger()) 
    }
}      

/*
   Military Planet, which can handle ships.
*/ 
class MilitaryPlanet extends Planet {

    static parse(tokens, planets) {
        new MilitaryPlanet(
           id: planets.size(),
           owner: tokens[3].toInteger(), 
           num_ships: tokens[4].toInteger(), 
           x: tokens[1].toFloat(), 
           y: tokens[2].toFloat()) 
    }
}

/*
 An aggressive fleet
*/
class Fleet {
    def owner
    def num_ships
    def sourcePlanet
    def destinationPlanet
    def totalTripLength
    def turnsRemaining
    def military

    static parse(tokens) {
        new Fleet(
          owner: tokens[1].toInteger(), 
          num_ships: tokens[2].toInteger(),
          sourcePlanet: tokens[3].toInteger(),
          destinationPlanet: tokens[4].toInteger(),
          totalTripLength: tokens[5].toInteger(),
          turnsRemaining: tokens[6].toInteger(),
          military: (tokens[0] == "F"))
    }
}



/*
    Constant class with :
    - DEBUG : set to true if program launch with -v
    - NEUTRAL_ID : Owner id of neutral fleets or planets
    - MY_ID : Owner id of current player
*/
class Constant {
    static DEBUG = false

    static final NEUTRAL_ID = 0
    static final MY_ID = 1
}


/*
    Logger write in stderr if program is in verbose mode (debug trace)
*/
class Logger {
    static print(msg) { if(Constant.DEBUG) System.err.println msg }
}