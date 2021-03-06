<?php

// These values must be changed to what is used for HTTP Basic auth
// Copy this file into another file that is untracked by git so client ID and secret aren't exposed.
const CLIENT_ID = '';
const CLIENT_SECRET = '';

require_once __DIR__ . '/../vendor/autoload.php';

use Covie\SDK\Model\DocumentType;

/*
 * SDK Client Initialization step
 */
$sdkClient = \Covie\SDK\Client::createFromCredentials(CLIENT_ID, CLIENT_SECRET);


/*
 * Integration setup step
 */

// **WARNING**: this line will create a new Integration each time.
// If this behavior is undesired, this link should be run once, then commented out.
$integration = $sdkClient->integrations()->create('Name of Integration');

// Extract Integration Key from $integration, it will be used to acquire a Policy entity
echo $integration->getIntegrationKey() . PHP_EOL;


/*
 * Manual Linking step, this occurs outside of the SDK.
 */

// Copy and paste Integration Key to use with Policy linking process
// Complete a Policy linking process



/*
 * Policy setup step
 */

// Policy object
$policy = $sdkClient->policies()->get('po_abcdefgh');
// JSON response
echo json_encode($policy->jsonSerialize(), JSON_PRETTY_PRINT) . PHP_EOL;
// PDF contents stored as string, can be saved to file
$pdfContent = $sdkClient->policies()->getLatestDocumentOfType($policy, DocumentType::DECLARATION);
