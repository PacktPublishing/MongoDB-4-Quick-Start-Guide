<?php
// generates array "purchases" inside a customer document

/*
{
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
