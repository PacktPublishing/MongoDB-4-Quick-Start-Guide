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
    user: "CN=zed,OU=mongodb,O=unlikelysource,L=Surin,ST=Surin,C=TH",
    pwd: "password",
    roles: [ { role: "readWrite", db: "sweetscomplete" } ]
  }
);

// change user's authentication mechanism
use admin;
db.getSiblingDB("$external").runCommand(
    {
        createUser: "CN=zed,OU=mongodb,O=unlikelysource,L=Surin,ST=Surin,C=TH",
        roles: [
            { role: 'readWrite', db: 'sweetscomplete' }
        ],
        writeConcern: { w: "majority" , wtimeout: 5000 }
    }
);

// authenticate user
use admin;
db.getSiblingDB("$external").auth(
    {
        mechanism: "MONGODB-X509",
        user: "CN=zed,OU=mongodb,O=unlikelysource,L=Surin,ST=Surin,C=TH"
    }
);

// check authenticated users
db.runCommand({connectionStatus : 1})

// remove user "zed"
use admin;
db.dropUser(
  "CN=zed,OU=mongodb,O=unlikelysource,L=Surin,ST=Surin,C=TH"
);
