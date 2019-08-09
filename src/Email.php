<?php

namespace SendgridHelper;

use SendGrid;

class Email {

    public static function Send(
        $from,
        $to,
        $subject,
        $content = null,
        $template_id = null,
        $substitutions = [],
        $attachments = []
    ) {

        $sendgrid = new SendGrid(config('sendgrid_mail.sendgrid_api_key'));

        if (isset($content)) {
            $content = new \SendGrid\Mail\Content(
                "text/plain", 
                $content
            );
        }
        
        $email = new \SendGrid\Mail\Mail();

        $email->setSubject($subject);
        $email->setFrom($from['email'], $from['name']);
        $email->addTo($to['email'], $to['name']);

        if (isset($template_id)) {
            $email->setTemplateId($template_id);
        } else {
            $email->addContent($content);
        }

        if (isset($substitutions)) {
            foreach ($substitutions as $key => $value) {
                $email->addSubstitution("{!$key!}", $value);
            }
        } 

        if (isset($attachments)) {
            foreach ($attachments as $attachment) {
                $email->addAttachment(
                    $attachment['file_encoded'],
                    $attachment['file_type'],
                    $attachment['file_name'],
                    "attachment"
                );
            }
        }
        return $sendgrid->send($email);
    }
}
