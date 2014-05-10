#include <limits.h>
#include <stdio.h>

#include "parser.h"
#include "game.h"

#define UNUSED(x) ((void)x)

void farm_planets(struct game *game)
{
	int i;
	FOR_MY_ECONOMIC_PLANETS(game, i) {
		struct planet *p = get_planet(game, i);

		if (p->num_ships > 50) {
			issue_order(game, i,
				find_my_closest_military_planet(game, i),
				50);
		}
	}
}

int has_enough_fleets(struct game *game)
{
	int i;
	int fleet_count = 0;

	FOR_MY_MILITARY_FLEETS(game, i) {
		++fleet_count;
		if (fleet_count > 2)
			return 1;
	}
	return 0;
}

int find_my_strongest_planet(struct game *game)
{
	int i;
	struct planet *p;
	int most_ships = 0;
	int best_planet_id = -1;

	FOR_MY_MILITARY_PLANETS(game, i) {
		p = get_planet(game, i);
		if (most_ships < p->num_ships) {
			most_ships = p->num_ships;
			best_planet_id = i;
		}
	}

	return best_planet_id;
}

int find_nearest_not_owned_planet(struct game *game, int from)
{
	int i, d;
	int nearest_planet_id = -1;
	int min_distance = INT_MAX;

	FOR_NOT_MY_PLANETS(game, i) {
		d = distance(game, from, i);
		if (d < min_distance) {
			min_distance = d;
			nearest_planet_id = i;
		}
	}
	return nearest_planet_id;
}

void do_turn(struct game *game)
{
	int strongest_planet;
	int target;
	struct planet *p;

	/* (1) If an economic planet has more than 50 ships, send 50
	 * ships to the closest military planet. */
	farm_planets(game);

	/* (2) If we currently have more than 2 fleet in flight, just
	 * do nothing. */
	if (has_enough_fleets(game))
		return;

	/* (3) Find my strongest military planet. */
	strongest_planet = find_my_strongest_planet(game);

	/* No military planet found, just stop here. */
	if (strongest_planet < 0)
		return;

	/* (4) Find the closest enemy or neutral planet. */
	target = find_nearest_not_owned_planet(game, strongest_planet);

	/* (5) Send all the ships from my strongest planet to the
	 * closest planet that I do not own. */
	p = get_planet(game, strongest_planet);
	issue_order(game, strongest_planet, target, p->num_ships);
}

int main(int argc, char **argv)
{
	UNUSED(argc);
	UNUSED(argv);

	struct game *game = init_game();

	read_options(game);

	for (;;) {
		printf("go\n");
		fflush(stdout);
		prepare_turn(game);
		if (read_turn(game))
			break;
		do_turn(game);
	}

	free_game(game);

	return 0;
}
