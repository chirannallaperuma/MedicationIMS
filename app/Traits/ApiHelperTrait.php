<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\Response;

trait ApiHelperTrait
{
    protected $responseObject = array();

    /**
     * addToResponse
     *
     * @param  mixed $overrides
     * @return void
     */
    protected function addToResponse(array $overrides = array())
    {
        $this->responseObject['timestamp'] = Carbon::now()->toDateTimeString();
        $this->responseObject = array_merge($this->responseObject, $overrides);
        return $this->responseObject;
    }

    /**
     * apiError
     *
     * @param  mixed $content
     * @param  mixed $status_code
     * @param  mixed $error_code
     * @param  mixed $user_message
     * @param  mixed $developer_message
     * @param  mixed $metaData
     * @return void
     */
    public function apiError($content, $status_code = 500, $error_code = null, $user_message = null, $developer_message = null, array $metaData = array())
    {
        $response = $this->addToResponse(array(
            'error_code' => $error_code,
            'user_message' => $user_message,
            'developer_message' => $developer_message,
            'errors' => $content,
            'http_status_code' => $status_code
        ));

        $this->addMetaData($metaData);

        return response()->json($this->responseObject, $status_code);
    }

    /**
     * apiSuccess
     *
     * @param  mixed $content
     * @param  mixed $status_code
     * @param  mixed $metaData
     * @return void
     */
    public function apiSuccess($content, $status_code = 200, array $metaData = [])
    {

        if ($content instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $pagination = $content->toArray();
            $content = $pagination['data'];
            unset($pagination['data']);
            $metaData = array_merge($pagination, $metaData);
        }

        $this->addToResponse([
            'data' => $content,
            'http_status_code' => $status_code
        ]);

        $this->addMetaData($metaData);

        return response()->json($this->responseObject);
    }

    /**
     * addMetaData
     *
     * @param  mixed $metaData
     * @return void
     */
    protected function addMetaData(array $metaData)
    {
        foreach ($metaData as $key => $value) {
            $this->responseObject[$key] = $value;
        }
    }
}
