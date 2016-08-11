<?php

namespace ride\library\queue\dispatcher;

use ride\library\queue\job\QueueJob;

/**
 * Round robin implementation for a queue dispatcher
 */
class RoundRobinQueueDispatcher extends AbstractQueueDispatcher {

    /**
     * Names of the available queues
     * @var array
     */
    protected $queues;

    /**
     * Constructs a new round robin queue dispatcher
     * @param array $queues Array with the name of the queue as key
     * @return null
     */
    public function __construct(array $queues) {
        $this->queues = $queues;
    }

    /**
     * Gets the queue for the next job
     * @param \ride\library\queue\job\QueueJob $queueJob Instance of the job
     * @return string Name of the queue
     */
    protected function getQueue(QueueJob $queueJob) {
        $queues = array_flip($this->queues);

        if (count($this->queues) != 1) {
            $status = $this->queueManager->getQueueStatus();

            foreach ($queues as $queue => $null) {
                if (isset($status[$queue])) {
                    $queues[$queue] = $status[$queue];
                } else {
                    $queues[$queue] = 0;
                }
            }

            asort($queues);
        }

        $queues = array_keys($queues);

        return array_shift($queues);
    }

}
