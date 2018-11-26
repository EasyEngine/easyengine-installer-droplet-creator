<?php

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Client\FileGetContents;
use DigitalOceanV2\DigitalOceanV2;

function slug( $str, $delimiter = '-' ) {

	$slug = strtolower( trim( preg_replace( '/[\s-]+/', $delimiter, preg_replace( '/[^A-Za-z0-9-]+/', $delimiter, preg_replace( '/[&]/', 'and', preg_replace( '/[\']/', '', iconv( 'UTF-8', 'ASCII//TRANSLIT', $str ) ) ) ) ), $delimiter ) );

	return $slug;

}

function get_all_do_ssh_keys( DigitalOceanV2 $do ) {
	try {
		$keys = $do->key()->getAll();

		return array_column( $keys, 'id' );
	} catch ( \Exception $e ) {
		echo 'Error getting ssh keys';
		var_dump( $e );
		exit( 1 );
	}
}

function get_all_distribution_os( DigitalOceanV2 $do ) {
	try {
		return $do->image()->getAll( [ 'type' => 'distribution' ] );
	} catch ( \Exception $e ) {
		echo 'Error getting distribution OS';
		var_dump( $e );
		exit( 1 );
	}
}

function add_ssh_key( DigitalOceanV2 $do, string $public_key, string $name ) {
	try {
		$do->key()->create( $name, $public_key );
	} catch ( \Exception $e ) {
		echo 'Error getting distribution OS';
		var_dump( $e );
		exit( 1 );
	}
}

function get_rtbrowser(){
	return new rtBrowser( function_exists( 'curl_exec' ) ? new Curl() : new FileGetContents() ); // to increase timeout to 30 sec. check below.
}

class rtBrowser extends Browser {
	public function __construct( \Buzz\Client\ClientInterface $client = null, \Buzz\Message\Factory\FactoryInterface $factory = null ) {
		parent::__construct( $client, $factory );
		$this->getClient()->setTimeout( 30 );
	}
}
