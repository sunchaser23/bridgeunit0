<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . '/config.php';

use deemru\UnitsBridge;
use deemru\UnitsKit;
use deemru\WavesKit;

if ($argc!=2)
    die("Usage: php bridgeunit0.php [amount|ALL]\n");
    
$wk = new WavesKit( $config['waves']['chain'] );
$txhash=false;
$unitsPrivateKey = $config['unit0']['pk'];
$wavesPrivateKey = $config['waves']['pk'];
$bridgeContract = $config['unit0']['bridgecontract'];

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

if ($argv[1]=='ALL')
{
    $unit0balance = UnitsKit::stringValue($uk->getBalance());
    $amount = intval($unit0balance - 1);
}
else
{
    if (is_numeric($argv[1]))
        $amount=intval($argv[1]);
    else
        $wk->log('w', 'Invalid amount specified.');
        die(1);
}    

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
