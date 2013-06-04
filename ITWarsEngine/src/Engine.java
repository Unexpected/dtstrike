import java.util.ArrayList;
import java.util.List;

public class Engine {

	public static void main(String[] args) {
		if (args.length < 5) {
			System.err
					.println("Error : wrong number of command-line arguments.");
			System.err
					.println("Usage : engine map_file_name max_turn_time max_num_turns log_filename player_one player_two [more_players]");

			System.exit(1);
		}

		String mapFile = args[0];
		String turnTime = args[1];
		String turns = args[2];
		String logFile = args[3];

		List<Process> players = new ArrayList<Process>();
		for (int i = 4; i < args.length; i++) {
			String player = args[i];
			Process localProcess = null;
			try {
				localProcess = Runtime.getRuntime().exec(player);
			} catch (Exception localException1) {
				localProcess = null;
			}
			if (localProcess == null) {
				killClients(players);
				System.err.println("Error : failed to start client: " + player);
				System.exit(1);
			}
			players.add(localProcess);
		}
	}

	private static void killClients(List<Process> players) {
		for (Process localProcess : players) {
			if (localProcess != null) {
				localProcess.destroy();
			}
		}
	}
}
