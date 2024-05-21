<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Loaner;
use App\Models\LoanType;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loaners = Loaner::all();
        $loan_types = LoanType::all();

        return view('simulasipinjaman', compact('loaners', 'loan_types'));
    }

    public function submitLoan(Request $request)
    {
        $request->validate([
            'loaner_id' => 'required|exists:loaners,id',
            'loan_type_id' => 'required|exists:loan_types,id',
            'amount' => 'required|numeric|min:0',
            'term' => 'required|numeric|min:1',
            'loan_start_date' => 'required|date',
        ]);

        $loan = new Loan();
        $loan->loaner_id = $request->loaner_id;
        $loan->loan_type_id = $request->loan_type_id;
        $loan->amount = $request->amount;
        $loan->term = $request->term;
        $loan->loan_start_date = $request->loan_start_date;
        $loan->save();

        return redirect()->back()->with('success', 'Pinjaman berhasil diajukan!');
    }
}
