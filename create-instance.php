<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/functions.php';

use DigitalOceanV2\Adapter\BuzzAdapter;
use DigitalOceanV2\DigitalOceanV2;

$token = getenv( 'DO_ACCESS_TOKEN' );
$image = getenv( 'DO_OS' );

if ( empty( $token ) ) {
	echo 'Please set DO Token env';
	exit( 1 );
}

if ( empty( $image ) ) {
	echo 'Please set OS env';
	exit( 1 );
}

// create an adapter with your access token which can be
// generated at https://cloud.digitalocean.com/settings/applications
$adapter      = new BuzzAdapter( $token, get_rtbrowser() );
$digitalocean = new DigitalOceanV2( $adapter ); // create a digital ocean object with the previous adapter

$unique_name = 'ee-installer-' . slug( $image );

$ssh_keys = get_all_do_ssh_keys( $digitalocean ); // get all ssh keys.

$size   = 's-1vcpu-1gb'; // Fixed size of droplet. smallest size.
$region = 'blr1'; // Closest to our branch.
$tags   = [ $unique_name, 'ee-installer-test' ];

$droplet     = $digitalocean->droplet();
$droplet_new = $droplet->create( 'EE-Insteraller-Test' . $image, $region, $size, $image, false, false, false, $ssh_keys, '', '', [], $tags );
do {
	$droplet_new = $digitalocean->droplet()->getById( $droplet_new->id );
	if ( 'active' === strtolower( $droplet_new->status ) ) {
		sleep( 30 ); // Takes x sec to start accepting ssh connection.
		break;
	}
	sleep( 3 ); // sleep for x sec to check if droplet is active yet or not.
} while ( true );

$data_file = 'droplet.json';
$handle = fopen( $data_file, 'w' ) or die( 'Cannot open file:  ' . $data_file );
fwrite( $handle, json_encode( [ 'id' => $droplet_new->id ] ) );
fclose( $handle );
echo array_shift( $droplet_new->networks )->ipAddress;
