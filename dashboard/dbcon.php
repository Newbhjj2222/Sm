<?php
require __DIR__ . '/../vendor/autoload.php';
use Kreait\Firebase\Factory;

$factory = (new Factory)
    ->withServiceAccount(__DIR__ . '/../school-managements-8cfb7-firebase-adminsdk-fbsvc-8019685afc.json')
    ->withDatabaseUri('https://school-managements-8cfb7-default-rtdb.firebaseio.com/');

$auth = $factory->createAuth();
$realtimeDatabase = $factory->createDatabase();
$cloudMessaging = $factory->createMessaging();
$remoteConfig = $factory->createRemoteConfig();
$cloudStorage = $factory->createStorage();

// Comment Firestore line because it requires grpc extension
// $firestore = $factory->createFirestore();
?>