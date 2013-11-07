package six.challenge.engine;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.ResourceBundle;

import six.challenge.engine.Player.Status;
import six.challenge.game.galaxsix.GalaxSix;

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
	private int turntime;

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
	public boolean errorAtStartup = false;

	public static ResourceBundle engineParameters;

	private List<Player> players;

	public Engine(String[] args) {
		mapFile = args[0];
		turntime = Integer.parseInt(args[1]);
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
		Map<String, String> options = new HashMap<String, String>();

		options.put("turns", String.valueOf(turns));
		options.put("turntime", String.valueOf(turntime));
		options.put("loadtime", String.valueOf(3 * turntime));
		if (!errorAtStartup) {
			game = new GalaxSix(new File(mapFile), options, players, logFile);
			if (game.errorAtStartup) {
				errorAtStartup = true;
			}
		}
	}

	public void play() {
		// Players are ready to rumble
		game.startGame();
		for (Player p : players) {
			p.status = Status.PLAYING;
		}

		int turn = 0;
		// Load turn
		for (Player p : players) {
			if (game.isAlive(p.id)) {
				// We send initialization only to living players
				String startMessage = game.getPlayerStart(p.id) + "ready\n";
				p.sendMessage(startMessage);
			}
		}
		try {
			Thread.sleep(Integer.parseInt(game.getOptions().get("loadtime")));
		} catch (NumberFormatException e) {
			e.printStackTrace();
		} catch (InterruptedException e) {
			e.printStackTrace();
		}

		// As long as there is no winner and that we have some turns left,
		// we'll play
		while (!game.isGameOver()) {
			game.startTurn();
			for (Player p : players) {
				if (p.status != Status.PLAYING) {
					// We don't care about stopped players
					continue;
				}
				if (game.isAlive(p.id)) {
					// We send data only to living players
					String playerView = game.getPlayerState(p.id) + "go\n";
					p.sendMessage(playerView);
					p.hasPlayed = false;
					game.writeLogMessage("engine > player" + p.id + ": "
							+ playerView);
				} else if (!game.isAlive(p.id)) {
					if (p.status == Status.PLAYING) {
						game.killPlayer(p.id);
						p.kill(Status.LOST);
					}
				}
			}
			long currentTurnTime = System.currentTimeMillis();
			boolean everyoneHasPlayed = false;
			// Loops until every player has finished is turn or turn time is
			// over
			for (Player p : players) {
				p.orders = new ArrayList<String>();
			}
			while (!everyoneHasPlayed
					&& (System.currentTimeMillis() - currentTurnTime) < turntime) {
				// For each player that hasn't played
				for (Player p : players) {
					if (p.status != Status.PLAYING || p.hasPlayed) {
						continue;
					}
					p.getIncomingOrders();
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
					game.killPlayer(p.id);
					p.kill(Status.TIMEOUT);
				}
			}
			for (Player p : players) {
				if (game.isAlive(p.id)) {
					game.doMoves(p.id, p.orders);
				}
			}
			game.finishTurn();
			turn++;
		}
	}

	/**
	 * Kill 'em all
	 */
	public String end() {
		for (Player p : players) {
			if (game.isAlive(p.id))
				p.kill(Status.SURVIVED);
			else if (p.status == Status.PLAYING)
				p.kill(Status.LOST);
		}
		// Save game Log
		String replayData = game.getReplay();
		return replayData;
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
			String replayData=e.end();
			System.out.println(replayData);
			System.exit(e.getWinner());
		}
		System.exit(0);
	}
	
	public int getWinner() {
		int winner = 0;
		int maxScore = -1;
		List<Integer> scores = game.getScores();
		for (int i=0; i<scores.size(); i++) {
			int score = scores.get(i).intValue();
			if (score > maxScore) {
				winner = (i + 1);
				maxScore = score;
			}
		}
		return winner;
	}
}
