hasMore_ships(planet(_,_,_,_,N1,_), planet(_,_,_,_,N2,_)):-
	N1 > N2.
hasMoreOrEquals_ships(planet(_, _, _, _, N1, _), planet(_, _, _, _, N2, _)):-
	N1 >= N2.

maxShips1([],X,X).
maxShips1([X|Xs], CurrMax, Max):-
	hasMore_ships(X, CurrMax), 
	maxShips1(Xs, X, Max).
maxShips1([X|Xs], CurrMax, Max):-
	hasMoreOrEquals_ships(CurrMax, X), 
	maxShips1(Xs, CurrMax, Max).

max_ships([X|Xs], Max) :- maxShips1(Xs, X, Max).

minShips1([],X,X).
minShips1([X|Xs], CurrMin, Min):-
	hasMore_ships(CurrMin, X), 
	minShips1(Xs, X, Min).
minShips1([X|Xs], CurrMin, Min):-
	hasMoreOrEquals_ships(X, CurrMin), 
	minShips1(Xs, CurrMin, Min).

min_ships([X|Xs], Min) :- minShips1(Xs, X, Min).
  
isMilitary(planet(_,_,_,_,_,0)).
