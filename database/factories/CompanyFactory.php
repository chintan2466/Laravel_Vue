<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Company::class;

    public function definition()
    {
        return [
            //
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            // 'logo' => Str::random(10),
            'logo' => '',
            // 'logo' => $this->faker->image(storage_path('app/public'), 400, 300, null, false),
            'website'=> 'www.'.strtolower(Str::random(10)).'.com',
        ];
    }
}
