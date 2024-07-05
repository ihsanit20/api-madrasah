<?php

namespace App\Http\Controllers\CSM;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Admission;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Student;
use Illuminate\Http\Request;

class AcademicClassStudentPaymentController extends Controller
{
    public function index(AcademicClass $academic_class, Student $student)
    {
        $admission = Admission::query()
            // ->with('academic_session')
            ->where([
                'academic_class_id' => $academic_class->id,
                'student_id'        => $student->id,
            ])
            ->firstOrFail();

        $payments = Payment::query()
            ->where('admission_id', $admission->id)
            ->with('payment_details')
            ->get();

        return response([
            "payments" => $payments,
        ]);
    }

    public function store(Request $request, AcademicClass $academic_class, Student $student)
    {
        // return $request;

        $request->validate([
            'selected_fees'             => 'required|array',
            'selected_fees.*.fee_id'    => 'integer',
            'selected_fees.*.month'     => 'nullable|string',
            'selected_fees.*.period'    => 'required|integer',
            'selected_fees.*.name'      => 'required|string',
            'selected_fees.*.amount'    => 'required|numeric',
            'deposit'                   => 'required|numeric',
        ]);

        // return
        $fees = $request->selected_fees;

        // return
        $total_amount = array_sum(array_column($fees, 'amount'));
        $total_concession = array_sum(array_column($fees, 'concession'));

        $deposit = $request->deposit;

        $previous_due = -($student->account);
        
        $current_due = ($total_amount + $previous_due) - $deposit;

        $student_account_new_amount = ($student->account + $previous_due) - $current_due;

        $admission = Admission::query()
            // ->with('academic_session')
            ->where([
                'academic_class_id' => $academic_class->id,
                'student_id'        => $student->id,
            ])
            ->firstOrFail();

        $feeConditions = collect($fees)->map(function ($fee) use ($admission) {
            return [
                'admission_id'  => $admission->id,
                'fee_id'        => $fee['fee_id'],
                'month'         => $fee['month'],
            ];
        })->toArray();

        // return
        $check_previous_payment_details = PaymentDetail::query()
            ->where(function ($query) use ($feeConditions) {
                foreach ($feeConditions as $condition) {
                    $query->orWhere(function ($query) use ($condition) {
                        $query->where($condition);
                    });
                }
            })
            ->exists();

        if($check_previous_payment_details) {
            return response([
                "message" => "Data not valid",
            ], 422);
        }

        $payment = Payment::create([
            'admission_id'  => $admission->id,
            'user_id'       => auth()->id(),
            'date'          => date('Y-m-d'),
            'total_amount'  => $total_amount,
            'total_concession'  => $total_concession,
            'paid'          => $deposit,
            'previous_due'  => $previous_due,
            'current_due'   => $current_due,
        ]);

        $data = collect($fees)->map(function ($fee) use ($admission, $payment) {
            return [
                'payment_id'    => $payment->id,
                'admission_id'  => $admission->id,
                'fee_id'        => $fee['fee_id'],
                'month'         => $fee['month'],
                'period'        => $fee['period'],
                'title'         => $fee['name'],
                'amount'        => $fee['amount'],
                'concession'    => $fee['concession'],
            ];
        })->toArray();

        PaymentDetail::insert($data);

        if($student_account_new_amount != $student->account) {
            $student->update([
                'account' => $student_account_new_amount
            ]);
        }

        $payment->load('payment_details');

        return response([
            "payment" => $payment,
        ], 201);
    }

    public function show(AcademicClass $academic_class, Student $student, Payment $payment)
    {
        $admission = Admission::query()
            // ->with('academic_session')
            ->where([
                'academic_class_id' => $academic_class->id,
                'student_id'        => $student->id,
            ])
            ->firstOrFail();

        if($payment->admission_id != $admission->id) {
            return $this->notFound();
        }

        $payment->load('payment_details');

        return response([
            "payment" => $payment,
        ]);
    }
}
