import java.io.File;
import java.io.InputStream;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.util.ArrayList;
import java.util.List;

public class Engine {

	public static void main(String[] args) {
		try {
			if (args.length < 5) {
				System.err
						.println("Error : wrong number of command-line arguments.");
				System.err
						.println("Usage : engine map_file_name max_turn_time max_num_turns log_filename player_one player_two [more_players]");

				System.exit(1);
			}

			String mapFile = args[0];
			int turnTime = Integer.parseInt(args[1]);
			int turns = Integer.parseInt(args[2]);
			String logFile = args[3];

			Game game = new Game(new File(mapFile), turnTime, turns, logFile);

			List<Process> players = new ArrayList<Process>();

			for (int i = 4; i < args.length; i++) {
				String player = args[i];
				Process localProcess = null;
				try {
					localProcess = Runtime
							.getRuntime()
							.exec(player,
									null,
									new File(
											"D:\\Perso\\DTStrike\\dtstrike\\Bot\\bin"));
				} catch (Exception localException1) {
					localProcess = null;
				}
				if (localProcess == null) {
					killClients(players);
					System.err.println("Error : failed to start client: "
							+ player);
					System.exit(1);
				}
				players.add(localProcess);
			}

			game.numPlayers = players.size();

			boolean[] keepPlaying = new boolean[players.size()];
			for (int i = 0; i < players.size(); i++) {
				keepPlaying[i] = true;
			}

			int turn = 1;
			while (game.winner == -1 && turn <= turns) {
				for (int i = 0; i < players.size(); i++) {
					int id = i + 1;
					String playerView;
					Process p = players.get(i);
					if (p != null && game.isAlive(id)) {
						// We send data only to living players
						playerView = game.playerView(id) + "go " + id + "\n";
						try {
							OutputStream localOutputStream = p
									.getOutputStream();
							OutputStreamWriter localOutputStreamWriter = new OutputStreamWriter(
									localOutputStream);
							localOutputStreamWriter.write(playerView);
							localOutputStreamWriter.flush();
							game.writeLogMessage("engine > player" + id + ": "
									+ playerView);
						} catch (Exception localException2) {
							localException2.printStackTrace();
							// Player p --> crash
							players.set(i, null);
						}
					} else if (!game.isAlive(id)) {
						game.dropPlayer(id);
						keepPlaying[i] = false;
					}
				}
				StringBuilder[] playersOrders = new StringBuilder[players
						.size()];
				boolean[] hasPlayed = new boolean[players.size()];

				for (int i = 0; i < players.size(); i++) {
					playersOrders[i] = new StringBuilder();
					hasPlayed[i] = !keepPlaying[i];
				}
				long currentTurnTime = System.currentTimeMillis();

				// Loops until every player has finished is turn or turn time is
				// over
				while (!allTrue(hasPlayed)
						&& (System.currentTimeMillis() - currentTurnTime) < turnTime) {

					// For each player that hasn't played
					for (int i = 0; i < players.size(); i++) {
						int id = i + 1;
						if (hasPlayed[i] == true || !game.isAlive(id)) {
							hasPlayed[i] = true;
						} else {
							try {
								// Get orders from each player
								InputStream processInputStream = players.get(i)
										.getInputStream();

								while (processInputStream.available() > 0) {
									char c = (char) processInputStream.read();
									if (c != '\n') {
										// Read order until end of line
										playersOrders[i].append(c);
									} else {
										// Execute order
										String order = playersOrders[i]
												.toString();

										order = order.toLowerCase().trim();
										game.writeLogMessage("player" + id
												+ " > engine: " + order);
										if (order.equals("go")) {
											hasPlayed[i] = true;
										} else {
											game.issueOrder(id, order);
										}
										playersOrders[i] = new StringBuilder();
									}
								}
							} catch (Exception localException3) {
								System.err.println("WARNING: player " + id
										+ " crashed.");
								players.get(i).destroy();
								game.dropPlayer(id);
								keepPlaying[i] = false;
							}
						}
					}
				}
				for (int i = 0; i < players.size(); i++) {
					int id = i + 1;
					if (!hasPlayed[i] && game.isAlive(id)) {
						if (keepPlaying[i] == true) {
							System.err.println("Warning: player " + id
									+ " timed out.");

							players.get(i).destroy();
							game.dropPlayer(id);
							keepPlaying[i] = false;
						}
					}
				}
				System.err.println("Turn " + turn++);
				game.doTimeStep();
			}
			killClients(players);
			if (game.winner > 0)
				System.err.println("Player " + game.winner + " Wins!");
			else {
				System.err.println("Draw!");
			}
			// Save game Log
			game.saveGameLogToFile(game.winner);
			System.out.println(game.gameLog.toString());
		} catch (Exception e) {
			System.err.println(e.toString());
		}
	}

	private static boolean allTrue(boolean[] bools) {
		for (int i = 0; i < bools.length; i++) {
			if (bools[i] == false) {
				return false;
			}
		}
		return true;
	}

	private static void killClients(List<Process> players) {
		for (Process localProcess : players) {
			if (localProcess != null) {
				localProcess.destroy();
			}
		}
	}
}
