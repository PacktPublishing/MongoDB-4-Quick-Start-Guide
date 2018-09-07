<?php
// generates array "purch_history" inside a customer document
// example:
/*
{
    "_id" : ObjectId("5b482b45533b843e7b6f70c3"),
    "name" : "Conrad Perry",
    "address" : "79 Amber Branch Falls",
    "city" : "Birdseye",
    "state_province" : "QC",
    "postal_code" : "G0U 0M5",
    "country" : "CA",
    "phone" : "484-181-9811",
    "balance" : 745.32,
    "email" : "conrad.perry@fastmedia.com",
    "password" : "$2y$10$dXcMwUgiezGGSrKqy0/4LOJNT6fy2BCaAvw4DDdnZ.3ZBztMEF/zK",
    "purch_history" : [
        "2017-09-20",
        "2018-03-10",
        "2019-03-04",
        "2017-09-13",
        "2019-04-19"
    ]
}
*/

// REQUIRED TO RUN THIS SCRIPT:
// (1) PHP v7.0 or above
// (2) mongodb PHP extension (use PECL)
// (3) mongodb/mongodb PHP library (use Composer)

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
$purchases = $client->sweetscomplete->purchases;

// scan purchases
try {
    // get list of purchases
    $cursor = $purchases->find([],[
        'options' => [
            'project' => ['customer.name' => 1, 'date' => 1],
            'sort' => ['customer.name' => 1]]
    ]);
    // generate purchases for customers
    $history = [];
    foreach ($cursor as $purch) {
        // number of "purchases"
        $history[$purch['customer']['name']][] = $purch['date'];
    }
    $count = 0;
    $updated = 0;
    foreach ($history as $name => $list) {
        $count++;
        if ($customers->updateOne(['name' => $name], ['$set' => ['purch_history' => $list]])) $updated++;
    }
    echo 'Customers scanned: ' . $count . ' : History added: ' . $updated;
} catch (Exception $e) {
    echo $e->getMessage();
}
