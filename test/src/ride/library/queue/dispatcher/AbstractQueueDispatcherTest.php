<?php

namespace ride\library\queue\dispatcher;

use ride\library\queue\QueueManager;

use \PHPUnit_Framework_TestCase;

abstract class AbstractQueueDispatcherTest extends PHPUnit_Framework_TestCase {

    public function testQueueManager() {
        $queueManager = $this->getMock('ride\\library\\queue\\QueueManager');
        $instance = $this->createInstance();

        $this->assertNull($instance->getQueueManager());

        $instance->setQueueManager($queueManager);

        $this->assertEquals($queueManager, $instance->getQueueManager());
    }

    abstract protected function createInstance();

}
