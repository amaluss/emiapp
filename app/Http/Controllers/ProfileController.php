<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\LoanDetail;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    function loandetails() {

         $data = LoanDetail::all();
        //  echo '<pre>';print_r($data);exit;
        return view('loan.loandetails', [
            'data' => $data,
        ]);
    }

    function processdata() {
        return view('loan.processdata', [
            'data' => [],
        ]);
    }

    public function processLoanData()
            {

                // DB::beginTransaction();

                try {

                    $minDate = DB::table('loan_details')->min('first_payment_date');
                    $maxDate = DB::table('loan_details')->max('last_payment_date');


                    $startDate = \Carbon\Carbon::parse($minDate);
                    $endDate = \Carbon\Carbon::parse($maxDate);


                    $columns = [];
                    while ($startDate->lte($endDate)) {
                        $yearMonth = $startDate->format('Y') . '_' . $startDate->format('M');
                        $columns[] = $yearMonth;
                        $startDate->addMonth();
                    }

                    // echo '<pre>';print_r($columns);exit;

                    DB::statement('DROP TABLE IF EXISTS emi_details');
                    DB::statement('CREATE TABLE emi_details (clientid INT)');


                    foreach ($columns as $month) {
                        DB::statement("ALTER TABLE emi_details ADD COLUMN `$month` DECIMAL(10,2) DEFAULT 0");
                    }

                    $loanDetails = DB::table('loan_details')->get();


                    foreach ($loanDetails as $loan) {
                        $clientId = $loan->clientid;
                        $loanAmount = $loan->loan_amount;
                        $numOfPayments = $loan->num_of_payment;

                        $emiAmount = $loanAmount / $numOfPayments;

                        $firstPaymentDate = Carbon::parse($loan->first_payment_date);

                        for ($i = 0; $i < $numOfPayments; $i++) {

                            $currentPaymentMonth = $firstPaymentDate->copy()->addMonths($i);
                            $monthColumn = $currentPaymentMonth->format('Y_M');

                            if (in_array($monthColumn, $columns)) {

                                DB::table('emi_details')->updateOrInsert(
                                    ['clientid' => $clientId],
                                    [$monthColumn => DB::raw("IFNULL($monthColumn, 0) + $emiAmount")]
                                );
                            }
                        }
                    }

                    // DB::commit();

                    $emiDetails = DB::table('emi_details')->get();

                    return response()->json([
                        'message' => 'EMI details processed successfully!',
                        'data' => $emiDetails,
                    ]);
                    }
                    catch (\Exception $e)
                    {

                        Log::error('Error processing data: ' . $e->getMessage());
                        if (DB::transactionLevel() > 0) {
                            DB::rollBack();
                        }
                        return response()->json(['message' => 'Failed to process data.'], 500);
                    }
        }
}
