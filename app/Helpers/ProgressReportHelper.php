<?php

namespace App\Helpers;

class ProgressReportHelper
{
    /**
     * Get the allowed progress report months from environment configuration
     */
    public static function getAllowedMonths(): array
    {
        $months = config('app.progress_report_months', '4,9');
        return array_map('intval', explode(',', $months));
    }

    /**
     * Get the month names for the allowed months
     */
    public static function getAllowedMonthNames(): array
    {
        $months = self::getAllowedMonths();
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return array_intersect_key($monthNames, array_flip($months));
    }

    /**
     * Check if the current month is allowed for progress report submission
     */
    public static function isCurrentMonthAllowed(): bool
    {
        $currentMonth = (int) date('n');
        return in_array($currentMonth, self::getAllowedMonths());
    }

    /**
     * Get the next allowed month for progress report submission
     */
    public static function getNextAllowedMonth(): ?string
    {
        $allowedMonths = self::getAllowedMonths();
        $currentMonth = (int) date('n');
        $currentYear = (int) date('Y');

        // Find the next allowed month
        foreach ($allowedMonths as $month) {
            if ($month > $currentMonth) {
                $monthNames = [
                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                ];
                return $monthNames[$month] . ' ' . $currentYear;
            }
        }

        // If no month found in current year, return the first month of next year
        if (!empty($allowedMonths)) {
            $firstMonth = min($allowedMonths);
            $monthNames = [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ];
            return $monthNames[$firstMonth] . ' ' . ($currentYear + 1);
        }

        return null;
    }

    /**
     * Check if progress report submission is currently allowed
     */
    public static function isSubmissionAllowed(): bool
    {
        return self::isCurrentMonthAllowed();
    }

    /**
     * Get the status message for progress report submission
     */
    public static function getSubmissionStatusMessage(): string
    {
        if (self::isSubmissionAllowed()) {
            return 'Progress report submission is currently open for ' . date('F Y');
        }

        $nextMonth = self::getNextAllowedMonth();
        if ($nextMonth) {
            return 'Progress report submission will be available in ' . $nextMonth;
        }

        return 'Progress report submission is not currently available';
    }

    /**
     * Check if a progress report can be submitted for a scholar
     * 
     * @param \App\Models\Scholar $scholar
     * @return array
     */
    public static function canSubmitProgressReport(\App\Models\Scholar $scholar): array
    {
        $currentMonth = (int) date('n');
        $currentMonthName = date('F');
        $allowedMonths = self::getAllowedMonths();

        $nextMonth = $currentMonth + 1;
        if ($nextMonth > 12) {
            $nextMonth = 1;
        }

        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        $nextMonthName = $monthNames[$nextMonth];

        $canSubmit = false;
        $reportPeriod = null;

        // Check current month
        if (in_array($currentMonth, $allowedMonths)) {
            $existingReport = \App\Models\ProgressReport::where('scholar_id', $scholar->id)
                ->where('report_period', $currentMonthName)
                ->where('status', '!=', 'rejected')
                ->first();

            if (!$existingReport) {
                $canSubmit = true;
                $reportPeriod = $currentMonthName;
            }
        }

        // Check next month if current month not allowed or already submitted
        if (!$canSubmit && in_array($nextMonth, $allowedMonths)) {
            $existingReport = \App\Models\ProgressReport::where('scholar_id', $scholar->id)
                ->where('report_period', $nextMonthName)
                ->where('status', '!=', 'rejected')
                ->first();

            if (!$existingReport) {
                $canSubmit = true;
                $reportPeriod = $nextMonthName;
            }
        }

        return [
            'can_submit' => $canSubmit,
            'report_period' => $reportPeriod,
        ];
    }
}
