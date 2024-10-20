<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Persons;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
class ReportsController extends Controller
{
    public function OfwList()
    { 
        
        try {
            $persons = Persons::with('user:id,email,personID','employmentDetails:id,personID,coe_attachment','address.barangay', 'address.region', 'address.province', 'address.municipality')
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
}
