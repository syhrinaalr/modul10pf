<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;
use PDF;


use Illuminate\Support\Str;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Employee List';

        confirmDelete();

        return view('employee.index', compact('pageTitle'));
    }

    public function getData(Request $request)
    {
        $employees = Employee::with('position');

        if ($request->ajax()) {
            return datatables()->of($employees)
                ->addIndexColumn()
                ->addColumn('actions', function($employee) {
                    return view('employee.actions', compact('employee'));
                })
                ->toJson();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle='Create Employee';
        // $posisi = DB::select('select * from positions');
        // $positions = DB::table('positions')
        //             ->select('*')
        //             ->get();
        $positions = Position::all();
        return view('employee.create',compact('pageTitle','positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];

        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get File
        $file = $request->file('cv');

        if ($file != null) {
            $originalFilename = $file->getClientOriginalName();
            $encryptedFilename = $file->hashName();

            // Store File
            $file->store('public/files');
        }

        // ELOQUENT
        $employee = New Employee;
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;

        if ($file != null) {
            $employee->original_filename = $originalFilename;
            $employee->encrypted_filename = $encryptedFilename;
        }

        $employee->save();

        Alert::success('Added Successfully', 'Employee Data Added Successfully.');

        return redirect()->route('employees.index');

    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pageTitle = 'Employee Detail';

        $employee = Employee::find($id);
        return view('employee.show', compact('pageTitle', 'employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Employee Edit';

        $employee = Employee::find($id);
        $positions = Position::all();
        return view('employee.edit', compact('pageTitle', 'employee', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'required' => 'Attribute harus diisi',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric'
        ], $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $employee = Employee::find($id);
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;

        // Cek apakah ada file CV yang diunggah
        if ($request->hasFile('cv')) {
            // Hapus file CV lama jika ada
            if ($employee->encrypted_filename) {
                Storage::disk('public')->delete('files/' . $employee->encrypted_filename);
            }

            // Upload file CV yang baru
            $file = $request->file('cv');
            $originalFilename = $file->getClientOriginalName();
            $encryptedFilename = $file->hashName();
            $file->store('public/files');

            $employee->original_filename = $originalFilename;
            $employee->encrypted_filename = $encryptedFilename;
        }

        $employee->save();

        Alert::success('Deleted Successfully', 'Employee Data Deleted Successfully.');

        return redirect()->route('employees.index');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::find($id);

        // Hapus file CV jika ada
        if ($employee->encrypted_filename) {
            Storage::disk('public')->delete('files/' . $employee->encrypted_filename);
        }

        // Hapus data employee
        $employee->delete();

        return redirect()->route('employees.index');
    }


    public function downloadFile($employeeId)
    {
            $employee = Employee::find($employeeId);
            $encryptedFilename = 'public/files/'.$employee->encrypted_filename;
            $downloadFilename = Str::lower($employee->firstname.'_'.$employee->lastname.'_cv.pdf');

            if(Storage::exists($encryptedFilename)) {
                return Storage::download($encryptedFilename, $downloadFilename);
        }
    }

    public function exportExcel()
    {
        return Excel::download(new EmployeesExport, 'Employees.xlsx');
    }


    public function exportPdf()
    {
        $employees = Employee::all();

        $pdf = PDF::loadView('employee.export_pdf', compact('employees'));

        return $pdf->download('Employees.pdf');
    }


}
