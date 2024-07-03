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

        // return
        $fees = $request->selected_fees;

        // return
        $total = array_sum(array_column($fees, 'amount'));

        $deposit = $request->deposit;

        $due = $total - $deposit;

        $admission = Admission::query()
            // ->with('academic_session')
            ->where([
                'academic_class_id' => $academic_class->id,
                'student_id'        => $student->id,
            ])
            ->firstOrFail();

        $payment = Payment::create([
            'admission_id'  => $admission->id,
            'user_id'       => auth()->id(),
            'date'          => date('Y-m-d'),
            'total'         => $total,
            'paid'          => $deposit,
            'due'           => $due,
        ]);

        if($due && $due != 0) {
            $student->update([
                'account' => $student->account - $due
            ]);
        }

        $data = [];

        foreach($fees as $fee) {
            $data[] = [
                'payment_id'    => $payment->id,
                'period'        => $fee['period'],
                'fee_id'        => $fee['fee_id'],
                'month'         => $fee['month'],
                'title'         => $fee['name'],
                'amount'        => $fee['amount'],
            ];
        }

        PaymentDetail::insert($data);

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
