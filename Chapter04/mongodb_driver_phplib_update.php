<?php
// initialize env
require __DIR__ . '/vendor/autoload.php';
use MongoDB\BSON\ObjectId;
use Application\Client;

// set up mongodb client + collection
$params = ['host' => '127.0.0.1'];
$client = (new Client($params))->getClient();
$collection = $client->sweetscomplete->customers;

// here is the javascript query we wish to emulate:
/*
db.customers.updateOne(
    {_id:ObjectId("5b47108b533b8406ac227798")},
    {$set:{balance:99.99}}
);
*/

$filter = ['_id' => new ObjectId('5b47108b533b8406ac227798')];
try {
    $document = $collection->findOne($filter);
    printf("Name: %s | Old Balance: %.2f\n", $document->name, $document->balance);
    $data = ['$set' => ['balance' => 99.99]];
    $result = $collection->updateOne($filter, $data);
    printf("Matched %d document(s)\n", $result->getMatchedCount());
    printf("Modified %d document(s)\n", $result->getModifiedCount());
    $document = $collection->findOne($filter);
    printf("Name: %s | New Balance: %.2f\n", $document->name, $document->balance);
} catch (Exception $e) {
    echo $e->getMessage();
}
