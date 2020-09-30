<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Traits\UploadTrait;
use App\UserCurrency;
use App\User;
use Auth;

class UserController extends Controller
{
    public $successStatus = 200;
    use UploadTrait;

    public function login()
    {
        if (
            Auth::attempt([
                'username' => request('username'),
                'password' => request('password'),
            ])
        ) {
            $user = Auth::user();
            $user['token'] = $user->createToken('MyApp')->accessToken;
            $user->user_display_pic = asset('images/' . $user->user_display_pic);

            return response()->json($user);
        } else {
            return response()->json(
                ['error' => 'Invalid credentials'],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }

    public function getBase64Image()
    {
        $user = User::findOrFail(auth()->user()->id);
        $image_path = asset('images/' . $user->user_display_pic);

        $fileExtension = pathinfo($image_path, PATHINFO_EXTENSION);
        $data = base64_encode(file_get_contents($image_path)); 
        $base64 = 'data:image/' . $fileExtension . ';base64,' . $data;
        return response()->json($base64);
    }

    public function getProfile()
    {
        $uid = User::findOrFail(auth()->user()->id);
        $users = User::select($uid->id)
            ->select([
                'id',
                'username',
                'email',
                'password',
                'user_display_pic',
                'name_first',
                'name_last',
                'contact_no',
                'birth_date',
                'zip_code',
                'city',
                'address',
                'country',
                'state',
                'is_staff',
                'is_superuser',
            ])
            ->find($uid->id);

        $users->user_display_pic = asset('images/' . $users->user_display_pic);

        return response()->json($users);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'name_first' => 'required',
            'name_last' => 'required',
            'contact_no' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()],
                Response::HTTP_BAD_REQUEST
            );
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user['token'] = $user->createToken('MyApp')->accessToken;

        $wallet = new UserCurrency();
        $wallet->user_id = $user->id;
        $wallet->btc = '0.0000000000';
        $wallet->eth = '0.0000000000';
        $wallet->xrp = '0.0000000000';
        $wallet->ltc = '0.0000000000';
        $wallet->bch = '0.0000000000';
        $wallet->eos = '0.0000000000';
        $wallet->bnb = '0.0000000000';
        $wallet->usdt = '0.0000000000';
        $wallet->bsv = '0.0000000000';
        $wallet->trx = '0.0000000000';
        $wallet->cash = '0.0000000000';
        $wallet->save();
        return response()->json($user, Response::HTTP_CREATED);
    }

    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function updateProfilePicture(Request $request) {
        if  (auth()->user()) {
            if ($request->transaction_pin == Auth::user()->transaction_pin) {

                $user = User::findOrFail(auth()->user()->id);

                $image_64 = $request->image; //your base64 encoded data
                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

                $replace = substr($image_64, 0, strpos($image_64, ',')+1);
                // find substring fro replace here eg: data:image/png;base64,
                $image = str_replace($replace, '', $image_64); 
                $image = str_replace(' ', '+', $image); 
                $imageName = $user->id . '.' . $extension;
                $user->user_display_pic = 'uploads/' . $user->id. '.' . $extension;
                Storage::disk('public')->put($imageName, base64_decode($image));
            }
            $user->save();
            return response()->json(['success' => $user], $this->successStatus);
        }
    }

    public function updateProfileBasic(Request $request, $id)
    {
        $user = Auth::user();

        if ($request->transaction_pin == $user->transaction_pin) {
            $validator = Validator::make($request->all(), [
                'name_first' => 'required',
                'name_last' => 'required',
                'contact_no' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $user = User::find($id);

            $user->name_first = $request->input('name_first');
            $user->name_last = $request->input('name_last');
            $user->contact_no = $request->input('contact_no');
            $user->birth_date = $request->input('birth_date');
            $user->zip_code = $request->input('zip_code');
            $user->city = $request->input('city');
            $user->address = $request->input('address');
            $user->country = $request->input('country');
            $user->state = $request->input('state');

            $user->save();

            return response()->json(compact('user'));
        } else {
            return response()->json([
                'message' => 'Incorrect Transaction Pin!',
            ]);
        }
    }

    public function updateProfileAccount(Request $request, $id)
    {
        $user = Auth::user();

        if ($request->transaction_pin == $user->transaction_pin) {
            $users = User::find($id);
            if (empty($request->password)) {
                $validator = Validator::make($request->all(), [
                    'username' => 'required',
                    'email' => 'required|email',
                ]);
                if ($validator->fails()) {
                    return response()->json(
                        ['error' => $validator->errors()],
                        401
                    );
                }

                $user = User::find($id);
                $user->username = $request->input('username');
                $user->email = $request->input('email');
                $user->save();

                return response()->json(compact('user'));
            } else {
                $validator = Validator::make($request->all(), [
                    'username' => 'required',
                    'email' => 'required|email',
                    'password' => 'required',
                    'c_password' => 'required|same:password',
                ]);
                if ($validator->fails()) {
                    return response()->json(
                        ['error' => $validator->errors()],
                        401
                    );
                }

                $user = User::find($id);
                $user->username = $request->input('username');
                $user->email = $request->input('email');
                $user->password = bcrypt($request->input('password'));
                $user->save();

                return response()->json(compact('user'));
            }
        } else {
            return response()->json([
                'message' => 'Incorrect Transaction Pin!',
            ]);
        }
    }

    public function updateProfilePin(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'transaction_pin' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = User::find($id);
        $user->transaction_pin = $request->input('transaction_pin');
        $user->save();

        return response()->json(compact('user'));
    }
}
