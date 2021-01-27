Zarinpal Payment
================
Online Zarinpal Payment Extension For Yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

کامند زیر را اجرا کنید

```
composer require mamadali/yii2-zarinpal-v4 "*"
```

کد زیر را

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