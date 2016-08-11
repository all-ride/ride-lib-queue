<?php

namespace ride\library\queue\dispatcher;

use ride\library\queue\job\QueueJob;
use ride\library\queue\QueueManager;

/**
 * Interface for a queue dispatcher
 */
interface QueueDispatcher {

    /**
     * Sets the queue manager
     * @param \ride\library\queue\QueueManager $queueManager
     * @return null
     */
    public function setQueueManager(QueueManager $queueManager);

    /**
     * Gets the queue manager
     * @return \ride\library\queue\QueueManager $queueManager
     */
    public function getQueueManager();

    /**
     * Queues a job
     * @param \ride\library\queue\job\QueueJob $queueJob Instance of the job
     * @param integer $dateScheduled Timestamp from which the invokation is
     * possible (optional)
     * @return \ride\library\queue\QueueJobStatus Status of the queued job
     */
    public function queue(QueueJob $queueJob, $dateScheduled = null);

}
