<?php

namespace App\Repository\Admin;

use App\Contract\Admin\StateInterface;
use App\Http\Resources\Admin\State as StateResource;
use App\Models\Admin\State;
use App\Traits\ApiResponser;
use Illuminate\Support\Collection;

class StateRepository implements StateInterface
{
    use ApiResponser;
    public function all()
    {
        try {
            $state = new State;
            if (isset($_GET['getAllData']) && $_GET['getAllData'] == '1') {
                $countries = explode(",",$_GET['country_id']);
                $state = $state->whereIn('country_id', $countries);
                if (isset($_GET['country_id']) && is_numeric($_GET['country_id']) && $_GET['country_id'] > 0) {
                    $state = $state->countryId($_GET['country_id']);
                }
                return $this->successResponse(StateResource::collection($state->get()), 'Data Get Successfully!');
            }
            if (isset($_GET['getCountry']) && $_GET['getCountry'] == '1') {
                $state = $state->with('country');
            }
            if (isset($_GET['country_id']) && is_numeric($_GET['country_id']) && $_GET['country_id'] > 0) {
                $state = $state->countryId($_GET['country_id']);
            }
            if (isset($_GET['searchParameter']) && $_GET['searchParameter'] != '') {
                $state = $state->searchParameter($_GET['searchParameter']);
            }
            if (isset($_GET['limit']) && is_numeric($_GET['limit']) && $_GET['limit'] > 0) {
                $numOfResult = $_GET['limit'];
            } else {
                $numOfResult = 100;
            }
            $sortBy = ['id', 'name'];
            $sortType = ['ASC', 'DESC', 'asc', 'desc'];
            if (isset($_GET['sortBy']) && $_GET['sortBy'] != '' && isset($_GET['sortType']) && $_GET['sortType'] != '' && in_array($_GET['sortBy'], $sortBy) && in_array($_GET['sortType'], $sortType)) {
                if ($state->Active()) {
                    $state = $state->orderBy($_GET['sortBy'], $_GET['sortType']);
                } else {
                    $state = $state->OrderByStatus();
                }
            }

            return $this->successResponse(StateResource::collection($state->paginate($numOfResult)), 'Data Get Successfully!');
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function show($state)
    {
        try {
            if (isset($_GET['getCountry']) && $_GET['getCountry'] == '1') {
                return $this->successResponse(new StateResource(State::with('country')->stateId($state->id)->firstOrFail()), 'Data Get Successfully!');
            }

            return $this->successResponse(new StateResource($state), 'Data Get Successfully!');
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function store(array $parms)
    {
        try {
            $sql = new State;
            $sql = $sql->create($parms);
        } catch (Exception $e) {
            return $this->errorResponse();
        }

        if ($sql) {
            return $this->successResponse(new StateResource($sql), 'State Save Successfully!');
        } else {
            return $this->errorResponse();
        }
    }

    public function update(array $parms, $state)
    {
        // return $parms;
        try {
            $state->update($parms);
        } catch (Exception $e) {
            return $this->errorResponse();
        }

        if ($state) {
            return $this->successResponse(new StateResource($state), 'State Update Successfully!');
        } else {
            return $this->errorResponse();
        }
    }

    public function destroy($state)
    {
        try {
            $sql = State::findOrFail($state);
            $sql->delete();
        } catch (Exception $e) {
            return $this->errorResponse();
        }

        if ($sql) {
            return $this->successResponse('', 'State Delete Successfully!');
        } else {
            return $this->errorResponse();
        }
    }
}
