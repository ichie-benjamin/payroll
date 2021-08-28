<?php

namespace App\Http\Controllers;

use App\Payroll;
use App\Employee;
use App\PayrollItem;
use App\Role;
use Session;
use Paginate;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id){
        $employee = Employee::findOrFail($id);
        $banks = [
            'Access Bank Plc',
'Citibank Nigeria Limited ',
'Ecobank Nigeria Plc ',
'Fidelity Bank Plc ',
'FIRST BANK NIGERIA LIMITED ',
'First City Monument Bank Plc ',
'Globus Bank Limited ',
'Guaranty Trust Bank Plc ',
'Heritage Banking Company Ltd.',
        'Key Stone Bank ',
'Polaris Bank ',
'Providus Bank',
        'Stanbic IBTC Bank Ltd.',
     	'Standard Chartered Bank Nigeria Ltd.',
    'Sterling Bank Plc ',
'SunTrust Bank Nigeria Limited ',
'Titan Trust Bank Ltd ',
'Union Bank of Nigeria Plc ',
'United Bank For Africa Plc',
                      'Unity Bank Plc ',
'Wema Bank Plc ',
'Zenith Bank Plc ',
        ];
		return view('payroll.create')->with(['employee' => $employee,'banks' => $banks]);
    }
    public function payments($id){
        $payroll = Payroll::findOrFail($id);
        $employee = Employee::findOrFail($payroll->employee_id);
		return view('payroll.payments')->with(['payroll' => $payroll, 'employee' => $employee]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id){
        $this->validate($request,[
            'bank'=> 'required',
            'account_name'=>'required',
            'account_no'=>'required',
        ]);

        $payroll = Payroll::create([
            'bank' => $request->bank,
            'account_name' => $request->account_name,
            'account_no' => $request->account_no,
            'employee_id' => $id

        ]);
		
		$payroll->grossPay();
		$payroll->save();
		
		Session::flash('success', 'Payroll Created');
		return redirect()->route('payrolls.show',['id'=>$id]);	
    }

    public function paymentStore(Request $request, $id){

	   $this->validate($request,[
			'title'=> 'required',
			'amount'=>'required',
			'type' => 'required'
		]);

	    $payroll = PayrollItem::create([
			'title' => $request->title,
			'amount' => $request->amount,
			'type' => $request->type,
			'payroll_id' => $id
		]);
		Session::flash('success', 'Payroll Payment Added');
		return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function payrollIndex($id){
		$employee = Employee::findOrFail($id);
        return view('payroll.payroll')->with('employee',$employee);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $payroll = Payroll::findOrFail($id);
        $banks = [
            'Access Bank Plc',
            'Citibank Nigeria Limited ',
            'Ecobank Nigeria Plc ',
            'Fidelity Bank Plc ',
            'FIRST BANK NIGERIA LIMITED ',
            'First City Monument Bank Plc ',
            'Globus Bank Limited ',
            'Guaranty Trust Bank Plc ',
            'Heritage Banking Company Ltd.',
            'Key Stone Bank ',
            'Polaris Bank ',
            'Providus Bank',
            'Stanbic IBTC Bank Ltd.',
            'Standard Chartered Bank Nigeria Ltd.',
            'Sterling Bank Plc ',
            'SunTrust Bank Nigeria Limited ',
            'Titan Trust Bank Ltd ',
            'Union Bank of Nigeria Plc ',
            'United Bank For Africa Plc',
            'Unity Bank Plc ',
            'Wema Bank Plc ',
            'Zenith Bank Plc ',
        ];

        return view('payroll.edit')->with(['payroll' => $payroll, 'banks' => $banks]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function pay($id){
        $payroll = Payroll::findOrFail($id);
        $payroll->paid = 1;
        $payroll->save();
        return redirect()->back()->with('success','payment maid');
    }

    public function update(Request $request, $id)
    {

		$payroll = Payroll::findOrFail($id);
		$payroll->bank = $request->bank;
		$payroll->account_name= $request->account_name;
		$payroll->account_no = $request->account_no;
		$payroll->save();		
		
		$payroll->grossPay();
		$payroll->save();
		
		Session::flash('success', 'Payroll Updated ready for download');
		return redirect()->route('payrolls.show',['id'=>$payroll->employee_id]);			
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payroll=Payroll::findOrFail($id);
		$payroll->delete();
		
		Session::flash('success','Payroll Deleted');
		return redirect()->back();
    }
	public function bin(){
		$payrolls=Payroll::onlyTrashed()->get();
		return view('payroll.bin')->with('payrolls', $payrolls);
	}
	
	public function restore($id){
		$payroll=Payroll::withTrashed()->where('id', $id)->first();
		$payroll->restore();
		
		Session::flash('success', 'payroll row is restored.');
		return redirect()->route('employees.index');
	}
	
	public function kill($id){
		$payroll=Payroll::withTrashed()->where('id', $id)->first();		
		$payroll->forceDelete();
		
		Session::flash('success', 'payroll permanently destroyed.');
		return redirect()->route('employees.index');
	}
}
