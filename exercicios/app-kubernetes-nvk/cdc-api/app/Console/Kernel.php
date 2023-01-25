<?php

namespace App\Console;

use Illuminate\Mail\Mailer;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Console\Commands\ManageVideocasts;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ManageVideocasts::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Setting Mailer to use gmail SMTP to avoid consuming sendinblue quota
        $mailTech = config('cdcdigital.mail_tech');

        // Backup your default mailer
        // $original = Mail::getSwiftMailer();
        // Setup your gmail mailer
        // hardcoded for now -> Laravel v7 have custom mailer drivers
        $transport = (new \Swift_SmtpTransport($mailTech['host'], $mailTech['port'], $mailTech['encryption']))
            ->setUsername($mailTech['username'])
            ->setPassword($mailTech['password']);
        // Set the mailer as gmail
        app(Mailer::class)->setSwiftMailer(new \Swift_Mailer($transport));


        // Commands

        $schedule
            ->command('message:schedule')
            ->dailyAt('8:00')
            ->name('[CDC Digital] Publish scheduled messages')
            ->emailOutputOnFailure('gianluca@penze.com.br');

        $schedule
            ->command('api:sync:token')
            ->dailyAt('17:25')
            ->skip(function() {
                return \App\User::doesntHave('christmas_token')->approved()->withoutGhosts()->count() === 0;
            })
            ->name('[CDC Digital] Sync User Tokens with API')
            ->emailWrittenOutputTo('gianluca@penze.com.br');
//            ->emailOutputOnFailure('cristiano@penze.com.br'); // change to only send on error when live
        $schedule
            ->command('push:birthday')
            ->dailyAt('17:26')
            ->name('[CDC Digital] Send push notification to birthday user')
            ->emailOutputOnFailure('gianluca@penze.com.br')
            ->emailOutputOnFailure('gianluca@penze.com.br');
        $schedule
            ->command('add:employee_conti')
            ->dailyAt('17:28')
            ->name('[CDC Digital] Add Employee Conti table')
            ->emailOutputOnFailure('gianluca@penze.com.br')
            ->emailWrittenOutputTo('gianluca@penze.com.br');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
