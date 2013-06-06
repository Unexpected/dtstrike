import java.io.BufferedWriter;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.Map.Entry;
import java.util.TreeMap;

public class Game {

	public static final String GAMES_FOLDER = "D:/Perso/DTStrike/games/";

	public int winner;
	public StringBuffer gameLog = new StringBuffer();
	public String mapName;
	public int numPlayers;

	public List<Planet> planets;
	public List<Fleet> fleets;

	public BufferedWriter logWriter;

	public Game(File mapFile, int turnTime, int turns, String logFile)
			throws Exception {
		this.winner = -1;
		this.mapName = mapFile.getName();
		new File(logFile).delete();
		this.logWriter = new BufferedWriter(new FileWriter(logFile, true));
		if (parse(mapFile) > 0) {
			throw new Exception("Invalid map file");
		}
	}

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
	public void dropPlayer(int id) {
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
		checkWinner();
	}

	public void checkWinner() {
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
		}
	}

	/**
	 * Returns a view of the current situation
	 * 
	 * @param paramInt
	 * @return
	 */
	public String playerView(int paramInt) {
		StringBuilder sb = new StringBuilder();
		Locale.setDefault(new Locale("en"));
		for (Planet p : planets) {
			if (p instanceof MilitaryPlanet) {
				sb.append(String.format("M %f %f %d %d\n", p.x, p.y, p.owner,
						p.numShips));
			} else if (p instanceof EconomicPlanet) {
				sb.append(String.format("E %f %f %d %d %d\n", p.x, p.y,
						p.owner, p.numShips, ((EconomicPlanet) p).revenue));
			}
		}
		for (Fleet f : fleets) {
			sb.append(String.format("F %d %d %d %d %d %d\n", f.owner,
					f.numShips, f.sourcePlanet, f.destinationPlanet,
					f.totalTripLength, f.turnsRemaining));
		}
		return sb.toString();
	}

	public void writeLogMessage(String message) {
		if (this.logWriter == null) {
			// No log file
			return;
		}
		try {
			this.logWriter.write(message);
			this.logWriter.newLine();
			this.logWriter.flush();
		} catch (Exception ex) {
		}
	}

	public int issueOrder(int id, String order) {
		String[] orderParts = order.split(" ");
		int sourcePlanet = Integer.parseInt(orderParts[0]);
		int destPlanet = Integer.parseInt(orderParts[1]);
		int k = Integer.parseInt(orderParts[2]);
		return issueOrder(id, sourcePlanet, destPlanet, k);
	}

	public int issueOrder(int id, int sourcePlanet, int destPlanet, int numShips) {
		Planet localPlanet = (Planet) this.planets.get(sourcePlanet);
		if ((localPlanet.owner != id) || (numShips > localPlanet.numShips)
				|| (numShips < 0)) {
			writeLogMessage("dropping player " + id + ". source.owner = "
					+ localPlanet.owner + ", player = " + id + ", numShips = "
					+ numShips + ", source.numShips = " + localPlanet.numShips);
			dropPlayer(id);
			return -1;
		}
		localPlanet.numShips -= numShips;
		int distance = distance(sourcePlanet, destPlanet);
		Fleet localFleet = new Fleet(localPlanet.owner, numShips, sourcePlanet,
				destPlanet, distance, distance);

		this.fleets.add(localFleet);
		return 0;
	}

	public int distance(int sourcePlanet, int destPlanet) {
		Planet localPlanet1 = planets.get(sourcePlanet);
		Planet localPlanet2 = planets.get(destPlanet);
		double d1 = localPlanet1.x - localPlanet2.x;
		double d2 = localPlanet1.y - localPlanet2.y;
		return (int) Math.ceil(Math.sqrt(d1 * d1 + d2 * d2));
	}

	public void doTimeStep() {
		int[] revenue = new int[64];

		for (Planet p : planets) {
			if (p instanceof EconomicPlanet && p.owner != 0) {
				revenue[p.owner] = revenue[p.owner]
						+ ((EconomicPlanet) p).revenue;
			}
		}

		for (Planet p : planets) {
			if (p instanceof MilitaryPlanet && p.owner != 0) {
				p.numShips += revenue[p.owner];
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

	private void fight(Planet p) {
		Map<Integer, Integer> ships = new TreeMap<Integer, Integer>();

		ships.put(p.owner, p.numShips);
		List<Fleet> keepFleets = new ArrayList<Fleet>();
		for (Fleet f : fleets) {
			if (planets.get(f.destinationPlanet) == p && f.turnsRemaining == 0) {
				if (ships.get(f.owner) == null) {
					ships.put(f.owner, 0);
				}
				int currentShips = ships.get(f.owner);
				ships.put(f.owner, f.numShips + currentShips);
			} else {
				keepFleets.add(f);
			}
		}

		if (ships.keySet().size() == 0) {
			// No fight
			return;
		} else if (ships.keySet().size() == 1) {
			// Only one player
			p.numShips = ships.get(p.owner);
			return;
		}

		fleets = keepFleets;

		int maxShips = ships.get(p.owner);
		int maxOwner = p.owner;
		int secondShips = 0;

		for (Entry<Integer, Integer> e : ships.entrySet()) {
			if (e.getValue() >= maxShips) {
				secondShips = maxShips;
				maxShips = e.getValue();
				maxOwner = e.getKey();
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

		File logsFolder = new File(GAMES_FOLDER);
		int gameId = logsFolder.listFiles().length + 1;
		File gLog = new File(GAMES_FOLDER + gameId + ".game");
		FileWriter fw = null;
		try {
			fw = new FileWriter(gLog);
			fw.write("game_id=" + gameId + "\n");
			fw.write("winner=" + winnerId + "\n");
			fw.write("map_id=" + mapName + "\n");
			fw.write("draw=" + (winnerId == 0 ? 1 : 0) + "\n");
			fw.write("timestamp=" + System.currentTimeMillis() + "\n");
			fw.write("players=");
			for (int i = 1; i <= numPlayers; i++) {
				if (i > 1) {
					fw.write("|");
				}
				fw.write(i + ":player" + i);
			}
			fw.write("\n");
			fw.write("playback_string=" + gameLog.toString());
			fw.write("\n");
		} catch (IOException e) {
			e.printStackTrace();
		} finally {
			if (fw != null) {
				try {
					fw.close();
				} catch (IOException e) {
					e.printStackTrace();
				}
			}
		}

	}

	private int parse(String map) {
		planets = new ArrayList<Planet>();
		fleets = new ArrayList<Fleet>();

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

						MilitaryPlanet p = new MilitaryPlanet(owner, numShips,
								x, y);
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

						EconomicPlanet p = new EconomicPlanet(owner, numShips,
								economicValue, x, y);
						planets.add(p);
						if (this.gameLog.length() > 0) {
							this.gameLog.append(":");
						}
						this.gameLog.append("E,").append(x).append(",")
								.append(y).append(",").append(owner)
								.append(",").append(numShips).append(",")
								.append(economicValue);
					} else if (line[0].equals("F")) {
						if (line.length != 7) {
							return 1;
						}
						int owner = Integer.parseInt(line[1]);
						int numShips = Integer.parseInt(line[2]);
						int sourceDept = Integer.parseInt(line[3]);
						int destDept = Integer.parseInt(line[4]);
						int tripLength = Integer.parseInt(line[5]);
						int turnsRemaining = Integer.parseInt(line[6]);
						Fleet fleet = new Fleet(owner, numShips, sourceDept,
								destDept, tripLength, turnsRemaining);
						this.fleets.add(fleet);
					} else {
						return 1;
					}
				}
			}
		}
		gameLog.append("|");
		return 0;
	}
}
