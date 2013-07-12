/*
 * game.c
 *
 *  Created on: 8 juil. 2013
 *      Author: louis
 */
#include "game.h"
#include <stdio.h>
#include <math.h>
#include <string.h>
#include <ctype.h>
#include <stdlib.h>

#ifndef DEFAULT_ARRAY_SIZE
#define DEFAULT_ARRAY_SIZE 10
#endif

static struct military_planets _my_military_planets;
static struct military_planets _enemy_military_planets;
static struct military_planets _neutral_military_planets;

static struct economic_planets _my_economic_planets;
static struct economic_planets _enemy_economic_planets;
static struct economic_planets _neutral_economic_planets;

static struct fleets _my_fleets;
static struct fleets _enemy_fleets;
static struct fleets _neutral_fleets;

static int _counter_planets;
static int _counter_fleets;

static struct military_planet convertStrToMilitary(const char * x, const char * y, const char * owner, const char * shipsNumber);

struct military_planets my_military_planets() {
	return _my_military_planets;
}

struct military_planets enemy_military_planets() {
	return _enemy_military_planets;
}

struct military_planets neutral_military_planets() {
	return _neutral_military_planets;
}

struct economic_planets my_economic_planets() {
	return _my_economic_planets;
}
struct economic_planets enemy_economic_planets() {
	return _enemy_economic_planets;
}
struct economic_planets neutral_economic_planets() {
	return _neutral_economic_planets;
}

struct fleets my_fleets() {
	return _my_fleets;
}

struct fleets enemy_fleets() {
	return _enemy_fleets;
}

struct fleets neutral_fleets() {
	return _neutral_fleets;
}

int distance(struct coordinates src, struct coordinates dest) {
	double dx = src.x - dest.x;
	double dy = src.y - dest.y;

	return (int) ceil(sqrt((dx * dx) + (dy * dy)));
}

void issueOrder(int src, int dest, int numShips) {
	printf("%d %d %d\n", src, dest, numShips);
}

void finishTurn() {
	printf("go\n");
}

void initTurn() {
	_counter_fleets = 0;
	_counter_planets = 0;

	_my_military_planets.size = 0;
	_enemy_military_planets.size = 0;
	_neutral_military_planets.size = 0;

	_my_economic_planets.size = 0;
	_enemy_economic_planets.size = 0;
	_neutral_economic_planets.size = 0;
}

void freeData() {
	if (_my_military_planets.size > 0 && _my_military_planets.list != NULL)
		free(_my_military_planets.list);
	if (_enemy_military_planets.size > 0 && _enemy_military_planets.list != NULL)
		free(_enemy_military_planets.list);
	if (_neutral_military_planets.size > 0 && _neutral_military_planets.list != NULL)
		free(_neutral_military_planets.list);

	if (_my_economic_planets.size > 0 && _my_economic_planets.list != NULL)
		free(_my_economic_planets.list);
	if (_enemy_economic_planets.size > 0 && _enemy_economic_planets.list != NULL)
		free(_enemy_economic_planets.list);
	if (_neutral_economic_planets.size > 0 && _neutral_economic_planets.list != NULL)
		free(_neutral_economic_planets.list);

	initTurn();

}

void parseLine(char *p) {
	// TODO

//	Un exemple de communication du moteur vers le bot :
//	E 11.014548 9.362856 1 363 2
//	M 5.518446 18.725713 1 505
//	M 16.510650 0.000000 2 158
//	E 18.571299 14.374196 1 190 2
//	E 3.457797 4.351517 1 248 2
//	E 10.563623 5.157520 2 39 2
//	F 1 2 11 20 15 1
//	F 2 2 16 19 3 2
//	F 1 2 17 1 6 5
//	go 2
//
//	Ligne de planète économique :
//	E pour le Type de planète économique, 2 coordonnées X et Y sous forme de float, ID du propriétaire, Nb de vaisseaux actuellement sur la planète, Income de la planète.
//
//
//	Ligne de flotte en cours de voyage :
//	F pour flotte, propriétaire, nombre de vaisseaux, ID planète source, ID planète destination, longueur totale du voyage (en nb de tours), nb de tours restants avant arrivée
//
//	A chaque tour, on reçoit l’intégralité de la description du monde. La dernière ligne reçue est « go ID_DE_JOUEUR »
//	Les séparateurs de ligne sont des \n

	if (NULL != p && strlen(p) > 0) {
		char ** split = str_split(p, " ");
		char type = split[0][0];

		switch (type) {
		case 'M':
			//	Ligne de planète militaire :
			//	M pour le type, coordonnées X et Y, ID du propriétaire, Nb de vaisseaux actuellement sur la planète

			// verif taille du tableau
			if (0 == _my_military_planets._array_size) {
				_my_military_planets.list = malloc(sizeof(struct military_planet) * DEFAULT_ARRAY_SIZE);
				_my_military_planets._array_size = DEFAULT_ARRAY_SIZE;
			} else if (_my_military_planets._array_size == _my_military_planets.size) {
				_my_military_planets._array_size += DEFAULT_ARRAY_SIZE;
				_my_military_planets.list = realloc(_my_military_planets.list, sizeof(struct military_planet) * _my_military_planets._array_size);
			}

			_my_military_planets.list[_my_military_planets.size++] = convertStrToMilitary(split[1], split[2], split[3], split[4]);

			break;
		case 'E':

			break;
		case 'F':

			break;
		default:
			break;
		}
	}
}
char **str_split(char *src, const char *ct) {
	char **tab = NULL;

	if (src != NULL && ct != NULL) {
		int i;
		char *cs = NULL;
		size_t size = 1;
		char *s = strdup(src);

		for (i = 0; (cs = strtok(s, ct)); i++) {
			if (size <= i + 1) {
				void *tmp = NULL;

				size <<= 1;
				tmp = realloc(tab, sizeof(*tab) * size);
				if (tmp != NULL) {
					tab = tmp;
				} else {
					fprintf(stderr, "Memoire insuffisante\n");
					free(tab);
					tab = NULL;
					exit(EXIT_FAILURE);
				}
			}
			tab[i] = cs;
			s = NULL;
		}
		tab[i] = NULL;
	}
	return tab;
}

/** Ligne de planète militaire :
 * coordonnées X et Y, ID du propriétaire, Nb de vaisseaux actuellement sur la planète
 **/
static struct military_planet convertStrToMilitary(const char * x, const char * y, const char * owner, const char * shipsNumber) {
	struct military_planet mp;
	struct coordinates xy;
	mp.id = ++_counter_fleets;
	xy.x = atof(x);
	xy.y = atof(y);
	mp.coordinates = xy;
	mp.owner = atoi(owner);
	mp.shipsNumber = atoi(shipsNumber);

	return mp;

}

