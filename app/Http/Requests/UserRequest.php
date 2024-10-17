<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route()->user;
        $emailRule = 'required|email|unique:users,email';
        
        if($id) {
            $emailRule .= ",{$id}";
            $name = $this->name;
            $email = $this->email;
            $password = $this->password;
            $rules = [];

            if($name) {
                $rules['name'] = 'required|min:4';
            }

            if($email) {
                $rules['email'] = $emailRule;
            }
            
            if($password) {
                $rules['password'] = 'required|min:6';
            }

            return $rules;
        }
        
        return [
            'name' => 'required|min:4',
            'email' => $emailRule,
            'password' => 'required|min:6',
        ];
    }

    public function message() {
        return [
            'required' => ':attribute bắt buộc phải nhập',
            'min' => ':attribute phải từ :min kí tự',
            'email' => ':attribute phải là định dang email',
            'unique' => ':attribute đã tồn tại'
        ];
    } 
    
    public function attributes()
    {
        return [
            'name' => 'Tên',
            'email' => 'Email',
            'password' => 'Mật khẩu'
        ];
    } 
}
