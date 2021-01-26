<?php

/**
 * Job Queue Handler
 * Background job handler for sending email
 * php version 7.4
 * 
 * @category Scripts
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

declare(strict_types=1);

use Bernard\Message\DefaultMessage;
use DEOTransCodeChallenge\Factories\DatabaseFactory;
use DEOTransCodeChallenge\Factories\JobQueueFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$queueFactory = JobQueueFactory::createQueueFactory();
$consumer = JobQueueFactory::createConsumer(
    function (DefaultMessage $message) {
        $delivered = mail(
            $message->receiver,
            $message->subject,
            $message->message,
            [
                'From' => $message->sender,
                'Reply-To' => $message->sender
            ]
        );

        if ($delivered) {
            $updateEmails = DatabaseFactory::createConnection()->prepare(
                'UPDATE emails SET sent_at = NOW() WHERE id = :id'
            );

            $updateEmails->execute([':id' => $message->id]);

            echo "Delivered email from {$message->sender} to {$message->receiver}";
        } else {
            echo "Failed to delivered email from {$message->sender} to {$message->receiver}";
        }
    }
);

// Jalanin job queue handler loop
$consumer->consume($queueFactory->create('send-email', ['max-runtime' => 900]));
