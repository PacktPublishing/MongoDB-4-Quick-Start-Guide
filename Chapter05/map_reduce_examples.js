// NOTE: although this is constructed as a mongo shell script ...
//       due to certain limitations of javascript running locally
//       these produce no output!
//       The best way to run these examples is to open a "mongo" shell and cut and paste

conn = new Mongo();
db = conn.getDB("sweetscomplete");

db.purchases.mapReduce(
    function() {
        if (this.customer.country == "US") {
            emit( this.customer.state_province, this.amount );
        }
    },
    function(key,values) {
        return Array.sum(values);
    },
    {
        out:   "totals_by_us_state_province"
    }
);
db.totals_by_us_state_province.find();
