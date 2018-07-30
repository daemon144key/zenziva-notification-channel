<?php

namespace TuxDaemon\ZenzivaNotificationChannel\Test;

use TuxDaemon\ZenzivaNotificationChannel\ZenzivaMessage;

class ZenzivaMessageTest extends \PHPUnit_Framework_TestCase
{
    protected $zenzivaMessage;

    public function setUp()
    {
        parent::setUp();
        $this->zenzivaMessage = new ZenzivaMessage();
    }

    /** @test */
    public function it_sets_a_zenziva_message()
    {
        $this->assertInstanceOf(
            ZenzivaMessage::class,
            $this->zenzivaMessage
        );
    }

    /** @test */
    public function it_can_construct_with_a_new_message()
    {
        $actual = ZenzivaMessage::create('This is message content!');

        $this->assertEquals('This is message content!', $actual->getContent());
    }

    /** @test */
    public function it_can_set_new_content()
    {
        $actual = ZenzivaMessage::create();

        $this->assertEmpty($actual->getContent());

        $actual->content('HiThere!');

        $this->assertEquals('HiThere!', $actual->getContent());
    }
}
