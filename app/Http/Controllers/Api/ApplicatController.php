<?php

namespace App\Http\Controllers\Api;

use Auth;
use Gate;
use App\User;
use App\Applicat;
use Illuminate\Http\Request;
use App\Notifications\pendReview;
use App\Http\Requests\ApplicatRequest;
use App\Repositories\ApplicatRepository;
use App\Repositories\OpinionRepository;
use App\Repositories\AppraisalRepository;
use App\Transformers\ApplicatTransformer;
use App\Transformers\AppraisalTransformer;
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
      if($request->has('condition')){
        return $this->respondWithPaginator($this->applicat->page($request->pageSize, $request->condition), new ApplicatTransformer);
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
            $request->merge(['role_id' => 1, 'type_id' => $type_id, 'stage' => Null]);
            $applicat = $this->applicat->store($request->all());
            $users = User::find(1);
        }
        //发送通知
        \Notification::send($users, new pendReview($applicat));
        return $this->noContent();
    }

    /**
   * 上传活动策划
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
        $applicat = Applicat::findOrFail($id);
        if (Gate::denies('forward', $applicat)) {
            abort(403);
        }
        $this->applicat->forward($applicat, $request->all());
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
        $applicat = Applicat::findOrFail($id);
        if (Gate::denies('updateUser', $applicat)) {
            abort(403);
        }
        // $this->applicat->destroy($id);
        $applicat->status = "已删除";
        $applicat->save();
        $applicat->delete();

        return $this->noContent();
    }

    /**
     * 取消申请
     * @param  int  $id
     * @param  Request $request
     * @return json
     */
    public function cancel($id,Request $request)
    {
        $applicat = Applicat::findOrFail($id);
        if (Gate::denies('updateUser', $applicat)) {
            abort(403);
        }
        $stautsName = $request->get('status');

        $applicat->status = $stautsName;
        return $applicat->save();
    }

    /**
     * 审批
     * @param  int $id
     * @return json
     */
    public function approval($id)
    {
        $this->applicat->updateColumn($id,['status' => '进行中']);

        return $this->noContent();
    }

    /**
     * 评价
     * @param  int              $id
     * @param  Request             $request
     * @param  AppraisalRepository $appraisal
     * @return
     */
    public function appraisal($id, Request $request,AppraisalRepository $appraisal)
    {
        $applicat = Applicat::findOrFail($id);
        if (Gate::denies('appraisal', $applicat)) {
            abort(403);
        }
        $data = array_merge(
          $request->all(),[
          'user_id' => \Auth::id(),
          'applicat_id' => $id
        ]);
        //新增评论
        $model = $appraisal->store($data);
        //添加成功则修改申请状态
        $applicat->status = '已结束';
        $applicat->save();

        return $this->respondWithItem($model,new AppraisalTransformer);
    }

    /**
     * [end description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function end($id)
    {
        $applicat = Applicat::findOrFail($id);
        if (Gate::denies('end', $applicat)) {
            abort(403);
        }
        $applicat->status = '待评价';
        return $applicat->save();
    }

}
