# Chapter 5 _Building Complex Queries Using Aggregation_

To run these queries, first restore the sample database:
* Open a terminal window / command prompt
* Run this command:
```
mongorestore
```
* Alternatively (if that doesn't work), run the following commands:
```
mongorestore -d sweetscomplete -c customers ./dump/sweetscomplete/customers.bson
mongorestore -d sweetscomplete -c products ./dump/sweetscomplete/products.bson
mongorestore -d sweetscomplete -c purchases ./dump/sweetscomplete/purchases.bson
```


