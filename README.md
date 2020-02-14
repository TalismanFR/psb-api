Library for PSB Bank API
==

Description [here](https://doc.finstar.online/api/fo/#/).

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/TalismanFR/psb-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/TalismanFR/psb-api/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/TalismanFR/psb-api/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/TalismanFR/psb-api/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/TalismanFR/psb-api/badges/build.png?b=master)](https://scrutinizer-ci.com/g/TalismanFR/psb-api/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/TalismanFR/psb-api/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

## Install

```php 
 composer require talismanfr/psb-api dev-master
```                                

## Tutorial

Use `ApiService`

```php      
<?php
$api = new talismanfr\psbbank\api\Api('https://api.lk.psbank.ru/fo/v1.0.0/');
$service = new talismanfr\psbbank\ApiService($api);
```                                              

Getting auth token via email:password
```php 
$login = $service->login('test@test.ru', '123');
echo 'token='.$login->getToken();
```                              

New  `Order`
```php 
// create `OrderRequest` object.
$orderReq = new OrderRequest(new InnValue('9724004969'), 'test firm', false, true,
            'tes test test', new PhoneValue('79675319122'), new EmailValue('test@test.ru'),
            190, 'comment');

//send order to bank
$order = $service->createOrder('YouToken', $orderReq);
if ($order != null){
    if ($order->isError()){
        print_r($order->getErrors());
    }else{
        echo 'id order='.$order->getId().PHP_EOL;
    }
}
```

## Tests
Income.
