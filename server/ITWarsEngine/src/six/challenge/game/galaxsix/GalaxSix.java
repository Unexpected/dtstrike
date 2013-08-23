package six.challenge.game.galaxsix;

import java.io.BufferedWriter;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.Map.Entry;
import java.util.Set;
import java.util.TreeMap;

import six.challenge.engine.Game;

public class GalaxSix extends Game {

	public GalaxSix(File mapFile, Map<String, String> options, int numPlayers,
			String logFile) {
		this.winner = -1;
		this.mapName = mapFile.getName();
		this.numPlayers = numPlayers;
		setOptions(options);
		new File(logFile).delete();
		try {
			this.logWriter = new BufferedWriter(new FileWriter(logFile, true));
		} catch (IOException ioEx) {
			System.err.println("Error: Unable to open log file " + logFile);
			ioEx.printStackTrace(System.err);
			errorAtStartup = true;
		}
		try {
			if (parse(mapFile) != 0) {
				System.err.println("Error: Invalid map " + mapFile);
				errorAtStartup = true;
			}
		} catch (IOException ioEx) {
			System.err.println("Error: Unable to open map file " + mapFile);
			ioEx.printStackTrace(System.err);
			errorAtStartup = true;
		}
	}

	public int winner;
	public StringBuffer gameLog = new StringBuffer();
	public String mapName;
	public int numPlayers;
	public boolean errorAtStartup = false;

	public List<Planet> planets;
	public List<Fleet> fleets;

	/**
	 * A player is alive if he owns at least one military planet or one fleet.
	 * 
	 * @param id
	 *            player id to check
	 * @return true if the player is alive
	 */
	public boolean isAlive(int id) {
		for (Planet p : planets) {
			if (p instanceof MilitaryPlanet && p.owner == id)
				return true;
		}
		for (Fleet f : fleets) {
			if (f.owner == id) {
				return true;
			}
		}
		return false;
	}

	/**
	 * All player bases are belong to neutral player
	 * 
	 * @param id
	 *            player id to drop
	 */
	public void killPlayer(int id) {
		for (Planet p : planets) {
			if (p.owner == id) {
				p.owner = 0;
			}
		}
		for (Fleet f : fleets) {
			if (f.owner == id) {
				f.destroy();
			}
		}
	}

	/**
	 * Returns a view of the current situation for player playerId
	 * 
	 * @param playerId
	 * @return
	 */
	public String getPlayerState(int playerId) {
		StringBuilder sb = new StringBuilder();
		Locale.setDefault(new Locale("en"));
		Map<Integer, Integer> pSwitch = playerSwitch.get(playerId);
		for (Planet p : planets) {
			if (p instanceof MilitaryPlanet) {
				sb.append(String.format("M %f %f %d %d\n", p.x, p.y,
						pSwitch.get(p.owner), p.numShips));
			} else if (p instanceof EconomicPlanet) {
				sb.append(String.format("E %f %f %d %d %d\n", p.x, p.y,
						pSwitch.get(p.owner), p.numShips,
						((EconomicPlanet) p).revenue));
			}
		}
		for (Fleet f : fleets) {
			sb.append(String.format("%s %d %d %d %d %d %d\n", f.military ? "F"
					: "R", pSwitch.get(f.owner), f.numShips, f.sourcePlanet,
					f.destinationPlanet, f.totalTripLength, f.turnsRemaining));
		}
		return sb.toString();
	}

	private int distance(int sourcePlanet, int destPlanet) {
		Planet localPlanet1 = planets.get(sourcePlanet);
		Planet localPlanet2 = planets.get(destPlanet);
		double d1 = localPlanet1.x - localPlanet2.x;
		double d2 = localPlanet1.y - localPlanet2.y;
		return (int) Math.ceil(Math.sqrt(d1 * d1 + d2 * d2));
	}

	private Planet getClosestPlanet(Planet origin,
			Set<? extends Planet> destinations) {
		Planet destination = null;
		int distance = Integer.MAX_VALUE;

		for (Planet d : destinations) {
			int newDistance = distance(origin.id, d.id);
			if (newDistance < distance) {
				distance = newDistance;
				destination = d;
			}
		}
		return destination;
	}

	private void fight(Planet p) {
		Map<Integer, Integer> battleships = new TreeMap<Integer, Integer>();

		battleships.put(p.owner, p.numShips);
		List<Fleet> keptFleets = new ArrayList<Fleet>();
		for (Fleet f : fleets) {
			if (f.destinationPlanet == p.id && f.turnsRemaining == 0) {
				if (battleships.get(f.owner) == null) {
					battleships.put(f.owner, 0);
				}
				int currentShips = battleships.get(f.owner);
				battleships.put(f.owner, f.numShips + currentShips);
			} else {
				keptFleets.add(f);
			}
		}

		fleets = keptFleets;

		if (battleships.keySet().size() == 0) {
			// No fight
			return;
		} else if (battleships.keySet().size() == 1) {
			// Only one player
			p.numShips = battleships.get(p.owner);
			return;
		}

		int maxShips = -1;
		int maxOwner = -1;
		int secondShips = -1;

		for (Entry<Integer, Integer> e : battleships.entrySet()) {
			if (e.getValue() > maxShips) {
				maxShips = e.getValue();
				maxOwner = e.getKey();
			}
		}
		for (Entry<Integer, Integer> e : battleships.entrySet()) {
			if (e.getKey().intValue() != maxOwner && e.getValue() > secondShips) {
				secondShips = e.getValue();
			}
		}

		// Mutually assured destruction --> Owner doesn't change.
		if (maxShips == secondShips) {
			p.numShips = 0;
		} else {
			// Biggest fleet is new owner (maybe doesn't change)
			p.numShips = maxShips - secondShips;
			p.owner = maxOwner;
		}
	}

	private int parse(File mapFile) throws IOException {
		FileInputStream fis = new FileInputStream(mapFile);
		ByteArrayOutputStream bos = new ByteArrayOutputStream();
		int len;
		byte[] buf = new byte[2048];
		while ((len = fis.read(buf)) > 0) {
			bos.write(buf, 0, len);
		}
		fis.close();
		String map = bos.toString();
		return parse(map);
	}

	public void saveGameLogToFile(int winnerId) {
		System.out.print("var data=\"");
		System.out.print("game_id=$$ID\\n");
		System.out.print("winner=" + winnerId + "\\n");
		System.out.print("map_id=" + mapName + "\\n");
		System.out.print("draw=" + (winnerId == 0 ? 1 : 0) + "\\n");
		System.out.print("timestamp=" + System.currentTimeMillis() + "\\n");
		System.out.print("players=");
		for (int i = 1; i <= numPlayers; i++) {
			if (i > 1) {
				System.out.print("|");
			}
			System.out.print(i + ":player" + i);
		}
		System.out.print("\\n");
		System.out.print("playback_string=" + gameLog.toString());
		System.out.print("\\n\"");
	}

	private int parse(String map) {
		planets = new ArrayList<Planet>();
		fleets = new ArrayList<Fleet>();

		String[] lines = map.split("\n");
		for (int i = 0; i < lines.length; i++) {
			String str = lines[i];

			int hash = str.indexOf(35);
			if (hash >= 0) {
				str = str.substring(0, hash);
			}
			if (str.trim().length() != 0) {
				String[] line = str.split(" ");
				if (line.length != 0) {
					if (line[0].equals("M")) {
						if (line.length != 5) {
							return 1;
						}
						double x = Double.parseDouble(line[1]);
						double y = Double.parseDouble(line[2]);
						int owner = Integer.parseInt(line[3]);
						int numShips = Integer.parseInt(line[4]);

						MilitaryPlanet p = new MilitaryPlanet(planets.size(),
								owner, numShips, x, y);
						planets.add(p);
						if (this.gameLog.length() > 0) {
							gameLog.append(":");
						}
						gameLog.append("M,").append(x).append(",").append(y)
								.append(",").append(owner).append(",")
								.append(numShips).append(",0");
					} else if (line[0].equals("E")) {
						if (line.length != 6) {
							return 1;
						}
						double x = Double.parseDouble(line[1]);
						double y = Double.parseDouble(line[2]);
						int owner = Integer.parseInt(line[3]);
						int numShips = Integer.parseInt(line[4]);
						int economicValue = Integer.parseInt(line[5]);

						EconomicPlanet p = new EconomicPlanet(planets.size(),
								owner, numShips, economicValue, x, y);
						planets.add(p);

						if (this.gameLog.length() > 0) {
							this.gameLog.append(":");
						}
						this.gameLog.append("E,").append(x).append(",")
								.append(y).append(",").append(owner)
								.append(",").append(numShips).append(",")
								.append(economicValue);
					} else {
						return 1;
					}
				}
			}
		}
		gameLog.append("|");
		return 0;
	}

	/**
	 * Everyone thinks he's the number one
	 */
	private Map<Integer, Map<Integer, Integer>> playerSwitch = new HashMap<Integer, Map<Integer, Integer>>();

	@Override
	public void startGame() {
		for (int numPlayer = 1; numPlayer < numPlayers + 1; numPlayer++) {
			Map<Integer, Integer> sw = new HashMap<Integer, Integer>();
			// 1 : 1:1, 2:2, 3:3
			// 2 : 1:3, 2:1; 3:2
			// 3 : 1:2, 2:3; 3:1
			for (int otherPlayer = 1; otherPlayer < numPlayers + 1; otherPlayer++) {
				int decalage = numPlayer - 1;
				int otherPlayerSwitched = otherPlayer - decalage;
				if (otherPlayerSwitched < 1) {
					otherPlayerSwitched += numPlayers;
				}
				sw.put(otherPlayer, otherPlayerSwitched);
			}
			// Neutral player is always neutral
			sw.put(0, 0);
			playerSwitch.put(numPlayer, sw);
		}
	}

	@Override
	public String getPlayerStart(int id) {
		String startMessage = "";
		for (Entry<String, String> e : getOptions().entrySet()) {
			startMessage += "*" + e.getKey() + ":" + e.getValue() + "\n";
		}
		startMessage += getPlayerState(id);
		return startMessage;
	}

	@Override
	public List<String> getScores() {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public String getState() {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public void startTurn() {
		// TODO Auto-generated method stub

	}

	private Set<MilitaryPlanet> getMilitaryPlanets(int id) {
		Set<MilitaryPlanet> mPlanets = new HashSet<MilitaryPlanet>();
		for (Planet p : planets) {
			if (p instanceof MilitaryPlanet && p.owner == id) {
				mPlanets.add((MilitaryPlanet) p);
			}
		}
		return mPlanets;
	}

	@Override
	public void finishTurn() {
		for (Planet p : planets) {
			if (p instanceof EconomicPlanet && p.owner > 0) {
				Set<MilitaryPlanet> mPlanets = getMilitaryPlanets(p.owner);
				if (mPlanets.size() > 0) {
					Planet dest = getClosestPlanet(p, mPlanets);
					if (dest != null) {
						int distance = distance(p.id, dest.id);
						fleets.add(new Fleet(p.owner,
								((EconomicPlanet) p).revenue, p.id, dest.id,
								distance, distance, false));
					}
				}
			}
		}

		for (Fleet f : fleets) {
			f.doTimeStep();
		}

		for (Planet p : planets) {
			fight(p);
		}

		int k = 0;
		for (Planet p : planets) {
			if (k++ > 0)
				gameLog.append(",");
			gameLog.append(p.owner).append(".").append(p.numShips);
		}

		for (Fleet f : fleets) {
			if (k++ > 0)
				gameLog.append(",");
			gameLog.append(f.owner).append(".").append(f.numShips).append(".")
					.append(f.sourcePlanet).append(".")
					.append(f.destinationPlanet).append(".")
					.append(f.totalTripLength).append(".")
					.append(f.turnsRemaining);
		}
		gameLog.append(":");
	}

	@Override
	public void finishGame() {
		// TODO We could send data to bots for testing purpose
	}

	@Override
	public boolean isGameOver() {
		int livePlayers = 0;
		int possibleWinner = -1;
		for (int i = 0; i < numPlayers; i++) {
			if (isAlive(i + 1)) {
				livePlayers++;
				possibleWinner = i + 1;
			}
		}
		if (livePlayers == 1) {
			winner = possibleWinner;
			return true;
		} else if (livePlayers == 0) {
			winner = 0;
		}
		return false;
	}

	@Override
	public void doMoves(int id, List<String> orders) {
		for (String order : orders) {
			try {
				String[] orderParts = order.split(" ");
				int sourcePlanet = Integer.parseInt(orderParts[0]);
				int destPlanet = Integer.parseInt(orderParts[1]);
				int numShips = Integer.parseInt(orderParts[2]);

				if (numShips == 0) {
					continue;
				}
				Planet localPlanet = (Planet) this.planets.get(sourcePlanet);
				if (localPlanet.owner != id) {
					writeLogMessage("invalid order - planet doesn't belong to player "
							+ id + ": " + order + "\n");
					continue;
				}
				if (numShips > localPlanet.numShips || numShips < 0) {
					writeLogMessage("invalid order - numShips must be positive and lower or equal to available ships "
							+ order + "\n");
					continue;
				}
				if (localPlanet instanceof EconomicPlanet) {
					writeLogMessage("invalid order - source planet must be a military planet"
							+ order + "\n");
					continue;
				}
				localPlanet.numShips -= numShips;
				int distance = distance(sourcePlanet, destPlanet);
				Fleet localFleet = new Fleet(localPlanet.owner, numShips,
						sourcePlanet, destPlanet, distance, distance, true);
				this.fleets.add(localFleet);
			} catch (Exception e) {
				writeLogMessage("invalid order for player " + id + ": " + order
						+ "\n" + e.getMessage());
			}
		}
	}
}
