using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;

namespace DTStrike.MyBot
{
    public class Program
    {
        // The DoTurn function is where your code goes. The Game object
	    // contains the state of the game, including information about all planets
	    // and fleets that currently exist. Inside this function, you issue orders
	    // using the game.issueOrder() function. For example, to send 10 ships from
	    // planet 3 to planet 8, you would say game.issueOrder(3, 8, 10).
	    //
	    // There is already a basic strategy in place here. You can use it as a
	    // starting point, or you can throw it out entirely and replace it with
	    // your own.

	    public static void doTurn(Game game) {
		    // (1) If we currently have a fleet in flight, just do nothing.
            if (game.getMyMilitaryFleets().Count() >= 1)
            {
			    return;
		    }
		    // (2) Find my strongest military planet.
		    Planet source = null;
		    int sourceShips = int.MinValue;
		    foreach (Planet p in game.getMyMilitaryPlanets()) {
			    int score = p.numShips;
			    if (score > sourceShips) {
				    sourceShips = score;
				    source = p;
			    }
		    }

            if (source == null)
            {
                return;
            }

		    // (3) Find the weakest enemy or neutral planet.
		    Planet dest = null;
		    int destDist = int.MaxValue;
		    foreach (Planet p in game.getNotMyPlanets()) {
			    int dist = game.distance(source.id, p.id);
			    if (dist < destDist) {
                    destDist = dist;
				    dest = p;
			    }
		    }

		    // (4) Send half the ships from my strongest planet to the weakest
		    // planet that I do not own.
		    if (source != null && dest != null) {
			    int numShips = source.numShips / 2;
			    game.issueOrder(source, dest, numShips);
		    }
	    }

	    public static void Main(String[] args) {

		    try {
                String line;
                while ((line = System.Console.ReadLine()) != "ready")
                {
                    if (line.StartsWith("*"))
                    {
                        // this is an option
                    }
                }
                System.Console.Out.Write("go\n");
                System.Console.Out.Flush();

                while (true) 
                {
                    List<String> data = new List<string>();
                    while ((line = System.Console.ReadLine()) != "go")
                    {
                        data.Add(line);
                    }
                    Game game = new Game(data);
                    doTurn(game);
                    game.finishTurn();
                    System.Console.Out.Flush();
                }
		    } finally {
			
		    }
	    }
    }
}
