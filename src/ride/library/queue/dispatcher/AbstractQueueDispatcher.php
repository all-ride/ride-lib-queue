<?php

namespace ride\library\queue\dispatcher;

use ride\library\queue\exception\QueueException;
use ride\library\queue\job\QueueJob;
use ride\library\queue\QueueManager;

/**
 * Abstract implementation for a queue dispatcher
 */
abstract class AbstractQueueDispatcher implements QueueDispatcher {

    /**
     * Instance of the queue manager
     * @var \ride\library\queue\QueueManager
     */
    protected $queueManager;

    /**
     * Sets the queue manager
     * @param \ride\library\queue\QueueManager $queueManager
     * @return null
     */
    public function setQueueManager(QueueManager $queueManager) {
        $this->queueManager = $queueManager;
    }

    /**
     * Gets the queue manager
     * @return \ride\library\queue\QueueManager
     */
    public function getQueueManager() {
        return $this->queueManager;
    }

    /**
     * Queues a job
     * @param \ride\library\queue\job\QueueJob $queueJob Instance of the job
     * @param integer $dateScheduled Timestamp from which the invokation is
     * possible (optional)
     * @return \ride\library\queue\QueueJobStatus Status of the queued job
     */
    public function queue(QueueJob $queueJob, $dateScheduled = null) {
        if (!$this->queueManager) {
            throw new QueueException('Could not queue the job: queue manager not set');
        }

        $queueJob->setQueue($this->getQueue($queueJob));

        return $this->queueManager->pushJobToQueue($queueJob, $dateScheduled);
    }

    /**
     * Gets the queue for the provided job
     * @param \ride\library\queue\job\QueueJob $queueJob
     * @return string Name of the queue
     */
    abstract protected function getQueue(QueueJob $queueJob);

}
