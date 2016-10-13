# Ride: Queue Library

Queue abstraction library of the PHP Ride framework.

Use a queue system for time-consuming tasks.

## What's In This Library

### QueueJob

The _QueueJob_ interface is for you to implement.
It holds the logic to run your task.

Your job implementation will probably need data to work with. 
Instead of attaching initialized objects to your job, you should attach references to the needed data, like id's.
It will make your job smaller to store since id's are easier to serialize then object instances.

When your job is invoked, you should lookup your data, using the references, in the current state instead of the state when the job was queued.

### QueueManager

The _QueueManager_ interface is to be implemented for the underlying queue system.
This is the facade to the queue system.
You can check the status of the system, a queue or a job.
It's also used to queue jobs and to update their status.

The _QueueJob_ interface receives the _QueueManager_ when it's invoked.
The queue manager should be extended so it can be used by queue jobs to lookup their references.

### QueueJobStatus

When you ask information about queue jobs, the _QueueManager_ will return _QueueJobStatus_ instances.
This interface is also to be implemented by the underlying queue system.

### QueueDispatcher

A _QueueJob_ holds the queue it is in.
When you create a job, you might not know about the available queues or their availability.
You can use a _QueueDispatcher_ to queue the job.
This dispatcher's goal is to select the proper queue for the job and perform the queueing process.

There are 2 dispatchers provided by the library.

#### SingleQueueDispatcher

The _SingleQueueDispatcher_ is a very simple dispatcher.
It's used when you have only one static queue.

#### RoundRobinQueueDispatcher

The _RoundRobinQueueDispatcher_ is initialized with a number of queues.
When this dispatcher queues a job, it will push it to the queue with the least amount of jobs pending.

### QueueWorker

A _QueueWorker_ is used to grab jobs from the queue, and invoke them.
This work process is most likely a command or daemon which runs in the background.
You will need a worker for each queue.

A generic implementation is provided with the _GenericQueueWorker_ class.

## Code Sample

Check this code sample to see some possibilities of this library:

```php
<?php

use ride\library\queue\dispatcher\QueueDispatcher;
use ride\library\queue\job\AbstractQueueJob;
use ride\library\queue\worker\GenericQueueWorker;
use ride\library\queue\QueueManager;

// push some jobs to the queue
function pushJobs(QueueDispatcher $queueDispatcher, array $files) {
    foreach ($files as $index => $file) {
        // create a job with the needed references
        $job = new MyQueueJob($file);
         
        // you can optionally set a priority
        // jobs with a smaller priority value are more urgent
        $job->setPriority(100);
         
        // the queue dispatcher will select a queue and push your job to it
        $queueJobStatus = $queueDispatcher->queue($job);
    
        // you get the job id and more properties through the resulting queue job status     
        $files[$index] = $queueJobStatus->getId();
    }
    
    return $files;
}

// work horse
function work(QueueManager $queueManager, $queue = 'default') {
    // use a generic worker
    $queueWorker = new GenericQueueWorker();
    
    // this worker will poll the queue every 3 seconds for a job forever
    // you can provide a 0 second sleep time, the worker will then stop when it has no jobs
    $queueWorker->work($queueManager, $queue, 3);
}

// example job which executes pngcrush on the provided path
class MyQueueJob extends AbstractQueueJob {

    public function __construct($path) {
        $this->path = $path;
    }

    public function run(QueueManager $queueManager) {
        // you can update the description of your job while running
        $queueManager->updateStatus($this->getJobId(), 'Checking file ' . $this->path);
        
        if (file_exists($this->path) && !is_directory($this->path)) {
            return;
        }
        
        $command = 'pngcrush -nofilecheck -rem alla -bail -blacken -ow ' . $this->path; 
        
        $queueManager->updateStatus($this->getJobId(), 'Invoking ' . $command);
        
        exec($command, $output, $code);
        
        $queueManager->updateStatus($this->getJobId(), $command . ' returned ' . $code);
    }
    
}

```

### Related Modules

You can check the following related modules to this library:

- [ride/app-queue-beanstalkd](https://github.com/all-ride/ride-app-queue-beanstalkd)
- [ride/app-queue-orm](https://github.com/all-ride/ride-app-queue-orm)
- [ride/cli-queue](https://github.com/all-ride/ride-cli-queue)
- [ride/lib-common](https://github.com/all-ride/ride-lib-common)
- [ride/lib-log](https://github.com/all-ride/ride-lib-log)
- [ride/lib-queue-beanstalkd](https://github.com/all-ride/ride-lib-queue-beanstalkd)
- [ride/wba-queue](https://github.com/all-ride/ride-wba-queue)
- [ride/wra-queue](https://github.com/all-ride/ride-wra-queue)

## Installation

You can use [Composer](http://getcomposer.org) to install this library.

```
composer require ride/lib-queue
```
