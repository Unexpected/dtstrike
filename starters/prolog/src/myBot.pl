/* do nothing if one of our fleet is in flight */
doTurn(PlayerId, []) :-
	catch(fleet(PlayerId,_,Index,_,_,_), _, fail),
	planet(Index,_,_,PlayerId,_,0).

/* Send half the ships from my strongest planet to the weakest planet that I do not own. */
doTurn(PlayerId, [Order]) :-
	findall(planet(P1,P2,P3,Id,P5,Value), (planet(P1,P2,P3,Id,P5,Value),Id=PlayerId,Value=0), MyMilitaryPlanets),
	max_ships(MyMilitaryPlanets, planet(From,_,_,_,Ships,_)),
	findall(planet(P1,P2,P3,Id,P5,P6), (planet(P1,P2,P3,Id,P5,P6), Id\=PlayerId), OtherPlanets),
	min_ships(OtherPlanets, planet(To,_,_,_,_,_)),
	ShipsSent is div(Ships, 2),
	Order = sendFleet(From, To, ShipsSent),!.
