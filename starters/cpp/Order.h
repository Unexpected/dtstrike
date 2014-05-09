#ifndef ORDER_H
#define ORDER_H

#include <ostream>
#include <memory>

class Order: public std::enable_shared_from_this<Order>
{
public:
	/**
	 * Creates a new Order object.
	 *
	 * @param sourcePlanet the source planet ID
	 * @param destPlanet the destination planet ID
	 * @param numShips the number of ships to send
	 */
	Order(int sourcePlanet, int destPlanet, int numShips);

	/**
	 * Prints an order to the specified stream.
	 */
	void print(std::ostream& out) const;

private:
	int sourcePlanet;
	int destPlanet;
	int numShips;
};

/**
 * Prints an order to the specified stream.
 */
inline std::ostream& operator<<(std::ostream& out, const Order& order)
{
	order.print(out);
	return out;
}

using OrderPtr = std::shared_ptr<Order>;


#endif
