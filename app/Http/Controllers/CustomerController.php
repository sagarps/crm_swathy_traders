<?php
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use App\Customer;
use App\Places;
use App\CustomerType;

class CustomerController extends Controller
{
    // Display a listing of the customers
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    // Show the form for creating a new customer
    public function create()
    {   
        $places = Places::all();
        $customer_types = CustomerType::all();
        return view('customers.create',compact('places','customer_types'));
    }

    // Store a newly created customer in storage
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_phone' => 'required|unique:customers,customer_phone',
            'office_id' => 'required|integer',
            'address' => 'required',
            'alternate_phone' => 'nullable',
            'place_id' => 'required|integer',
        ]);

        $customerData = $request->all();
        $customerData['created_by'] = auth()->user()->id;

        Customer::create($customerData);

        return redirect()->route('customers.index')
                         ->with('save', 'Customer created successfully.');
    }
    
    // Display the specified customer
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    // Show the form for editing the specified customer
    public function edit($customer_id)
    {
        $places = Places::all();
        $customer=Customer::find($customer_id);
        $customer_types = CustomerType::all();
        return view('customers.edit', compact('customer','places','customer_types'));
    }

    // Update the specified customer in storage
    public function update(Request $request,$customer_id )
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_phone' => 'required|unique:customers,customer_phone,' . $customer_id,
            'office_id' => 'required|integer',
            'address' => 'required',
            'alternate_phone' => 'nullable',
            'place_id' => 'required|integer',
        ]);

        $customer = Customer::findOrFail($customer_id);
        $customer->update($request->all());

        return redirect()->route('customers.index')
                         ->with('update', 'Customer updated successfully.');
    }

    // Remove the specified customer from storage
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
                         ->with('success', 'Customer deleted successfully.');
    }

    public function addCustomerType(Request $request)
    {
        $ncustomertype = $request->get('ctype');
        $count = CustomerType::where('customer_type_name',$ncustomertype)->count();
        $data = null;
        if($count > 0) {
            $data  = 0 ;
            return $data;
        }
        else {
            $data = 1;
            $ctype = new CustomerType;
            $ctype->customer_type_name = strtoupper($ncustomertype);
            $ctype->office_id = auth()->user()->office_id;
            $ctype->created_by = auth()->user()->id;
            $ctype->save();
            return $ctype->id;
        }
        
    }
}