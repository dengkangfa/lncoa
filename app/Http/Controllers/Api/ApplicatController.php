<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ApplicatRequest;
use App\Repositories\ApplicatRepository;
use App\Services\FileManager\UploadManager;

class ApplicatController extends ApiController
{
    protected $applicat;

    protected $manager;

    public function __construct(ApplicatRepository $applicat,UploadManager $manager)
    {
        $this->applicat = $applicat;
        $this->manager = $manager;
    }

    public function store(ApplicatRequest $request)
    {
        $this->applicat->store($request->all());
        return $this->noContent();
    }

    /**
   * Upload the avatar.
   *
   * @param Request $request
   * @return mixed
   */
    public function upload(Request $request)
    {
        $file = $request->file('file');

        $validator = \Validator::make([ 'file' => $file ],
         [ 'file' => 'mimetypes:application/pdf,application/msword' ]);

        if($validator->fails()) {
            return response()->json([
                    'success' => false,
                    'errors'  => $validator->getMessageBag()->toArray()
                ]);
        }

        $path = 'proposal/' . Auth::user()->id;

        $result = $this->manager->store($file, $path);

        return response()->json($result);
    }

}
