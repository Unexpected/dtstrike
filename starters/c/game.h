#ifndef GAME_H
#define GAME_H


/**
 * A planet.
 *
 * If economic_value is 0, this is a military planet,
 * otherwise an ecomonic planet.
 */
struct planet
{
	int id;
	double x;
	double y;
	int owner;
	int num_ships;
	int economic_value;
};

/**
 * A fleet currently in a trip.
 */
struct fleet
{
	int id;
	int owner;
	int num_ships;
	int source_planet;
	int dest_planet;
	int trip_length;
	int turns_remaining;
	int military;
};

/**
 * Contains the various structures of the game.
 */
struct game
{
	/*
	 * Various options
	 */
	int loadtime;
	int turntime;
	int turns;

	struct planet *planets;
	struct fleet *fleets;

	int allocated_planets;
	int allocated_fleets;

	int num_planets;
	int num_fleets;
};


/**
 * Initializes the game structure.
 */
struct game *init_game();

/**
 * Releases all memory associated to the game.
 */
void free_game(struct game *game);

/**
 * Prepares the game for a new turn.
 */
void prepare_turn(struct game *game);

/**
 * Adds a planet to the game.
 */
void add_planet(struct game *game, double x, double y, int owner,
	int num_ships, int economic_value);

/**
 * Adds a fleet to the game.
 */
void add_fleet(struct game *game, int owner, int num_ships,
	int source_planet, int dest_planet,
	int trip_length, int turns_remaining,
	int military);

struct planet *get_planet(struct game *game, int planet_id);
struct fleet *get_fleet(struct game *game, int fleet_id);

/**
 * Returns non null if the specified planet is an economic planet.
 */
int is_economic_planet(int planet_id);

/**
 * Find the distance between two planets.
 */
int distance(struct game *game, int source, int destination);

/**
 * Returns nonzero if the fleet is owned by me.
 */
int is_my_fleet(struct game *game, int id);

/**
 * Returns nonzero if the planet is owned by me.
 */
int is_my_planet(struct game *game, int id);

/**
 * Returns nonzero if this is a military planet.
 */
int is_military_planet(struct game *game, int id);

/**
 * Returns nonzero if this is a military fleet.
 */
int is_military_fleet(struct game *game, int id);

/**
 * Returns nonzero if this is a neutral planet.
 */
int is_neutral_planet(struct game *game, int id);

/**
 * Returns nonzero if this is an enemy planet.
 */
int is_enemy_planet(struct game *game, int id);

/**
 * Return my closest military planet to another planet.
 * -1 if none match.
 */
int find_my_closest_military_planet(struct game *game, int source);

/**
 * Return the closest enemy planet to another planet.
 * -1 if none match.
 */
int find_closest_enemy_military_planet(struct game *game, int source);


#define FOR_ALL_PLANETS(game, i)			     \
	for(i = 0; i < game->num_planets; ++i)

#define FOR_MY_PLANETS(game, i)			\
	FOR_ALL_PLANETS(game, i)		\
		if (is_my_planet(game, i))

#define FOR_NOT_MY_PLANETS(game, i)		\
	FOR_ALL_PLANETS(game, i)		\
		if (!is_my_planet(game, i))

#define FOR_ENEMY_PLANETS(game, i)		\
	FOR_ALL_PLANETS(game, i)		\
		if (is_enemy_planet(game, i))

#define FOR_MY_MILITARY_PLANETS(game, i)		\
	FOR_MY_PLANETS(game, i)				\
		if (is_military_planet(game, i))

#define FOR_MY_ECONOMIC_PLANETS(game, i)		\
	FOR_MY_PLANETS(game, i)				\
		if (!is_military_planet(game, i))


#define FOR_ENEMY_MILITARY_PLANETS(game, i)		\
	FOR_ENEMY_PLANETS(game, i)			\
		if (is_military_planet(game, i))

#define FOR_ENEMY_ECONOMIC_PLANETS(game, i)		\
	FOR_ENEMY_PLANETS(game, i)			\
		if (!is_military_planet(game, i))


#define FOR_ALL_FLEETS(game, i)			\
	for (i = 0; i < game->num_fleets; ++i)

#define FOR_MY_FLEETS(game, i)			\
	FOR_ALL_FLEETS(game, i)			\
		if (is_my_fleet(game, i))

#define FOR_MY_MILITARY_FLEETS(game, i)			\
	FOR_MY_FLEETS(game, i)				\
		if (is_military_fleet(game, i))

#define FOR_MY_ECONOMIC_FLEETS(game, i)			\
	FOR_MY_FLEETS(game, i)					\
		if (!is_military_fleet(game, i))

/**
 * Send some ships from one planet to another.
 */
void issue_order(struct game *game, int from, int to, int ships);


#endif
