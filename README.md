# Covie PHP SDK

PHP SDK to work with Covie's API

**NOTE: This is pre-release software and is subject to change as we develop it.**

## Installation

We recommend that you use [Composer](https://getcomposer.org/) to bring the Covie PHP SDK into your project.

```bash
$ composer require covie/sdk-php
```

## Quickstart

Firstly, create a client using your own `client-id` and `client-secret`:

```php
use Covie\SDK\Client;
$client = Client::createFromCredentials('client-id', 'client-secret');
```
Now that you have a client, you can interact with Covie.

#### Create an Integration

To create a new integration named `test`:

```php
$integration = $sdk->integrations()->create('test');
```
You can then access the new integration's key using:

```php
$key = $integration->getIntegrationKey();
```

The key can then be used with the SDK and other integration level API calls. 

#### Retrieve Policy Data

To retrieve a policy, you need to know its ID which is of the form `po_xxxxxxxxxxxxxxxx` and can then use:

```php
$policy = $sdk->policies()->get($policyId);
```

Now that we have a policy, we can obtain the policy data in JSON format:

```php
echo json_encode($policy->jsonSerialize(), JSON_PRETTY_PRINT) . PHP_EOL;
```

We can also retrieve its documents. For example to retrieve the declaration PDF and write to disk:

```php
use Covie\SDK\Model\DocumentType;
$pdfContent = $sdk->policies()->getLatestDocumentOfType($policy, DocumentType::DECLARATION);
file_put_contents('declaration.pdf', $pdfContent);
```

The available types are listed in the [DocumentType](src/Model/DocumentType.php) class.

## Contributions

We are not accepting contributions to the Covie PHP SDK at this time.
