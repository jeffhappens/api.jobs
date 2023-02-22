<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Services\CompanyService;
use App\Http\Requests\CompanyRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CompanyService $company)
    {
        return response()->json( $company->all() );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request, CompanyService $companyService)
    {
        $company = $companyService->add( $request->all() );
        return response()->json( $company );
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logo(Request $request, CompanyService $companyService)
    {
        $request->validate([
            'filepond' => 'file|required|mimes:png,jpeg'
        ]);

        $path = $request->file('filepond')->store('logos');
        return $path;

        

        


        // if($request->file()) {
        //     $fileName = time().'_'.$request->file->getClientOriginalName();
        //     $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
        //     $fileModel->name = time().'_'.$request->file->getClientOriginalName();
        //     $fileModel->file_path = '/storage/' . $filePath;
        //     $fileModel->save();

        //     return back()
        //     ->with('success','File has been uploaded.')
        //     ->with('file', $fileName);
        // }

        
        return response()->json($request->hasFile('filepond') );

        $logo = $companyService->logo( $request->file('file') );
        return response()->json( $logo );
        
    }
}
