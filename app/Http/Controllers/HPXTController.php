<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\Volunteer;
use App\Model\HPXTClass;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Constants\DefaultValue;

class HPXTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $openid = \Session::get('logged_user');
        $data = false;

        if ($openid) {
            $volunteer = Volunteer::where('openid', $openid['openid'])->first();

            $data = (strpos($volunteer['represent']['belong_project'],'黄埔学堂')===false) ? false:true;
        }
        if(!$data){
            return redirect('activity');
        }
        //
        return view('hpxt.huangpu_index');
    }

    public function introduction() {
        return view('hpxt.huangpu_profile');
    }

    public function procedure() {
        return view('hpxt.huangpu_profile');
    }

    public function document() {
        return view('hpxt.huangpu_document');
    }

    public function documentPpt() {
        return view('hpxt.huangpu_ppt');
    }

    public function documentAgreement() {
        return view('hpxt.huangpu_agreement');
    }

    public function classManage() {
        $user = \Session::get('logged_user');
        $volunteer = Volunteer::where('openid', $user['openid'])->first();
        $classes = HPXTClass::where('volunteer_id', $volunteer->id)->get();
        return view('hpxt.class_manage')->with([
            'classes' => $classes
        ]);
    }

    public function classApplication() {
//        $classModes = HPXTClassMode::all();
//        $classScales = HPXTClassScale::all();
        $classModes = array();
        $rowMode1 = array('id' => 1, 'mode' => DefaultValue::DEFAULT_CLASS_MODE_COMMON);
        array_push($classModes, $rowMode1);
        $rowMode2 = array('id' => 2, 'mode' => DefaultValue::DEFAULT_CLASS_MODE_CITY);
        array_push($classModes, $rowMode2);
        $rowMode3 = array('id' => 3, 'mode' => DefaultValue::DEFAULT_CLASS_MODE_HOSPITAL);
        array_push($classModes, $rowMode3);
        $classScales = array();
        $rowScales1 = array('id' => 1, 'scale' => DefaultValue::DEFAULT_CLASS_SCALES_FIRST);;
        array_push($classScales, $rowScales1);
        $rowScales2 = array('id' => 2, 'scale' => DefaultValue::DEFAULT_CLASS_SCALES_SECOND);;
        array_push($classScales, $rowScales2);

        return view('hpxt.class_application')->with([
            'modes'=>$classModes,
            'scales'=>$classScales
        ]);
    }

    public function classApplicationAddDoctor() {
        return view('hpxt.class_application_add_doctor');
    }

    public function classApplicationAddAssistant() {
        return view('hpxt.class_application_add_assistant');
    }

    public function classStore(Request $request) {
        $user = \Session::get('logged_user');
        $volunteer = Volunteer::where('openid', $user['openid'])->first();

        $validator = \Validator::make($request->all(), [

        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } /*if>*/

    }

}
