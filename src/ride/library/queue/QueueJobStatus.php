<?php

namespace ride\library\queue;

/**
 * Interface for the status of a queue job
 */
interface QueueJobStatus {

    /**
     * Gets the id of the job
     * @return integer
     */
    public function getId();

    /**
     * Gets the name of the queue
     * @return string
     */
    public function getQueue();

    /**
     * Gets the class name of the queue job
     * @return string
     */
    public function getClassName();

    /**
     * Gets the queue job
     * @return QueueJob
     */
    public function getQueueJob();

    /**
     * Gets the status code
     * @return string
     */
    public function getStatus();

    /**
     * Gets a detailed description of the status
     * @return string
     */
    public function getDescription();

    /**
     * Gets the slot number
     * @return integer
     */
    public function getSlot();

    /**
     * Gets the total number of slots
     * @return integer
     */
    public function getSlots();

    /**
     * Gets the added date
     * @return integer Timestamp
     */
    public function getDateAdded();

    /**
     * Gets the schedule date
     * @return integer|null Timestamp
     */
    public function getDateScheduled();

}
