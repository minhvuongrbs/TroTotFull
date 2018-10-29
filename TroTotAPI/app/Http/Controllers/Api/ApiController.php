<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{

    /**
     * HTTP status code.
     *
     * @var int
     */
    private $statusCode = HttpResponse::HTTP_OK;
    private $errors = [];

    /**
     * Return a 201 response with the given created resource.
     *
     * @param null $resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withCreated($message = 'Created', $data = [])
    {
        return $this->setStatusCode(HttpResponse::HTTP_CREATED)->withSuccess($message, $data);
    }

    /**
     * Make a 204 no content response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withNoContent()
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_NO_CONTENT
        )->withSuccess(HttpResponse::$statusTexts[HttpResponse::HTTP_NO_CONTENT]);
    }

    /**
     * Make a 400 'Bad Request' response.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withBadRequest($message = 'Bad Request')
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_BAD_REQUEST
        )->withError($message);
    }

    /**
     * Make a 401 'Unauthorized' response.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_UNAUTHORIZED
        )->withError($message);
    }

    /**
     * Make a 403 'Forbidden' response.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_FORBIDDEN
        )->withError($message);
    }

    /**
     * Make a 404 'Not Found' response.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_NOT_FOUND
        )->withError($message);
    }

    /**
     * Make an error response.
     *
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withError($message)
    {
        return $this->json([
            'meta' => [
                'success' => false,
                'statusCode' => $this->getStatusCode(),
                'message' => $message,
                'errors' => $this->getErrors()
            ]
        ]);
    }

    /**
     * Make a success response.
     *
     * @param $message
     * @param $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withSuccess($message = 'Success', $data = []){
        if( is_null($data) ){
            return $this->json([
                'meta' => [
                    'success' => true,
                    'statusCode' => $this->getStatusCode(),
                    'message' => $message,
                    'errors' => $this->getErrors()
                ]
            ]);
        }
        return $this->json([
            'meta' => [
                'success' => true,
                'statusCode' => $this->getStatusCode(),
                'message' => $message,
                'errors' => $this->getErrors()
            ],
            'data' => $data
        ]);
    }

    /**
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function json($data = [], array $headers = [])
    {
        return response()->json($data, $this->statusCode, $headers);
    }

    /**
     * Set HTTP status code.
     *
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Gets the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set Errors
     *
     * @param array $errors
     * @return $this
     */
    public function setErrors($errors = []){
        $this->errors = $errors;
        return $this;
    }

    /**
     * Get errors
     *
     * @return array
     */
    public function getErrors(){
        return $this->errors;
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->withSuccess('Get current user success!', $this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();
        return $this->withSuccess('Successfully logged out');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->json([
            'meta' => [
                "success" => true,
                'serverCode' => $this->getStatusCode(),
                'message' => trans('api.success')
            ],
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $this->guard()->factory()->getTTL() * 60,
                'user' => $this->guard()->user(),
            ]
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard($guard = 'api')
    {
        return Auth::guard($guard);
    }

    /**
     * Check permission of current user
     *
     * @param  string  $permission [description]
     * @return boolean             [description]
     */
    public function hasAccess($permission = 'read'){
        if(strrpos($permission, "index") || strrpos($permission, "show")){
            return true;
        }
        return $this->guard()->user()->hasAccess($permission);
    }
}
