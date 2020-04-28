<?php

namespace ride\library\queue\worker;

use ride\library\queue\QueueManager;

/**
 * A worker for a queue.
 */
interface QueueWorker {

    /**
     * Continiously checks the queue and invokes the scheduled jobs
     * @param \ride\library\queue\QueueManager $queueManager Instance of the
     * @param string $queue Name of the queue
     * @param float $sleepTime Time to sleep between jobs/ticks
     * @param integer $maxJobs Maximum number of jobs to invoke
     * @return null
     */
    public function work(QueueManager $queueManager, $queue, $sleepTime, $maxjobs = 0);

}
