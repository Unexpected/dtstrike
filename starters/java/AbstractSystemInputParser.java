import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.Scanner;
import java.util.regex.Pattern;

/**
 * Handles system input stream parsing.
 */
public abstract class AbstractSystemInputParser extends
		AbstractSystemInputReader {
	private static final String READY = "ready";

	private static final String GO = "go";

	private static final char COMMENT_CHAR = '#';

	private final List<String> input = new ArrayList<String>();

	private enum UpdateToken {
		M, E, F, R;

		private static final Pattern PATTERN = compilePattern(UpdateToken.class);
	}

	@SuppressWarnings("rawtypes")
	private static Pattern compilePattern(Class<? extends Enum> clazz) {
		StringBuilder builder = new StringBuilder("(");
		for (Enum enumConstant : clazz.getEnumConstants()) {
			if (enumConstant.ordinal() > 0) {
				builder.append("|");
			}
			builder.append(enumConstant.name());
		}
		builder.append(")");
		return Pattern.compile(builder.toString());
	}

	/**
	 * Collects lines read from system input stream until a keyword appears and
	 * then parses them.
	 */
	@Override
	public void processLine(String line) {
		if (line.equals(READY)) {
			parseSetup(input);
			doReadyTurn();
			finishTurn();
			input.clear();
		} else if (line.equals(GO)) {
			parseUpdate(input);
			doTurn();
			finishTurn();
			input.clear();
		} else if (!line.isEmpty()) {
			input.add(line);
		}
	}

	/**
	 * Parses the setup information from system input stream.
	 * 
	 * @param input
	 *            setup information
	 */
	public void parseSetup(List<String> input) {
		Map<String, String> options = new HashMap<String, String>();
		List<String> map = new ArrayList<String>();
		for (String line : input) {
			line = removeComment(line);
			if (line.isEmpty()) {
				continue;
			}
			Scanner scanner = new Scanner(line);
			if (!scanner.hasNext()) {
				continue;
			}
			String token = scanner.next();
			if (token.startsWith("*")) {
				String option = line.substring(1);
				options.put(option.split(":")[0], option.split(":")[1]);
				scanner.close();
			} else {
				map.add(line);
			}
		}
		setup(options);
		parseUpdate(map);
	}

	/**
	 * Parses the update information from system input stream.
	 * 
	 * @param input
	 *            update information
	 */
	public void parseUpdate(List<String> input) {
		beforeUpdate();
		int id = 0;
		Locale.setDefault(new Locale("en"));
		for (String line : input) {
			line = removeComment(line);
			if (line.isEmpty()) {
				continue;
			}
			Scanner scanner = new Scanner(line);
			if (!scanner.hasNext()) {
				continue;
			}
			String token = scanner.next().toUpperCase();
			if (!UpdateToken.PATTERN.matcher(token).matches()) {
				continue;
			}
			UpdateToken updateToken = UpdateToken.valueOf(token);
			switch (updateToken) {
			case M:
				double mx = scanner.nextDouble();
				double my = scanner.nextDouble();
				int mowner = scanner.nextInt();
				int mnumShips = scanner.nextInt();
				addMilitaryPlanet(id++, mowner, mnumShips, mx, my);
				break;
			case E:
				double ex = scanner.nextDouble();
				double ey = scanner.nextDouble();
				int eowner = scanner.nextInt();
				int enumShips = scanner.nextInt();
				int economicValue = scanner.nextInt();
				addEconomicPlanet(id++, eowner, enumShips, economicValue, ex,
						ey);
				break;
			case F:
			case R:
				int fowner = scanner.nextInt();
				int fnumShips = scanner.nextInt();
				int fsourceDept = scanner.nextInt();
				int fdestDept = scanner.nextInt();
				int ftripLength = scanner.nextInt();
				int fturnsRemaining = scanner.nextInt();
				addFleet(fowner, fnumShips, fsourceDept, fdestDept,
						ftripLength, fturnsRemaining,
						(updateToken == UpdateToken.F));
				break;
			}
			scanner.close();
		}
		afterUpdate();
	}

	/**
	 * Sets up the game state.
	 * 
	 * @param options
	 */
	public abstract void setup(Map<String, String> options);

	/**
	 * Enables performing actions which should take place prior to updating the
	 * game state, like clearing old game data.
	 */
	public abstract void beforeUpdate();

	/**
	 * Adds new fleet.
	 * 
	 * @param owner
	 *            player id
	 * @param numShips
	 *            number of ships in fleet
	 * @param sourceDept
	 *            source planet id
	 * @param destDept
	 *            destination planet id
	 * @param tripLength
	 * @param turnsRemaining
	 * @param military
	 *            is military fleet
	 */
	public abstract void addFleet(int owner, int numShips, int sourceDept,
			int destDept, int tripLength, int turnsRemaining, boolean military);

	/**
	 * Adds new military planet.
	 * 
	 * @param id
	 *            planet id
	 * @param owner
	 *            player id
	 * @param numShips
	 *            number of ships
	 * @param x
	 *            row index
	 * @param y
	 *            column index
	 */
	public abstract void addMilitaryPlanet(int id, int owner, int numShips,
			double x, double y);

	/**
	 * Adds new economic planet.
	 * 
	 * @param id
	 *            planet id
	 * @param owner
	 *            player id
	 * @param numShips
	 *            number of ships
	 * @param revenue
	 *            growthRate of the planet
	 * @param x
	 *            row index
	 * @param y
	 *            column index
	 */
	public abstract void addEconomicPlanet(int id, int owner, int numShips,
			int revenue, double x, double y);

	/**
	 * Enables performing actions which should take place just after the game
	 * state has been updated.
	 */
	public abstract void afterUpdate();

	/**
	 * Subclasses are supposed to use this method to process the game state and
	 * send orders.
	 */
	public abstract void doTurn();

	/**
	 * Subclasses are supposed to use this method to process the game init
	 */
	public abstract void doReadyTurn();

	/**
	 * Finishes turn.
	 */
	public void finishTurn() {
		System.out.println("go\n");
		System.out.flush();
	}

	private String removeComment(String line) {
		int commentCharIndex = line.indexOf(COMMENT_CHAR);
		String lineWithoutComment;
		if (commentCharIndex >= 0) {
			lineWithoutComment = line.substring(0, commentCharIndex).trim();
		} else {
			lineWithoutComment = line;
		}
		return lineWithoutComment;
	}
}
