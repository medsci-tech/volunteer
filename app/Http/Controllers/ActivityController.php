<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Model\Activity;
use App\Model\Volunteer;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.wechat');
        $this->middleware('auth.access');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = \Session::get('logged_user');
        if (!$user) {
            return redirect('/home/error');
        } /*if>*/

        $volunteer = Volunteer::where('openid', $user['openid'])->first();
        if (!$volunteer) {
            return redirect('/home/error');
        } /*if>*/

        if($volunteer->status=='0'){
            return redirect('/volunteer/check');
        }
        $activities = $volunteer->unit->activities;
        //$activities = explode(',',$volunteer['represent']['belong_project']);
        if ((!$activities) || (0 == count($activities))) {
            return view('activity.no_activities');
        } /*if>*/

        return view('activity.all_activities')->with([
            'volunteer' => $volunteer,
            'activities' => $activities
        ]);
    }

    public function loadView(Request $request)
    {
        $user = \Session::get('logged_user');
        if (!$user) {
            return response()->json(['result' => '-1']);
        } /*if>*/

        $volunteer = Volunteer::where('openid', $user['openid'])->first();
        if (!$volunteer) {
            return response()->json(['result' => '-1']);
        } /*if>*/

        $activities = $volunteer->unit->activities;
        //$activities = explode(',',$volunteer['represent']['belong_project']);
        if ((!$activities) || (0 == count($activities))) {
            return response()->json(['result' => '-2']);
        } /*if>*/

        return response()->json(['result' => '1','volunteer' => $volunteer,
            'activities' => $activities]);
    }

    public function noneView(Request $request)
    {
        return view('activity.no_activities');
    }
} /*class*/
