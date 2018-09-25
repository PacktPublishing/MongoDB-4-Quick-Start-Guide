<?php
// generates collection "purchases" which looks like this:

/*
{
    "_id" : ObjectId("5b500ad7533b844173064582"),
    "customer" : {
        "_id" : ObjectId("5b482b45533b843e7b6f70c3"),
        "name" : "Conrad Perry",
        "state_province" : "QC",
        "country" : "CA",
        "balance" : 745.32
    },
    "product" : {
        "_id" : ObjectId("5b4c232accf2ea73a85ed2c7"),
        "sku" : "C22000",
        "title" : "Chocolate Toaster Tarts",
        "price" : 2.2
    },
    "date" : "2017-09-20",
    "quantity" : 15,
    "amount" : 33
}
*/

require __DIR__ . '/vendor/autoload.php';
use Application\Client;

// set up mongodb client + collection
$max    = 20;   // number of purchases generated: (1 to $max)
$maxQ   = 100;  // quantity of each item "purchased" range: (1 to $maxQ)
$range  = 365;  // purchase date range: (today - $range) <=> (today + $range)
$list   = [];
$params = ['host' => '127.0.0.1'];
$client = (new Client($params))->getClient();
$today  = new DateTime();

// create collection instances for customers and products
$customers = $client->sweetscomplete->customers;
$products = $client->sweetscomplete->products;
$purchases = $client->sweetscomplete->purchases;

// clear out purchases
$purchases->drop();
$purchases = $client->sweetscomplete->purchases;

// generate random set of purchases
try {
    // get list of products
    $cursor = $products->find([],['sku' => 1]);
    foreach ($cursor as $document) {
        $list[] = $document->sku;
    }
    $numProds = count($list) - 1;
    // get list of customers including these fields:
    $projection = [
        'projection' => [
            '_id' => 1,
            'name' => 1,
            'state_province' => 1,
            'country' => 1,
            'balance' => 1
        ]
    ];
    $cursor = $customers->find([], $projection);
    // generate purchases for customers
    foreach ($cursor as $cust) {
        // number of "purchases"
        echo 'Customer: ' . $cust->name . PHP_EOL;
        // create a random number of purchases for each customer
        $num = rand(0, $max);
        for ($x = 0; $x < $num; $x++) {
            // find product at random
            $filter = ['sku' => $list[rand(0, $numProds)]];
            $projection = ['projection' => ['description' => 0]];
            $prod = $products->findOne($filter,$projection);
            $prod->price = (float) $prod->price;
            // generate random date
            $int = new DateInterval('P' . rand(1, $range) . 'D');
            $date = clone $today;
            if ($x % 2) {
                $date->add($int);
            } else {
                $date->sub($int);
            }
            $date = $date->format('Y-m-d');
            $qty  = rand(1, $maxQ);
            // store purchase
            $data = [
                'customer' => $cust,
                'product' => $prod,
                'date' => $date,
                'quantity' => $qty,
                'amount' => (float) sprintf('%.2f', ($qty * $prod->price))
            ];
            $purchases->insertOne($data);
            printf('%20s : %10s : %3d : %.2f' . PHP_EOL, $prod->sku, $date, $qty, $data['amount']);
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
