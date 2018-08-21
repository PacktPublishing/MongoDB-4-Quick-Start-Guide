// NOTE: you can't run these commands as a script!
//       you will need to cut and paste these into a mongo shell

// create the "admin" user
use admin;
db.createUser(
  {
    user: "superMan",
    pwd: "up.up.and.away!",
    roles: [ { role: "userAdminAnyDatabase", db: "admin" } ]
  }
);

// create a basic user who can read and write from/to sweetscomplete
// authentication database = sweetscomplete
use sweetscomplete;
db.createUser(
  {
    user: "sweetBasic",
    pwd: "some.password",
    roles: [ { role: "readWrite", db: "sweetscomplete" } ]
  }
);

// create a basic user who can read and write from/to sweetscomplete
// authentication database = admin
use admin;
db.createUser(
  {
    user: "zed",
    pwd: "some.password",
    roles: [ { role: "readWrite", db: "sweetscomplete" } ]
  }
);

// change user's password and authentication mechanism
use admin;
db.updateUser(
  "zed",
  {
    pwd: "password",
    mechanisms: [ "MONGODB-X509" ]
  }
);

// remove use "zed"
use admin;
db.dropUser(
  "zed"
);
