<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\TemporaryFolder;
use App\Services\UploadService;
use App\Services\CompanyService;
use App\Http\Requests\CompanyRequest;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

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
    public function update(Request $request)
    {
        $c = $request->get('company');
        $l = $request->get('logo');

        $company = Company::find($c['id']);

        $company->name = $c['name'];
        $company->address = $c['address'];

        if(!$l) {
            $company->logo = $c['logo'];
        } else {

            $explodedPath = explode('/', $l);
            $folder = $explodedPath[1];
            
            $tempFile = TemporaryFolder::where('folder', $folder)->first();

            // mv the file to permanent disk
            Storage::disk('public')
                ->writeStream(
                    'logos/'.$tempFile->folder.'/'.$tempFile->file,
                    Storage::disk('public')
                        ->readStream('tmp/'.$tempFile->folder.'/'.$tempFile->file)
                );

            Storage::disk('public')->deleteDirectory('tmp/'.$folder);
            // return $tempFile;
            
            $company->logo = 'logos/'.$tempFile->folder.'/'.$tempFile->file;
        }
        
        
        $company->industry_id = $c['industry_id'];
        $company->user_id = $c['user_id'];
        $company->save();

        return $company;
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
     * Store a newly created logo resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logo(Request $request, CompanyService $companyService)
    {

        $request->validate([
            'filepond' => 'file|required|mimes:png,jpeg'
        ]);

        $file = $request->file('filepond');
        $folder = uniqid();
        $path = $file->hashName('public/tmp/'.$folder);
        
        $image = Image::make($file);

        $uploadService = new UploadService;
        $squaredImage = $uploadService->squareImage($image);
        $resizedImage = $uploadService->resizeImage($image, 250, 250);
        $encodedImage = $resizedImage->encode('jpg', 100);
        
        Storage::put($path, (string) $encodedImage);
        

        $explodedPath = explode('/', $path);

        $temporaryFolder = new TemporaryFolder;
        $temporaryFolder->folder = $explodedPath[2];
        $temporaryFolder->file = $explodedPath[3];
        $temporaryFolder->save();


        return $explodedPath[2];
    }
}
