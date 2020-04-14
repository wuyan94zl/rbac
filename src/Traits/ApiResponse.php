<?php

namespace Eachdemo\Rbac\Traits;

use Symfony\Component\HttpFoundation\Response as FoundationResponse;

trait ApiResponse
{
    /**
     * 响应创建
     *
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseCreated($data = null)
    {
        if ($data === null){
            $data = $this->setMessage($data);
        }

        return response()->json($data, FoundationResponse::HTTP_CREATED);
    }

    /**
     * 响应删除
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseDeleted($data = null)
    {
        if ($data === null){
            $data = $this->setMessage($data);
        }

        return response()->json($data, FoundationResponse::HTTP_NO_CONTENT);
    }

    /**
     * 响应异步
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseAccepted($data = null)
    {
        if ($data === null){
            $data = $this->setMessage($data);
        }

        return response()->json($data, FoundationResponse::HTTP_ACCEPTED);
    }

    /**
     * 响应成功
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSucceed($data = null, $lock = false)
    {
        if ($data === null){
            $data = $this->setMessage($data);
        }
        if($lock !== false) $lock->release();

        return response()->json($data, FoundationResponse::HTTP_OK);
    }

    /**
     * 响应失败
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseFailed($message = '')
    {
        if (empty($message)){
            $data = $this->setMessage([], FoundationResponse::HTTP_BAD_REQUEST);
        }else{
            $data['message'] = $message;
        }

        $data['status'] = 500;

        return response()->json($data, FoundationResponse::HTTP_BAD_REQUEST);
    }

    /**
     * 响应not found
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseNotFound()
    {
        $data = $this->setMessage([], FoundationResponse::HTTP_NOT_FOUND);

        return response()->json($data, FoundationResponse::HTTP_NOT_FOUND);
    }

    /**
     * 响应未登录
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseUnauthorized()
    {
        $data = $this->setMessage([], FoundationResponse::HTTP_UNAUTHORIZED);

        return response()->json($data, FoundationResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * 响应server error
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseServerError()
    {
        $data = $this->setMessage([], FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);

        return response()->json($data, FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * 响应请求太多
     *
     * @param $header
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseTooManyRequests($header)
    {
        $data = $this->setMessage([], FoundationResponse::HTTP_TOO_MANY_REQUESTS);

        return response()->json($data, FoundationResponse::HTTP_TOO_MANY_REQUESTS, $header);
    }

    /**
     * 响应验证
     *
     * @param string $message
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseValidated($message = '')
    {
        if (empty($message)){
            $data = $this->setMessage([], FoundationResponse::HTTP_BAD_REQUEST);
        }else{
            $data['message'] = $message;
        }

        $data['status'] = '缺少参数或无效';

        return response()->json($data, FoundationResponse::HTTP_BAD_REQUEST);
    }

    /**
     * 响应重定向
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseRedirect($data)
    {
        return response()->json($data, FoundationResponse::HTTP_SEE_OTHER);
    }

    /**
     * 响应禁止访问
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseAccessDenied()
    {
        $data = $this->setMessage([], FoundationResponse::HTTP_FORBIDDEN);

        return response()->json($data, FoundationResponse::HTTP_FORBIDDEN);
    }

    /**
     * 设置默认返回信息
     *
     * @param $data
     * @param int $code
     * @return array
     */
    private function setMessage($data, $code = FoundationResponse::HTTP_OK)
    {
        $data = empty($data) ? [] : $data;

        $data['message'] = FoundationResponse::$statusTexts[$code];

        return $data;
    }
}
