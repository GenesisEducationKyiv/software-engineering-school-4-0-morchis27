<?php

namespace App\DTO\CreationDTO\Customer;

use App\DTO\RequestFillableDTOInterface;
use Illuminate\Http\Request;

class CustomerDTO extends AbstractCustomerDTO implements RequestFillableDTOInterface
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
