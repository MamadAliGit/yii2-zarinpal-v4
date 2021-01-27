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

    private $_card_hash;
    private $_card_pan;
    private $_ref_id;

    private $_validations;

    /**
     * @param $amount ریال
     * @param $description
     * @param null $mobile
     * @param null $email
     * @param null $card_pan
     * @param array $additional_params
     * @return $this
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($amount, $description, $mobile = null, $email = null, $card_pan = null, $additional_params = [])
    {

        if($additional_params){
            $additional_params = http_build_query($additional_params);
            $this->callback_url = $this->callback_url.'?'.$additional_params;
        }

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
        $body = Json::encode($request_params);
        $client = new Client();
        $response = $client->request('POST',$url,$request_params);
        $result = Json::decode($response->getBody());

        if(!empty($result['data'])){
            $data = $result['data'];

            $this->_authority = $data['authority'];
            $this->_code = $data['code'];
            $this->_message = $data['message'];
            $this->_fee_type = $data['fee_type'];
            $this->_fee = $data['fee'];
        } elseif($result['errors']) {
            $data = $result['errors'];

            $this->_code = $data['code'];
            $this->_message = $data['message'];
            $this->_validations = $data['validations'];
        }


        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        if($this->testing){
            $url = 'https://sandbox.zarinpal.com/pg/StartPay/' . $this->_authority;
        } else {
            $url = 'https://www.zarinpal.com/pg/StartPay/' . $this->_authority;
        }
        return $url;
    }

    /**
     * @param $amount
     * @param $authority
     * @return $this
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verify($amount, $authority)
    {
        $request_params = ['form_params' => [
            'merchant_id' => $this->merchant_id,
            'amount' => $amount,
            'authority' => $authority,
        ]];

        if($this->testing){
            $url = 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json';
        } else {
            $url = 'https://api.zarinpal.com/pg/v4/payment/verify.json';
        }

        $client = new Client();
        $response = $client->request('POST',$url,$request_params);
        $result = Json::decode($response->getBody());

        if(!empty($result['data'])){
            $data = $result['data'];

            $this->_code = $data['code'];
            $this->_message = $data['message'];
            $this->_card_hash = $data['card_hash'];
            $this->_card_pan = $data['card_pan'];
            $this->_ref_id = $data['ref_id'];
            $this->_fee_type = $data['fee_type'];
            $this->_fee = $data['fee'];
        } elseif($result['errors']) {
            $data = $result['errors'];

            $this->_code = $data['code'];
            $this->_message = $data['message'];
            //$this->_validations = $data['validations'];
        }


        return $this;
    }

    public function getRefId(){
        return $this->_ref_id;
    }

    public function getCardPan(){
        return $this->_card_pan;
    }

    public function getCardHash(){
        return $this->_card_hash;
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

    public function getValidations(){
        return $this->_validations;
    }

}
