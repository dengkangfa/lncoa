<?php

namespace App\Repositories;

use Auth;
use App\User;
use App\Scopes\StatusScope;

class UserRepository
{
    use BaseRepository;

    /**
     * User Model
     *
     * @var User
     */
    protected $model;

    /**
     * Constructor
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Get the list of all the user without myself.
     *
     * @return mixed
     */
    public function getList()
    {
        return $this->model
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Get the user by name.
     *
     * @param  string $name
     * @return mixed
     */
    public function getByName($name)
    {
        return $this->model
                    ->where('name', $name)
                    ->first();
    }

    /**
     * Get number of the records
     *
     * @param  int $number
     * @param  string $sort
     * @param  string $sortColumn
     * @return Paginate
     */
    public function page($option = [],
      $created_at = ['1970-01-01 00:00:01','2038-01-09 03:14:07'],
      $number = 10,
      $sort = 'desc',
      $sortColumn = 'created_at'
    ){
        $users = $this->model
        ->withoutGlobalScope(StatusScope::class)
        ->where($option)
        ->when($created_at, function ($query) use ($created_at) {
            return $query->whereBetween('created_at', $created_at);
        })
        ->orderBy($sortColumn, $sort)
        ->paginate($number);
        return $users;
    }

    /**
     * Get the article record without draft scope.
     *
     * @param  int $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
    }

    /**
     * Update the article record without draft scope.
     *
     * @param  int $id
     * @param  array $input
     * @return boolean
     */
    public function update($id, $input)
    {
        $this->model = $this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        // if(isset($input['roles'])){
        //   $this->model->roles()->sync($input['roles']);
        // }
        if (isset($_GET['include']) && $_GET['include'] == 'roles') {
             $this->model->roles()->sync($input['roles']);
        }
        return $this->save($this->model, $input);
    }


    /**
     * Change the user password.
     *
     * @param  App\User $user
     * @param  string $password
     * @return boolean
     */
    public function changePassword($user, $password)
    {
        return $user->update(['password' => $password]);
    }

    /**
     * Save the user avatar path.
     *
     * @param  int $id
     * @param  string $photo
     * @return boolean
     */
    public function saveAvatar($id, $photo)
    {
        $user = $this->getById($id);

        $user->avatar = $photo;

        return $user->save();
    }

    /**
     * Delete the draft article.
     *
     * @param int $id
     * @return boolean
     */
    public function destroy($id)
    {
        return $this->getById($id)->delete();
    }

    // /**
    //  * 获取用户的角色
    //  *
    //  * @param  App\User $user
    //  * @return mixed
    //  */
    // public function getRole($user)
    // {
    //     $roles = $user->roles()->get(['display_name']);
    //     $array = array_pluck($roles,'display_name');
    //     return $array;
    // }
}
