<?php

namespace ride\library\queue\dispatcher;

use ride\library\queue\job\AbstractQueueJob;
use ride\library\queue\QueueManager;

class RoundRobinQueueDispatcherTest extends AbstractQueueDispatcherTest {

    private $queues = array('queue1', 'queue2', 'queue3');

    protected function createInstance() {
        return new RoundRobinQueueDispatcher($this->queues);
    }

    /**
     * @dataProvider providerQueue
     */
    public function testQueue($queue, $queueStatus, $dateSchedule = null) {
        $queueJob = $this->getMock('ride\\library\\queue\\job\\AbstractQueueJob');
        $queueJob->expects($this->once())
                 ->method('setQueue')
                 ->with($this->equalTo($queue))
                 ->will($this->returnValue(null));

        $queueManager = $this->getMock('ride\\library\\queue\\QueueManager');
        $queueManager->expects($this->once())
                     ->method('getQueueStatus')
                     ->will($this->returnValue($queueStatus));
        $queueManager->expects($this->once())
                     ->method('pushJobToQueue')
                     ->with($this->equalTo($queueJob), $this->equalTo($dateSchedule))
                     ->will($this->returnValue(1));

        $dispatcher = $this->createInstance();
        $dispatcher->setQueueManager($queueManager);

        $dispatcher->queue($queueJob, $dateSchedule);
    }

    public function providerQueue() {
        return array(
            array('queue3', array('queue1' => 0, 'queue2' => 0)),
            array('queue2', array('queue1' => 1, 'queue2' => 0, 'queue3' => 1)),
            array('queue3', array('queue1' => 1, 'queue2' => 1)),
            array('queue1', array('queue1' => 1, 'queue2' => 4, 'queue3' => 5)),
            array('queue1', array('queue1' => 1, 'queue2' => 4, 'queue3' => 5, 'queue4' => 0), time() + 500),
        );
    }

}
