<?php
error_reporting(0);
session_start();
define('__SYNC__', '127.0.0.1');
define('__SYNC_PORT__', '3100');


define('__MAX_UPLOAD_FILE_SIZE__', '5');

define('__HOSTNAME__', 'http://' . $_SERVER['SERVER_ADDR'] . '/serge/client');
define('__HOSTAPI__', 'http://' . $_SERVER['SERVER_ADDR'] . '/serge/api');
define('__HOST__', 'http://' . $_SERVER['SERVER_ADDR'] . '/serge/');

define('__PC_CUSTOMER__', 'Siloam Hospital');
define('__PC_CUSTOMER_GROUP__', 'Pemerintahan Provinsi Riau');
define('__PC_CUSTOMER_ADDRESS__', 'Jalan Dr. Soetomo No. 65');
define('__PC_CUSTOMER_EMAIL__', 'rsudpetalabumi@riau.go.id');
define('__PC_CUSTOMER_ADDRESS_SHORT__', 'Pekanbaru');
define('__PC_CUSTOMER_CONTACT__', '(0761)23024');
define('__PC_IDENT__', 'sil');
define('__SYSTEM_DOMAIN__', 'kuisioner.com');
/*
	 * terminologi_item*/
define('__STATUS_BARANG_MASUK__', 51);
define('__STATUS_STOK_AWAL__', 52);
define('__STATUS_MUTASI_STOK__', 53);
define('__STATUS_AMPRAH__', 54);
define('__STATUS_RETUR_AMPRAH__', 55);
define('__STATUS_RETUR_PEMASOK__', 56);
define('__STATUS_OPNAME__', 57);
