<?php

namespace ride\library\queue\job;

use ride\library\queue\exception\QueueException;

/**
 * Abstract implementation of a queue job
 */
abstract class AbstractQueueJob implements QueueJob {

    /**
     * Name of the queue for this job
     * @var string
     */
    protected $queue;

    /**
     * Id of this job
     * @var string
     */
    protected $jobId;

    /**
     * Priority level of this job
     * @var integer
     */
    protected $priority;

    /**
     * Constructs a new abstract job
     * @param string $queue Name of the queue
     * @param string $jobId Id of the job
     * @return null
     */
    public function __construct($queue = null, $jobId = null) {
        $this->queue = $queue;
        $this->jobId = $jobId;
    }

    /**
     * Sets the name of the queue
     * @param string $queue
     * @return null
     */
    public function setQueue($queue) {
        $this->queue = $queue;
    }

    /**
     * Gets the name of the queue
     * @return string
     */
    public function getQueue() {
        return $this->queue;
    }

    /**
     * Sets the id of the job
     * @param integer $id
     * @return null
     */
    public function setJobId($id) {
        $this->jobId = $id;
    }

    /**
     * Gets the id of the job
     * @return integer
    */
    public function getJobId() {
        return $this->jobId;
    }

    /**
     * Sets the priority of this job
     * @param integer $priority
     * @return null
     * @throws \ride\library\queue\exception\QueueException
     */
    public function setPriority($priority) {
        if (!is_numeric($priority) || $priority < 0) {
            throw new QueueException('Could not set the priority: provided value should be a number greater or equals to 0');
        }

        $this->priority = $priority;
    }

    /**
     * Gets the priority of this job
     * @return integer
     */
    public function getPriority() {
        return $this->priority;
    }

}
