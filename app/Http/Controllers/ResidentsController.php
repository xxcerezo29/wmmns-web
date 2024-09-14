<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResidentsController extends Controller
{
    public function list(Request $request)
    {
        $residents = Resident::when($request->searchTerm, function ($query, $searchTerm) {
            return $query->where('firstname', 'like', '%' . $searchTerm . '%')
                ->orWhere('middlename', 'like', '%' . $searchTerm . '%')
                ->orWhere('lastname', 'like', '%' . $searchTerm . '%')
                ->orWhere('email', 'like', '%' . $searchTerm . '%')
                ->orWhere('barangay', 'like', '%' . $searchTerm . '%');
        })->paginate(10)->withQueryString();
        return Inertia::render('Residents/List', [
            'residents' => $residents
        ]);
    }

    public function show($id)
    {
        $resident = Resident::findOrFail($id);
        try {
            return Inertia::render('Residents/View', [
                'resident' => $resident
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function downloadPDF(){
        try{
            $residents = Resident::all();
            
            $pdf = app('dompdf.wrapper');
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->getDomPDF()->set_option("isRemoteEnabled", true);

            $pdf->loadView('pdf.residentslist', ['residents' => $residents]);

            return $pdf->download('ResidentList.pdf');

        }catch(Exception $e){
            return redirect()->back()->with(['message' => $e->getMessage(), 'status' => 'error']);
        }
    }
}
