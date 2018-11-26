# Setup and files.

Do `composer install` to get dependency.

####`create-instance.php` 
Creates new droplet and output ip address. It requires two environment variables:
1. DO_ACCESS_TOKEN - Digital ocean access token.
2. DO_OS - Operating system. You can get list of available os from [here](/distribution.json).

It also creates new file called `droplet.json` which saves droplet id in it.

#### `destroy.php`

Deletes droplet specified in `droplet.json` and also deletes file `droplet.json`.



