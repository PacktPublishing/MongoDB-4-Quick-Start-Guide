// NOTE: you can't run these commands as a script!
//       you will need to cut and paste these into a mongo shell

conn = new Mongo();
db = conn.getDB("sweetscomplete");
// here is the query:
/*
db.purchases.find(
   {"customer.country": {$not:/US/}},
   {_id:0,"customer.name":1,"customer.state_province":1,"customer.country":1}
).sort(
   {"customer.country":1,"customer.state_province":1,"customer.name":1}
);
*/
// this gives us performance stats on the query:
db.purchases.find(
   {"customer.country": {$not:/US/}},
   {_id:0,"customer.name":1,"customer.state_province":1,"customer.country":1}
).sort(
   {"customer.country":1,"customer.state_province":1,"customer.name":1}
).explain("executionStats");
// now we create the index:
db.purchases.createIndex(
  {
    "customer.country":1,
    "customer.state_province":1,
    "customer.name":1
  }
);
// and re-run the query using "explain()" to get stats
db.purchases.find(
   {"customer.country": {$not:/US/}},
   {_id:0,"customer.name":1,"customer.state_province":1,"customer.country":1}
).sort(
   {"customer.country":1,"customer.state_province":1,"customer.name":1}
).explain("executionStats");
