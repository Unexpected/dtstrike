import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;
import java.util.regex.Pattern;

/**
 * Handles system input stream parsing.
 */
public abstract class AbstractSystemInputParser extends AbstractSystemInputReader {
    private static final String READY = "ready";
    
    private static final String GO = "go";
    
    private static final char COMMENT_CHAR = '#';
    
    private final List<String> input = new ArrayList<String>();
    
    private enum SetupToken {
        LOADTIME, TURNTIME, TURNS;
        
        private static final Pattern PATTERN = compilePattern(SetupToken.class);
    }
    
    private enum UpdateToken {
        M, E, F;
        
        private static final Pattern PATTERN = compilePattern(UpdateToken.class);
    }
    
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
     * Collects lines read from system input stream until a keyword appears and then parses them.
     */
    @Override
    public void processLine(String line) {
        if (line.equals(READY)) {
            parseSetup(input);
            doTurn();
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
     * @param input setup information
     */
    public void parseSetup(List<String> input) {
        int loadTime = 0;
        int turnTime = 0;
        int turns = 0;
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
            if (!SetupToken.PATTERN.matcher(token).matches()) {
                continue;
            }
            SetupToken setupToken = SetupToken.valueOf(token);
            switch (setupToken) {
                case LOADTIME:
                    loadTime = scanner.nextInt();
                break;
                case TURNTIME:
                    turnTime = scanner.nextInt();
                break;
                case TURNS:
                    turns = scanner.nextInt();
                break;
            }
            scanner.close();
        }
        setup(loadTime, turnTime, turns);
    }
    
    /**
     * Parses the update information from system input stream.
     * 
     * @param input update information
     */
    public void parseUpdate(List<String> input) {
        beforeUpdate();
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
            int id = 0;
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
                    addEconomicPlanet(id++, eowner, enumShips, economicValue, ex, ey);
                break;
                case F:
					int fowner = scanner.nextInt();
					int fnumShips = scanner.nextInt();
					int fsourceDept = scanner.nextInt();
					int fdestDept = scanner.nextInt();
					int ftripLength = scanner.nextInt();
					int fturnsRemaining = scanner.nextInt();
                	addFleet(fowner, fnumShips, fsourceDept, fdestDept, ftripLength, fturnsRemaining);
                break;
            }
            scanner.close();
        }
        afterUpdate();
    }
    
    /**
     * Sets up the game state.
     * 
     * @param loadTime timeout for initializing and setting up the bot on turn 0
     * @param turnTime timeout for a single game turn, starting with turn 1
     * @param turns maximum number of turns the game will be played
     */
    public abstract void setup(int loadTime, int turnTime, int turns);
    
    /**
     * Enables performing actions which should take place prior to updating the game state, like
     * clearing old game data.
     */
    public abstract void beforeUpdate();
    
    /**
     * Adds new fleet.
     * 
     * @param owner player id
     * @param numShips number of ships in fleet 
     * @param sourceDept source planet id
     * @param destDept destination planet id
     * @param tripLength 
     * @param turnsRemaining 
     */
    public abstract void addFleet(int owner, int numShips, int sourceDept, int destDept, int tripLength, int turnsRemaining);
    
    /**
     * Adds new military planet.
     * 
     * @param id planet id
     * @param owner player id
     * @param numShips number of ships
     * @param x row index
     * @param y column index
     */
    public abstract void addMilitaryPlanet(int id, int owner, int numShips, double x, double y);
    
    /**
     * Adds new economic planet.
     * 
     * @param id planet id
     * @param owner player id
     * @param numShips number of ships
     * @param revenue growthRate of the planet
     * @param x row index
     * @param y column index
     */
    public abstract void addEconomicPlanet(int id, int owner, int numShips, int revenue, double x, double y);
    
    /**
     * Enables performing actions which should take place just after the game state has been
     * updated.
     */
    public abstract void afterUpdate();
    
    /**
     * Subclasses are supposed to use this method to process the game state and send orders.
     */
    public abstract void doTurn();
    
    /**
     * Finishes turn.
     */
    public void finishTurn() {
        System.out.println("go");
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
