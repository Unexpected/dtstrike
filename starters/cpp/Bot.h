#ifndef BOT_H
#define BOT_H

#include <vector>
#include <string>
#include "Game.h"

class Bot
{
public:
	Bot();
	virtual ~Bot();

	/**
	 * Reads system input stream line by line. All characters are converted to
	 * lower case and each line is passed for processing to
	 * {@link #processLine(String)} method.
	 */
	void readSystemInput();

protected:
	Game game;

private:

	/**
	 * Collects lines read from system input stream until a keyword appears and
	 * then parses them.
	 */
	void processLine(const std::string& line);

	void parseSetup();

	void parseUpdate();

	/**
	 * Finishes turn.
	 */
	void finishTurn();

	/**
	 * Enables performing actions which should take place prior to updating the
	 * game state, like clearing old game data.
	 */
	virtual void beforeUpdate();

	/**
	 * Enables performing actions which should take place just after the game
	 * state has been updated.
	 */
	virtual void afterUpdate();

	/**
	 * Subclasses are supposed to use this method to process the game init
	 */
	virtual void doReadyTurn() = 0;

	/**
	 * Subclasses are supposed to use this method to process the game state and
	 * send orders.
	 */
	virtual void doTurn() = 0;

	std::vector<std::string> input;
};

#endif
