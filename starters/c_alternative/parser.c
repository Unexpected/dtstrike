#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <assert.h>

#include "game.h"

#define COMMENT '#'

int read_option_name(char **option)
{
	int first = getchar();
	int num_match;

	if (first != '*') {
		ungetc(first, stdin);
		return 1;
	}

	num_match = scanf("%m[^:]:", option);
	assert(num_match == 1);

	return 0;
}

void read_options(struct game *game)
{
	char *option, *value;
	int num_match;
	while (!read_option_name(&option)) {
		if (!strcmp(option, "loadtime")) {
			num_match = scanf("%d", &game->loadtime);
		} else if (!strcmp(option, "turntime")) {
			num_match = scanf("%d", &game->turntime);
		} else if (!strcmp(option, "turns")) {
			num_match = scanf("%d", &game->turns);
		} else {
			num_match = scanf("%m[^\n]", &value);
			fprintf(stderr, "Unknown option [%s]=[%s]\n",
				option, value);
			free(value);
		}
		assert(num_match == 1);

		free(option);

		num_match = scanf("\n");
		assert(num_match >= 0);
	}

	if (scanf("ready") < 0)
		fprintf(stderr, "Expected to have a ready line but didin't receive it.\n");
}

void read_planet(struct game *game, int military)
{
	double x, y;
	int owner, num_ships, economic_value;
	int num_match;
	num_match = scanf("%lf %lf %d %d", &x, &y, &owner, &num_ships);

	assert(num_match == 4);

	if (military) {
		economic_value = 0;
	} else {
		num_match = scanf("%d", &economic_value);
		assert(num_match == 1);
	}

	add_planet(game, x, y, owner, num_ships, economic_value);
}

void read_fleet(struct game *game, int military)
{
	int owner, num_ships, source_planet, dest_planet;
	int trip_length, turns_remaining;

	int num_match;

	num_match = scanf("%d %d %d %d %d %d", &owner, &num_ships,
		&source_planet, &dest_planet,
		&trip_length, &turns_remaining);

	assert(num_match == 6);

	add_fleet(game, owner, num_ships, source_planet, dest_planet,
		trip_length, turns_remaining, military);
}

int read_state(struct game *game)
{
	int type = getchar();
	int num_match;
	switch(type)
	{
	case EOF:
		return 2;
	case 'e':
		ungetc(type, stdin);
		if (scanf("end") < 0)
			fprintf(stderr, "Expected end but didn't receive it.\n");
		return 2;
	case 'g':
		ungetc(type, stdin);
		if (scanf("go") < 0)
			fprintf(stderr, "Expected go but didn't receive it.\n");
		return 1;
	case '#':
	case '\n':
		/* Ignore comment and empty lines */
		ungetc(type, stdin);
		break;
	case 'M':
	case 'E':
		read_planet(game, type == 'M');
		break;
	case 'F':
	case 'R':
		read_fleet(game, type == 'F');
		break;
	default:
		fprintf(stderr, "Unknown line type: %c\n", type);
	}

	type = getchar();
	if (type != '\n') {
		/* discard remaining chars until end of line */
		ungetc(type, stdin);
		num_match = scanf("%*[^\n]\n");
		assert(num_match >= 0);
	}
	return 0;
}

int read_turn(struct game *game)
{
	int ret;
	do
	{
		ret = read_state(game);
	} while (ret == 0);

	return ret == 2;
}
