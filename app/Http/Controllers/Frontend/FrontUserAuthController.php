<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserApiResource;
use App\Http\Services\Validation\ValidationException;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Traits\ApiHelperTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class FrontUserAuthController extends Controller
{
    use ApiHelperTrait;

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'user_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'role' => 'required'
            ];

            $messages = [
                'unique' => 'Seems like your email address is already existing !'
            ];

            $input = $request->only('name', 'user_name', 'email', 'password', 'role');

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                throw new ValidationException("error validating user", $validator->messages()->all());
            }

            $input['password'] = Hash::make($request->input('password'));

            $user = $this->userRepository->create($input);

            $authToken = $user->createToken('auth_token', ['*'], Carbon::now()->addHours(1))->plainTextToken;
            $user->access_token = $authToken;
            $user->token_type = 'Bearer';

            Log::info('FrontuserAuthController (register) : User successfuly registered | user_id :' . $user->id);

            return $this->apiSuccess(new UserApiResource($user));
        } catch (ValidationException $e) {
            Log::error("FrontuserAuthController (register) : error validating user | Reason - {$e->getMessage()}" . PHP_EOL . $e->getTraceAsString());

            return $this->apiError($e->getErrors(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable $th) {
            Log::error("FrontuserAuthController (register) : error registered user | Reason - {$th->getMessage()}" . PHP_EOL . $th->getTraceAsString());

            return $this->apiError('something went wrong');
        }
    }

    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        try {
            if (!Auth::attempt($request->only('user_name', 'password'))) {
                return $this->apiError("Invalid login details");
            }

            $rules = [
                'user_name' => 'required',
                'password' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->apiError($validator->messages()->all());
            }

            if (Auth::attempt(['user_name' => $request->user_name, 'password' => $request->password])) {
                $user = Auth::user();
                $authToken = $user->createToken('auth_token', ['*'], Carbon::now()->addHours(1))->plainTextToken;
                $user->access_token = $authToken;
                $user->token_type = 'Bearer';

                Log::info("FrontuserAuthController (login) : User successfuly logged in | user_id :" . $user->id);

                return $this->apiSuccess(new UserApiResource($user));
            }

            return $this->apiError("login failed");
        } catch (Throwable $th) {
            $userId = data_get(Auth::user(), 'id', 'Undefined');

            Log::error("FrontuserAuthController (login) : error logged in user' - {$userId} | Reason - {$th->getMessage()}" . PHP_EOL . $th->getTraceAsString());

            return $this->apiError("something went wrong");
        }
    }
}
