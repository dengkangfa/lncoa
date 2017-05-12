<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Status;
use Illuminate\Http\Request;
use App\Notifications\pendReview;
use App\Http\Requests\ApplicatRequest;
use App\Repositories\ApplicatRepository;
use App\Repositories\OpinionRepository;
use App\Transformers\ApplicatTransformer;
use App\Transformers\DateTakeUpTransformer;
use App\Services\FileManager\UploadManager;

class ApplicatController extends ApiController
{
    protected $applicat;

    protected $manager;

    public function __construct(ApplicatRepository $applicat,UploadManager $manager, OpinionRepository $opinion)
    {
        parent::__construct();

        $this->applicat = $applicat;

        $this->manager = $manager;

        $this->opinion = $opinion;
    }

    /**
     * 获取当前用户的申请
     * @return array
     */
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

    /**
     * 新增申请
     * @param  ApplicatRequest $request
     * @return json
     */
    public function store(ApplicatRequest $request)
    {
        //获取申请类型组
        $types = $request->get('type_id');
        //获取类型组最后一个类型对应的id
        $type_id = $types[count($types)-1];
        //找出第一个审核角色组
        $role = \App\Type::find($type_id)->startRole()->first();
        if(!is_null($role)){
            //将第一个审核角色组的id添加进申请表
            $request->merge(['role_id' => $role->id, 'type_id' => $type_id]);
            $applicat = $this->applicat->store($request->all());
            //找到优先级第一管理该类型申请的管理员用户组
            $users = $role->users;
        }else{
            $request->merge(['role_id' => 1, 'type_id' => $type_id]);
            $applicat = $this->applicat->store($request->all());
            $users = User::find(1);
        }
        //发送通知
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

    /**
     * 转发
     * @param  int  $id
     * @param  Request $request
     * @return
     */
    public function forward($id, Request $request)
    {
        $this->applicat->forward($id, $request->all());
        if($request->get('opinion') != ''){
          $this->opinion->forwardOpinion($id, $request->all());
        }

        return $this->noContent();
    }

    public function dateTakeUp($type_id)
    {
        $applicats = $this->applicat->getByType($type_id);

        return $this->respondWithCollection($applicats, new DateTakeUpTransformer);
    }

    /**
     * 软删除
     * @param int $id
     */
    public function softDeletes($id)
    {
        $this->applicat->destroy($id);

        return $this->noContent();
    }

    /**
     * 权限申请
     * @param  int  $id
     * @param  Request $request
     * @return json
     */
    public function cancel($id,Request $request)
    {
        $stautsName = $request->get('status');
        $status = Status::where('name',$stautsName)->first();

        $this->applicat->updateColumn($id,['status_id' => $status->id]);

        return $this->noContent();
    }

    public function approval($id)
    {
        $this->applicat->find($id);
        $status = Status::where('name','进行中')->first();

        $this->applicat->updateColumn($id,['status_id' => $status->id]);

        return $this->noContent();
    }

}
