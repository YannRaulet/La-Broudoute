<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mailjet 
{
    private $api_key = '699209cf3af68551d2c7eb99f84e361c';
    private $api_secret_key ='3e16416b105c58f38a46985227a697a8';

    /* template cf : https://dev.mailjet.com/email/guides/send-api-v31/#use-a-template */
    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_secret_key, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "labroudoute@gmail.com",
                        'Name' => "La Broudoute"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3349333,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}