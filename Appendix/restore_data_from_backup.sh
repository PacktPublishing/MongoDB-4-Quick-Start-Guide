#!/bin/sh
# Restores data by collection
# Alternatively, do to it all at once, just run the command "mongorestore"
mongorestore -d sweetscomplete -c customers ./dump/sweetscomplete/customers.bson
mongorestore -d sweetscomplete -c products ./dump/sweetscomplete/products.bson
mongorestore -d sweetscomplete -c purchases ./dump/sweetscomplete/purchases.bson
