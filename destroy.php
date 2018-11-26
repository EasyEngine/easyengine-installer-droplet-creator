<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/functions.php';

use DigitalOceanV2\Adapter\BuzzAdapter;
use DigitalOceanV2\DigitalOceanV2;

$token = getenv( 'DO_ACCESS_TOKEN' );

if ( empty( $token ) ) {
	echo 'Please set DO Token env';
	exit( 1 );
}

// create an adapter with your access token which can be
// generated at https://cloud.digitalocean.com/settings/applications
$adapter = new BuzzAdapter( $token );

// create a digital ocean object with the previous adapter
$digitalocean = new DigitalOceanV2( $adapter );

$data_file = 'droplet.json';
$droplet_info = file_get_contents( './' . $data_file, true );
$droplet_info = json_decode( $droplet_info, true );
$digitalocean->droplet()->delete( $droplet_info['id'] );
unlink( $data_file );
