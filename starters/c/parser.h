#ifndef PARSING_H
#define PARSING_H

struct game;

/**
 * Read the options from the standard input.
 */
void read_options(struct game *game);

/**
 * Read the whole state of a turn until a go.
 *
 * Returns nonzero if there is no more turns to read.
 */
int read_turn(struct game *game);

#endif
