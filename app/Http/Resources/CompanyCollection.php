<?php
 
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CompanyCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function($company)
            {
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                    'email' => $company->email,
                    'logo' => $company->logo,
                    'website' => $company->website,
                ];
            })
        ];
    }
}
