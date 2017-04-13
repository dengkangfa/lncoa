<?php

namespace App\Http\Controllers\Api;

use Auth;
use Hash;
use Image;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;
use App\Transformers\UserTransformer;
use App\Services\FileManager\UploadManager;

class UserController extends ApiController
{
    protected $user;

    protected $manager;

    public function __construct(UserRepository $user, UploadManager $manager)
    {
        parent::__construct();

        $this->user = $user;

        $this->manager = $manager;
    }

    /**
     * 获取当前登录用户的信息
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function me(Request $request)
    {
        if(isset($_GET['login'])) {
            $login = [];
            $login['ip'] = $request->getClientIp();
            $login['create_at'] = time();
            \Redis::lpush('loginlogs', serialize($login));
        }
        return $this->respondWithItem(Auth::user(), new UserTransformer);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('pageSize')){
          return $this->respondWithPaginator($this->user->page($request->pageSize), new UserTransformer);
        }
        return $this->respondWithPaginator($this->user->page(), new UserTransformer);
    }

    /**
     * Update User Status By User ID
     *
     * @param $id
     * @param Request $request
     * @return \App\User
     */
    public function status($id, Request $request)
    {
        $input = $request->all();
        if (auth()->user()->id == $id) {
            return $this->errorUnauthorized('You can\'t change status for yourself and other Administrators!');
        }

        $this->user->update($id, $input);

        return $this->noContent();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $identicon = new \Identicon\Identicon();
        $data = array_merge($request->all(), [
            // 'confirm_code' => str_random(64),
            'avatar' => $identicon->getImageDataUri($request->name,80),
        ]);

        $this->user->store($data);

        return $this->noContent();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->respondWithItem($this->user->getById($id), new UserTransformer);
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
        $this->user->update($id, $request->all());

        return $this->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $isAdmin = \App\User::find($id)->hasRole('admin');
        \Log::info($isAdmin);
        if (Auth::id() == $id || $isAdmin = 1) {
            // return $this->errorForbidden('You can\'t delete for yourself and other Administrators!');
            return $this->errorForbidden(trans('notification.delete_user_error'));
        }

        $this->user->destroy($id);

        return $this->noContent();
    }

    /**
   * Upload the avatar.
   *
   * @param Request $request
   * @return mixed
   */
    public function avatar(Request $request)
    {
        $file = $request->file('file');

        $validator = \Validator::make([ 'file' => $file ], [ 'file' => 'image' ]);

        if($validator->fails()) {
            return response()->json([
                    'success' => false,
                    'errors'  => $validator->getMessageBag()->toArray()
                ]);
        }

        $path = 'avatars/' . Auth::user()->id;

        $result = $this->manager->store($file, $path);

        return response()->json($result);
    }

    /**
     * Crop Avatar
     *
     * @param  Request $request
     * @return array
     */
    public function cropAvatar(Request $request)
    {
        $currentImage = $request->get('image');
        $data = $request->get('data');

        $image = Image::make($currentImage['relative_url']);

        $image->crop((int) $data['width'], (int) $data['height'], (int) $data['x'], (int) $data['y']);

        $image->save($currentImage['relative_url']);

        $this->user->saveAvatar(Auth::user()->id, $currentImage['url']);

        return response()->json($currentImage);
    }

    public function changePassword(Request $request)
    {

        $this->validate($request, [
          'old_password' => 'required',
          'password_confirmation' => 'required',
          'password' => 'required|max:16|min:6|confirmed',
        ]);
        if (! Hash::check($request->get('old_password'), Auth::user()->password)) {
          // return response()->json(['old_password' => ['The password must be the same of current password.']], 422);
          return response()->json(['old_password' => [trans('message.Inconsistent')]], 422);
        }
        $this->user->changePassword(Auth::user(), $request->password);
    }

    // /**
    //  * 新注册用户激活操作
    //  * @param  [type] $token [description]
    //  * @return [type]        [description]
    //  */
    // public function confirmEmail($token){
    //   $user = User::where('activation_token',$token)->firstOrFail();
    //   return 1;
    //   if(isset($user)){
    //     response()->json([
    //             'success' => false,
    //             'errors'  => 'token无效'
    //         ]);
    //   }
    //   $user->status = 0;
    //   $user->activation_token = null;
    //   $user->save();
    //
    //   response()->json([
    //           'success' => false,
    //           // 'user'  => ['email' => ]
    //       ]);
    //
    //   // $this->login($user);
    //   // return redirect()->route('users.show',[$user]);
    // }

    // protected function login($user)
    // {
    //     $url = route('login',[
    //       'grant_type' => 'password',
    //       'client_id' => '2',
    //       'client_secret' => 'OkABZOuxDMaiaaFJBrESpYnmIMf6eSwyU42fPVdM',
    //       'name' => $user->email,
    //       'password' => $user->password,
    //     ]);
    //     $request = Request::create(route($url, 'post'));
    //     return $request;
    //     echo "<script>localStorage.access_token = vm.$store.state.access_token </script>";
    // }

}
