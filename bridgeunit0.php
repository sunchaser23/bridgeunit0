<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . '/config.php';

use deemru\UnitsBridge;
use deemru\UnitsKit;
use deemru\WavesKit;

if ($argc!=2)
    die("Usage: php bridgeunit0.php [amount]\n");

$amount=$argv[1];
$txhash=false;

$unitsPrivateKey = $config['unit0']['pk'];
$wavesPrivateKey = $config['waves']['pk'];
$bridgeContract = $config['unit0']['bridgecontract'];

$wk = new WavesKit( $config['waves']['chain'] );
if ($config['waves']['chain']=='T')
{
        $bridge = UnitsBridge::TESTNET();
        $uk = UnitsKit::TESTNET();
}
elseif ($config['waves']['chain']=='W')
{
        $bridge = UnitsBridge::MAINNET();
        $uk = UnitsKit::MAINNET();
}
else
    die('Not supported chain');

$uk->setPrivateKey( $unitsPrivateKey );
$wk->log('i', 'UNITS: ' . $uk->getAddress() . ' ~ ' . $uk->stringValue( $uk->getBalance() ) . ' UNIT0' );
$wk->setPrivateKey( $wavesPrivateKey );
$wk->log('i', 'WAVES: ' . str_pad( $wk->getAddress(), 42 ) . ' ~ ' . $uk->stringValue( $wk->balance( null, 'WAVES' ), 8 ) . ' WAVES' );
$wk->log('i', 'amount: ' . $amount);
$wk->log('i', 'txhash: ' . $txhash);

$amount = UnitsKit::hexValue( $amount );

if( $txhash === false )
{
    $tx = $bridge->sendNative( $uk, $wk, $amount );
    if( $tx !== false )
        $txhash = $tx['hash'];
}

$tx = $bridge->waitFinalized( $uk, $wk, $txhash );
$wk->log('i', 'WAVES: finalized block reached' );
$wk->log('i', 'WAVES: withdrawing' );
$tx = $bridge->withdraw( $uk, $wk, $tx );
$wk->log('i', 'WAVES: withdrawn ' . $uk->stringValue( $tx['call']['args'][3]['value'], 8 ) . ' UNIT0' );
$wk->log('s', 'DONE.' );
?>
