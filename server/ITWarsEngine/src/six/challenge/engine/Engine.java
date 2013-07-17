package six.challenge.engine;

import java.io.File;
import java.util.ArrayList;
import java.util.List;
import java.util.ResourceBundle;

import six.challenge.engine.Player.Status;
import six.challenge.game.Game;

public class Engine {

	/**
	 * Map played
	 */
	private String mapFile;

	/**
	 * Log file (different from output file which will contain "replay data")
	 */
	private String logFile;

	/**
	 * Maximum time allowed to bots for a turn in milliseconds
	 */
	private int turnTime;

	/**
	 * Maximum turn number
	 */
	private int turns;

	/**
	 * The game object
	 */
	private Game game;

	/**
	 * Error during startup (game initialization and players initialization)
	 */
	private boolean errorAtStartup = false;

	public static ResourceBundle engineParameters;

	private List<Player> players;

	public Engine(String[] args) {
		mapFile = args[0];
		turnTime = Integer.parseInt(args[1]);
		turns = Integer.parseInt(args[2]);
		logFile = args[3];

		players = new ArrayList<Player>();
		int id = 1;

		for (int i = 4; i < args.length; i++) {
			String pCommand = args[i];
			Player p = new Player(id++, pCommand);
			players.add(p);
			if (p.status != Status.STARTED) {
				// Bot didn't start. We won't launch the game.
				errorAtStartup = true;
				break;
			}
		}
		if (!errorAtStartup) {
			game = new Game(new File(mapFile), turnTime, turns, logFile);
			if (game.errorAtStartup) {
				errorAtStartup = true;
			}
			game.numPlayers = players.size();
		}
		if (errorAtStartup) {
			for (Player p : players) {
				p.kill();
			}
		}
	}

	public void play() {
		// Players are ready to rumble
		for (Player p : players) {
			p.status = Status.PLAYING;
		}

		int turn = 1;
		// As long as there is no winner and that we have some turns left,
		// we'll play
		while (game.winner == -1 && turn <= turns) {
			for (Player p : players) {
				if (p.status != Status.PLAYING) {
					// We don't care about stopped players
					continue;
				}
				if (game.isAlive(p.id)) {
					// We send data only to living players
					String playerView = game.playerView(p.id) + "go " + p.id
							+ "\n";
					p.sendMessage(playerView);
					p.hasPlayed = false;
					game.writeLogMessage("engine > player" + p.id + ": "
							+ playerView);
				} else if (!game.isAlive(p.id)) {
					if (p.status == Status.PLAYING) {
						game.dropPlayer(p.id);
						p.kill(Status.LOST);
					}
				}
			}
			long currentTurnTime = System.currentTimeMillis();
			boolean everyoneHasPlayed = false;
			// Loops until every player has finished is turn or turn time is
			// over
			while (!everyoneHasPlayed
					&& (System.currentTimeMillis() - currentTurnTime) < turnTime) {
				// For each player that hasn't played
				for (Player p : players) {
					if (p.status != Status.PLAYING || p.hasPlayed) {
						continue;
					}
					for (String order : p.getOrders()) {
						game.issueOrder(p.id, order);
						game.writeLogMessage("player" + p.id + " > engine: "
								+ order);
					}
				}
				everyoneHasPlayed = true;
				for (Player p : players) {
					if (p.status == Status.PLAYING && !p.hasPlayed) {
						everyoneHasPlayed = false;
					}
				}
			}
			for (Player p : players) {
				if (p.status == Status.PLAYING && !p.hasPlayed) {
					System.err.println("Warning: player " + p.id
							+ " timed out.");
					game.dropPlayer(p.id);
					p.kill(Status.TIMEOUT);
				}
			}
			System.err.println("Turn " + turn++);
			game.doTimeStep();
		}
	}

	/**
	 * Kill 'em all
	 */
	private void end() {
		for (Player p : players) {
			p.kill(Status.ENDED);
		}
		if (game.winner > 0) {
			System.err.println("Player " + game.winner + " Wins!");
		} else {
			System.err.println("Draw!");
		}
		// Save game Log
		game.saveGameLogToFile(game.winner);
	}

	public static void main(String[] args) {
		if (args.length < 5) {
			System.err
					.println("Error : wrong number of command-line arguments.");
			System.err
					.println("Usage : engine map_file_name max_turn_time max_num_turns log_filename player_one player_two [more_players]");
			System.exit(1);
		}
		Engine e = new Engine(args);
		if (!e.errorAtStartup) {
			e.play();
			e.end();
		}
		System.exit(0);
	}
}
