package six.challenge.engine;

import java.io.BufferedWriter;
import java.util.List;
import java.util.Map;

public abstract class Game {

	public String mapName;
	public boolean errorAtStartup = false;

	private Map<String, String> options;

	public BufferedWriter logWriter;

	/**
	 * Warm up game
	 */
	public abstract void startGame();

	/**
	 * Is player id alive ?
	 * 
	 * @param id
	 *            player id to check
	 * @return true if the player is alive
	 */
	public abstract boolean isAlive(int id);

	/**
	 * Drop player
	 * 
	 * @param id
	 *            player id to drop
	 */
	public abstract void killPlayer(int id);

	/**
	 * Returns a view of the start situation for a player
	 * 
	 * @param id
	 *            player id
	 * @return
	 */
	public abstract String getPlayerStart(int id);

	/**
	 * Returns a view of the current situation for a player
	 * 
	 * @param id
	 *            player id
	 * @return
	 */
	public abstract String getPlayerState(int id);

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

	public abstract void doMoves(int id, List<String> orders);

	/**
	 * Get players scores
	 * 
	 * @return
	 */
	public abstract List<Integer> getScores();

	/**
	 * Get current game state for logging
	 * 
	 * @return
	 */
	public abstract String getState();

	/**
	 * Warm up game for a turn
	 */
	public abstract void startTurn();

	/**
	 * Finish the current turn
	 */
	public abstract void finishTurn();

	/**
	 * Finish the game
	 */
	public abstract void finishGame();

	/**
	 * Is the game over ?
	 * 
	 * @return true if the game is over, false if "la fête continue !" (oui,
	 *         ceci est une private joke)
	 */
	public abstract boolean isGameOver();

	public abstract String getReplay();

	public Map<String, String> getOptions() {
		return options;
	}

	public void setOptions(Map<String, String> options) {
		this.options = options;
	}
}
