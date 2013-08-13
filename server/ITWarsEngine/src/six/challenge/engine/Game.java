package six.challenge.engine;

import java.io.BufferedWriter;
import java.util.List;

public abstract class Game {

	public int winner;
	public StringBuffer gameLog = new StringBuffer();
	public String mapName;
	public int numPlayers;
	public boolean errorAtStartup = false;

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
		} else if (livePlayers == 0) {
			winner = 0;
		}
	}

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
	public abstract List<String> getScores();

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
}
