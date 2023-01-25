<?php

namespace App\Repositories\Eloquent;

use App\City;
use App\Repositories\Interfaces\ManagerInterface;
use App\Http\Requests\ManagerStoreRequest;
use App\Http\Requests\ManagerUpdateRequest;
use App\Manager;
use App\State;
use Illuminate\Support\Arr;

class ManagerEloquent extends AbstractEloquent implements ManagerInterface {

    public function __construct() {
        parent::__construct('Manager');
    }

    public function managersWithCitiesAndStates($model)
    {
        return $model->load('cities');
    }

    public function all()
    {
        return Manager::all();
    }

    public function create(ManagerStoreRequest $request) {
        $validated = $request->validated();
        $manager = Manager::create($validated);

        if (Arr::exists($validated, 'cities')) {
            $cityIds = collect($validated['cities'])->pluck('city_id');
            $cities = City::whereIn('id', $cityIds)->get();
            $manager->cities()->saveMany($cities);
        }

        return $manager;
    }

    public function update(ManagerUpdateRequest $request, $model) {
        $validated = $request->validated();
        $model->update($validated);

        if (Arr::exists($validated, 'cities')) {
            $model->loadMissing('cities');
            $previousCities = $model->cities;
            $newCities = $validated['cities'];

            $previousCitiesIds = $previousCities->pluck('id');

            $addedCities = collect($newCities)->whereNotIn('id', $previousCitiesIds);

            if ($addedCities->count() > 0) {
                $addedCitiesIds = $addedCities->pluck('id');
                $citiesToAdd = City::whereIn('id', $addedCitiesIds)->get();

                foreach ($citiesToAdd as $city) {
                    $city->managers()->attach($model->id);
                }
            }
        }

        return $model;
    }

    public function removeCitiesManager($manager, $city)
    {
        return $manager->cities()->detach($city->id);
    }

    public function delete($model) {
        $model->delete();
    }
}
