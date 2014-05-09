#include <stdlib.h>
#include <stdio.h>
#include <memory.h>
#include <assert.h>
#include <math.h>
#include <limits.h>

#include "game.h"

struct game *init_game()
{
	struct game *game = calloc(1, sizeof(struct game));
	if (!game) {
		fprintf(stderr, "Cannot allocate enough memory for a game\n");
		exit(1);
	}
	return game;
}

void prepare_turn(struct game *game)
{
	if (game->planets)
		memset(game->planets, 0,
			sizeof(struct planet) * game->allocated_planets);
	if (game->fleets)
		memset(game->fleets, 0, sizeof(struct planet) * game->allocated_fleets);

	game->num_planets = 0;
	game->num_fleets = 0;
}

void free_game(struct game *game)
{
	free(game->planets);
	free(game->fleets);
	free(game);
}

void add_planet(struct game *game, double x, double y, int owner,
	int num_ships, int economic_value)
{
	struct planet *p;
	int id;

	if (game->num_planets >= game->allocated_planets) {
		game->allocated_planets = game->allocated_planets * 2 + 10;
		game->planets = realloc(game->planets,
			game->allocated_planets * sizeof(struct planet));
		if (!game->planets) {
			fprintf(stderr, "Cannot allocate enough planets\n");
			exit(1);
		}
	}

	id = game->num_planets;
	++game->num_planets;

	p = get_planet(game, id);

	p->id = id;
	p->x = x;
	p->y = y;
	p->owner = owner;
	p->num_ships = num_ships;
	p->economic_value = economic_value;
}


void add_fleet(struct game *game, int owner, int num_ships,
	int source_planet, int dest_planet,
	int trip_length, int turns_remaining,
	int military)
{
	struct fleet *p;
	int id;

	if (game->num_fleets >= game->allocated_fleets) {
		game->allocated_fleets = game->allocated_fleets * 2 + 10;
		game->fleets = realloc(game->fleets,
			game->allocated_fleets * sizeof(struct fleet));
		if (!game->fleets) {
			fprintf(stderr, "Cannot allocate enough fleets\n");
			exit(1);
		}
	}
	id = game->num_fleets;
	++game->num_fleets;

	p = get_fleet(game, id);

	p->id = id;
	p->owner = owner;
	p->num_ships = num_ships;
	p->source_planet = source_planet;
	p->dest_planet = dest_planet;
	p->trip_length = trip_length;
	p->turns_remaining = turns_remaining;
	p->military = military;
}

struct planet *get_planet(struct game *game, int id)
{
	assert(id >= 0);
	assert(id < game->num_planets);

	return &game->planets[id];
}

struct fleet *get_fleet(struct game *game, int id)
{
	assert(id >= 0);
	assert(id < game->num_fleets);

	return &game->fleets[id];
}

int distance(struct game *game, int source_id, int destination_id)
{
	struct planet *source, *destination;
	double dx, dy;

	source = get_planet(game, source_id);
	destination = get_planet(game, destination_id);

	dx = source->x - destination->x;
	dy = source->y - destination->y;

	return (int)(dx*dx + dy*dy);
}



int is_my_fleet(struct game *game, int id)
{
	return get_fleet(game, id)->owner == 1;
}

int is_my_planet(struct game *game, int id)
{
	return get_planet(game, id)->owner == 1;
}

int is_military_planet(struct game *game, int id)
{
	return get_planet(game, id)->economic_value == 0;
}

int is_military_fleet(struct game *game, int id)
{
	return get_fleet(game, id)->military;
}

int is_neutral_planet(struct game *game, int id)
{
	return get_planet(game, id)->owner == 0;
}

int is_enemy_planet(struct game *game, int id)
{
	return get_planet(game, id)->owner > 1;
}

int find_my_closest_military_planet(struct game *game, int source)
{
	int i, d;
	int min_distance = INT_MAX, nearest_planet = -1;

	FOR_MY_MILITARY_PLANETS(game, i) {
		d = distance(game, i, source);
		if (min_distance > d) {
			min_distance = d;
			nearest_planet = i;
		}
	}
	return nearest_planet;
}

int find_closest_enemy_military_planet(struct game *game, int source)
{
	int i, d;
	int min_distance = INT_MAX, nearest_planet = -1;

	FOR_ENEMY_MILITARY_PLANETS(game, i) {
		d = distance(game, i, source);
		if (min_distance > d) {
			min_distance = d;
			nearest_planet = i;
		}
	}
	return nearest_planet;
}


void issue_order(struct game *game, int from, int to, int num)
{
	assert(is_my_planet(game, from));
	assert(get_planet(game, from)->num_ships >= num);

	printf("%d %d %d\n", from, to, num);
}
