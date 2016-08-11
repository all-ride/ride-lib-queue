<?php

namespace ride\library\queue\dispatcher;

use ride\library\queue\job\AbstractQueueJob;
use ride\library\queue\QueueManager;

class SingleQueueDispatcherTest extends AbstractQueueDispatcherTest {

    private $queue = 'queue';

    protected function createInstance() {
        return new SingleQueueDispatcher($this->queue);
    }

    public function testQueue() {
        $queueJob = $this->getMock(AbstractQueueJob::class);
        $queueJob->expects($this->once())
                 ->method('setQueue')
                 ->with($this->equalTo($this->queue))
                 ->will($this->returnValue(null));

        $queueManager = $this->getMock(QueueManager::class);
        $queueManager->expects($this->once())
                     ->method('pushJobToQueue')
                     ->with($this->equalTo($queueJob))
                     ->will($this->returnValue(1));

        $dispatcher = $this->createInstance();
        $dispatcher->setQueueManager($queueManager);

        $dispatcher->queue($queueJob);
    }

}
