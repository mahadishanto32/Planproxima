<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\MonthlyReportPermission::class,
        Commands\AlertMonthlyReportPermission::class,
        Commands\AchievementReportPermissionOff::class,
        Commands\AchievementsPanel::class,
        Commands\DeptAlert::class,
        Commands\DeptAchievementsAlert::class,
        Commands\DailyMail::class,
        Commands\MonthlyAchievementPermissions::class,
        Commands\AchievmentNotUpdate::class,
        Commands\FoAchivAndTarget::class,
        Commands\PriorityTasksQuarterly::class,
        Commands\TourEntryAccuracy::class

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // ->monthlyOn(1, '00:01');   

        // $schedule->command('report:achievement_permission_off')   
        // ->monthlyOn(5, '00:01');  

        $schedule->command('report:monthly_summery_report')
            ->monthlyOn(1, '00:01');

        // Dept Alert
        $schedule->command('report:dept_alert')
            ->monthlyOn(4, '00:01');

        $schedule->command('report:dept_achievements_alert')
            ->monthlyOn(5, '00:01');

        // Achievement Permission Off 
        $schedule->command('report:achievements_panel')
            ->monthlyOn(6, '00:01');
        
        $schedule->command('report:daily_mail')->dailyAt('11:00');        
        $schedule->command('achievement:permission')->monthlyOn(1, '00:01');  

        $schedule->command('achievement:permission')->monthlyOn(1, '00:01');   
         // Achievement not update mail  
         $schedule->command('report:fo_achiv_target')
         ->monthlyOn(1, '00:01');
         
         $schedule->command('report:achievements_not_update')
         ->monthlyOn(1, '00:01');
         $schedule->command('report:achievements_not_update')
         ->monthlyOn(6, '00:01'); 
         $schedule->command('priority_tasks_quarter_sync')
         ->monthlyOn(1, '00:01'); 
         // Tour plan accuracy check with sales automation
         $schedule->command('tour_entry_accuracy')->hourly();


        // $schedule->command('report:daily_mail')->weekly()->sundays()->at('11:00');
        // $schedule->command('report:daily_mail')->weekly()->mondays()->at('11:00');
        // $schedule->command('report:daily_mail')->weekly()->tuesdays()->at('11:00');
        // $schedule->command('report:daily_mail')->weekly()->wednesdays()->at('11:00');
        // $schedule->command('report:daily_mail')->weekly()->thursdays()->at('11:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
