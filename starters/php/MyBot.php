<?php

require_once 'Game.php';
define('PHP_INT_MIN', ~PHP_INT_MAX); 

class MyBot
{
	/**
	 * Method called at each turn
	 * 
	 * @param Game $game
	 */
	public function doTurn( $game )
	{
		// (0) Send reinforcement from eco to military
		$planets = $game->myEconomicPlanets();
		foreach ($planets as $ecoPlanet) {
			if ($ecoPlanet->numShips > 50) {
				$target = $game->findNearestMilitaryPlanet($ecoPlanet);
				if ($target != null) {
					$game->issueOrder($ecoPlanet->id, $target->id, 50);
				}
			}
		}
		
		// (1) If we currently have 2 fleets in flight, just do nothing.
		if (count($game->myMilitaryFleets()) >= 2) {
			return;
		}
		
		// (2) Find my strongest military planet.
		$source = null;
		$sourceShips = PHP_INT_MIN;
		$planets = $game->myMilitaryPlanets();
		foreach ($planets as $p) {
			$score = $p->numShips;
			if ($score > $sourceShips) {
				$sourceShips = $score;
				$source = $p;
			}
		}

		// (3) Find the nearest enemy or neutral planet.
		$dest = null;
		$destScore = PHP_INT_MAX;
		$planets = $game->notMyPlanets();
		foreach ($planets as $p) {
			$score = $game->distanceWithPlanets($source, $p);
			if ($score < $destScore) {
				$destScore = $score;
				$dest = $p;
			}
		}

		// (4) Send all the ships to the target planet.
		if ($source != null && $dest != null) {
			$game->issueOrder($source->id, $dest->id, $source->numShips);
		}
	}

	/**
	 * Method called at the init phase of the Game
	 * (ie before first turn)
	 * !! No orders could be given here !!
	 * 
	 * @param Game $game
	 */
	public function doReadyTurn( $game )
	{
		
	}
}

/**
 * Don't run bot when unit-testing
 */
if( !defined('PHPUnit_MAIN_METHOD') ) {
	game::run( new MyBot() );
}