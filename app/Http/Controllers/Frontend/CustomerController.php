<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Traits\ApiHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CustomerController extends Controller
{
    use ApiHelperTrait;

    /**
     * customerRepository
     *
     * @var mixed
     */
    protected $customerRepository;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->customerRepository = $customerRepository;
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        try {
            $customers = $this->customerRepository->findAllPaginated();

            return $this->apiSuccess($customers);
        } catch (Throwable $th) {
            Log::error("CustomerController (index) : error fetching customers , Reason - {$th->getMessage()}" . PHP_EOL . $th->getTraceAsString());

            return $this->apiError("something went wrong");
        }
    }

    /**
     * create
     *
     * @param  mixed $request
     * @return void
     */
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->apiError($validator->messages()->all());
        }

        try {
            $user = Auth::user();

            $customer = $this->customerRepository->create($request->all());

            Log::info("CustomerController (create) : customer successfuly created | user_id : {$user->id} | customer_id: {$customer->id}");

            return $this->apiSuccess($customer);
        } catch (Throwable $th) {
            Log::error("CustomerController (create) : error creating customer , customer_name - {$request->name}  | Reason - {$th->getMessage()}" . PHP_EOL . $th->getTraceAsString());

            return $this->apiError("something went wrong");
        }
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $customerId
     * @return void
     */
    public function update(Request $request, $customerId)
    {
        $rules = [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->apiError($validator->messages()->all());
        }

        try {
            $customer = $this->customerRepository->find($customerId);

            if (!$customer) {
                return $this->apiError("customer not found", Response::HTTP_NOT_FOUND);
            }

            $user = Auth::user();

            $customer = $this->customerRepository->update($customerId, $request->all());

            Log::info("CustomerController (update) : customer successfuly updated | user_id : {$user->id} | customer_id: {$customerId}");

            return $this->apiSuccess($customer);
        } catch (Throwable $th) {
            Log::error("CustomerController (update) : error updating customer , customer_name - {$request->name}  | Reason - {$th->getMessage()}" . PHP_EOL . $th->getTraceAsString());

            return $this->apiError("something went wrong");
        }
    }

    /**
     * destroy
     *
     * @param  mixed $customerId
     * @return void
     */
    public function destroy ($customerId)
    {
        try {
            $customer = $this->customerRepository->find($customerId);

            if (!$customer) {
                return $this->apiError("customer not found", Response::HTTP_NOT_FOUND);
            }

            $user = Auth::user();

            if ($user->role == 'owner') {
                $customer->forceDelete();

                return $this->apiSuccess("customer deleted successfuly");
            }

            $customer->delete();

            return $this->apiSuccess("customer deleted successfuly");

        } catch (Throwable $th) {
            return $this->apiError("something went wrong");
        }
    }
}
