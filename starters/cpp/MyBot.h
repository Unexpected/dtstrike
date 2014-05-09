#ifndef MYBOT_H
#define MYBOT_H

#include "Bot.h"

class MyBot: public Bot
{
public:
	MyBot();

private:
	/**
	 * The DoTurn function is where your code goes.<br/>
	 * The Game object contains the state of the game, including information
	 * about all planets and fleets that currently exist.<br/>
	 * Inside this function, you issue orders using the {@link Game#issueOrder}
	 * functions.<br/>
	 * For example, to send 10 ships from planet 3 to planet 8, you would say
	 * <code>game.issueOrder(3, 8, 10).</code>
	 *
	 * <p>
	 * There is already a basic strategy in place here.<br/>
	 * You can use it as a starting point, or you can throw it out entirely and
	 * replace it with your own.
	 * </p>
	 *
	 * @param game
	 *            the Game instance
	 */
	virtual void doTurn() override;

	/**
	 * Method called at the init phase of the Game
	 * (ie before first turn)
	 * !! No orders could be given here !!
	 */
	virtual void doReadyTurn() override;
};

#endif
