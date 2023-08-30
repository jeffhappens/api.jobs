<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Models\TemporaryFolder;
use App\Services\CompanyService;
use App\Services\UploadService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(CompanyService $company): JsonResponse
    {
        return response()->json($company->all());
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CompanyRequest $request, CompanyService $companyService): JsonResponse
    {
        $company = $companyService->add($request->all());

        return response()->json($company);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function show($id): Collection
    {
        $companies = Company::withCount('listings')
            ->with('industry')
            ->where('author', $id)
            ->latest()
            ->get();

        return $companies;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function edit($id)
    {
        return Company::where('id', $id)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request): Company
    {
        $c = $request->get('company');
        $l = $request->get('logo');
        $company = Company::find($c['id']);
        $company->name = $c['name'];
        $company->address = $c['address'];
        $company->city = $c['city'];
        $company->state = $c['state'];
        $company->zip = $c['zip'];
        $company->email = $c['email'];
        $company->url = $c['url'];

        if (! $l) {
            $company->logo = $c['logo'];
        } else {
            $folder = $l;
            $tempFile = TemporaryFolder::where('folder', $folder)->first();

            // mv the file to permanent disk
            Storage::disk('public')
                ->writeStream(
                    'logos/'.$tempFile->folder.'/'.$tempFile->file,
                    Storage::disk('public')
                        ->readStream('tmp/'.$tempFile->folder.'/'.$tempFile->file)
                );

            Storage::disk('public')->deleteDirectory('tmp/'.$folder);
            $company->logo = 'logos/'.$tempFile->folder.'/'.$tempFile->file;
        }

        $company->industry_id = $c['industry_id'];
        $company->description = $c['description'];
        $company->author = $c['author'];
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
     * @return \Illuminate\Http\Response
     */
    public function logo(Request $request, CompanyService $companyService): JsonResponse
    {
        $request->validate([
            'filepond' => 'file|required|mimes:png,jpeg',
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

        return response()->json(['folder' => $explodedPath[2], 'filename' => $explodedPath[3]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function single(CompanyService $companyService, $uuid, $slug)
    {
        return response()->json($companyService->single($uuid, $slug));
    }
}
