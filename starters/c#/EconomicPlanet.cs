using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace DTStrike.MyBot
{
    public class EconomicPlanet : Planet
    {
        public int revenue;

        public EconomicPlanet(int id, int owner, int numShips, int revenue,
                double x, double y) : base(id, owner, numShips, x, y)
        {
            
            this.revenue = revenue;
        }
    }
}
