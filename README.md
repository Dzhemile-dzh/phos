
# Shipment Assignment Algorithm

This repository contains an algorithm for assigning shipments to vendors based on product availability, shipping destinations, and costs.

## Problem Description

Suppose there are two products, X and Y, that need to be shipped to a customer located in the US state of Virginia. There are five vendors available, each with a different cost and product availability, and some may not be able to ship to the destination country. The task is to select the cheapest combination of vendors to ship both products while ensuring that they are in stock and can ship to the destination.

The algorithm follows the following steps:

1.  Filter out vendors that don't have the products in stock.
2.  Filter out vendors that cannot ship to the shipping country.
3.  Sort the remaining vendors by the cost of each product.
4.  Select the cheapest vendor that has the product in stock and can ship to the shipping country for each product.
5.  If there's only one vendor that can supply both products, assign the shipment to that vendor and exit.
6.  Otherwise, iterate through all possible combinations of vendors that can supply each product.
7.  For each combination, calculate the total cost.
8.  Select the combination with the lowest total cost and assign the shipment to those vendors.

## Solution
**For product X:**

-   Vendor D, E cannot ship to US - Virginia, so they are eliminated.
    
-   Vendors B and E do not have product X in stock, so they are eliminated.
    
-   Vendors A and C can ship to US - Virginia and have product X in stock.
    
-   Since vendor C has a lower cost for product X than vendor A, vendor C will be selected to ship product X.
    
   **For product Y:**
    
-   Vendor D, E cannot ship to US - Virginia, so they are eliminated.
    
-   Vendors B and C do not have product Y in stock, so they are eliminated.
    
-   Vendor A ship to Virginia and have product Y in stock.
    
-   Vendor A will be selected to ship product Y.
    
 **Shiping cost will be $112 + $15 + $62.20 + $15 = $204.2**
 ## CODE
  

      
    
    $order = [
    
    'shipping_country'  =>  'US - Virginia',
    
    'products'  => [
    
    ['name'  =>  'Product X', 'quantity'  =>  1],
    
    ['name'  =>  'Product Y', 'quantity'  =>  1]
    
    ]
    
    ];
    
      
    
    $vendors = [
    
    'A'  => [
    
    'Product X'  => ['cost'  =>  150.00, 'stock'  =>  true, 'shipping_cost'  => ['1'  =>  15.00, 'additional'  =>  3.00]],
    
    'Product Y'  => ['cost'  =>  67.20, 'stock'  =>  true, 'shipping_cost'  => ['1'  =>  15.00, 'additional'  =>  3.00]],
    
    ],
    
    'B'  => [
    
    'Product X'  => ['cost'  =>  182.00, 'stock'  =>  true, 'shipping_cost'  => ['1'  =>  20.00, 'additional'  =>  3.00]],
    
    'Product Y'  => ['cost'  =>  'NO_STOCK', 'stock'  =>  false, 'shipping_cost'  =>  null],
    
    ],
    
    'C'  => [
    
    'Product X'  => ['cost'  =>  112.00, 'stock'  =>  true, 'shipping_cost'  => ['1'  =>  15.00, 'additional'  =>  3.00]],
    
    'Product Y'  => ['cost'  =>  'NO_STOCK', 'stock'  =>  false, 'shipping_cost'  =>  null],
    
    ],
    
    'D'  => [
    
    'Product X'  => ['cost'  =>  110.50, 'stock'  =>  true, 'shipping_cost'  =>  null],
    
    'Product Y'  => ['cost'  =>  62.99, 'stock'  =>  true, 'shipping_cost'  =>  null],
    
    ],
    
    'E'  => [
    
    'Product X'  => ['cost'  =>  'NO_STOCK', 'stock'  =>  false, 'shipping_cost'  =>  null],
    
    'Product Y'  => ['cost'  =>  65.00, 'stock'  =>  true, 'shipping_cost'  =>  null],
    
    ],
    
    ];
    
      
    
    foreach ($order['products'] as  &$product) {
    
    $vendors = array_filter($vendors, function ($vendor) use ($product) {
    
    return  $vendor[$product['name']]['stock'];
    
    });
    
    if (empty($vendors)) {
    
    echo  "No vendors have {$product['name']} in stock.";
    
    exit;
    
    }
    
    }
    
      
    
    $vendors = array_filter($vendors, function ($vendor) use ($order) {
    
    foreach ($order['products'] as  $product) {
    
    if (!isset($vendor[$product['name']]['shipping_cost'][$product['quantity'] >  1  ?  'additional'  :  '1'])) {
    
    return  false;
    
    }
    
    }
    
    return  true;
    
    });
    
      
    
    foreach ($order['products'] as  &$product) {
    
    usort($vendors, function ($a, $b) use ($product) {
    
    return  $a[$product['name']]['cost'] -  $b[$product['name']]['cost'];
    
    });
    
    }
    
      
    
    $shipment_vendors = [];
    
    foreach ($order['products'] as  $product) {
    
    foreach ($vendors  as  $vendor  =>  $details) {
    
    if ($details[$product['name']]['stock'] &&  isset($details[$product['name']]['shipping_cost'][$product['quantity'] >  1  ?  'additional'  :  '1'])) {
    
    $shipment_vendors[$product['name']] = $vendor;
    
    break;
    
    }
    
    }
    
    $product['vendor'] = $shipment_vendors[$product['name']] ??  null;
    
    }
    
      
    
    if (count($shipment_vendors) ==  1) {
    
    $shipment_vendor = reset($shipment_vendors);
    
    echo  "Shipment assigned to Vendor $shipment_vendor";
    
    } else {
    
    $shipment_combination = null;
    
    $shipment_cost = null;
    
    foreach ($vendors  as  $vendor  =>  $details) {
    
    if (isset($shipment_vendors['Product X']) &&  $shipment_vendors['Product X'] ==  $vendor  &&  isset($shipment_vendors['Product Y']) &&  $shipment_vendors['Product Y'] ==  $vendor) {
    
    $shipment_combination = [$vendor];
    
    $shipment_cost = $details['Product X']['cost'] +  $details['Product Y']['cost'];
    
    break;
    
    } elseif (isset($shipment_vendors['Product X']) &&  $shipment_vendors['Product X'] ==  $vendor) {
    
    foreach ($vendors  as  $other_vendor  =>  $other_details) {
    
    if (isset($shipment_vendors['Product Y']) &&  $shipment_vendors['Product Y'] ==  $other_vendor) {
    
    $combination = [$vendor, $other_vendor];
    
    $cost = $details['Product X']['cost'] +  $other_details['Product Y']['cost'];
    
    if ($shipment_cost  ===  null  ||  $cost  <  $shipment_cost) {
    
    $shipment_combination = $combination;
    
    $shipment_cost = $cost;
    
    }
    
    break;
    
    }
    
    }
    
    } elseif (isset($shipment_vendors['Product Y']) &&  $shipment_vendors['Product Y'] ==  $vendor) {
    
    foreach ($vendors  as  $other_vendor  =>  $other_details) {
    
    if (isset($shipment_vendors['Product X']) &&  $shipment_vendors['Product X'] ==  $other_vendor) {
    
    $combination = [$vendor, $other_vendor];
    
    $cost = $details['Product Y']['cost'] +  $other_details['Product X']['cost'];
    
    if ($shipment_cost  ===  null  ||  $cost  <  $shipment_cost) {
    
    $shipment_combination = $combination;
    
    $shipment_cost = $cost;
    
    }
    
    break;
    
    }
    
    }
    
    }
    
    }
    
    if ($shipment_combination  !==  null) {
    
    echo  'Shipment assigned to Vendors '  .  implode(',', $shipment_combination) .  ' with a total cost of '  .  $shipment_cost;
    
    } else {
    
    echo  'Could not assign shipment to any vendor combination';
    
    }
    
    }
