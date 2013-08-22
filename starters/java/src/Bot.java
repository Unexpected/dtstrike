/**
 * Provides basic game state handling.
 */
public abstract class Bot extends AbstractSystemInputParser {
    private Game game;
    
    /**
     * {@inheritDoc}
     */
    @Override
    public void setup(int loadTime, int turnTime, int turns) {
        setGame(new Game(loadTime, turnTime, turns));
    }
    
    /**
     * Returns game state information.
     * 
     * @return game state information
     */
    public Game getGame() {
        return game;
    }
    
    /**
     * Sets game state information.
     * 
     * @param game game state information to be set
     */
    protected void setGame(Game game) {
        this.game = game;
    }
    
    /**
     * {@inheritDoc}
     */
    @Override
    public void beforeUpdate() {
        game.setTurnStartTime(System.currentTimeMillis());
        game.clearFleets();
        game.clearPlanets();
        game.getOrders().clear();
    }
    
    /**
     * {@inheritDoc}
     */
    @Override
    public void addFleet(int owner, int numShips, int sourceDept, int destDept, int tripLength, int turnsRemaining, boolean military) {
    	if (military) {
    		game.addFleet(new MilitaryFleet(owner, numShips, sourceDept, destDept, tripLength, turnsRemaining));
    	} else {
    		game.addFleet(new EconomicFleet(owner, numShips, sourceDept, destDept, tripLength, turnsRemaining));
    	}
    }
    
    /**
     * {@inheritDoc}
     */
    @Override
    public void addMilitaryPlanet(int id, int owner, int numShips, double x, double y) {
        game.addPlanet(new MilitaryPlanet(id, owner, numShips, x, y));
    }
    
    /**
     * {@inheritDoc}
     */
    @Override
    public void addEconomicPlanet(int id, int owner, int numShips, int revenue, double x, double y) {
        game.addPlanet(new EconomicPlanet(id, owner, numShips, revenue, x, y));
    }
    
    /**
     * {@inheritDoc}
     */
    @Override
    public void afterUpdate() {
    }
}
