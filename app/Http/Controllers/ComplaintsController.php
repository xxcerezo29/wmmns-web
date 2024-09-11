<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ComplaintsController extends Controller
{
    public function list (Request $request) {
        $user = Auth::user();
        if($user->hasRole('admin'))
            $complaints = Report::when($request->searchTerm, function ($query, $searchTerm) {
                return $query->where('reference_number', 'like', '%' . $searchTerm . '%')
                    ->orWhere('report_type', 'like', '%' . $searchTerm . '%')
                    ->orWhere('barangay', 'like', '%' . $searchTerm . '%');
            })->with('resident')->with('schedule')->paginate(10)->withQueryString();
        else
            $complaints = Report::when($request->searchTerm, function ($query, $searchTerm) {
                return $query->where('reference_number', 'like', '%' . $searchTerm . '%')
                    ->orWhere('report_type', 'like', '%' . $searchTerm . '%')
                    ->orWhere('barangay', 'like', '%' . $searchTerm . '%');
            })->where('barangay', $user->barangay)->with('resident')->with('schedule')->paginate(10)->withQueryString();

        return Inertia::render('Complaints/List', [
            'complaints' => $complaints
        ]);
    }

    public function view ($reference_number){
        $complaint = Report::with('resident')->with('schedule')->where('reference_number', $reference_number)->firstOrFail();

        return Inertia::render('Complaints/View', [
            'complaint' => $complaint
        ]);
    }

    public function update_status(Request $request, $reference_number)
    {
        DB::beginTransaction();

        $request->validate([
            'status' => ['required']
        ]);

        $complaint = Report::where('reference_number', $reference_number)->firstOrFail();

        try{
            if($request->status === 'resolved'){
                $complaint->resolved_at = today();
            }else if($request->status === 'pending' &&  $complaint->status === 'resolved'){
                $complaint->resolved_at = '';
            }

            $complaint->status = $request->status;

            DB::commit();
            return back()->with(['message' => 'Complaint Reviewed.', 'status'=> 'success']);
        }catch(Exception $e)
        {
            DB::rollBack();
            return back()->with(['message' => 'Complaint Updated.', 'status'=> 'error']);
        }
    }

    public function reviewed($id){
        DB::beginTransaction();
        $complaint = Report::findOrFail($id);
        try{
            $complaint->update([
                'status'=> 'reviewed'
            ]);
            DB::commit();
            return back()->with(['message' => 'Complaint Reviewed.', 'status'=> 'success']);
        }catch(Exception $e)
        {
            DB::rollBack();
           
            return back()->with(['message' => 'Complaint cant be mark as reviewed.', 'status'=> 'error']);
        }
    }

    public function resolved($id){
        DB::beginTransaction();

        $complaint = Report::findOrFail($id);
        try{

            $complaint->update([
                'status'=> 'resolved'
            ]);

            DB::commit();
            return back()->with(['message' => 'Complaint Reviewed.', 'status'=> 'success']);
        }catch(Exception $e)
        {
            DB::rollBack();
            return back()->with(['message' => 'Complaint cant be mark as resolved.', 'status'=> 'error']);
        }
    }
    public function closed($id){
        DB::beginTransaction();

        $complaint = Report::findOrFail($id);
        try{

            $complaint->update([
                'status'=> 'closed'
            ]);

            DB::commit();
            return back()->with(['message' => 'Complaint Closed.', 'status'=> 'success']);
        }catch(Exception $e)
        {
            DB::rollBack();
            return back()->with(['message' => 'Complaint cant be mark as closed.', 'status'=> 'error']);
        }
    }
}
