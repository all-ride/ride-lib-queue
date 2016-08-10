<?php

namespace ride\library\queue\worker;

use ride\library\queue\job\QueueJob;
use ride\library\queue\QueueJobStatus;
use ride\library\queue\QueueManager;

use \PHPUnit_Framework_TestCase;

class GenericQueueWorkerTest extends PHPUnit_Framework_TestCase {

    public function testWork() {
        $queue = 'default';
        $sleepTime = -1;

        $this->queueManager = $this->getMock(QueueManager::class);
        $this->queueManager->expects($this->any())
                     ->method('popJobFromQueue')
                     ->with($this->equalTo($queue))
                     ->will($this->returnCallback(array($this, 'popJobFromQueue')));

        $worker = new GenericQueueWorker();
        $worker->work($this->queueManager, $queue, $sleepTime);
    }

    public function popJobFromQueue() {
        static $index = 3;

        $index--;
        if ($index === 0) {
            return null;
        }

        $queueJob = $this->getMock(QueueJob::class);
        $queueJob->expects($this->once())
                 ->method('run')
                 ->with($this->equalTo($this->queueManager))
                 ->will($this->returnValue(null));

        $queueJobStatus = $this->getMock(QueueJobStatus::class);
        $queueJobStatus->expects($this->once())
                       ->method('getQueueJob')
                       ->will($this->returnValue($queueJob));

        return $queueJobStatus;
    }

}
