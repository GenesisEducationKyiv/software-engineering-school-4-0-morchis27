<?php

namespace App\DTO;

use Illuminate\Http\Request;

class CreateSubscriberDTO extends AbstractSubscriberDTO implements RequestFillableDTOInterface
{
    /**
     * @param Request $request
     * @return void
     */
    public function fillByRequest(Request $request): void
    {
        $this->email = $request->string('email')->toString();
    }
}
