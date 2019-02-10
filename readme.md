------ purpose -------
php cli application for ordering and meal management.

--preparation--
`composer install` will install app dependencies.
import `cli-app.sql` script to prepare a database.
copy `.env.dist` > `.env` and change db credentials accordingly

--- usage ---
`./index.php place-order` command will guide you through ordering process and will show message in case of error. 
There is a possibility to upload a csv file by adding a path to csv file as an argument. (input.csv for testing purposes)
On success, cli will show order id.

`./index.php update-order` requires id . If provided it will show which field you wish to update.
It is limited to only one change per request. Input is also validated and will show message on error.

`./index.php delete-order` requires id. deletes order.

`./index.php fetch-orders` --export flag optional. If provided will export all data from database to csv file, 
else will print all orders in console.


