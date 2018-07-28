// NOTE: although this is constructed as a mongo shell script ...
//       due to certain limitations of javascript running locally
//       these produce no output!
//       The best way to run these examples is to open a "mongo" shell and cut and paste

conn = new Mongo();
db = conn.getDB("sweetscomplete");

// $group:
db.purchases.aggregate( [
    { $group: {
            _id: "$customer.name",
            total: { $sum: "$amount" },
            avgQty: { $avg: "$quantity" },
            count: { $sum: 1 }
        }
    },
] );

// $bucket:
// A B C D E F G H I J K L M N O P Q R S T U V W X Y Z
// ----------- ----------- ----------- ----------- ---
db.purchases.aggregate( [
{
    $bucket: {
      groupBy: "$customer.name",
      boundaries: [ "A", "G", "M", "S", "Y" ],
      default: "Y-Z",
      output: {
        "count": { $sum: 1 },
        "names" : { $push: "$customer.name" },
        "amounts" : { $push: "$amount"}
      }
    }
},
{
    $project: {
        _id : "$_id",
        count : "$count",
        amounts : { $sum: "$amounts" }
    }
}
] );

// $lookup:
db.purchases.aggregate([
   { $lookup: {
         from: "customers",
         localField: "customer.name",
         foreignField: "name",
         as: "purch_plus_cust" }
   },
   { $limit: 1 },
   { $project: { _id: 0, customer: 0, "purch_plus_cust.password": 0 } }
]).pretty();

// $match:
db.purchases.aggregate( [
    { $match: {
            "customer.country":/UK/,
            "product.title":/chocolate/i
        }
    },
    { $group:  {
            _id:"$product.title",
            "total": {$sum:"$amount"}
        }
}
] );

// $avg
db.purchases.aggregate( [
{ $match: { "customer.country": /AU/ } },
{
    $bucket: {
      groupBy: "$quantity",
      boundaries: [ 0,10,50,100 ],
      default: "other",
      output: {
        "qty" : { $push: "$quantity" }
      }
    }
},
{ $project: { qty : { $avg: "$qty" } } }
] );

// $$REMOVE
db.purchases.aggregate( [
{ $match: { "customer.country": /AU/ } },
{
    $bucket: {
      groupBy: "$quantity",
      boundaries: [ 0,10,50,100 ],
      default: "other",
      output: {
        "qty" : { $push: "$quantity" }
      }
    }
},
{
    $project: {
        _id : { $cond: {if: { $eq: ["other", "$_id"] },
                then: "$$REMOVE",
                else: "$_id" }},
        qty : { $cond: {if: { $eq: ["other", "$_id"] },
                then: "$$REMOVE",
                else: { $avg: "$qty" }}}
    }
}
] );

// $bucket + $dayOfWeek + $toDate + $push + $project + $min + $arrayElemAt + $subtract + $sum
days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
db.purchases.aggregate( [
{
    $bucket: {
      groupBy: { $dayOfWeek: { $toDate: "$date" } },
      boundaries: [ 1,2,3,4,5,6,7,8 ],
      default: "other",
      output: {
        "dow" : { $push: { $dayOfWeek: { $toDate: "$date" } } },
        "amounts" : { $push: "$amount"}
      }
    }
},
{
    $project: {
        dow : { $min: "$dow" },
        day : { $arrayElemAt: [days, { $subtract: [{ $min:"$dow" }, 1] }]},
        amounts : { $sum: "$amounts" }
    }
}
] );
