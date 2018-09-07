// NOTE: although this is constructed as a mongo shell script ...
//       due to certain limitations of javascript running locally
//       these produce no output!
//       The best way to run these examples is to open a "mongo" shell and cut and paste

conn = new Mongo();
db = conn.getDB("sweetscomplete");
db.purchases.aggregate([
    {$match:{"customer.country":/AU/}},
    {$group:{_id:"$customer.name",total:{$sum:"$amount"}}}
]);
