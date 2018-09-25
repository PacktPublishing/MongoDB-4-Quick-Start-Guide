conn = new Mongo();
db = conn.getDB("sweetscomplete");
db.customers.insertOne({"name":"Karyn Francis","address":"871 Rocky Autumn Mews","city":"Gassaway",
                        "state_province":"","postal_code":"RF21 26MI","country":"UK","phone":"385-836-7870",
                        "balance":"919.76","email":"karyn.francis@fastnet.com","password":"the9642He"});
db.customers.updateOne({"email":"karyn.francis@fastnet.com"},{$unset:{"password":1}});
db.customers.insertOne({"name":"Blanca Le","address":"179 Noble Pine Place","city":"Diagonal",
                        "state_province":"NS","postal_code":"B6R 2T3","country":"CA","phone":"185-787-5938",
                        "balance":"833.32","email":"blanca.le@telecom.com","password":"and6425said"});
db.customers.updateOne({"email":"blanca.le@telecom.com"},{$unset:{"password":1}});
db.customers.insertOne({"name":"Renee Decker","address":"42 Robbers Way","city":"Nome",
                        "state_province":"ACT","postal_code":"2900","country":"AU","phone":"660-333-4444",
                        "balance":"447.83","email":"renee.decker@westcom.net","password":"Stephens6135that"});
db.customers.updateOne({"email":"renee.decker@westcom.net"},{$unset:{"password":1}});
db.customers.insertOne({"name":"C.T. Russell","address":"123 Main Street","city":"New York",
                        "state_province":"NY","postal_code":"10001","country":"US","phone":"555-1212",
                        "balance":"0","email":"ctrussell@jw.org","password":"password"});
db.customers.updateOne({"email":"ctrussell@jw.org"},{$unset:{"password":1}});
