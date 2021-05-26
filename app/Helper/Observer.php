<?php


namespace App\Helper;


use Core\Model;

class Observer
{
    /**
     * Handle the User "created" event.
     *
     * @param Model $model
     * @return void
     */
    public function created(Model $model)
    {
        Event::listen(get_class($model) . ".created", $model);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param Model $model
     * @return void
     */
    public function updated(Model $model)
    {
        Event::listen(get_class($model) . ".updated", $model);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param Model $model
     * @return void
     */
    public function deleted(Model $model)
    {
        Event::listen(get_class($model) . ".deleted", $model);
    }

    /**
     * Handle the User "forceDeleted" event.
     *
     * @param Model $model
     * @return void
     */
    public function forceDeleted(Model $model)
    {
        Event::listen(get_class($model). ".forceDeleted", $model);
    }
}