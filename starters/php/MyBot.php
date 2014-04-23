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
					$numShips = (int) ($ecoPlanet->numShips / 2);
					$game->issueOrder($ecoPlanet->id, $target->id, $numShips);
				}
			}
		}
		
		// (1) If we currently have a fleet in flight, just do nothing.
		if (count($game->myMilitaryFleets()) >= 1) {
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

		// (3) Find the weakest enemy or neutral planet.
		$dest = null;
		$destScore = PHP_INT_MAX;
		$planets = $game->notMyPlanets();
		foreach ($planets as $p) {
			$score = $p->numShips;
			if ($score < $destScore) {
				$destScore = $score;
				$dest = $p;
			}
		}

		// (4) Send half the ships from my strongest planet to the weakest
		// planet that I do not own.
		if ($source != null && $dest != null) {
			$numShips = (int) ($source->numShips / 2);
			$game->issueOrder($source->id, $dest->id, $numShips);
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