<?php

namespace ride\library\queue\job;

use ride\library\queue\QueueManager;

/**
 * Interface for a job
 */
interface QueueJob {

    /**
     * Sets the name of the queue
     * @param string $queue
     * @return null
     */
    public function setQueue($queue);

    /**
     * Gets the name of the queue
     * @return string
     */
    public function getQueue();

    /**
     * Sets the id of the job
     * @param integer $id
     * @return null
     */
    public function setJobId($id);

    /**
     * Gets the id of the job
     * @return integer
     */
    public function getJobId();

    /**
     * Gets the priority of this job
     * @return integer
     */
    public function getPriority();

    /**
     * Invokes the implementation of the job
     * @param QueueManager $queueManager Instance of the queue manager
     * @return integer|null A timestamp from which time this job should be
     * invoked again or null when the job is done
     */
    public function run(QueueManager $queueManager);

}
