<?php

namespace App\Traits;

use Carbon\Carbon;
use Exception;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{
	/**
     * Return a success JSON response.
     *
     * @param  array|string  $data
     * @param  string  $message
     * @param  int|null  $code
     * @return \Illuminate\Http\JsonResponse
     */
	protected function success($data, string $message = null, int $code = 200)
	{
		return response()->json([
			// 'status' => 'Success',
			'message' => $message,
			'data' => $data
		], $code);
	}

	/**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @param  array|string|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
	protected function error(int $code, string $message = null, Exception $exception = null, $data = null)
	{
		if($exception && env('APP_ENV') !== 'production'){
			$message = $exception->getMessage() . ' # ' . $exception->getFile() . ' # ' . $exception->getLine();
		}

		return response()->json([
			// 'status' => 'Error',
			'message' => $message,
			'data' => $data
		], $code);
	}

}