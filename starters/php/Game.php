<?php

define('MY_ID', 1);
define('NEUTRAL_ID', 0);
define('MILITARY_TYPE', 'M');
define('ECONOMIC_TYPE', 'E');
define('PLANET_MILITARY', 'M');
define('PLANET_ECONOMIC', 'E');
define('FLEET_MILITARY', 'F');
define('FLEET_ECONOMIC', 'R');
define('UNSEEN', -5);

class Game
{
	/** 
	 * @var int
	 */
    public $turns = 0;
	/** 
	 * @var int
	 */
    public $loadtime = 0;
	/** 
	 * @var int
	 */
    public $turntime = 0;
    /**
     * 
     * @var Planet[]
     */
    public $myPlanets = array();
    /**
     * 
     * @var Planet[]
     */
    public $enemyPlanets = array();
    /**
     * 
     * @var Planet[]
     */
    public $neutralPlanets = array();
    /**
     * 
     * @var Fleet[]
     */
    public $myFleets = array();
    /**
     * 
     * @var Fleet[]
     */
    public $enemyFleets = array();

    
    /*
     * Functions
     */

	/**
	 * @return Planet[]
	 */
    public function myPlanets() {
        return $this->myPlanets;
    }
	/**
	 * @return Planet[]
	 */
    public function myMilitaryPlanets() {
        return $this->getEntryFromType($this->myPlanets, MILITARY_TYPE);
    }
	/**
	 * @return Planet[]
	 */
    public function myEconomicPlanets() {
        return $this->getEntryFromType($this->myPlanets, ECONOMIC_TYPE);
    }
	/**
	 * @return Planet[]
	 */
    public function enemyPlanets() {
        return $this->enemyPlanets;
    }
	/**
	 * @return Planet[]
	 */
    public function enemyMilitaryPlanets() {
        return $this->getEntryFromType($this->enemyPlanets, MILITARY_TYPE);
    }
	/**
	 * @return Planet[]
	 */
    public function enemyEconomicPlanets() {
        return $this->getEntryFromType($this->enemyPlanets, ECONOMIC_TYPE);
    }
	/**
	 * @return Planet[]
	 */
    public function enemyPlanetsByPlayerId	($player_id) {
		$result = array();
		foreach ($this->enemyPlanets as $p) {
			if ($p->owner == $playerID) {
				$result []= $p;
			}
		}
        return $result;
    }
	/**
	 * @return Planet[]
	 */
    public function neutralPlanets() {
        return $this->neutralPlanets;
    }
	/**
	 * NOT my planets = neutral + ennemy
	 * @return Planet[]
	 */
    public function notMyPlanets() {
        return array_merge($this->enemyPlanets, $this->neutralPlanets);
    }
	
	/**
	 * @return Fleet[]
	 */
    public function myFleets() {
        return $this->myFleets;
    }
	/**
	 * @return Fleet[]
	 */
    public function myMilitaryFleets() {
        return $this->getEntryFromType($this->myFleets, MILITARY_TYPE);
    }
	/**
	 * @return Fleet[]
	 */
    public function enemyFleets() {
        return $this->enemyFleets;
    }
	/**
	 * @return Fleet[]
	 */
    public function enemyMilitaryFleets() {
        return $this->getEntryFromType($this->enemyFleets, MILITARY_TYPE);
    }
	
	/**
	 * Get entries from the given array that have the given type.
	 * 
	 * @return array
	 */
	private function getEntryFromType($array, $type) {
		$result = array();
		foreach ($array as $e) {
			if ($e->type == $type) {
				$result []= $e;
			}
		}
        return $result;
	}
	
	/**
	 * Get the distance from one planet to another.
	 * 
	 * @param Planet $srcPlanet
	 * @param Planet $dstPlanet
	 * @return int
	 */
    public function distanceWithPlanets($srcPlanet, $dstPlanet) {
		return $this->distance($srcPlanet->x, $srcPlanet->y, $dstPlanet->x, $dstPlanet->y);
	}

	/**
	 * Get the distance from one point to another.
	 * 
	 * @param float $row1 X from start point
	 * @param float $col1 Y from start point
	 * @param float $row2 X from dest point
	 * @param float $col2 Y from dest point
	 * @return int
	 */
    public function distance($row1, $col1, $row2, $col2) {
        $dRow = abs($row1 - $row2);
        $dCol = abs($col1 - $col2);
        
        return ceil(sqrt($dRow * $dRow + $dCol * $dCol));
    }
    
    /**
     * 
     * @param Planet $ecoPlanet
     * @return Planet
     */
    public function findNearestMilitaryPlanet($ecoPlanet) {
    	$min_dist = PHP_INT_MAX;
    	$target = null;
		$planets = $this->myMilitaryPlanets();
		foreach ($planets as $p) {
			$dist = $this->distanceWithPlanets($ecoPlanet, $p);
			if ($dist < $min_dist) {
				$min_dist = $dist;
				$target = $p;
			}
		}
		return $target;
    }

	/**
	 *  Do NOT touch the following methods
	 */

    /**
     * Issue an order
     * 
     * @param int $src Source planet ID
     * @param int $dest Destination planet ID
     * @param int $numShip Number of ships to send
     */
    public function issueOrder($src, $dest, $numShip) {
        printf("%s %s %s\n", $src, $dest, $numShip);
        flush();
    }

    public function finishTurn() {
        echo("go\n");
        flush();
    }
    
    public function setup($data) {
        foreach ( $data as $line) {
            if (strlen($line) > 0) {
                $tokens = explode(' ',$line);
                $key = $tokens[0];
                if (property_exists($this, $key)) {
                    $this->{$key} = (int)$tokens[1];
                }
            }
        }
    }

    public function update($data) {
		// Reset
		$this->myPlanets = array();
		$this->enemyPlanets = array();
		$this->neutralPlanets = array();
		$this->myFleets = array();
		$this->enemyFleets = array();
		
        # update map and create new ant and food lists
		$planet_id = 0;
        foreach ( $data as $line) {
            if (strlen($line) > 0) {
                $tokens = explode(' ',$line);

                if (count($tokens) > 1) {
					
                    if ($tokens[0] == PLANET_MILITARY) {
                        $owner = (int)$tokens[3];
						$planet = new Planet(MILITARY_TYPE, $planet_id, (float)$tokens[1], (float)$tokens[2], $owner, (int)$tokens[4]);
						
                        if ($owner === MY_ID) {
                            $this->myPlanets []= $planet;
                        } else if ($owner === NEUTRAL_ID) {
                            $this->neutralPlanets []= $planet;
                        } else {
                            $this->enemyPlanets []= $planet;
                        }
						$planet_id += 1;
                    } elseif ($tokens[0] == PLANET_ECONOMIC) {
                        $owner = (int)$tokens[3];
						$planet = new Planet(ECONOMIC_TYPE, $planet_id, (float)$tokens[1], (float)$tokens[2], $owner, (int)$tokens[4]);
						$planet->revenue = (int)$tokens[5];
						
                        if ($owner === MY_ID) {
                            $this->myPlanets []= $planet;
                        } else if ($owner === NEUTRAL_ID) {
                            $this->neutralPlanets []= $planet;
                        } else {
                            $this->enemyPlanets []= $planet;
                        }
						$planet_id += 1;
                    } elseif ($tokens[0] == FLEET_MILITARY) {
                        $owner = (int)$tokens[1];
						$fleet = new Fleet(MILITARY_TYPE, $owner, (int)$tokens[2], (int)$tokens[3], (int)$tokens[4], (int)$tokens[5], (int)$tokens[6]);
						
                        if ($owner === MY_ID) {
                            $this->myFleets []= $fleet;
                        } else {
                            $this->enemyFleets []= $fleet;
                        }
                    } elseif ($tokens[0] == FLEET_ECONOMIC) {
                        $owner = (int)$tokens[1];
						$fleet = new Fleet(ECONOMIC_TYPE, $owner, (int)$tokens[2], (int)$tokens[3], (int)$tokens[4], (int)$tokens[5], (int)$tokens[6]);
						
                        if ($owner === MY_ID) {
                            $this->myFleets []= $fleet;
                        } else {
                            $this->enemyFleets []= $fleet;
                        }
                    }
                }
            }
        }
    }
	
    public static function run($bot) {
        $game = new Game();
        $data = array();
        while(true) {
            $current_line = fgets(STDIN,1024);
            $current_line = trim($current_line);
            if ($current_line === 'ready') {
                $game->setup($data);
                $bot->doReadyTurn($game);
                $game->finishTurn();
                $data = array();
            } elseif ($current_line === 'go') {
                $game->update($data);
                $bot->doTurn($game);
                $game->finishTurn();
                $data = array();
            } else {
                $data []= $current_line;
            }
        }

    }
}

class Planet
{
	/**
	 * @var string
	 */
	public $type;
	/**
	 * @var int
	 */
	public $id;
	/**
	 * @var float
	 */
	public $x;
	/**
	 * @var float
	 */
	public $y;
	/**
	 * @var int
	 */
	public $owner;
	/**
	 * @var int
	 */
	public $numShips;
	/**
	 * @var int may be null
	 */
	public $revenue;
	
	function __construct($type, $id, $x, $y, $owner, $numShips) {
		$this->type = $type;
		$this->id = $id;
		$this->x = $x;
		$this->y = $y;
		$this->owner = $owner;
		$this->numShips = $numShips;
	}
}

class Fleet
{
	/**
	 * @var string
	 */
	public $type;
	/**
	 * @var int
	 */
	public $owner;
	/**
	 * @var int
	 */
	public $numShips;
	/**
	 * @var int
	 */
	public $sourcePlanet;
	/**
	 * @var int
	 */
	public $destinationPlanet;
	/**
	 * @var int
	 */
	public $totalTripLength;
	/**
	 * @var int
	 */
	public $turnsRemaining;
	
	function __construct($type, $owner, $numShips, $sourcePlanet, $destinationPlanet, $totalTripLength, $turnsRemaining) {
		$this->type = $type;
		$this->owner = $owner;
		$this->numShips = $numShips;
		$this->sourcePlanet = $sourcePlanet;
		$this->destinationPlanet = $destinationPlanet;
		$this->totalTripLength = $totalTripLength;
		$this->turnsRemaining = $turnsRemaining;
	}
}
