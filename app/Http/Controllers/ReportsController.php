<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\JobApplicants;
use App\Models\JobPost;
use App\Models\Persons;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    public function OfwList()
    {

        try {
            $persons = Persons::with('user:id,email,personID', 'employmentDetails:id,personID,coe_attachment', 'address.barangay', 'address.region', 'address.province', 'address.municipality')
                ->get()
                ->map(function ($person) {
                    return array_merge(
                        $person->toArray(),
                        [
                            'email' => $person->user->email ?? null,
                            'userID' => $person->user->id ?? null,
                            'barangayName' => $person->address->barangay->name ?? null,
                            'regionName' => $person->address->region->name ?? null,
                            'provinceName' => $person->address->province->name ?? null,
                            'municipalityName' => $person->address->municipality->name ?? null,
                            'coe_attachment' => $person->employmentDetails->coe_attachment ?? null, // Accessing coe_attachment
                        ]
                    );
                });

            $pdf = Pdf::loadView('Reports.Ofwlist', ['data' => $persons])
                ->setPaper('legal', 'landscape');
            return $pdf->download('file.pdf');
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }
    public function AllEmployers()
    {
        try {
            // Retrieve all employers
            $employers = Employer::all();

            $pdf = Pdf::loadView('Reports.CompanyList', ['data' => $employers])
                ->setPaper('legal', 'landscape');
            return $pdf->download('file.pdf');
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    public function GetNumberofApplicant()
    {
        try {
            // Initialize arrays for months in the current and previous year
            $months = [
                'January' => 0,
                'February' => 0,
                'March' => 0,
                'April' => 0,
                'May' => 0,
                'June' => 0,
                'July' => 0,
                'August' => 0,
                'September' => 0,
                'October' => 0,
                'November' => 0,
                'December' => 0,
            ];

            $currentYear = date('Y'); // Get the current year
            $previousYear = $currentYear - 1; // Get the previous year

            // Fetch applicant data for the current year
            $currentYearApplicants = JobApplicants::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // Populate the current year's months array
            $currentYearData = $months; // Clone for each year's data
            foreach ($currentYearApplicants as $applicant) {
                $monthName = date('F', mktime(0, 0, 0, $applicant->month, 10));
                $currentYearData[$monthName] = $applicant->total;
            }

            // Fetch applicant data for the previous year
            $previousYearApplicants = JobApplicants::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', $previousYear)
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // Populate the previous year's months array
            $previousYearData = $months; // Clone for each year's data
            foreach ($previousYearApplicants as $applicant) {
                $monthName = date('F', mktime(0, 0, 0, $applicant->month, 10));
                $previousYearData[$monthName] = $applicant->total;
            }

            // Structure the data to return both years
            $data = [
                'currentYear' => $currentYearData,
                'previousYear' => $previousYearData,
            ];

            return $this->sendSuccess($data, 'Job applicants for the current and previous year fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }
    public function GetNumberofCompany()
    {
        try {
            // Initialize arrays for months in the current and previous year
            $months = [
                'January' => 0,
                'February' => 0,
                'March' => 0,
                'April' => 0,
                'May' => 0,
                'June' => 0,
                'July' => 0,
                'August' => 0,
                'September' => 0,
                'October' => 0,
                'November' => 0,
                'December' => 0,
            ];

            $currentYear = date('Y'); // Get the current year
            $previousYear = $currentYear - 1; // Get the previous year

            // Fetch company data for the current year
            $currentYearCompanies = Employer::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', $currentYear)
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // Populate the current year's months array
            $currentYearData = $months; // Clone for each year's data
            foreach ($currentYearCompanies as $company) {
                $monthName = date('F', mktime(0, 0, 0, $company->month, 10));
                $currentYearData[$monthName] = $company->total;
            }

            // Fetch company data for the previous year
            $previousYearCompanies = Employer::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', $previousYear)
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // Populate the previous year's months array
            $previousYearData = $months; // Clone for each year's data
            foreach ($previousYearCompanies as $company) {
                $monthName = date('F', mktime(0, 0, 0, $company->month, 10));
                $previousYearData[$monthName] = $company->total;
            }

            // Structure the data to return both years
            $data = [
                'currentYear' => $currentYearData,
                'previousYear' => $previousYearData,
            ];

            return $this->sendSuccess($data, 'Number of Companies for the current and previous year fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }
    public function GetNumberofOFW()
    {
        try {
            // Initialize arrays for months in the current and previous year
            $months = [
                'January' => 0,
                'February' => 0,
                'March' => 0,
                'April' => 0,
                'May' => 0,
                'June' => 0,
                'July' => 0,
                'August' => 0,
                'September' => 0,
                'October' => 0,
                'November' => 0,
                'December' => 0,
            ];

            $currentYear = date('Y'); // Get the current year
            $previousYear = $currentYear - 1; // Get the previous year

            // Fetch OFW data for the current year
            $currentYearOFW = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', $currentYear) // Filter by the current year
                ->where('user_type', 2)
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // Populate the current year's months array
            $currentYearData = $months; // Clone for each year's data
            foreach ($currentYearOFW as $ofw) {
                $monthName = date('F', mktime(0, 0, 0, $ofw->month, 10));
                $currentYearData[$monthName] = $ofw->total;
            }

            // Fetch OFW data for the previous year
            $previousYearOFW = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                ->whereYear('created_at', $previousYear) // Filter by the previous year
                ->where('user_type', 2)
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();

            // Populate the previous year's months array
            $previousYearData = $months; // Clone for each year's data
            foreach ($previousYearOFW as $ofw) {
                $monthName = date('F', mktime(0, 0, 0, $ofw->month, 10));
                $previousYearData[$monthName] = $ofw->total;
            }

            // Structure the data to return both years
            $data = [
                'currentYear' => $currentYearData,
                'previousYear' => $previousYearData,
            ];

            return $this->sendSuccess($data, 'OFW for the current and previous year fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }
    public function GetTopPaidJobs()
    {
        try {
            $currentYear = now()->year;
            $previousYear = $currentYear - 1;
    
            // Fetch current year's job posts with their job categories
            $currentYearData = JobPost::with('jobCategory')
                ->whereYear('created_at', $currentYear)
                ->get()
                ->groupBy('jobCategory.id')
                ->map(function ($posts, $categoryId) {
                    $categoryName = $posts->first()->jobCategory->name;
    
                    $highestBaseSalary = $posts->map(function ($post) {
                        $salaryRange = explode('-', $post->base_salary_value);
                        return isset($salaryRange[1]) ? (int)str_replace(',', '', trim($salaryRange[1])) : 0;
                    })->max();
    
                    return [
                        'name' => $categoryName,
                        'highest_base_salary' => $highestBaseSalary,
                    ];
                })->values();
    
            // Fetch previous year's job posts with their job categories
            $previousYearData = JobPost::with('jobCategory')
                ->whereYear('created_at', $previousYear)
                ->get()
                ->groupBy('jobCategory.id')
                ->map(function ($posts, $categoryId) {
                    $categoryName = $posts->first()->jobCategory->name;
    
                    $highestBaseSalary = $posts->map(function ($post) {
                        $salaryRange = explode('-', $post->base_salary_value);
                        return isset($salaryRange[1]) ? (int)str_replace(',', '', trim($salaryRange[1])) : 0;
                    })->max();
    
                    return [
                        'name' => $categoryName,
                        'highest_base_salary' => $highestBaseSalary,
                    ];
                })->values();
    
            $response = [
                'currentYear' => $currentYearData,
                'previousYear' => $previousYearData,
            ];
    
            return $this->sendSuccess($response, 'Top paid jobs grouped by category for current and previous years fetched successfully', 200);
        } catch (\Throwable $th) {
            return $this->sendError('unexpectedError', $th, 500);
        }
    }

    public function GetCompanyHighHired() {
        $currentYear = now()->year;
        $previousYear = $currentYear - 1;

        $jobPostsCurr = JobPost::has('applicants', '>', 0)
            ->whereHas('applicants', function($query) {
                $query->where('status', 'hired');
            })
            ->whereYear('created_at', $currentYear)
            ->selectRaw('hiring_organization_name, COUNT(*) as total') // Select organization name and count
            ->groupBy('hiring_organization_name') // Group by organization name
            ->get();
    
        // Format the result into an array
        $currentYearData = $jobPostsCurr->map(function($jobPost) {
            return [
                'hiring_organization_name' => $jobPost->hiring_organization_name,
                'total' => $jobPost->total,
            ];
        });

        $jobPostsCurr = JobPost::has('applicants', '>', 0)
            ->whereHas('applicants', function($query) {
                $query->where('status', 'hired');
            })
            ->whereYear('created_at', $previousYear)
            ->selectRaw('hiring_organization_name, COUNT(*) as total') // Select organization name and count
            ->groupBy('hiring_organization_name') // Group by organization name
            ->get();

        // Format the result into an array
        $previousYearData = $jobPostsCurr->map(function($jobPost) {
            return [
                'hiring_organization_name' => $jobPost->hiring_organization_name,
                'total' => $jobPost->total,
            ];
        });

        $response = [
            'currentYear' => $currentYearData,
            'previousYear' => $previousYearData,
        ];

        return $this->sendSuccess($response, 'Top companies for the current and previous year fetched successfully', 200);
    }
    
    
    
}
