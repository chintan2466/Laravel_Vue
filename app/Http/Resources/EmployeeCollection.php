<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmployeeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'data' => $this->collection->transform(function($employee)
            {
                return [
                    'id' => $employee->id,
                    'firstname' => $employee->firstname,
                    'lastname' => $employee->lastname,
                    'email' => $employee->email,
                    'phone' => $employee->phone,
                    'company_id' => $employee->company_id,
                ];
            })
        ];
    }
}
