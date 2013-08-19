:- use_module(library(readln)).
:- consult(tools).
:- consult(myBot).

/* global parameters */
store_info(I, I, ['loadtime', T]) :-
  assertz(load_time(T)).
store_info(I, I, ['turntime', T]) :-
  assertz(turn_time(T)).
store_info(I, I, ['turns', T]) :-
  assertz(turns(T)).

/* store military planet (value=0) */
store_info(Index, NewIndex, ['M', X, Y, Owner, Ships]) :-
  assertz(planet(Index, X, Y, Owner, Ships, 0)),
  NewIndex is Index + 1.

/* store economic planet */
store_info(Index, NewIndex, ['E', X, Y, Owner, Ships, Value]) :-
  assertz(planet(Index, X, Y, Owner, Ships, Value)),
  NewIndex is Index + 1.

/* store moving fleet */
store_info(I, I, ['F', Owner, Ships, Source, Target, Trip, Turns]) :-
  assertz(fleet(Owner, Ships, Source, Target, Trip, Turns)).

store_infos(_, [exit]):- throw('Normal end').
store_infos(_, [ready]).
store_infos(_, [go]).
store_infos(Index, H):-
  store_info(Index, NewIndex, H),
  read_lines(NewIndex).

read_lines(Index) :-
  readln(H),
  store_infos(Index, H).

write_lines([]).
write_lines([H|T]) :-
  write(H),nl, 
  write_lines(T).

clear_mem :-
	retractall(planet(_, _, _, _, _, _)),
	retractall(fleet(_, _, _, _, _, _)).

main :-
	repeat,
	clear_mem,
	write('Listening'),nl,
	read_lines(0),
	write('Thinking'),nl,
	doTurn(1, Order),
	write('Giving orders:'),nl,
	write_lines(Order),	write('go'),nl,
	fail.
