conn = new Mongo();
db = conn.getDB("sweetscomplete");
db.purchases.aggregate([
    {$match:{"customer.country":/AU/}},
    {$group:{_id:"$customer.name",total:{$sum:"$amount"}}}
]);
