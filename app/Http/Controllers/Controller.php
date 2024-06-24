<?php

namespace App\Http\Controllers;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function notFound()
    {
        return response('Not Found!', 404);
    }

    function getAcademicSessionMonths($academic_session) {
        $start_date = new DateTime($academic_session->starting);
        $end_date = new DateTime($academic_session->ending);

        // return compact('start_date', 'end_date');

        $end_date->modify('first day of next month'); // to include the end month
    
        // Define a period interval of 1 month
        $interval = new DateInterval('P1M');
    
        // Generate the period from start to end date
        $period = new DatePeriod($start_date, $interval, $end_date);
    
        // Initialize an array to store the month names
        $months = [];
    
        // Iterate over the period and store month names
        foreach ($period as $date) {
            $months[] = $date->format('F Y'); // 'F' gives the full textual representation of a month (e.g., January)
        }
    
        // Return the array of month names
        return $months;
    }
}
