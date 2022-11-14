<?php
 
namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Check;
use App\Models\Leave;
use Illuminate\Http\Request;
use Validator,Redirect,Response;
Use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
 
class AuthController extends Controller
{

    public function index()
    {
        return view('employee.login');
    }  
 
    public function registration()
    {
        return view('registration');
    }
     
    public function postLogin(Request $request)
    {
        Auth::guard('emp');
        request()->validate([
        'email' => 'required',
        'password' => 'required',
        ]);
 
        $credentials = $request->only('email', 'password');
        if (Auth::guard('emp')->attempt($credentials)) {
            // $user = Auth::guard('emp')->user();
            return redirect()->intended('dashboard');
        }
        return Redirect::to("login")->withSuccess('Oppes! You have entered invalid credentials');
    }
 
    public function postRegistration(Request $request)
    {  
        Auth::guard('emp');
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
         
        $data = $request->all();
 
        $check = $this->create($data);
       
        return Redirect::to("dashboard")->withSuccess('Great! You have Successfully loggedin');
    }
     
    public function dashboard()
    {
        function getDatesFromRange($start, $end, $format = 'Y-m-d') {
            $array = array();
            $interval = new \DateInterval('P1D');
            $realEnd = new \DateTime($end);
            $realEnd->add($interval);
            $period = new \DatePeriod(new \DateTime($start), $interval, $realEnd);
            foreach($period as $date) {                 
                $array[] = $date->format($format); 
            }          
                // dd($date->format($format));
            return $array;
        }
        date_default_timezone_set("Asia/Karachi");
        if(Auth::guard('emp')->check()){
            $oneWeekAgo = date("Y-m-d", strtotime("-10 days"));
            $today = date('Y-m-d');
            $week_dates_arr = getDatesFromRange($oneWeekAgo, $today);
            $week_dates_arr = array_reverse($week_dates_arr);
            $attendances = [];
            $leave_state_now = Leave::whereLeave_date(date("Y-m-d"))->whereEmp_id(Auth::guard('emp')->user()->id)->first();
            $attendance_state_now = Attendance::whereAttendance_date(date("Y-m-d"))->whereEmp_id(Auth::guard('emp')->user()->id)->first();
            foreach($week_dates_arr as $keys){
                $att = Attendance::whereAttendance_date($keys)->whereEmp_id(Auth::guard('emp')->user()->id)->whereType(0)->first();
                $lev = Leave::whereLeave_date($keys)->whereEmp_id(Auth::guard('emp')->user()->id)->whereType(1)->first();
                $obj = [
                    'date' => $keys,
                    'in' => ($att != null) ? $att['attendance_time'] : "no entry found",
                    'out' => ($lev != null) ? $lev['leave_time'] : "no entry found",
                    'hours_given' => ($att != null && $lev != null) ? round(abs(strtotime($lev['leave_time']) - strtotime($att['attendance_time'])) / 3600,2) : "no entry found",
                    'late' => ($att != null) ? ($att['late']) ? 'late' : 'on-time' : 'no entry found'
                ];
                // dd($obj);
                array_push($attendances, $obj);
            }
            // $attendances = array_reverse($attendances);
            // dd($attendances);

            return view('employee.dashboard', [
                'attendances' => $attendances,
                'attendance_state_now' => (!$attendance_state_now) ? 'in' : 'already-in',
                'leave_state_now' => (!$leave_state_now) ? 'out' : 'already-out',
            ]);
        }
        return Redirect::to("login")->withSuccess('Opps! You do not have access');
    }
 
    public function create(array $data)
    {
        Auth::guard('emp');
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }
     
    public function logout() {
        Auth::guard('emp');
        Session::flush();
        Auth::guard('emp')->logout();
        return Redirect('login');
    }

    public function calcSalary(Request $request){
        $salary = $request['salary'];
        $lates = $request['lates'];
        $per_day_wage = $salary/26;
        
        if($lates > 2){
            $lates = $lates-2;
            $late_fines = $lates * $per_day_wage;
        } else {
            $late_fines = 0;
        }
        $late_fines = ceil($late_fines / 10) * 10;
        
        return [
            'lates' => $lates,
            'fine_total' => $late_fines,
            'expected_salary' => $salary - $late_fines
        ];
        // return view('employee.dashboard', [
        //     'attendances' => $attendances,
        //     'attendance_state_now' => (!$attendance_state_now) ? 'in' : 'already-in',
        //     'leave_state_now' => (!$leave_state_now) ? 'out' : 'already-out',
        // ]);
    }
}