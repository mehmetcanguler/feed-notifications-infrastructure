<?php

namespace App\Enums;

enum KafkaTopics: string
{
    case USER_INTERACTIONS = 'user-interactions';
    case EMAIL_SEND_REQUESTS = 'email-send-requests';
}
