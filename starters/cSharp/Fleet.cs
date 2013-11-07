using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace DTStrike.MyBot
{
    public class Fleet
    {
        public int owner;
        public int numShips;
        public int sourcePlanet;
        public int destinationPlanet;
        public int totalTripLength;
        public int turnsRemaining;
        public bool militaryFleet;

        public Fleet(int owner, int numEngineers, int sourceDept, int destDept,
                int tripLength, int turnsRemaining, bool militaryFleet)
        {
            this.owner = owner;
            this.numShips = numEngineers;
            this.sourcePlanet = sourceDept;
            this.destinationPlanet = destDept;
            this.totalTripLength = tripLength;
            this.turnsRemaining = turnsRemaining;
            this.militaryFleet = militaryFleet;
        }

        public void destroy()
        {
            owner = 0;
            numShips = 0;
            turnsRemaining = 0;
        }

        public void doTimeStep()
        {
            turnsRemaining -= 1;
            if (turnsRemaining < 0)
            {
                turnsRemaining = 0;
            }
        }
    }
}
