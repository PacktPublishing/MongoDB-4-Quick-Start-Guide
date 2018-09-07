<?php
// Demonstrates how to use some classes available in the mongodb PHP extension
// Does not use the mongodb PHP library (which, BTW, is recommended!)

// emulates this command:
// db.customers.find(
//    {country:/UK/,balance:{$lt:100}},
//    {_id:0,name:1,email:1,country:1,postal_code:1}
//);

$uri     = 'mongodb://localhost';
$filter  = [
    'country' => 'UK',
    'balance' => ['$lt' => 100]
];
$options = [
    'projection' => [
        '_id' => 0,
        'name' => 1,
        'email' => 1,
        'country' => 1,
        'postal_code' => 1
    ]
];
$manager = new MongoDB\Driver\Manager($uri);
$query   = new MongoDB\Driver\Query($filter, $options);
$cursor  = $manager->executeQuery('sweetscomplete.customers', $query);

foreach($cursor as $document) {
    vprintf(' %20s : %9s : %2s : %s' . PHP_EOL, (array) $document);
}
