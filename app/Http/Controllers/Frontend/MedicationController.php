<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MedicationRepositoryInterface;
use App\Traits\ApiHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class MedicationController extends Controller
{
    use ApiHelperTrait;

    /**
     * medicationRepository
     *
     * @var mixed
     */
    protected $medicationRepository;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        MedicationRepositoryInterface $medicationRepository
    ) {
        $this->medicationRepository = $medicationRepository;
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        try {
            $medications = $this->medicationRepository->findAllPaginated();

            return $this->apiSuccess($medications);
        } catch (Throwable $th) {
            Log::error("MedicationController (index) : error fetching medications , Reason - {$th->getMessage()}" . PHP_EOL . $th->getTraceAsString());

            return $this->apiError("something went wrong");
        }
    }

    /**
     * create
     *
     * @param  mixed $request
     * @return void
     */

    /**
     * @LRDparam customer_id required string
     * @LRDparam name required string
     * @LRDparam description required string
     * @LRDparam quantity required integer
     */
    public function create(Request $request)
    {
        $rules = [
            'customer_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->apiError($validator->messages()->all());
        }

        try {
            $user = Auth::user();

            $customer = $this->medicationRepository->create($request->all());

            Log::info("MedicationController (create) : medication successfuly created | user_id : {$user->id} | customer_id: {$request->customer_id}");

            return $this->apiSuccess($customer);
        } catch (Throwable $th) {
            Log::error("MedicationController (create) : error creating medication , customer_id - {$request->customer_id}  | Reason - {$th->getMessage()}" . PHP_EOL . $th->getTraceAsString());

            return $this->apiError("something went wrong");
        }
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $medicationId
     * @return void
     */

    /**
     * @LRDparam customer_id required string
     * @LRDparam name required string
     * @LRDparam description required string
     * @LRDparam quantity required integer
     */
    public function update(Request $request, $medicationId)
    {
        $rules = [
            'customer_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->apiError($validator->messages()->all());
        }

        try {
            $medication = $this->medicationRepository->find($medicationId);

            if (!$medication) {
                return $this->apiError("medication not found", Response::HTTP_NOT_FOUND);
            }

            $user = Auth::user();

            $medication = $this->medicationRepository->update($medicationId, $request->all());

            Log::info("MedicationController (update) : medication successfuly updated | user_id : {$user->id} | medication_id: {$medicationId}");

            return $this->apiSuccess($medication);
        } catch (Throwable $th) {
            Log::error("MedicationController (update) : error updating medication , medication_id - {$medicationId}  | Reason - {$th->getMessage()}" . PHP_EOL . $th->getTraceAsString());

            return $this->apiError("something went wrong");
        }
    }

    /**
     * destroy
     *
     * @param  mixed $medication
     * @return void
     */

    public function destroy($medicationId)
    {
        try {
            $medication = $this->medicationRepository->find($medicationId);

            if (!$medication) {
                return $this->apiError("medication not found", Response::HTTP_NOT_FOUND);
            }

            $user = Auth::user();

            if ($user->role == 'owner') {
                $medication->forceDelete();

                Log::info("MedicationController (destroy) : medication deleted | user_id : {$user->id} | medication_id: {$medicationId}");

                return $this->apiSuccess("medication deleted successfuly");
            }

            $medication->delete();

            Log::info("MedicationController (destroy) : medication deleted | user_id : {$user->id} | medication_id: {$medicationId}");

            return $this->apiSuccess("medication deleted successfuly");
        } catch (Throwable $th) {
            return $this->apiError("something went wrong");
        }
    }
}
