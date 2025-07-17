<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\User;
use App\Models\StudentAddFeesModel;
use App\Models\SettingModel;
use Stripe\Stripe;
use App\Exports\ExportCollectFees;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class FeesCollectionController extends Controller
{

    public function collect_fees(Request $request)
    {
        $data['getClass'] = ClassModel::getClass();

        if (!empty($request->all())) {
            $data['getRecord'] = User::getCollectFeesStudent();
        }

        $data['header_title'] = "Collect Fees";
        return view('admin.fees_collection.collect_fees', $data);
    }


    public function collect_fees_report()
    {

        $data['getClass'] = ClassModel::getClass();
        $data['getRecord'] = StudentAddFeesModel::getRecord();
        $data['header_title'] = "Collect Fees Report";
        return view('admin.fees_collection.collect_fees_report', $data);
    }

    public function export_collect_fees_report(Request $request)
    {
        return Excel::download(new ExportCollectFees, 'CollectFeesReport_' . date('d-m-Y') . '.xls');
    }

    public function collect_fees_add($student_id)
    {
        $data['getFees'] = StudentAddFeesModel::getFees($student_id);
        $getStudent = User::getSingleClass($student_id);
        $data['getStudent'] = $getStudent;
        $data['header_title'] = "Add Collect Fees";
        $data['paid_amount'] = StudentAddFeesModel::getPaidAmount($student_id, $getStudent->class_id);
        return view('admin.fees_collection.add_collect_fees', $data);
    }


    public function collect_fees_insert($student_id, Request $request)
    {
        $getStudent = User::getSingleClass($student_id);
        $paid_amount = StudentAddFeesModel::getPaidAmount($student_id, $getStudent->class_id);
        if (!empty($request->amount)) {
            $RemaningAmount = $getStudent->amount - $paid_amount;
            if ($RemaningAmount >= $request->amount) {
                $remaning_amount_user =  $RemaningAmount - $request->amount;

                $payment = new StudentAddFeesModel;
                $payment->student_id = $student_id;
                $payment->class_id = $getStudent->class_id;
                $payment->paid_amount = $request->amount;
                $payment->total_amount = $RemaningAmount;
                $payment->remaning_amount = $remaning_amount_user;
                $payment->payment_type = $request->payment_type;
                $payment->remark = $request->remark;
                $payment->created_by = Auth::user()->id;
                $payment->is_payment = 1;
                $payment->save();

                return redirect()->back()->with('success', "Fees Successfully Add");
            } else {
                return redirect()->back()->with('error', "Your amount go to greather than remaning amount");
            }
        } else {
            return redirect()->back()->with('error', "You need add your amount atleast $1");
        }
    }


    // studen side work

    // public function CollectFeesStudent(Request $request)
    // {
    //     $student_id = Auth::user()->id;

    //     $data['getFees'] = StudentAddFeesModel::getFees($student_id);

    //     $getStudent = User::getSingleClass($student_id);
    //     $data['getStudent'] = $getStudent;

    //     $data['header_title'] = "Fees Collection";

    //     $data['paid_amount'] = StudentAddFeesModel::getPaidAmount(Auth::user()->id, Auth::user()->class_id);

    //     return view('student.my_fees_collection', $data);
    // }

    public function CollectFeesStudent(Request $request)
    {
        $student = Auth::user();
        $academicYearId = session('academic_year_id');

        // Récupérer la classe pour l'année académique courante
        $class = $student->classes()
            ->wherePivot('academic_year_id', $academicYearId)
            ->first();

        if (!$class) {
            return redirect()->back()->with('error', "Aucune classe assignée pour cette année académique.");
        }

        $data['getStudent'] = $class;

        $student_id = $student->id;
        $data['getFees'] = StudentAddFeesModel::getFees($student_id);

        $data['header_title'] = "Fees Collection";

        // Récupérer la somme payée pour ce student et sa classe
        $data['paid_amount'] = StudentAddFeesModel::getPaidAmount($student_id, $class->id);

        return view('student.my_fees_collection', $data);
    }



    public function CollectFeesStudentPayment(Request $request)
    {
        $student = Auth::user();
        $academicYearId = session('academic_year_id');

        // Récupérer la classe liée à l'étudiant pour l'année académique courante
        $getStudent = $student->classes()
            ->wherePivot('academic_year_id', $academicYearId)
            ->first();

        if (!$getStudent) {
            return redirect()->back()->with('error', "Vous n'avez pas de classe assignée pour l'année académique en cours.");
        }

        // Récupérer la somme déjà payée pour cet étudiant et sa classe
        $paid_amount = StudentAddFeesModel::getPaidAmount($student->id, $getStudent->id);

        if (empty($request->amount) || $request->amount <= 0) {
            return redirect()->back()->with('error', "Veuillez saisir un montant valide d'au moins 1.");
        }

        $remainingAmount = $getStudent->amount - $paid_amount;
        if ($request->amount > $remainingAmount) {
            return redirect()->back()->with('error', "Le montant saisi dépasse le restant dû.");
        }

        $remainingAfterPayment = $remainingAmount - $request->amount;

        // Enregistrer le paiement
        $payment = new StudentAddFeesModel;
        $payment->student_id = $student->id;
        $payment->class_id = $getStudent->id;
        $payment->paid_amount = $request->amount;
        $payment->total_amount = $remainingAmount;
        $payment->remaning_amount = $remainingAfterPayment;
        $payment->payment_type = $request->payment_type;
        $payment->remark = $request->remark;
        $payment->created_by = $student->id;
        $payment->save();

        // Traitement Paypal ou Stripe
        $getSetting = SettingModel::getSingle();

        if ($request->payment_type == 'Paypal') {
            $query = [
                'business' => $getSetting->paypal_email,
                'cmd' => '_xclick',
                'item_name' => "Student Fees",
                'no_shipping' => '1',
                'item_number' => $payment->id,
                'amount' => $request->amount,
                'currency_code' => 'USD',
                'cancel_return' => url('student/paypal/payment-error'),
                'return' => url('student/paypal/payment-success'),
            ];
            $queryString = http_build_query($query);

            // Redirection vers PayPal sandbox (changer pour live en prod)
            return redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?' . $queryString);
        } elseif ($request->payment_type == 'Stripe') {
            $setPublicKey = $getSetting->stripe_key;
            $setApiKey = $getSetting->stripe_secret;

            \Stripe\Stripe::setApiKey($setApiKey);
            $finalPrice = $request->amount * 100;

            $session = \Stripe\Checkout\Session::create([
                'customer_email' => $student->email,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'name' => 'Student Fees',
                    'description' => 'Student Fees',
                    'images' => [url('public/dist/img/user2-160x160.jpg')],
                    'amount' => intval($finalPrice),
                    'currency' => 'INR',
                    'quantity' => 1,
                ]],
                'success_url' => url('student/stripe/payment-success'),
                'cancel_url' => url('student/stripe/payment-error'),
            ]);

            $payment->stripe_session_id = $session['id'];
            $payment->save();

            Session::put('stripe_session_id', $session['id']);

            $data = [
                'session_id' => $session['id'],
                'setPublicKey' => $setPublicKey,
            ];

            return view('stripe_charge', $data);
        } else {
            return redirect()->back()->with('error', "Méthode de paiement non valide.");
        }
    }


    public function PaymentSuccessStripe(Request $request)
    {

        $getSetting = SettingModel::getSingle();
        $setPublicKey   = $getSetting->stripe_key;
        $setApiKey      = $getSetting->stripe_secret;

        $trans_id = Session::get('stripe_session_id');
        $getFee = StudentAddFeesModel::where('stripe_session_id', '=', $trans_id)->first();

        \Stripe\Stripe::setApiKey($setApiKey);
        $getdata = \Stripe\Checkout\Session::retrieve($trans_id);


        if (!empty($getdata->id) && $getdata->id == $trans_id && !empty($getFee) && $getdata->status == 'complete' && $getdata->payment_status == 'paid') {
            $getFee->is_payment = 1;
            $getFee->payment_data = json_encode($getdata);
            $getFee->save();

            Session::forget('stripe_session_id');

            return redirect('student/fees_collection')->with('success', "Your Payment Successfully");
        } else {
            return redirect('student/fees_collection')->with('error', "Due to some error please try again");
        }
    }

    public function PaymentError()
    {
        return redirect('student/fees_collection')->with('error', "Due to some error please try again");
    }


    public function PaymentSuccess(Request $request)
    {
        if (!empty($request->item_number) && !empty($request->st) && $request->st == 'Completed') {
            $fees = StudentAddFeesModel::getSingle($request->item_number);
            if (!empty($fees)) {
                $fees->is_payment = 1;
                $fees->payment_data = json_encode($request->all());
                $fees->save();

                return redirect('student/fees_collection')->with('success', "Your Payment Successfully");
            } else {
                return redirect('student/fees_collection')->with('error', "Due to some error please try again");
            }
        } else {
            return redirect('student/fees_collection')->with('error', "Due to some error please try again");
        }
    }


    // parent side work

    public function CollectFeesStudentParent($student_id, Request $request)
    {
        $data['getFees'] = StudentAddFeesModel::getFees($student_id);

        $getStudent = User::getSingleClass($student_id);
        $data['getStudent'] = $getStudent;

        $data['header_title'] = "Fees Collection";

        $data['paid_amount'] = StudentAddFeesModel::getPaidAmount($student_id, $getStudent->class_id);

        return view('parent.my_fees_collection', $data);
    }


    public function CollectFeesStudentPaymentParent($student_id, Request $request)
    {
        $getStudent = User::getSingleClass($student_id);

        $paid_amount = StudentAddFeesModel::getPaidAmount($student_id, $getStudent->class_id);

        if (!empty($request->amount)) {
            $RemaningAmount = $getStudent->amount - $paid_amount;
            if ($RemaningAmount >= $request->amount) {
                $remaning_amount_user =  $RemaningAmount - $request->amount;

                $payment = new StudentAddFeesModel;
                $payment->student_id   = $getStudent->id;
                $payment->class_id     = $getStudent->class_id;
                $payment->paid_amount  = $request->amount;
                $payment->total_amount = $RemaningAmount;
                $payment->remaning_amount = $remaning_amount_user;
                $payment->payment_type = $request->payment_type;
                $payment->remark = $request->remark;
                $payment->created_by = Auth::user()->id;
                $payment->save();

                $getSetting = SettingModel::getSingle();

                if ($request->payment_type == 'Paypal') {
                    $query = array();
                    $query['business']      = $getSetting->paypal_email;
                    $query['cmd']           = '_xclick';
                    $query['item_name']     = "Student Fees";
                    $query['no_shipping']   = '1';
                    $query['item_number']   = $payment->id;
                    $query['amount']        = $request->amount;
                    $query['currency_code'] = 'USD';
                    $query['cancel_return'] = url('parent/paypal/payment-error/' . $student_id);
                    $query['return']        = url('parent/paypal/payment-success/' . $student_id);

                    $query_string = http_build_query($query);

                    // header('Location: https://www.paypal.com/cgi-bin/webscr?' . $query_string);
                    header('Location: https://www.sandbox.paypal.com/cgi-bin/webscr?' . $query_string);
                    exit();
                } else if ($request->payment_type == 'Stripe') {
                    $setPublicKey   = $getSetting->stripe_key;
                    $setApiKey      = $getSetting->stripe_secret;

                    Stripe::setApiKey($setApiKey);
                    $finalprice = $request->amount * 100;

                    $session = \Stripe\Checkout\Session::create([
                        'customer_email' => Auth::user()->email,
                        'payment_method_types' => ['card'],
                        'line_items'    => [[
                            'name'      => 'Student Fees',
                            'description' => 'Student Fees',
                            'images'    => [url('public/dist/img/user2-160x160.jpg')],
                            'amount'    => intval($finalprice),
                            'currency'  => 'INR',
                            'quantity'  => 1,
                        ]],
                        'success_url' => url('parent/stripe/payment-success/' . $student_id),
                        'cancel_url' => url('parent/stripe/payment-error/' . $student_id),
                    ]);


                    $payment->stripe_session_id = $session['id'];
                    $payment->save();

                    $data['session_id']   = $session['id'];
                    Session::put('stripe_session_id', $session['id']);
                    $data['setPublicKey'] = $setPublicKey;

                    return view('stripe_charge', $data);
                }
            } else {
                return redirect()->back()->with('error', "Your amount go to greather than remaning amount");
            }
        } else {
            return redirect()->back()->with('error', "You need add your amount atleast $1");
        }
    }


    public function PaymentErrorParent($student_id)
    {
        return redirect('parent/my_student/fees_collection/' . $student_id)->with('error', "Due to some error please try again");
    }

    public function PaymentSuccessParent($student_id, Request $request)
    {
        if (!empty($request->item_number) && !empty($request->st) && $request->st == 'Completed') {
            $fees = StudentAddFeesModel::getSingle($request->item_number);
            if (!empty($fees)) {
                $fees->is_payment = 1;
                $fees->payment_data = json_encode($request->all());
                $fees->save();

                return redirect('parent/my_student/fees_collection/' . $student_id)->with('success', "Your Payment Successfully");
            } else {
                return redirect('parent/my_student/fees_collection/' . $student_id)->with('error', "Due to some error please try again");
            }
        } else {
            return redirect('parent/my_student/fees_collection/' . $student_id)->with('error', "Due to some error please try again");
        }
    }


    public function PaymentSuccessStripeParent($student_id, Request $request)
    {
        $getSetting = SettingModel::getSingle();
        $setPublicKey   = $getSetting->stripe_key;
        $setApiKey      = $getSetting->stripe_secret;

        $trans_id = Session::get('stripe_session_id');
        $getFee = StudentAddFeesModel::where('stripe_session_id', '=', $trans_id)->first();

        \Stripe\Stripe::setApiKey($setApiKey);
        $getdata = \Stripe\Checkout\Session::retrieve($trans_id);


        if (!empty($getdata->id) && $getdata->id == $trans_id && !empty($getFee) && $getdata->status == 'complete' && $getdata->payment_status == 'paid') {
            $getFee->is_payment = 1;
            $getFee->payment_data = json_encode($getdata);
            $getFee->save();

            Session::forget('stripe_session_id');

            return redirect('parent/my_student/fees_collection/' . $student_id)->with('success', "Your Payment Successfully");
        } else {
            return redirect('parent/my_student/fees_collection/' . $student_id)->with('error', "Due to some error please try again");
        }
    }
}
