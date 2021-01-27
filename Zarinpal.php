<?php

namespace mamadali\zarinpal;

use GuzzleHttp\Client;
use yii\base\Model;
use yii\helpers\Json;

/**
 * This is just an example.
 */
class Zarinpal extends Model
{

    public $merchant_id;
    public $callback_url;
    public $testing = false;

    private $_code;
    private $_message;
    private $_authority;
    private $_fee_type;
    private $_fee;


    public function request($amount, $description, $mobile = null, $email = null, $card_pan = null)
    {
        $request_params = ['form_params' => [
            'merchant_id' => $this->merchant_id,
            'amount' => $amount,
            'description' => $description,
            'callback_url' => $this->callback_url,
            'metadata' => [
                'mobile' => $mobile,
                'email' => $email,
                'card_pan' => $card_pan,
            ]
        ]];

        if($this->testing){
            $url = 'https://sandbox.zarinpal.com/pg/v4/payment/request.json';
        } else {
            $url = 'https://api.zarinpal.com/pg/v4/payment/request.json';
        }

        $client = new Client();
        $response = $client->request('POST',$url,$request_params);
        $result = Json::decode($response->getBody());

        $data = $result['data'];

        $this->_authority = $data['authority'];
        $this->_code = $data['code'];
        $this->_message = $data['message'];
        $this->_fee_type = $data['fee_type'];
        $this->_fee = $data['fee'];

        return $this;
    }

    public function getCode(){
        return $this->_code;
    }

    public function getAuthority(){
        return $this->_authority;
    }

    public function getMessage(){
        return $this->_message;
    }

    public function getFeeType(){
        return $this->_fee_type;
    }

    public function getFee(){
        return $this->_fee;
    }

}
