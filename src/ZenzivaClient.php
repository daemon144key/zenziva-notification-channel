<?php

namespace TuxDaemon\ZenzivaNotificationChannel;

use Requests;
use TuxDaemon\ZenzivaNotificationChannel\Exceptions\ZenzivaException;

class ZenzivaClient
{
    const TYPE_REGULER = 'reguler';
    const TYPE_MASKING = 'masking';

    const REQUEST_TYPE_SENDING = 'sending';
    const REQUEST_TYPE_CREDIT_CHECKING = 'checking';

    /**
     * Zenziva end point.
     *
     * @var string
     */
    protected $url = 'https://{subdomain}.zenziva.net/apps';
    
    /**
     * Zenziva path for sending sms
     *
     * @var string
     */
    protected $sendingPath = '/smsapi.php';

    /**
     * Zenziva path for checking sms balance/credit
     *
     * @var string
     */
    protected $creditCheckingPath = '/smsapibalance.php';

    /**
     * Zenziva userkey.
     *
     * @var string
     */
    protected $userkey;

    /**
     * Zenziva passkey.
     *
     * @var string
     */
    protected $passkey;

    /**
     * Phone number destination, can altered by application.
     *
     * @var string
     */
    public $to;

    /**
     * Message, can altered by application.
     *
     * @var string
     */
    public $text;

    /**
     * Sub-domain, can altered by application.
     *
     * @var string
     */
    public $subdomain = 'reguler';

    /**
     * SMS Type : Masking or reguler. Reguler is default type.
     *
     * @var string
     */
    public $type = self::TYPE_REGULER;

    /**
     * Create the instance.
     *
     * @param string $userkey
     * @param string $passkey
     */
    public function __construct($userkey, $passkey, $isMasking = false)
    {
        $this->userkey = $userkey; // userkey
        $this->passkey = $passkey; // passkey

        if ($isMasking) {
            $this->masking();
        }
    }

    /**
     * Set destination phone number.
     *
     * @param $to  Phone number
     *
     * @return self
     */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Set messages.
     *
     * @param $text  Message
     *
     * @return self
     */
    public function text($text)
    {
        if (! is_string($text)) {
            throw new ZenzivaException('Text should be string type!');
        }

        $this->text = $text;

        return $this;
    }

    /**
     * Set sub-domain.
     *
     * @param $subdomain  Sub-domain
     *
     * @return self
     */
    public function subdomain($subdomain)
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    /**
     * Set masking.
     *
     * @param bool $masking  Masking
     *
     * @return self
     */
    public function masking($masking = true)
    {
        $this->type = $masking ? self::TYPE_MASKING : self::TYPE_REGULER;

        return $this;
    }

    /**
     * Sending SMS Request to SMS Gateway with given destination phone number and message
     *
     * @param $to  Phone number
     * @param $text  Message
     *
     * @return int Number of processed notifications
     * @throws \ZenzivaException
     */
    public function send($to, $text)
    {
        $destinationNumbers = $to;
        if (! is_array($destinationNumbers)) {
            $destinationNumbers = [$destinationNumbers];
        }

        $nbProcessed = 0;
        if ($destinationNumbers && count($destinationNumbers) > 0) {
            foreach ($destinationNumbers as $number) {
                if (! is_string($text)) {
                    throw new ZenzivaException('Text should be string type!');
                }

                $this->to = ! empty($number) ? $number : $this->to;
                $this->text = ! empty($text) ? $text : $this->text;

                if (empty($this->to)) {
                    throw new ZenzivaException('One of your destination phone number is empty!');
                }
                if (is_null($this->text)) {
                    throw new ZenzivaException('Text is not set!');
                }

                $url = $this->buildQuery(self::REQUEST_TYPE_SENDING);
                $this->doRequest($url);

                $nbProcessed++;
            }
        }

        return $nbProcessed;
    }

    /**
     * Get current SMS Credit/Balance
     *
     * @return int Number of SMS credit left
     * @throws \ZenzivaException
     */
    public function checkBalance()
    {
        $url = $this->buildQuery(self::REQUEST_TYPE_CREDIT_CHECKING);
        $response = $this->doRequest($url);
        $rawdata = simplexml_load_string($response);
        $json = json_encode($rawdata);
        $parsedData = json_decode($json);
                  
        return ($parsedData && $parsedData->message && isset($parsedData->message->value) ? $parsedData->message->value : 0);
    }

    /**
     * Do HTTP Request
     *
     * @param  string $url
     * @return \Requests_Response
     */
    private function doRequest($url)
    {
        return Requests::get($url);
    }

    /**
     * Build query string.
     *
     * @return string
     */
    protected function buildQuery($requestType)
    {
        if ($this->type == self::TYPE_MASKING) {
            $this->subdomain = 'alpha';
        }

        if (empty($this->subdomain)) {
            throw new ZenzivaException('Sub domain is not set!');
        }

        $url = str_replace('{subdomain}', $this->subdomain, $this->url);
        if ($requestType == self::REQUEST_TYPE_CREDIT_CHECKING) {
            $url .= $this->creditCheckingPath;
        } else {
            $url .= $this->sendingPath;
        }

        $params = [
            'userkey' => $this->userkey,
            'passkey' => $this->passkey,
        ];
        if ($requestType == self::REQUEST_TYPE_SENDING) {
            $params = array_merge($params, [
                'tipe' => $this->type,
                'nohp' => $this->to,
                'pesan' => $this->text,
            ]);
        }

        $requestParams = urldecode(http_build_query($params));

        return $url.'?'.$requestParams;
    }
}
