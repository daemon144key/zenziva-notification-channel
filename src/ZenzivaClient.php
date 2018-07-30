<?php

namespace TuxDaemon\ZenzivaNotificationChannel;

use Requests;
use TuxDaemon\ZenzivaNotificationChannel\Exceptions\ZenzivaException;

class ZenzivaClient
{
    const TYPE_REGULER = 'reguler';
    const TYPE_MASKING = 'masking';

    /**
     * Zenziva end point.
     *
     * @var string
     */
    protected $url = 'https://{subdomain}.zenziva.net/apps/smsapi.php';

    /**
     * Zenziva username.
     *
     * @var string
     */
    protected $username;

    /**
     * Zenziva password.
     *
     * @var string
     */
    protected $password;

    /**
     * Phone number.
     *
     * @var string
     */
    public $to;

    /**
     * Message.
     *
     * @var string
     */
    public $text;

    /**
     * Sub-domain.
     *
     * @var string
     */
    public $subdomain = 'reguler';

    /**
     * SMS Type : Masking or reguler.
     *
     * @var string
     */
    public $type = self::TYPE_REGULER;

    /**
     * Create the instance.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password, $isMasking = false)
    {
        $this->username = $username; // username
        $this->password = $password; // password

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

                $url = $this->buildQuery();

                $this->doRequest($url);

                $nbProcessed++;
            }
        }

        return $nbProcessed;
    }

    /**
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
    protected function buildQuery()
    {
        if ($this->type == self::TYPE_MASKING) {
            $this->subdomain = 'alpha'; // self::TYPE_MASKING;
        }

        if (empty($this->subdomain)) {
            throw new ZenzivaException('Sub domain is not set!');
        }

        $url = str_replace('{subdomain}', $this->subdomain, $this->url);

        $params = http_build_query([
            'userkey' => $this->username,
            'passkey' => $this->password,
            'tipe' => $this->type,
            'nohp' => $this->to,
            'pesan' => $this->text,
        ]);

        $params = urldecode($params);

        return $url.'?'.$params;
    }
}
