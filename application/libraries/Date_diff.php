<?php
defined('BASEPATH') OR exit('No direct script access allowed');


    class Date_diff
    {
        /*****************************
         *  days_diff
         *
         *  $from_date YYYY-MM-DD
         *
         *  $to_date   YYYY-MM-DD
         *
         *  return: days away as sting
         *
         *  "Today"
         *  "Tomorrow"
         *  "in x days"
         */
        function days_diff_str($from_date, $to_date)
        {
        
            $days_away = $this->days_diff($from_date, $to_date);

            if($days_away < 0)
            {
                switch ($days_away) {

                    case -1:
                        $days = "Yesterday";
                        break;
                    default:
                        $days = abs($days_away) . " days ago";
                        break;
                }            
            }
            else
            {
                switch ($days_away) {
                    case 0:
                        $days = "Today";
                        break;
                    case 1:
                        $days = "Tomorrow";
                        break;
                    default:
                        $days = "in " .abs($days_away)  . " days";
                        break;
                }            
            }

            
//            echo "days_diff_str: $from_date - $to_date: $days_away $days<br>";

            return $days;
        }
        
        /*****************************
         *  days_diff
         *
         *  $from_date YYYY-MM-DD
         *
         *  $to_date   YYYY-MM-DD
         *
         *  return: days away as sting
         *
         *  "Today"
         *  "Tomorrow"
         *  "in x days"
         */
        function days_diff($from_date, $to_date)
        {

            $datetime1 = date_create($from_date);
            $datetime2 = date_create($to_date);

            // Calculates the difference between DateTime objects
            $interval = date_diff($datetime1, $datetime2);
            

            // Display the result
            $days_away = $interval->format('%R%a');


            return $days_away;
        }
    
        
    }