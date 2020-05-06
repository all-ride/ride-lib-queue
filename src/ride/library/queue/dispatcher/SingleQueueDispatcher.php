<?php

namespace ride\library\queue\dispatcher;

use ride\library\queue\exception\QueueException;
use ride\library\queue\job\QueueJob;

/**
 * Queue dispatcher with a single queue
 */
class SingleQueueDispatcher extends AbstractQueueDispatcher {

    /**
     * Names of the available queues
     * @var array
     */
    protected $queue;

    /**
     * Constructs a new queue dispatcher
     * @param string $queue Name of the queue
     */
    public function __construct($queue) {
        $this->setQueue($queue);
    }

    /**
     * Sets the queue for this dispatcher
     * @param string $queue Name of the queue
     */
    public function setQueue($queue) {
        if (!is_string($queue) || $queue === '') {
            throw new QueueException('Could not set queue for dispatcher: empty or invalid string provided');
        }

        $this->queue = $queue;
    }

    /**
     * Gets the queue for the next job
     * @param \ride\library\queue\job\QueueJob $queueJob Instance of the job
     * @return string Name of the queue
     */
    protected function getQueue(QueueJob $queueJob) {
        return $this->queue;
    }

}
