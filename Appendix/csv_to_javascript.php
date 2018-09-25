<?php
// get name of collection
$database   = $argv[1] ?? $_GET['database'] ?? NULL;
$collection = $argv[2] ?? $_GET['collection'] ?? NULL;
$dropFirst  = $argv[3] ?? $_GET['drop_first'] ?? FALSE;
$target     = $argv[4] ?? $_GET['target'] ?? '';

$usage = <<<EOT
Collection not specified ... exiting
Usage:
   php csv_to_javascript.php <database> <collection> [Y|N] [target_js]

EOT;

if (!$collection || !$database) exit ($usage);

// set up as a *.js file
$out  = '';
$out .= 'conn = new Mongo();' . PHP_EOL;
$out .= 'db = conn.getDB("' . $database . '");' . PHP_EOL;

// drop first?
if ($dropFirst == 'Y') {
    $out .= 'db.' . $collection . '.drop();' . PHP_EOL;
}

// process headers
$csv  = new SplFileObject(__DIR__ . '/' . $collection . '.csv', 'r');
$headers = $csv->fgetcsv();
$numFields = count($headers);

// process data from CSV file
while ($row = $csv->fgetcsv()) {
    // only take complete rows
    if (isset($row[0]) && count($row) == $numFields) {
        // convert any numeric data to float
        foreach ($row as $key => $item)
            if (ctype_digit($item))
                $row[$key] = (float) $row[$key];

        // create assoc array
        $data = array_combine($headers, $row);
        $out .= 'db.' . $collection . '.insertOne(' . PHP_EOL
              . json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL
              . ');' . PHP_EOL;
    }
}
echo $out;
if ($target) {
    file_put_contents($target, $out);
}
