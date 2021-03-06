<?php

namespace App;

use Mail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Naux\Mail\SendCloudTemplate;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'confirmation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];



    public function sendPasswordResetNotification($token)
    {
        $data = [
            'url'  => url('password/reset',$token)
        ];
        $template = new SendCloudTemplate('test_template_reset', $data);

        Mail::raw($template, function ($message) {
            $message->from('117898271@11.com', 'Laravel');
            $message->to($this->email);
        });
    }
}
