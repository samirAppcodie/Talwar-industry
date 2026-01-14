<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class UserInfoAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Info';
    }

    public function getIcon()
    {
        return 'fa fa-info-circle';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-info',
        ];
    }

    public function getDefaultRoute()
    {
        return route('voyager.users.info', $this->data->id);
    }

    
    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug === 'users';
    }
}
