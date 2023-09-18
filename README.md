Zarinpal Payment
[![Latest Stable Version](https://img.shields.io/packagist/v/mamadali/yii2-zarinpal-v4.svg)](https://packagist.org/packages/mamadali/yii2-zarinpal-v4)
[![Total Downloads](https://img.shields.io/packagist/dt/mamadali/yii2-zarinpal-v4.svg)](https://packagist.org/packages/mamadali/yii2-zarinpal-v4)

================
افزونه پرداخت زرین پال برای فریم ورک yii2

نصب
------------

بهترین روش برای نصب از طریق  [composer](http://getcomposer.org/download/).

کامند زیر را اجرا کنید

```
composer require mamadali/yii2-zarinpal-v4 "*"
```

یا کد زیر را

```
"mamadali/yii2-zarinpal-v4": "*"
```

در قسمت require فایل `composer.json` اضافه کنید.


استفاده
-----

برای استفاده کد زیر را در قسمت components فایل config پروژه اضافه کنید :

```php
        'zarinpal' => [
            'class' => 'mamadali\zarinpal\Zarinpal',
            'merchant_id' => مرچنت کد دریافتی از زرین پال,
            'callback_url' => آدرس صفحه بازگشت کاربر از درگاه,
            'testing' => true, // اگر درحال تست درگاه هستید true در غیر اینصورت این قسمت را کامنت کنید
        ],
```

مستندات فنی در آدرس زیر
https://docs.zarinpal.com/paymentGateway

ارسال درخواست پرداخت
-----

```php
$zarinpal = Yii::$app->zarinpal;
$result = $zarinpal->request($amount, $description, $mobile, $email, $card_pan, $additional_params);
```

ریدایرکت کردن کاربر به صفحه پرداخت
----
```php
$this->redirect($zarinpal->redirectUrl);
```

اعتبار سنجی پرداخت کاربر بعد از بازگشت
----
```php
$zarinpal = Yii::$app->zarinpal;
$result = $zarinpal->verify($amount, $authority);
```
