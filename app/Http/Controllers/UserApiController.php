<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Validator;

use Auth;

class UserApiController extends Controller
{
  public function showUser($id=null){
    if($id==''){
        $users=User::get();
        return response()->json(['users'=>$users],200);
    }else{
        $user=User::find($id);
        return response()->json(['users'=>$user],200); 
    }
  }

  public function addUser(Request $request){
    if($request->ismethod('post')){
        $data=$request->all();
        //return $data;
        $rules=[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required',
        ];

        $customMessage=[
            'name.required'=>'Name is required',
            'email.required'=>'Email is required',
            'email.email'=>'Email must be a valid email',
            'password.required'=>'Password is required',
        ];

        $validator=Validator::make($data, $rules, $customMessage);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $user=new User();
        $user->name=$data ['name'];
        $user->email=$data ['email'];
        $user->password=bcrypt($data['password']);
        $user->save();
        $message='User Successfully Added';
        return response()->json(['message'=>$message],201); 
    }
  }

  public function addMultipleUser(Request $request){
    if($request->ismethod('post')){
      $data=$request->all();

      $rules=[
        'users.*.name'=>'required',
        'users.*.email'=>'required|email|unique:users',
        'users.*.password'=>'required',
    ];

    $customMessage=[
        'users.*.name.required'=>'Name is required',
        'users.*.email.required'=>'Email is required',
        'users.*.email.email'=>'Email must be a valid email',
        'users.*.password.required'=>'Password is required',
    ];

    $validator=Validator::make($data, $rules, $customMessage);
    if($validator->fails()){
        return response()->json($validator->errors(),422);
    }

    foreach($data['users'] as $adduser){
    $user=new User();
    $user->name=$adduser ['name'];
    $user->email=$adduser ['email'];
    $user->password=bcrypt($adduser['password']);
    $user->save();
    $message='User Successfully Added';
    }
    return response()->json(['message'=>$message],201); 
    }
  }


  public function updateUserDetails(Request $request, $id){
    if($request->ismethod('put')){
        $data=$request->all();
        //return $data;
        $rules=[
            'name'=>'required',
            'password'=>'required',
        ];

        $customMessage=[
            'name.required'=>'Name is required',
            'password.required'=>'Password is required',
        ];

        $validator=Validator::make($data, $rules, $customMessage);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $user=User::findOrFail($id);
        $user->name=$data ['name'];
        $user->password=bcrypt($data['password']);
        $user->save();
        $message='User Successfully Updated';
        return response()->json(['message'=>$message],202); 
    }
  }

  public function updateSingleRecord(Request $request, $id){
    if($request->ismethod('patch')){
        $data=$request->all();
        //return $data;
        $rules=[
            'name'=>'required',
        ];

        $customMessage=[
            'name.required'=>'Name is required',
        ];

        $validator=Validator::make($data, $rules, $customMessage);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $user=User::findOrFail($id);
        $user->name=$data ['name'];
        $user->save();
        $message='User Successfully Updated';
        return response()->json(['message'=>$message],202); 
    }
  }
public function deleteSingleUser($id=null){
    User::findOrFail($id)->delete();
    $message='User Successfully Deleted';
    return response()->json(['message'=>$message],200);
}

public function deleteUserJson(Request $request){
    if($request->isMethod('delete')){
        $data=$request->all();
        User::where('id',$data['id'])->delete();
        $message='User Successfully Deleted';
        return response()->json(['message'=>$message],200);
    }
}
public function deleteMultipleUser($ids){
    $ids=explode(',',$ids);
    User::whereIn('id',$ids)->delete();
    $message='User Successfully Deleted';
    return response()->json(['message'=>$message],200);
}
public function deleteMultipleUserJson(Request $request){
    if($request->isMethod('delete')){
        $data=$request->all();
        User::whereIn('id',$data['ids'])->delete();
        $message='User Successfully Deleted';
        return response()->json(['message'=>$message],200);
    }
}

//use passport with registration
public function registerUserUsingPassport(Request $request){
    if($request->isMethod('post')){
        $data=$request->all();

        $rules=[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required',
        ];
    
        $customMessage=[
            'name.required'=>'Name is required',
            'email.required'=>'Email is required',
            'email.email'=>'Email must be a valid email',
            'password.required'=>'Password is required',
        ];
    
        $validator=Validator::make($data, $rules, $customMessage);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
    
        $user=new User();
        $user->name=$data ['name'];
        $user->email=$data ['email'];
        $user->password=bcrypt($data['password']);
        $user->save();

        if(Auth::attempt(['email'=>$data['email'], 'password'=>$data['password']])){
            $user=User::where('email',$data['email'])->first();
            $access_token=$user->createToken($data['email'])->accessToken;

            User::where('email',$data['email'])->update(['access_token'=>$access_token]);
            $message='User Successfully Register';
            return response()->json(['message'=>$message,'access_token'=>$access_token],201);
        }else{
        $message='opps! Something went wrong';
    
        return response()->json(['message'=>$message],422); 
        }
    }
}


//use passport with login
public function loginUserUsingPassport(Request $request){
    if($request->isMethod('post')){
        $data=$request->all();

        $rules=[
            'email'=>'required|email|exists:users',
            'password'=>'required',
        ];
    
        $customMessage=[
            'email.required'=>'Email is required',
            'email.email'=>'Email must be a valid email',
            'email.exists'=>'Email dose not exists',
            'password.required'=>'Password is required',
        ];
    
        $validator=Validator::make($data, $rules, $customMessage);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
    

        if(Auth::attempt(['email'=>$data['email'], 'password'=>$data['password']])){
            $user=User::where('email',$data['email'])->first();
            $access_token=$user->createToken($data['email'])->accessToken;

            User::where('email',$data['email'])->update(['access_token'=>$access_token]);
            $message='User Successfully Login';
            return response()->json(['message'=>$message,'access_token'=>$access_token],201);
        }else{
        $message='Invalid email or password';
    
        return response()->json(['message'=>$message],422); 
        }
    }
}

public function logoutUserUsingPassport(){
    Auth::user()->token()->revoke();
    return response()->json([
    'message'=>'User Successfully Logout'
    ]);
}

}
