package six.challenge.engine;

import java.io.File;
import java.io.OutputStreamWriter;
import java.util.ArrayList;
import java.util.List;

public class Player {

	public Player(int id, String dir, String command) {
		this.id = id;
		this.dir = new File(dir);
		this.command = command;
		start();
	}

	public int id;
	public String command;
	public File dir;
	public Status status = Status.NOT_INITIALIZED;
	public Process process;

	public boolean hasPlayed = false;

	public enum Status {
		NOT_INITIALIZED, STARTED, CRASHED, PLAYING, LOST, TIMEOUT, ENDED,
	}

	/**
	 * Starts the bot.
	 */
	public void start() {
		try {
			process = Runtime.getRuntime().exec(command, null, dir);
			status = Status.STARTED;
		} catch (Exception ex) {
			kill(Status.CRASHED);
			System.err.println("Error: player " + id + " crashed at startup. ");
			ex.printStackTrace(System.err);
		}
	}

	/**
	 * Kills the bot process and sets the status.
	 */
	public void kill(Status s) {
		if (process != null) {
			process.destroy();
			process = null;
			status = s;
		}
	}

	public void kill() {
		kill(Status.ENDED);
	}

	public void sendMessage(String text) {
		try {
			OutputStreamWriter osw = new OutputStreamWriter(
					process.getOutputStream());
			osw.write(text);
			osw.flush();
		} catch (Exception ex) {
			kill(Status.CRASHED);
			System.err.println("Error: player " + id
					+ " crashed when sending orders. ");
			ex.printStackTrace(System.err);
		}
	}

	public List<String> getOrders() {
		// Get orders from each player
		StringBuilder orderStream = new StringBuilder();
		List<String> orders = new ArrayList<String>();
		try {
			while (process.getInputStream().available() > 0) {
				char c = (char) process.getInputStream().read();
				if (c != '\n') {
					// Read order until end of line
					orderStream.append(c);
				} else {
					// Execute order
					String order = orderStream.toString();
					order = order.toLowerCase().trim();
					if (order.equals("go")) {
						hasPlayed = true;
						return orders;
					} else if (!"".equals(order)) {
						orders.add(order);
					}
					orderStream = new StringBuilder();
				}
			}
		} catch (Exception ex) {
			kill(Status.CRASHED);
			System.err.println("Error: player " + id
					+ " crashed when getting orders. ");
			ex.printStackTrace(System.err);
		}
		return orders;
	}
}
