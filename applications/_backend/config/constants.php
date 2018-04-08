<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('APP_URL',								'http://b.kiddyjoy.rs/');
define('ECOM_APP_URL',							'http://www.kiddyjoy.rs/');
define('WEB_APP_URL',							'http://b.kiddyjoy.rs/');
define('SERVER_PATH',                           $_SERVER['DOCUMENT_ROOT'].'/'); 
define('SERVER_IMAGE_PATH',						$_SERVER['DOCUMENT_ROOT'].'/assets/img/');

define('USER_TYPE_ADMIN',						0);
define('USER_TYPE_PERSONAL',					1);
define('USER_TYPE_BUSINESS',					2);
define('USER_TYPE_NEWSLETTER',					3);

define ('ADS_CONF' , serialize(array(
    '1' => array( 'category' => 'slideshow', 'route_id' => 1),
    '2' => array( 'category' => 'body', 'route_id' => 2),
    '3' => array( 'category' => 'footer', 'route_id' => 3),
    '4' => array( 'category' => 'popup', 'route_id' => 4),
    '5' => array( 'category' => 'catalog_menu', 'route_id' => 5),
    '6' => array( 'category' => 'bestbuy', 'route_id' => 6),
    '7' => array( 'category' => 'popup', 'route_id' => 7),
    '8' => array( 'category' => 'product', 'route_id' => 8),
    '9' => array( 'category' => 'filter', 'route_id' => 9),
    '10' => array( 'category' => 'filter_small', 'route_id' => 10),
)));

define('UPLOAD_CONF', serialize(array(
    'news' => array( 'path' => SERVER_IMAGE_PATH.'news/pages/', 'entity' => 'models\Entities\News\InfoImage', 'method' => 'setInfo', 'reference' => 'models\Entities\News\Info' ),
    'promotions' => array( 'path' => SERVER_IMAGE_PATH.'promotions/pages/', 'entity' => 'models\Entities\Promotion\PageImage', 'method' => 'setPage', 'reference' => 'models\Entities\Promotion\Page' ),
    'newsletter' => array( 'path' => SERVER_IMAGE_PATH.'newsletter/', 'entity' => 'models\Entities\NewsletterImage', 'method' => 'setNewsletter', 'reference' => 'models\Entities\Newsletters' ),
    'informations' => array( 'path' => SERVER_IMAGE_PATH.'info_desk/', 'entity' => 'models\Entities\Images\PageImage', 'method' => 'setPage', 'reference' => 'models\Entities\InfoDesk' ),
    'shopping_guide' => array( 'path' => SERVER_IMAGE_PATH.'shopping_guide/', 'entity' => 'models\Entities\ShoppingGuide\GuideImage', 'method' => 'setGuide', 'reference' => 'models\Entities\ShoppingGuide\Guide' ), 
    'benchmark' => array( 'path' => SERVER_IMAGE_PATH.'benchmark/pages/', 'entity' => 'models\Entities\Benchmark\Image', 'method' => 'setBenchmark', 'reference' => 'models\Entities\Benchmark' ),
    'preorders' => array( 'path' => SERVER_IMAGE_PATH.'preorders/pages/', 'entity' => 'models\Entities\Images\PreorderImage', 'method' => 'setPreorder', 'reference' => 'models\Entities\Preorder' ),
	'articles' => array( 'path' => SERVER_IMAGE_PATH.'articles/content/', 'entity' => 'models\Entities\Article\Image', 'method' => 'setArticle', 'reference' => 'models\Entities\Article' )
)));

define('DELIVERY', serialize(array(
    '2' => 'KiddyJoy Isporuka',
    '3' => 'Preuzimanje',
    '1' => 'Kurirska dostava',
)));

define('PAYMENT_TYPE', serialize(array(
    '1' => 'Po preuzimanju',
    '2' => 'Uplata na račun',
    '3' => 'Kreditna kartica',
    '4' => 'On-line kredit'
)));

define('CARD_TYPE', serialize(array(
    'VISA'   => 'Visa',
    'MC'     => 'Master Card',
    'MC2'    => 'Master Card',
    'AMEX'   => 'American Express ',
    'DINERS' => 'Diners Club ',
    'JCB'    => 'JCB',
    'MAES' => 'Maestro'
)));

define('PRICE_TYPE', serialize(array(
    '0' => 'Regularna',
    '1' => 'Šok',
)));

define('NEWS_TYPE', serialize(array(
    '1' => 'Novost',
    '2' => 'Akcija',
)));

define('REVIEW_DEFAULT_SPECIFICATIONS',			serialize(array(array('id'=>1, 'name'=>'Performanse', 'ratings' => array(1,3,5,7,9,11,13,15,17,19)),
																array('id'=>3, 'name'=>'Kvalitet', 'ratings' => array(21,23,25,27,29,31,33,35,37,39)),
																array('id'=>5, 'name'=>'Cena', 'ratings' => array(41,43,45,47,49,51,53,55,57,59)))));

define('ACTIVITY_EVENT',						'activity_event');

define('ACTIVITY_PRODUCT',						1);
define('ACTIVITY_BUNDLE',						2);

define('ACTIVITY_OPERATION_PRODUCT',			1);
define('ACTIVITY_OPERATION_BUNDLE',				2);
define('ACTIVITY_OPERATION_SLIDESHOW',			3);
define('ACTIVITY_OPERATION_MENU',				4);

define('ADMIN_ACTIVITY_OPERATIONS', serialize(array(
    '1' => 'Rad na proizvodu',
    '2' => 'Rad sa paketima proizvoda',
	'3' => 'Highlight proizvoda za slideshow',
	'4' => 'Highlight proizvoda za glavni meni'
)));

define('ACTIVITY_PROCESS_CREATE',				1);
define('ACTIVITY_PROCESS_READ',					2);
define('ACTIVITY_PROCESS_UPDATE',				3);
define('ACTIVITY_PROCESS_DELETE',				4);
define('ACTIVITY_PROCESS_SINGLE_LINKAGE',			5);
define('ACTIVITY_PROCESS_GROUP_LINKAGE',			6);
define('ACTIVITY_PROCESS_PRODUCT_ACTIVE',			7);
define('ACTIVITY_PROCESS_PRODUCT_INACTIVE',		8);

define('ACTIVITY_PROCESS', serialize(array(
	'1' => 'Unos',
	'2' => 'Pregled',
	'3' => 'Modifikacija',
	'4' => 'Brisanje',
	'5' => 'Pojedinačno vezivanje paketa',
	'6' => 'Grupno vezivanje paketa',
	'7' => 'Aktivacija proizvoda',
	'8' => 'Deaktivacija proizvoda'
)));

define('ORDER_FINALIZED',						5);
define('ORDER_CANCELED',						8);

define('ORDER_PROCESS', serialize(array(
    '1' => 'Kupovina uneta u sistem',
    '2' => 'Korisnik informisan email-om',
	'3' => '',
	'4' => 'Kupovina povezana sa korisnikom',
	'5' => 'Dodeljeni bodovi',
	'6' => 'Istekao aktivacioni period od 15 dana',
	'7' => 'Greška',
	'8' => 'Poništena kupovina'
)));

define('MENU_SECTIONS', serialize(array(
    '1' => 'Svi proizvodi',
    '2' => 'Akcije i novosti',
    '3' => 'Katalozi',
    '4' => 'Apecijalizovane radnje',
    '5' => 'Podrška pri kupovini',
    '6' => 'Poslovanje'
)));

define('SUBMENU_SECTIONS', serialize(array(
    '5' => 'Akcije',
    '6' => 'Novosti',
    '9' => 'Testovi',
    '10' => 'Gaming korner',
    '1' => 'Blic, 24sata, alo',
    '2' => 'Magazin',
    '3' => 'Katalog',
    '4' => 'Liflet'
)));


/* End of file constants.php */
/* Location: ./application/config/constants.php */