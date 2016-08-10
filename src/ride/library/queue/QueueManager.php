<?php

namespace ride\library\queue;

use ride\library\queue\job\QueueJob;

/**
 * Interface for the queue manager
 */
interface QueueManager {

    /**
     * Status for a waiting job
     * @var integer
     */
    const STATUS_WAITING = 'waiting';

    /**
     * Status for a job in progress
     * @var integer
     */
    const STATUS_PROGRESS = 'progress';

    /**
     * Status for a job which ran into an error
     * @var integer
     */
    const STATUS_ERROR = 'error';

    /**
     * Gets the status of the queue's
     * @return array Array with the name of the queue as key and the number of
     * queued slots as value
     */
    public function getQueueStatus();

    /**
     * Gets the jobs for the provided queue
     * @param string $queue Name of the queue
     * @return array Array with the QueueJobStatus objects
     */
    public function getQueueJobStatuses($queue);

    /**
     * Gets the status of a job in the queue
     * @param string $id Id of the job in the queue
     * @return null|QueueJobStatus Null if the job is finished, the status of
     * the job otherwise
     */
    public function getQueueJobStatus($id);

    /**
     * Pushes a job to the queue
     * @param \ride\library\queue\job\QueueJob $queueJob Instance of the job
     * @param integer $dateScheduled Timestamp from which the invokation is
     * possible (optional)
     * @return QueueJobStatus Status of the job
     */
    public function pushJobToQueue(QueueJob $queueJob, $dateScheduled = null);

    /**
     * Pops a job from the queue (FIFO) and marks it as in progress
     * @param string $queue Name of the queue
     * @return QueueJobStatus|null Status of the first job in the provided queue
     * or null if the queue is empty
     */
    public function popJobFromQueue($queue);

    /**
     * Updates the status of a job
     * @param integer $id Id of the job status
     * @param string $destription Description of the progress
     * @param string $status Status code
     * @throws \ride\library\queue\exception\QueueException
     */
    public function updateStatus($id, $description, $status = null);

    /**
     * Reschedule a existing job
     * @param \ride\library\queue\job\QueueJob $queueJob Instance of the job
     * @param integer $dateScheduled Timestamp from which the invokation is
     * possible
     * @return null
     */
    public function rescheduleJob(QueueJob $queueJob, $dateScheduled);

    /**
     * Finishes a job
     * @param \ride\library\queue\job\QueueJob $queueJob Instance of the job
     * @return null
     */
    public function finishJob(QueueJob $queueJob);

}
