<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCafeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 用于判断请求者是否有权限访问这个请求，
     * 由于我们已经在路由定义的时候定义过通过相应中间件 auth:api 进行权限过滤，
     * 所以这里将返回值设置为 true 即可
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * 用于设置各个请求字段的验证规则
     * 对应验证失败请求，Laravel 将会返回 400 响应（ Ajax 请求返回状态码为 422），
     * 并返回验证失败信息，我们可以在客户端通过捕获响应状态码及失败消息进行处理即可
     * @return array
     */
    public function rules()
    {
        return [
            //
            'name' => 'required|min:2|max:10',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required|regex:/\b\d{6}\b/'
        ];
    }

    /**
     * 对于验证失败字段，会有默认的失败消息，
     * 但往往因为可读性问题并不能满足我们的需要
     * 对验证字段失败消息进行自定义
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'name.required' => '咖啡店名字不能为空',
            'name.min' => '咖啡店名不能短于2个字符',
            'name.max' => '咖啡店名不能超过10个字符',
            'address.required' => '咖啡店地址不能为空',
            'city.required' => '咖啡店所在城市不能为空',
            'state.required' => '咖啡店所在省份不能为空',
            'zip.required' => '咖啡店邮编不能为空',
            'zip.regex' => '无效的邮政编码'
        ];
    }


}
