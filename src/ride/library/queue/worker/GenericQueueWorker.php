<?php

namespace ride\library\queue\worker;

use ride\library\log\Log;
use ride\library\queue\exception\QueueException;
use ride\library\queue\job\QueueJob;
use ride\library\queue\QueueManager;
use ride\library\Timer;

use \Exception;

/**
 * A worker for a queue.
 */
class GenericQueueWorker implements QueueWorker {

    /**
     * Source for the log messages
     * @var string
     */
    const LOG_NAME = 'queue';

    /**
     * Instance of the Log
     * @var \ride\library\log\Log
     */
    protected $log;

    /**
     * Instance of the queue manager
     * @var \ride\library\queue\QueueManager
     */
    protected $queueManager;

    /**
     * Constructs a new queue worker
     * @param \ride\library\log\Log $log Instance of the log
     * @return null
     */
    public function __construct(Log $log = null) {
        $this->log = $log;
    }

    /**
     * Continiously checks the queue and invokes the scheduled jobs
     * @param \ride\library\queue\QueueManager $queueManager Instance of the
     * queue manager
     * @param string $queue Name of the queue
     * @param float $sleepTime Time in seconds to sleep between jobs/ticks
     * @return null
     */
    public function work(QueueManager $queueManager, $queue, $sleepTime) {
        $timer = new Timer();

        do {
            $queueJobStatus = $queueManager->popJobFromQueue($queue);
            if ($queueJobStatus) {
                $timer->reset();

                $queueJob = $queueJobStatus->getQueueJob();

                if ($this->log) {
                    $queueJobId = $queueJob->getJobId();

                    $this->log->logDebug('Invoking job #' . $queueJobId, null, self::LOG_NAME);
                }

                $dateReschedule = $this->invokeJob($queueManager, $queueJob);
                if ($dateReschedule === false) {
                    if ($this->log) {
                        $this->log->logError('Invoked job #' . $queueJobId, null, self::LOG_NAME);
                    }
                } else {
                    if ($this->log) {
                        $this->log->logDebug('Invoked job #' . $queueJobId, null, self::LOG_NAME);
                    }

                    if (is_numeric($dateReschedule)) {
                        $canReschedule = false;

                        if ($queueJob->getMaxSchedules() === true || $queueJob->getMaxSchedules() > $queueJobStatus->getNumSchedules()) {
                            $canReschedule = true;
                        }

                        if ($canReschedule) {
                            if ($this->log) {
                                $this->log->logDebug('Rescheduling job #' . $queueJobId . ' for ' . date('Y-m-d H:i:s', $dateReschedule), null, self::LOG_NAME);
                            }

                            $queueManager->rescheduleJob($queueJob, $dateReschedule);
                        } else {
                            if ($this->log) {
                                $this->log->logError('Can\'t reschedule job #' . $queueJobId . ' for ' . date('Y-m-d H:i:s', $dateReschedule), 'Max schedules reached', self::LOG_NAME);
                            }

                            $queueManager->updateStatus($queueJob->getJobId(), 'Could not reschedule job: max schedules reached', QueueManager::STATUS_ERROR);
                        }
                    } else {
                        $queueManager->finishJob($queueJob);
                    }
                }

                if ($this->log) {
                    $time = $timer->getTime();

                    $this->log->logDebug('Job #' . $queueJobId . ' took ' . $time . ' seconds', null, self::LOG_NAME);
                }

                continue;
            } elseif ($sleepTime <= 0) {
                if ($this->log) {
                    $this->log->logDebug('Nothing to be done and no sleep time. Exiting ...', null, self::LOG_NAME);
                }

                break;
            }

            if ($sleepTime > 0) {
                if ($this->log) {
                    $this->log->logDebug('Sleeping ' . $sleepTime . ' second(s)...', null, self::LOG_NAME);
                }

                sleep($sleepTime);
            }
        } while (true);
    }

    /**
     * Invokes a queue job
     * @param \ride\library\queue\QueueManager $queueManager Instance of the
     * queue manager
     * @param \ride\library\queue\job\QueueJob $job Queue job to invoke
     * @return null|boolean|integer Null when the job is finished, a timestamp
     * to reschedule the job or false when an error occured
     */
    protected function invokeJob(QueueManager $queueManager, QueueJob $queueJob) {
        try {
            $result = $queueJob->run($queueManager);
        } catch (Exception $exception) {
            if ($this->log) {
                $this->log->logException($exception, self::LOG_NAME);
            }

            $description  = '';

            do {
                $message = $exception->getMessage();
                $message = get_class($exception) . ($message ? ': ' . $message : '');

                $description .= $description ? "\n\nCaused by:\n" : '';
                $description .= $message . "\n\nTrace:\n" . $exception->getTraceAsString();

                $exception = $exception->getPrevious();
            } while ($exception);

            $queueManager->updateStatus($queueJob->getJobId(), $description, QueueManager::STATUS_ERROR);

            $result = false;
        }

        return $result;
    }

}
