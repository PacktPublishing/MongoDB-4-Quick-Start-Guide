# Chapter 6 _Maintaining MongoDB Performance_

To restore the sample database:
* Open a terminal window / command prompt
* Run the following commands:
```
mongorestore -d sweetscomplete -c customers ./dump/sweetscomplete/customers.bson
mongorestore -d sweetscomplete -c products ./dump/sweetscomplete/products.bson
mongorestore -d sweetscomplete -c purchases ./dump/sweetscomplete/purchases.bson
```


