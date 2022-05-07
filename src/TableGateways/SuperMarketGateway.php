<?php
namespace Src\TableGateways;

class SuperMarketGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function checkout($input) {
        $items = array();
        foreach ($input['items'] as $k => $item) {
            $items[$item['name']] = $item['quantity'];
        }
        $statement = "
            SELECT
                ip.id, ip.item_name, ip.unit_price, spoq.quantity, spoq.total_price, (spoq.total_price / spoq.quantity) AS price_per_unit, ip2.item_name AS item_name2, spbt.unit_price AS per_unit_price
            FROM item_price AS ip
                LEFT JOIN special_price_on_quantity AS spoq ON ip.id = spoq.item_id
                LEFT JOIN special_price_on_buying_together AS spbt ON ip.id = spbt.item_id
                LEFT JOIN item_price AS ip2 ON spbt.pre_bought_item_id = ip2.id
            ORDER BY id, price_per_unit
        ";
        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $row = 0;
            $prices = array();
            foreach ($result as $price) {
                if (!empty($price['quantity']) && !empty($price['total_price'])) {
                    $prices[$price['item_name']]['quantities'][] = $price['quantity'];
                    $prices[$price['item_name']]['prices'][] = $price['total_price'];
                } else if (!empty($price['item_name2']) && !empty($price['per_unit_price'])) {
                    $prices[$price['item_name']]['pre_bought_item'] = $price['item_name2'];
                    $prices[$price['item_name']]['pre_bought_item_price'] = $price['per_unit_price'];
                }
                if ((isset($result[$row+1]) && $price['item_name'] != $result[$row+1]['item_name']) || $row == count($result)) {
                    $prices[$price['item_name']]['quantities'][] = 1;
                    $prices[$price['item_name']]['prices'][] = $price['unit_price'];
                }
                $row++;
            }
            $totalPrice = 0;
            foreach ($items as $name => $quantity) {
                if (!empty($prices[$name]['pre_bought_item']) && array_key_exists($prices[$name]['pre_bought_item'], $items)) {
                    if ($quantity > $items[$prices[$name]['pre_bought_item']]) {
                        $totalPrice += ($prices[$name]['pre_bought_item_price'] * $items[$prices[$name]['pre_bought_item']]);
                        $quantity -= $items[$prices[$name]['pre_bought_item']];
                    } else {
                        $totalPrice += ($prices[$name]['pre_bought_item_price'] * $quantity);
                        continue;
                    }
                }
                $price = $prices[$name];
                foreach ($price['quantities'] as $k => $qty) {
                    if ($quantity >= $qty) {
                        $totalPrice += (((int) ($quantity / $qty)) * $price['prices'][$k]);
                        $quantity %= $qty;
                        if ($quantity == 0) break;
                    }
                }
            }
            return array('totalPrice' => $totalPrice);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}