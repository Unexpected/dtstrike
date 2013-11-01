/*
	To create new Bot :
		- Create new class extends Bot. StarterKitGroovy is an BotExample.
		- Implement doTurn function in you're bot
		- Remplace "new StarterKitGroovy" with your bot initialization

	Game module contains game general logic (Data extracts and calcul, Distance Calcul, ...)
	Bot class contains logic specific to your bot (What do, when ?)
	Created on 31 juil. 2013
	@author: Zuberl
*/

/*
	TODO - Initialize you're bot class
*/
Bot bot = new StarterBotGroovy()

def data = []
def game

parseVerboseMode()
System.in.eachLine() { line -> 
    if (line == "ready") {
    	// Initialize game data
	    game = new Game(data)
	    bot.initGame(game)
        data.clear()
        game.running = true
    } else if (line == "end") {
    	 // End game data incoming
        game.running = false
    } else if (line.equals("go")) {
        if (!game.running) { 
			game.end_game(data)
		    return
        } else {
        	// Do Turn
            bot.doTurn(data)
            data.clear()
        }
    } else {
        data.add(line) // Keep receiving data
    }
}

/*
	Make the program debug mode if -v parameter give to batch
*/
def parseVerboseMode() {
	Binding b = new Binding()
	b.setVariable("args", args)
	args.each{ it ->
		if (it == "-v") {
			Constant.DEBUG = true
		}
	}
}
