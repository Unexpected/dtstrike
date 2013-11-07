using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace DTStrike.MyBot
{
    public class MilitaryPlanet : Planet
    {

        public MilitaryPlanet(int id, int owner, int numShips, double x, double y)
            : base(id, owner, numShips, x, y)
        {
            
        }

    }
}
