package six.challenge.game.galaxsix;

import java.io.BufferedWriter;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.HashSet;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.Map.Entry;
import java.util.Set;
import java.util.TreeMap;

import six.challenge.engine.Game;
import six.challenge.engine.Player;

public class GalaxSix extends Game {

	public GalaxSix(File mapFile, Map<String, String> options,
			List<Player> players, String logFile) {
		this.mapName = mapFile.getName();
		this.players = players;
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

	public String mapName;
	private List<Player> players;
	public boolean errorAtStartup = false;

	public List<Planet> planets;
	public List<Fleet> fleets;

	private List<List<Integer>> scoreHistory;
	private List<String> initialMap;
	private List<List<String>> dataHistory;
	private List<Integer> playerTurns;
	private int turn;
	private String cutoff;

	/**
	 * A player is alive if he owns at least one military planet or one fleet.
	 * 
	 * @param id
	 *            player id to check
	 * @return true if the player is alive
	 */
	public boolean isAlive(int id) {
		for (Planet p : planets) {
			if (p.owner == id)
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

	private int parse(String map) {
		planets = new ArrayList<Planet>();
		fleets = new ArrayList<Fleet>();

		String[] lines = map.split("\r\n");
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

					} else {
						return 1;
					}
				}
			}
		}
		initialMap = new ArrayList<String>();
		for (Planet p : planets) {
			initialMap.add(p.toString());
		}
		return 0;
	}

	/**
	 * Everyone thinks he's the number one
	 */
	private Map<Integer, Map<Integer, Integer>> playerSwitch = new HashMap<Integer, Map<Integer, Integer>>();

	@Override
	public void startGame() {
		for (int numPlayer = 1; numPlayer < players.size() + 1; numPlayer++) {
			Map<Integer, Integer> sw = new HashMap<Integer, Integer>();
			// 1 : 1:1, 2:2, 3:3
			// 2 : 1:3, 2:1; 3:2
			// 3 : 1:2, 2:3; 3:1
			for (int otherPlayer = 1; otherPlayer < players.size() + 1; otherPlayer++) {
				int decalage = numPlayer - 1;
				int otherPlayerSwitched = otherPlayer - decalage;
				if (otherPlayerSwitched < 1) {
					otherPlayerSwitched += players.size();
				}
				sw.put(otherPlayer, otherPlayerSwitched);
			}
			// Neutral player is always neutral
			sw.put(0, 0);
			playerSwitch.put(numPlayer, sw);
		}

		scoreHistory = new ArrayList<List<Integer>>();
		for (Player p : players) {
			scoreHistory.add(new ArrayList<Integer>());
		}
		dataHistory = new ArrayList<List<String>>();
		playerTurns = new ArrayList<Integer>();
		for (Player p : players) {
			playerTurns.add(0);
		}
		// planets_history = [('%d %d' % (p.owner,p.num_ships)) for p in
		// self.planets]
		// fleets_history = [('%s %d %d %d %d %d %d' % (f.type, f.owner,
		// f.num_ships, f.source_planet, f.destination_planet,
		// f.total_trip_length, f.turns_remaining)) for f in self.fleets]
		// turn_history = ','.join(planets_history)
		// if len(self.fleets) > 0:
		// turn_history = turn_history + ',' + ','.join(fleets_history)
		// self.replay_history.append(turn_history)
	}

	@Override
	public String getPlayerStart(int id) {
		String startMessage = "";
		for (Entry<String, String> e : getOptions().entrySet()) {
			startMessage += "*" + e.getKey() + ":" + e.getValue() + "\n";
		}
		return startMessage;
	}

	@Override
	public List<Integer> getScores() {
		List<Integer> scores = new ArrayList<Integer>();
		for (Player p : players) {
			int score = 0;
			for (Planet planet : planets) {
				if (planet instanceof EconomicPlanet && planet.owner == p.id) {
					score++;
				}
			}
			scores.add(score);
		}
		return scores;
	}

	@Override
	public String getState() {
		// UNUSED
		return null;
	}

	@Override
	public void startTurn() {
		turn++;
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

		List<Integer> scores = getScores();
		for (Player p : players) {
			if (isAlive(p.id)) {
				playerTurns.set(p.id - 1, turn);
			}
			scoreHistory.get(p.id - 1).add(scores.get(p.id - 1));
		}

		List<String> turnHistory = new ArrayList<String>();
		for (Planet p : planets) {
			turnHistory.add(p.owner + " " + p.numShips);
		}
		for (Fleet f : fleets) {
			turnHistory.add(f.toString());
		}
		dataHistory.add(turnHistory);
	}

	@Override
	public void finishGame() {
		// TODO We could send data to bots for testing purpose
	}

	@Override
	public boolean isGameOver() {
		if (turn > Integer.parseInt(getOptions().get("turns"))) {
			cutoff = "limit turns";
			return true;
		}
		int livePlayers = 0;
		for (int i = 0; i < players.size(); i++) {
			if (isAlive(i + 1)) {
				livePlayers++;
			}
		}
		if (livePlayers == 1) {
			cutoff = "lone survivor";
			return true;
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

	@Override
	public String getReplay() {
		Map<String, Object> replay = new LinkedHashMap<String, Object>();
		replay.put("status", getStatuses());
		replay.put("replaydata", getReplayData());

		replay.put("rank", getRanks());
		replay.put("post_id", 0);
		replay.put("matchup_id", 0);
		replay.put("challenge", "galaxsix");
		replay.put("playerturns", playerTurns);
		replay.put("score", getScores());
		replay.put("replayformat", "json");
		replay.put("location", "localhost");
		replay.put("game_length", turn);
		replay.put("playernames", getPlayerStubData(false));
		replay.put("submission_ids", getPlayerStubData(true));
		replay.put("user_ids", getPlayerStubData(true));

		replay.put("challenge_rank", getPlayerStubData(true));
		replay.put("challenge_skill", getPlayerStubData(true));
		replay.put("user_url", "http://localhost/user/~");
		replay.put("game_url", "http://localhost/game/~");
		replay.put("date", new Date().toString());
		replay.put("game_id", 0);
		replay.put("worker_id", 1);

		return mapToJSON("", replay);
	}

	public String valToJSON(Object value) {
		if (value == null) {
			return "";
		}
		if (value instanceof Integer) {
			return String.valueOf(value);
		}
		if (value instanceof String) {
			return "\"" + value + "\"";
		}
		return value.toString();
	}

	public String objToJSON(String name, Object value) {
		return "\"" + name + "\":" + valToJSON(value);
	}

	public String listToJSON(String name, List<Object> array) {
		String values = "";
		for (Object o : array) {
			if (!"".equals(values))
				values += ",";
			values += valToJSON(o);
		}
		return "\"" + name + "\":[" + values + "]";
	}

	public String mapToJSON(String name, Map<String, Object> map) {
		String entries = "";
		for (Entry<String, Object> e : map.entrySet()) {
			if (!"".equals(entries))
				entries += ",";
			entries += toJSON(e.getKey(), e.getValue());
		}
		if ("".equals(name)) {
			return "{" + entries + "}";
		}
		return "\"" + name + "\":{" + entries + "}";
	}

	public String toJSON(String name, Object o) {
		if (o instanceof Map) {
			return mapToJSON(name, (Map) o);
		} else if (o instanceof List) {
			return listToJSON(name, (List) o);
		}
		return objToJSON(name, o);
	}

	private List<String> getStatuses() {
		List<String> statuses = new ArrayList<String>();
		for (Player p : players) {
			statuses.add(p.status.toString());
		}
		return statuses;
	}

	private List<Integer> getRanks() {
		List<Integer> ranks = new ArrayList<Integer>();
		List<Integer> scores = getScores();
		for (int i = 0; i < scores.size(); i++) {
			int s = scores.get(i);
			int r = 1;
			for (int j = 0; j < scores.size(); j++) {
				if (s < scores.get(j)) {
					r++;
				}
			}
			ranks.add(r);
		}
		return ranks;
	}

	private Map<String, Object> getReplayData() {
		Map<String, Object> m = new LinkedHashMap<String, Object>();
		m.put("ranking_turn", 1);
		m.put("map", getMapData());
		m.put("loadtime", getOptions().get("loadtime"));
		m.put("bonus", getPlayerStubData(true));
		m.put("turns", getOptions().get("turns"));
		m.put("winning_turn", 0);
		m.put("players", players.size());
		m.put("turntime", getOptions().get("turntime"));
		m.put("scores", scoreHistory);
		m.put("cutoff", cutoff);
		m.put("revision", 3);
		return m;
	}

	public Map<String, Object> getMapData() {
		Map<String, Object> m = new LinkedHashMap<String, Object>();
		m.put("data", initialMap);
		m.put("history", dataHistory);
		return m;
	}

	private List<Object> getPlayerStubData(boolean intData) {
		List<Object> stub = new ArrayList<Object>();
		int id = 1;
		for (Player p : players) {
			if (intData) {
				stub.add(0);
			} else {
				stub.add("player " + id++);
			}
		}
		return stub;
	}

}
