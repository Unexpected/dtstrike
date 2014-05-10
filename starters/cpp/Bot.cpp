#include "Bot.h"

#include <iostream>
#include <sstream>

const std::string READY("ready");
const std::string GO("go");
const char COMMENT_CHAR = '#';


Bot::Bot() = default;

Bot::~Bot() = default;

void Bot::readSystemInput()
{
	while (std::cin.good()) {
		std::string line;
		std::getline(std::cin, line);
		processLine(line);
	}
}
void Bot::processLine(const std::string& line)
{
	if (line == READY) {
		parseSetup();
		doReadyTurn();
		finishTurn();
		input.clear();
	} else if (line == GO) {
		parseUpdate();
		doTurn();
		finishTurn();
		input.clear();
	} else if (not line.empty()) {
		input.emplace_back(std::move(line));
	}
}

void Bot::parseSetup()
{
	auto it = input.begin();
	while (it != input.end()) {
		const std::string& line = *it;
		if (line.empty() or line[0] == COMMENT_CHAR)
			continue;

		if (line[0] == '*') {
			std::istringstream splitter(line);
			char useless_char;
			std::string option_name, option_value;

			/* Remove the trailing * */
			splitter >> useless_char;

			std::getline(splitter, option_name, ':');
			std::getline(splitter, option_value);

			game.setOption(option_name, option_value);

			it = input.erase(it);
		} else {
			/* keep it because we want to feed it to parseUpdate. */
			++it;
		}
	}
	parseUpdate();
}

void Bot::parseUpdate()
{
	int id = 0;
	beforeUpdate();

	for (const std::string& line : input)
	{
		if (line.empty() or line[0] == COMMENT_CHAR)
			continue;
		std::istringstream splitter(line);

		std::string updateToken;
		splitter >> updateToken;

		if (updateToken.empty())
			continue;

		switch (updateToken[0])
		{
		case 'M':
		{
			double x, y;
			int owner, numShips;
			splitter >> x >> y >> owner >> numShips;
			game.addPlanet(std::make_shared<MilitaryPlanet>(id++, owner, numShips, x, y));
			break;
		}
		case 'E':
		{
			double x, y;
			int owner, numShips, economicValue;
			splitter >> x >> y >> owner >> numShips >> economicValue;
			game.addPlanet(std::make_shared<EconomicPlanet>(id++, owner, numShips, economicValue, x, y));
			break;
		}
		case 'F':
		case 'R':
		{
			int owner, numShips, sourcePlanet, destPlanet, tripLength, turnsRemaining;
			splitter >> owner >> numShips >> sourcePlanet >> destPlanet
			         >> tripLength >> turnsRemaining;
			if (updateToken[0] == 'F') {
				game.addFleet(std::make_shared<MilitaryFleet>(
						owner, numShips, sourcePlanet, destPlanet,
						tripLength, turnsRemaining));
			} else {
				game.addFleet(std::make_shared<EconomicFleet>(
						owner, numShips, sourcePlanet, destPlanet,
						tripLength, turnsRemaining));
			}
			break;
		}
		default:
			std::cerr << "Ignored line: <" << line << ">" << std::endl;
			break;
		}

		afterUpdate();
	}
}

void Bot::finishTurn()
{
	std::cout << "go" << std::endl;
}

void Bot::beforeUpdate()
{
	game.setTurnStartTime(getSystemTimeInMillis());
	game.clearFleets();
	game.clearPlanets();
	game.clearOrders();
}

void Bot::afterUpdate()
{

}
