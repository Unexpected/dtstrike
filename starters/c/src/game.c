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

void parseLine(char *p) {
	// TODO
	if (NULL != p && strlen(p) > 0) {
		char type = p[0];
		switch (type) {
		case 'M':

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
