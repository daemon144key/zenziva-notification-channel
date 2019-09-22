<?php

namespace TuxDaemon\ZenzivaNotificationChannel\Test;

use Mockery;
use TuxDaemon\ZenzivaNotificationChannel\ZenzivaClient;
use TuxDaemon\ZenzivaNotificationChannel\Exceptions\ZenzivaException;

class ZenzivaClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var $zenzivaClient ZenzivaClient */
    private $zenzivaClient;

    public function setUp()
    {
        parent::setUp();

        $this->zenzivaClient = new ZenzivaClient(env('ZENZIVA_SMS_CLIENT_USERKEY', ''), env('ZENZIVA_SMS_CLIENT_PASSKEY', ''));
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_creates_a_new_zenziva_client_given_login_details()
    {
        $this->assertInstanceOf(ZenzivaClient::class, $this->zenzivaClient);
    }

    /** @test */
    public function it_sends_a_message_to_a_single_number()
    {
        $to = '62811223344';
        $message = 'Hi this is from Zenziva!';

        $this->zenzivaClient->send($to, $message);
    }

    /** @test */
    public function it_sends_a_message_to_multiple_numbers()
    {
        $to = ['62811223344', '62811223355'];

        $message = 'Hi this is from Zenziva for multiple receivers!';

        $this->zenzivaClient->send($to, $message);
    }

    /** @test */
    public function throws_an_exception_on_failed_response_code()
    {
        $this->setExpectedException(
            ZenzivaException::class,
            'One of your destination phone number is empty!'
        );

        $to = ['']; // empty number
        $message = 'Hi there, this message sent to an empty number!';

        $this->zenzivaClient->send($to, $message);
    }

    /** @test */
    public function it_check_sms_credit_left()
    {
        $this->zenzivaClient->checkBalance();
    }
}
