import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.Random;

public class MySecondBot {
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
		// if (game.getMyFleets().size() >= 3) {
		// return;
		// }
		// (2) Find my strongest military planet.
		Planet source = null;
		int sourceShips = Integer.MIN_VALUE;
		for (Planet p : game.getMyMilitaryPlanets()) {
			int score = p.numShips;
			if (score > sourceShips) {
				sourceShips = score;
				source = p;
			}
		}
		if (source == null) {
			return;
		}

		// (3) Find the weakest enemy or neutral planet.
		Planet dest = null;
		int destScore = Integer.MAX_VALUE;
		for (Planet p : game.getNotMyPlanets()) {
			int score = game.distance(p.id, source.id);
			if (score < destScore) {
				destScore = score;
				dest = p;
			}
		}

		// (4) Send half the ships from my strongest planet to the weakest
		// planet that I do not own.
		if (source != null && dest != null) {
			int numShips = source.numShips;
			game.issueOrder(source, dest, numShips);
		}
	}

	public static FileWriter fw = null;

	public static void main(String[] args) {

		try {
			fw = new FileWriter(new File("D:/Temp/bot"
					+ new Random().nextInt(1000) + ".log"));
			log("bot started", fw);
			String line = "";
			String message = "";
			int c;
			while ((c = System.in.read()) >= 0) {
				switch (c) {
				case '\n':
					if (line.startsWith("go")) {
						Game game = new Game(message, (Integer.parseInt(line
								.split(" ")[1])));
						doTurn(game);
						game.finishTurn();
						message = "";
					} else {
						message += line + "\n";
					}
					line = "";
					break;
				default:
					line += (char) c;
					break;
				}
			}
			fw.flush();
		} catch (Exception e) {
			e.printStackTrace(System.err);
			try {
				fw.write(e.toString());
			} catch (IOException e1) {
				e1.printStackTrace(System.err);
			}
		} finally {
			if (fw != null) {
				try {
					fw.close();
				} catch (IOException e) {
					e.printStackTrace(System.err);
				}
			}
		}
	}

	private static void log(String message, FileWriter fw) throws IOException {
		fw.write(message);
		fw.write("\n");
		fw.flush();
	}
}
