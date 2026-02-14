<?php
// vendor iri hanze ya dashboard
require __DIR__ . '/../vendor/autoload.php';

use Appwrite\Client;
use Appwrite\Services\Storage;

// SETUP Appwrite Client
$client = new Client();

$client
    ->setEndpoint('https://nyc.cloud.appwrite.io/v1') // API endpoint
    ->setProject('6974d1e80024bfbb626f')         // project ID
    ->setKey('standard_44ae890941faec2c75c65a9a3ee18748986357b6664c8bec02cd85957594cc833832dd4f2dc74fc86987ab108c05ae77aecb4745d347fd2c2ce30f857a1fa12e603b79e2848c4b74d8c18bf63c5ca941b6a3ebb2eda1de8f77b14e3e7703e879e13d5d905dec4a387787a6b750108e1776d8e600e4ea1be9aa7cb69481402946') // API key
    ->setSelfSigned(true);

// STORAGE SERVICE
$storage = new Storage($client);
