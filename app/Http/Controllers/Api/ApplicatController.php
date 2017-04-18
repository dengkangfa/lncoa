<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use App\Notifications\pendReview;
use App\Http\Requests\ApplicatRequest;
use App\Repositories\ApplicatRepository;
use App\Transformers\ApplicatTransformer;
use App\Services\FileManager\UploadManager;

class ApplicatController extends ApiController
{
    protected $applicat;

    protected $manager;

    public function __construct(ApplicatRepository $applicat,UploadManager $manager)
    {
        parent::__construct();

        $this->applicat = $applicat;

        $this->manager = $manager;
    }

    public function me()
    {
        return $this->respondWithPaginator($this->applicat->getByUserId(Auth::id()), new ApplicatTransformer);
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(Request $request)
    {
      if($request->has('pageSize')){
        return $this->respondWithPaginator($this->applicat->page($request->pageSize), new ApplicatTransformer);
      }
        return $this->respondWithPaginator($this->applicat->page(), new ApplicatTransformer);
    }

    public function show($id)
    {
        return $this->respondWithItem($this->applicat->getById($id), new ApplicatTransformer);
    }

    public function store(ApplicatRequest $request)
    {
        $applicat = $this->applicat->store($request->all());
        $users = \App\Type::find($applicat->type_id)->startRole()->first()->users;
        \Log::info($users);
        \Notification::send($users, new pendReview($applicat));
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
