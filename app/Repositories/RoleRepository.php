<?php

namespace App\Repositories;

use App\Role;

class RoleRepository
{
    use BaseRepository;

    protected $model;

    protected $menu;

    public function __construct(Role $role, MenuRepository $menu){
        $this->model = $role;
        $this->menu = $menu;
    }

    /**
     * Get number of the records
     *
     * @param  int $number
     * @param  string $sort
     * @param  string $sortColumn
     * @return Paginate
     */
    public function page($number = 10, $sort = 'desc', $sortColumn = 'created_at')
    {
        return $this->model
        ->orderBy($sortColumn, $sort)
        ->with('menus')
        ->paginate($number);
    }

    /**
     * Get the article record without draft scope.
     *
     * @param  int $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }


    public function update($id, $input)
    {
        $this->model = $this->getById($id);
        if(is_array($input['menus'])){
            $this->model->menus()->sync($input['menus']);
        }
        $this->model->permissions()->sync($input['permissions']);

        return $this->save($this->model, $input);
    }

}
