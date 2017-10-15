<?php
/**
 * Created by PhpStorm.
 * User: Chelsea
 * Date: 2016/4/6
 * Time: 21:12
 */
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Model\Address;
use App\Model\Doctor;
use App\Model\Hospital;
use App\Model\KZKTClass;
use App\Model\Office;
use App\Model\RepresentInfo;
use App\Model\Unit;
use App\Model\Volunteer;
use DB;
use Illuminate\Http\Request;
require dirname(__FILE__) . '/../../PHPExcel/PHPExcel/IOFactory.php';
require dirname(__FILE__) . '/../../PHPExcel/PHPExcel.php';
use Mockery\CountValidator\Exception;
use PHPExcel_IOFactory;
use PHPExcel;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Border;
use PhpParser\Comment\Doc;

class AdminController extends Controller{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

        $activities = DB::select('select id,title from activities');
        $units = DB::select('select id,full_name from units');
        $areas = DB::select('select DISTINCT belong_area as province from mime_represent_info');
        $dbms = DB::select('select DISTINCT belong_dbm from mime_represent_info');

        $query =  DB::table("mime_represent_info");
        if(!empty($request->get("name"))){
            $query->where("name","like","%".$request->get("name")."%");
        }
        if(!empty($request->get("phone"))){
            $query->where("phone","like","%".$request->get("phone")."%");
        }
        if(!empty($request->get("initial"))){
            $query->where("initial","like","%".$request->get("initial")."%");
        }
        if(!empty($request->get("belong_area"))){
            $query->where("belong_area","like","%".$request->get("belong_area")."%");
        }
        if(!empty($request->get("belong_dbm"))){
            $query->where("belong_dbm","like","%".$request->get("belong_dbm")."%");
        }
        $represents = $query->paginate(10);
        $represents->setPath(url('/admin'));

        $query_arr = ["name"=>$request->get("name"),"phone"=>$request->get("phone"),"initial"=>$request->get("initial"),
            "belong_area"=>$request->get("belong_area"),"belong_dbm"=>$request->get("belong_dbm")];

        return view('admin.represents')->with(["represents"=>$represents,"activities"=>$activities,"units"=>$units,"areas"=>$areas,"dbms"=>$dbms,"query_arr"=>$query_arr]);
    }

    public function getImportRepresent(){
        return view('admin.importRepresent');
    }

    public function postRepresent(Request $request){
        $post = $request->all();
        $post["initial"] = trim($post["initial"]);
        if(array_key_exists('belong_project', $post)){
            $post["belong_project"] = implode(",",$post["belong_project"]);
        }

        if(empty($post["id"])){
            $represent = RepresentInfo::create($post);
        }else{
            $represent = RepresentInfo::find(intval($post["id"]))->update($post);
        }
        if($represent){
            $success = true;
        }else{
            $success = false;
        }
        return response()->json(["success"=>$success]);
    }

    public function deleteRepresent($id){
        $success = RepresentInfo::destroy($id)>0;
        $volunteer = Volunteer::where('represent_id',$id) ->first();
        //$volunteer->represent_id = '';
        //$volunteer->save();
        if($volunteer){
            //$success = Volunteer::destroy($volunteer->id)>0;
            DB::query('set FOREIGN_KEY_CHECKS=0;');
            DB::table('volunteers')->where('id',$volunteer->id)->delete();
            DB::query("set FOREIGN_KEY_CHECKS=1;");
        }
        return response()->json(["success"=>$success]);
    }

    public function importRepresent(Request $request){
        $file = $request->file('excel');
        $path = $file->getPathname();
        $extension = $file->getClientOriginalExtension();
        $fileType = "";
        $success = false;
        $msg = '';
        $count = 0;
        if($extension == 'xls'){
            $fileType = "Excel5";
        }else if($extension == 'xlsx'){
            $fileType = "Excel2007";
        }else{
            $msg = '文件格式不合法，只能是后缀为xls,xlsx的excel文件';
        }
        $error_num = 0;
        $total_num = 0;
        $repeat_count = 0;
        try{
            $objReader = PHPExcel_IOFactory::createReader($fileType);
            $objPHPExcel = $objReader->load($path);
            $objPHPExcel->setActiveSheetIndex(0);
            $objWorksheet = $objPHPExcel->getActiveSheet();

            $represents = [];

            foreach($objWorksheet->getRowIterator() as $row){
                $rowIndex = $row->getRowIndex();
                if($rowIndex == 1){//第一行为标题
                    continue;
                }
                $total_num ++;
                $name = $objWorksheet->getCell("A".$rowIndex,true)->getValue();
                if(empty($name)){
                    $msg = "单元格‘"."A".$rowIndex."’姓名不能为空";
                    $error_num ++;
                    continue;
                }
                $phone = $objWorksheet->getCell("B".$rowIndex,true)->getValue();
//                if(empty($phone)){
//                    $msg = "单元格‘"."B".$rowIndex."’电话不能为空";
//                    $error_num ++;
//                    continue;
//                }
                $initial = $objWorksheet->getCell("C".$rowIndex,true)->getValue();
                if(empty($initial)){
                    $msg = "单元格‘"."C".$rowIndex."’initial不能为空";
                    $error_num ++;
                    continue;
                }
                $belong_company = $objWorksheet->getCell("G".$rowIndex,true)->getValue();
//                if(empty($belong_company)){
//                    $msg = "单元格‘"."G".$rowIndex."’所属公司不能为空";
//                    $error_num ++;
//                    continue;
//                }
                $represent = RepresentInfo::where('phone',$phone)
                    ->where('initial', $initial)
                    ->where('belong_company', $belong_company)
                    ->first();
                if($represent){
                    $repeat_count++;
                    continue;
                }
                $belong_area = $objWorksheet->getCell("D".$rowIndex,true)->getValue();
                $belong_dbm = $objWorksheet->getCell("E".$rowIndex,true)->getValue();
                $belong_project = $objWorksheet->getCell("F".$rowIndex,true)->getValue();

                //phone作为键，保证phone不会重复
                //$key = strval($phone);
                $key =  $initial;
                if($key){
                    if(!array_key_exists($key,$represents)){
                        $represents[$key] = ["name"=>$name,"phone"=>$phone,"initial"=>$initial,
                            "belong_area"=>$belong_area,"belong_dbm"=>$belong_dbm,
                            "belong_project"=>$belong_project,"belong_company"=>$belong_company];
                    }else{
                        $repeat_count++;
                    }
                }else{
                    $error_num++;
                }

            }
            try{
                //$number = count($represents);
                //$represents = array_unique($represents);
                $count = $this->batchImportRepresent($represents);
                //$count = count($represents);
                //$repeat_count += $number-$count;
                $success = true;
            }catch (\Exception $e){
                $msg = $e->getMessage();
            }
        }catch (\Exception $ex){
            $msg = $ex->getMessage();
        }
        return response()->json(["success"=>$success,"msg"=>$msg,"count"=>$count,"error_count"=>$error_num,"total_count"=>$total_num,"repeat"=>$repeat_count]);
    }

    public function exportRepresent(Request $request){
        $query =  DB::table("mime_represent_info");
        if(!empty($request->get("name"))){
            $query->where("name","like","%".$request->get("name")."%");
        }
        if(!empty($request->get("phone"))){
            $query->where("phone","like","%".$request->get("phone")."%");
        }
        if(!empty($request->get("initial"))){
            $query->where("initial","like","%".$request->get("initial")."%");
        }
        if(!empty($request->get("belong_area"))){
            $query->where("belong_area","like","%".$request->get("belong_area")."%");
        }
        if(!empty($request->get("belong_dbm"))){
            $query->where("belong_dbm","like","%".$request->get("belong_dbm")."%");
        }
        $represents = $query->get();

        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0)->setTitle('代表信息');
        $sheet->setCellValue('A1', '姓名')
            ->setCellValue('B1', '电话')
            ->setCellValue('C1', 'initial')
            ->setCellValue('D1', '所属区域')
            ->setCellValue('E1', '所属DBM')
            ->setCellValue('F1', '所属项目')
            ->setCellValue('G1', '所属公司');
        $row = 2;
        foreach ($represents as $represent){
            $sheet->setCellValue('A'.$row, $represent->name)
                ->setCellValue('B'.$row, $represent->phone)
                ->setCellValue('C'.$row, $represent->initial)
                ->setCellValue('D'.$row, $represent->belong_area)
                ->setCellValue('E'.$row, $represent->belong_dbm)
                ->setCellValue('F'.$row, $represent->belong_project)
                ->setCellValue('G'.$row, $represent->belong_company);
            $row++;

        }
        $sheet->getStyle('A1:G1')->applyFromArray(
            array('fill' 	=> array(
                'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                'color'		=> array('argb' => 'FFCCFFCC')
            )
            )
        );
        $borderStyle = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN));
        $sheet->getStyle('A1:G'.($row-1))->getBorders()->applyFromArray($borderStyle);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="represent.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public');
        $objWriter->save("php://output");
        return;
    }

    private function batchImportRepresent($represents){
        $count = 0;
        DB::beginTransaction();
        try{
            foreach ($represents as $phone=>$rep){
                //$arr = RepresentInfo::where('phone',"=",$rep["phone"])->get();
                $arr = RepresentInfo::where('initial',"=",$rep["initial"])->get();
                if(count($arr) == 0){
                    $represent = new RepresentInfo();
                    $represent->name = $rep["name"];
                    $represent->phone = $rep["phone"];
                    $represent->initial = $rep["initial"];
                    $represent->belong_area = $rep["belong_area"];
                    $represent->belong_dbm = $rep["belong_dbm"];
                    $represent->belong_project = $rep["belong_project"];
                    $represent->belong_company =  $rep["belong_company"];
                    $represent->save();
                    $count++;
                }
                //$this->sendCheckMessage($represent->name,$represent->phone);
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
        return $count;
    }

    public function downloadRepresentExcel(){
        $excel_path = dirname(__FILE__)."/../../import_sample.xls";
        //header('Content-Type: application/vnd.ms-excel');
        return response()->download($excel_path,"import_sample.xls",array("Content-Type"=>"application/vnd.ms-excel"));
    }

    public function getVolunteerCheck(Request $request){
        $activities = DB::select('select id,title from activities');
        $units = DB::select('select id,full_name from units');
        $areas = DB::select('select DISTINCT belong_area as province from mime_represent_info');
        $dbms = DB::select('select DISTINCT belong_dbm from mime_represent_info');
        $query = Volunteer::with('represent','unit')->where('status','=',0);
        if(!empty($request->get("name"))){
            $query->where('name','like','%'.$request->get('name').'%');
        }
        if(!empty($request->get("phone"))){
            $query->where('phone','like','%'.$request->get('phone').'%');
        }
        if(!empty($request->get('belong_area')) || !empty($request->get('belong_dbm'))){
            $query->whereHas('represent',function($query) use($request){
                if(!empty($request->get("belong_area"))){
                    $query->where('belong_area','like','%'.$request->get("belong_area").'%');
                }
                if(!empty($request->get("belong_dbm"))){
                    $query->where('belong_dbm','like','%'.$request->get("belong_dbm").'%');
                }
            });
        }
        $volunteers = $query->paginate(10);
        $volunteers->setPath(url('/admin/check'));
        $query_arr = ["name"=>$request->get("name"),"phone"=>$request->get("phone"),"belong_area"=>$request->get("belong_area"),"belong_dbm"=>$request->get("belong_dbm")];
        return view('admin/check')->with(['volunteers'=>$volunteers,"activities"=>$activities,"units"=>$units,"areas"=>$areas,"dbms"=>$dbms,"query_arr"=>$query_arr]);
    }
    //提交修改
    public function postVolunteer(Request $request){
        $success = false;
        DB::beginTransaction();
        try{
            $post = $request->all();
            $volunteer = Volunteer::find(intval($post["id"]));
            if(empty($volunteer->represent)){
                $represent = new RepresentInfo();
            }else{
                $represent = $volunteer->represent;
            }
            $represent->name = $post["name"];
            $represent->phone = $post["phone"];
            $represent->initial = $post["initial"];
            $represent->belong_area = $post["belong_area"];
            $represent->belong_dbm = $post["belong_dbm"];
            $represent->belong_project = implode(",",$post["belong_project"]);
            $represent->belong_company = $post["belong_company"];
            $represent->save();
            $volunteer->represent_id = $represent->id;
            $volunteer->name = $post["name"];
            $volunteer->phone = $post["phone"];
            $volunteer->save();
            DB::commit();
            $success = true;
        }catch (\Exception $e){
            DB::rollBack();
        }
        return response()->json(["success"=>$success]);
    }

    public function exportCheck(Request $request){
        $query = Volunteer::with('represent')->where('status','=',0);
        if(!empty($request->get("name"))){
            $query->where('name','like','%'.$request->get('name').'%');
        }
        if(!empty($request->get("phone"))){
            $query->where('phone','like','%'.$request->get('phone').'%');
        }
        if(!empty($request->get('belong_area')) || !empty($request->get('belong_dbm'))){
            $query->whereHas('represent',function($query) use($request){
                if(!empty($request->get("belong_area"))){
                    $query->where('belong_area','like','%'.$request->get("belong_area").'%');
                }
                if(!empty($request->get("belong_dbm"))){
                    $query->where('belong_dbm','like','%'.$request->get("belong_dbm").'%');
                }
            });
        }
        $volunteers = $query->get();

        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0)->setTitle('注册审核信息');
        $sheet->setCellValue('A1', '姓名')
            ->setCellValue('B1', '电话')
            ->setCellValue('C1', 'initial')
            ->setCellValue('D1', '所属区域')
            ->setCellValue('E1', '所属DBM')
            ->setCellValue('F1', '所属项目')
            ->setCellValue('G1', '所属公司');
        $row = 2;
        foreach ($volunteers as $volunteer){
            $sheet->setCellValue('A'.$row, $volunteer->name)
                ->setCellValue('B'.$row, $volunteer->phone)
                ->setCellValue('C'.$row, isset($volunteer->represent)?$volunteer->represent->initial:"")
                ->setCellValue('D'.$row, isset($volunteer->represent)?$volunteer->represent->belong_area:"")
                ->setCellValue('E'.$row, isset($volunteer->represent)?$volunteer->represent->belong_dbm:"")
                ->setCellValue('F'.$row, isset($volunteer->represent)?$volunteer->represent->belong_project:"")
                ->setCellValue('G'.$row, isset($volunteer->represent)?$volunteer->represent->belong_company:"");
            $row++;

        }
        $sheet->getStyle('A1:G1')->applyFromArray(
            array('fill' 	=> array(
                'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                'color'		=> array('argb' => 'FFCCFFCC')
            )
            )
        );
        $borderStyle = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN));
        $sheet->getStyle('A1:G'.($row-1))->getBorders()->applyFromArray($borderStyle);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="check.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public');
        $objWriter->save("php://output");
        return;

    }

    //审核通过
    public function checkVolunteer(Request $request,$id){
        $status = intval($request->get("status"));
        $volunteer = Volunteer::find($id);
        $volunteer->status = $status;
        if(empty($volunteer->represent_id)){
            //return response()->json(["success"=>false, "msg"=>"信息不完善"]);
            $respense = RepresentInfo::where('phone',$volunteer->phone)
                        ->first();
            if($respense->id > 0){
                $volunt = Volunteer::where('represent_id', $respense->id)->first();

                if($volunt){
                    $success = false;
                    $msg = "手机号已被使用";
                }
                else{
                    $volunteer->represent_id = $respense->id;
                }
            }
            else{
                $respense = new RepresentInfo();
                $respense->phone = $volunteer->phone;
                $respense->initial = $volunteer->number;
                $respense->name = $volunteer->name;

                $respense->save();

                $volunteer->represent_id=$respense->id;
            }
        }
        $success = $volunteer->save();
        if($success){
            $this->sendCheckMessage($volunteer->name,$volunteer->phone);
        }
        return response()->json(["success"=>$success]);
    }

    function request_post($url = '', $post_data = array()) {
        if (empty($url) || empty($post_data)) {
            return false;
        }
        $o = "";
        foreach ( $post_data as $k => $v )
        {
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);
        $postUrl = $url;
        $curlPost = $post_data;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
    }

    public function test(){
        //$xml_str = $this->sendCheckMessage("wjh",13871408949);
        //$xml = simplexml_load_string($xml_str);
        //return $xml->returnstatus;
        /*$addresses = Address::where('province','01019')->get();
        DB::beginTransaction();
        try{
            foreach ($addresses as $address){
                $a = new Address();
                $a->province = $address->province_id;
                $a->province_id = $address->province;
                $a->city = $address->city_id;
                $a->city_id = $address->city;
                $a->country = $address->country;
                $a->country_id = $address->country_id;
                $a->save();
            }
            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
        }*/
        /*$objReader = PHPExcel_IOFactory::createReader("Excel5");
        $objPHPExcel = $objReader->load("D:\\code\\Volunteer\\Volunteer\\storage\\app\\aa.xls");
        $objPHPExcel->setActiveSheetIndex(0);
        $objWorksheet = $objPHPExcel->getActiveSheet();

        $count = 0;
        foreach($objWorksheet->getRowIterator() as $row){
            $rowIndex = $row->getRowIndex();
            if($rowIndex == 1){//第一行为标题
                continue;
            }

            $province_id = $objWorksheet->getCell("A".$rowIndex,false)->getValue();
            $province = $objWorksheet->getCell("B".$rowIndex,false)->getValue();
            $city_id = $objWorksheet->getCell("C".$rowIndex,false)->getValue();
            $city = $objWorksheet->getCell("D".$rowIndex,false)->getValue();
            $country_id = $objWorksheet->getCell("E".$rowIndex,false)->getValue();
            $country = $objWorksheet->getCell("F".$rowIndex,false)->getValue();

            $address =  Address::where("province_id","=",$province_id)
                ->where("province","=",$province)
                ->where("city_id","=",$city_id)
                ->where("city","=",$city)
                ->where("country_id","=",$country_id)
                ->where("country","=",$country)->get();
            if(count($address) == 0){
                $address = new Address();
                $address->province = $province;
                $address->province_id = $province_id;
                $address->city_id = $city_id;
                $address->city = $city;
                $address->country_id = $country_id;
                $address->country = $country;
                $address->save();
                $count++;
            }

        }

        return "success:".$count;*/
        return $this->sendCheckMessage("wujunhui",13871408949);
    }

    public function sendCheckMessage($userName,$userPhone){
        if(strlen($userPhone) == 11){
            $content = "【迈德移动医学教育】迈德通行证已注册成功，你的账号是".$userPhone."，密码是".substr($userPhone,5);
            $url = "http://120.76.25.160:7788/sms.aspx";
            $post = array("action"=>"send","userid"=>"59","account"=>"medsci","password"=>"medsci123",
                "mobile" =>$userPhone ,"content"=>$content);
            $response = $this->request_post($url,$post);
            $xml = simplexml_load_string($response);
            return $xml->returnstatus;
        }
    }

    public function deleteVolunteer($id){
        $success = Volunteer::destroy($id)>0;
        return response()->json(["success"=>$success]);
    }

    public function getHospitals(Request $request){
        $query = DB::table("mime_hospitals");
        if(!empty($request->get('province'))){
            $province = $request->get('province');
            $find   = '省';
            $pos = strpos($province, $find);
            if ($pos !== false)
                $province =str_replace($find,"",$province);

            $query->where('province','like', "%".$province."%");
        }
        if(!empty($request->get('city'))){
            $city = $request->get('city');
            $find   = '市';
            $pos = strpos($city, $find);
            if ($pos !== false)
                $city =str_replace($find,"",$city);
            $query->where('city','like', "%".$city."%");
        }
        if(!empty($request->get('country'))){
            $country= $request->get('country');
            $find   = '区';
            $pos = strpos($country, $find);
            if ($pos !== false)
                $country =str_replace($find,"",$country);

            $query->where('country','like', "%".$country."%");
        }
        $name = $request->get("name");
        if(!empty($name)){
            $query->where('hospital','like', "%".$name."%");
        }
        $hospitals = $query->paginate(50);
        $hospitals->setPath(url('/admin/hospital'));
        $provinces = DB::select("select distinct province from mime_address");

        $query_arr = ["province"=>$request->get("province"),"city"=>$request->get("city"),"country"=>$request->get("country"), "name"=>$request->get("name")];

        return view('admin/hospitals')->with(['hospitals'=>$hospitals,"provinces"=>$provinces,"query_arr"=>$query_arr]);
    }

    public function getCitiesByProvince(Request $request){
        $province = $request->get("province");
        $cities = DB::select("select distinct city from mime_address where province = :province",["province"=>$province]);
        $arr = [];
        foreach ($cities as $city){
            $arr[] = $city->city;
        }
        return response()->json(["success"=>true,"cities"=>$arr]);
    }
    public function getHospitalByProvince(Request $request){

        $province = $request->input('province');
        $city = $request->input('city');
        $area = $request->input('area');
        $res = [];
        if($province && $city && $area){
            $res = Hospital::where('province', 'like', '%'.$province.'%')
                ->where('city', 'like', '%'.$city.'%')
                ->where('country', 'like', '%'.$area.'%')
                ->get();
        }

        return response()->json(["code"=>200,"data"=>$res]);
    }

    public function getCountriesByCity(Request $request){
        $city = $request->get("city");
        $countries = DB::select("select distinct country from mime_address where city = :city",["city"=>$city]);
        $arr = [];
        foreach ($countries as $country){
            $arr[] = $country->country;
        }
        return response()->json(["success"=>true,"countries"=>$arr]);
    }

    public function postHospital(Request $request){
        $id = $request->get("id");
        $success = false;
        try{
            if(empty($id)){
                $hospital = new Hospital();
            }else{
                $hospital = Hospital::find(intval($id));
            }
            $hospital->hospital = $request->get('name');
            $hospital->province = $request->get('province');
            $hospital->city = $request->get('city');
            $hospital->country = $request->get('country');

            $query = DB::table("mime_address");
            if(!empty($request->get('province'))){
                $query->where('province','=',$request->get('province'));
            }
            if(!empty($request->get('city'))){
                $query->where('city','=',$request->get('city'));
            }
            if(!empty($request->get('country'))){
                $query->where('country','=',$request->get('country'));
            }
            $hospitals = $query->first();

            $hospital->province_id = $hospitals->province_id;
            $hospital->city_id = $hospitals->city_id;
            $hospital->country_id = $hospitals->country_id;
            //$hospital->hospital_id = $this->getHospitalCode($hospitals->province_id,$hospitals->city_id,$hospitals->country_id);

            $success = $hospital->save();
        }catch (\Exception $e){
        }
        return response()->json(["success"=>$success]);
    }

    public  function getHospitalCode($province,$city,$country)
    {

        //$name = $request->input('hospital');

        $hospital = Hospital::where('province_id', $province)
            ->where('city_id', $city)
            ->where('country_id', $country)
            ->orderBy('hospital_id', 'desc')
            ->first();

        if($hospital) {
            $arr = explode('-',$hospital->hospital_id);
//            dd($arr);

//            $length = strlen($hospital->country_id);
//            $strRealId = substr($hospital->hospital_id, $length);
//            $realId = intval($strRealId, 10);
            $realId = intval($arr[1], 10);
            $realId = $realId + 1;
            $temp = sprintf("%02d", $realId);//生成2位数，不足前面补0
            $newId = $arr[0] . '-' . $temp;

            return $newId;
        }
        else {
            $hospitals = Hospital::where('province_id', $province)
                ->orderBy('hospital_id', 'desc')
                ->first();
            if($hospitals){
                $arr = explode('-',$hospitals->hospital_id);

                $address = DB::table('mime_address')->where('province_id', $province)
                    ->where('city_id', $city)
                    ->where('country_id', $country)->first();
//            $length = strlen($hospital->country_id);
//            $strRealId = substr($hospital->hospital_id, $length);
//            $realId = intval($strRealId, 10);
                $realId = floatval($arr[0]);
                $realId = $realId + 1;
                $newId = $realId . '-01';

                return $newId;
            }
            else{
                return '';
            }

            //return response()->json(['result' => '1']);
        }

    }

    public function deleteHospital($id){
        $success = Hospital::destroy($id)>0;
        return response()->json(["success"=>$success]);
    }

    public function exportHospitals(Request $request){
        $query = DB::table("mime_hospitals");
        if(!empty($request->get('province'))){
            $query->where('province','=',$request->get('province'));
        }
        if(!empty($request->get('city'))){
            $query->where('city','=',$request->get('city'));
        }
        if(!empty($request->get('country'))){
            $query->where('country','=',$request->get('country'));
        }
        $name = $request->get("name");
        if(!empty($name)){
            $query->where('hospital','like', "%".$name."%");
        }
        $hospitals = $query->get();
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0)->setTitle('医院信息');
        $sheet->setCellValue('A1', '医院代号')
            ->setCellValue('B1', '医院名称')
            ->setCellValue('C1', '省')
            ->setCellValue('D1', '市')
            ->setCellValue('E1', '区/县')
            ->setCellValue('F1', '医院等级');
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $row = 2;
        foreach ($hospitals as $hospital){
            $sheet->setCellValue('A'.$row, $hospital->id)
                ->setCellValue('B'.$row, $hospital->hospital)
                ->setCellValue('C'.$row, $hospital->province)
                ->setCellValue('D'.$row, $hospital->city)
                ->setCellValue('E'.$row, $hospital->country)
                ->setCellValue('F'.$row, $hospital->hospital_level);
            $row++;

        }
        $sheet->getStyle('A1:F1')->applyFromArray(
            array('fill' 	=> array(
                'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                'color'		=> array('argb' => 'FFCCFFCC')
            )
            )
        );
        $borderStyle = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN));
        $sheet->getStyle('A1:F'.($row-1))->getBorders()->applyFromArray($borderStyle);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="hospital.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public');
        $objWriter->save("php://output");
        return;
    }

    public function getImportHospital(){
        return view('admin.importHospital');
    }

    public function downloadHospitalExcel(){
        $excel_path = dirname(__FILE__)."/../../import_hospital.xls";
        return response()->download($excel_path,"import_hospital.xls",array("Content-Type"=>"application/vnd.ms-excel"));
    }

    public function importHospital(Request $request){
        $file = $request->file('excel');
        $path = $file->getPathname();
        $extension = $file->getClientOriginalExtension();
        $fileType = "";
        $success = false;
        $msg = '';
        $count = 0;
        if($extension == 'xls'){
            $fileType = "Excel5";
        }else if($extension == 'xlsx'){
            $fileType = "Excel2007";
        }else{
            $msg = '文件格式不合法，只能是后缀为xls,xlsx的excel文件';
        }
        $error_num = 0;
        $total_num = 0;
        $repeat_count = 0;
        try{
            $objReader = PHPExcel_IOFactory::createReader($fileType);
            $objPHPExcel = $objReader->load($path);
            $objPHPExcel->setActiveSheetIndex(0);
            $objWorksheet = $objPHPExcel->getActiveSheet();

            $hospitals = [];

            foreach($objWorksheet->getRowIterator() as $row){
                $rowIndex = $row->getRowIndex();
                if($rowIndex == 1){//第一行为标题
                    continue;
                }
                $total_num ++;
                $hospital_code = $objWorksheet->getCell("A".$rowIndex,true)->getValue();
                if(empty($hospital_code)){
                    $msg = "单元格‘"."A".$rowIndex."’医院代号不能为空";
                    $error_num ++;
                    continue;
                }
                $hospital_name = $objWorksheet->getCell("B".$rowIndex,true)->getValue();
                $hospital_level = $objWorksheet->getCell("F".$rowIndex,true)->getValue();
                if(empty($hospital_name)){
                    $msg = "单元格‘"."B".$rowIndex."’医院名称不合法";
                    $error_num ++;
                    continue;
                }

                $province= $objWorksheet->getCell("C".$rowIndex,true)->getValue();
                if(empty($province)){
                    $msg = "单元格‘"."C".$rowIndex."’省不能为空";
                    $error_num ++;
                    continue;
                }
                $add_province = Address::where('province','like','%'.$province.'%')->first();
                if(empty($add_province)){
                    $msg = "找不到省：".$province;
                    $error_num ++;
                    continue;
                }
                $city = $objWorksheet->getCell("D".$rowIndex,true)->getValue();
                if(empty($city)){
                    $msg = "单元格‘"."D".$rowIndex."’市不能为空";
                    $error_num ++;
                    continue;
                }
                $add_city = Address::where('city','like','%'.$city.'%')->first();
                if(empty($add_city)){
                    $msg = "找不到市：".$province;
                    $error_num ++;
                    continue;
                }
                $country = $objWorksheet->getCell("E".$rowIndex,true)->getValue();
                $add_country = Address::where('country','like','%'.$country.'%')->first();

                $exist = Hospital::where([
                    'hospital'=>$hospital_name,
                    'province'=>isset($add_province->province) ? $add_province->province : '',
                    'city'=>isset($add_city->city) ? $add_city->city : '',
                    'country'=>isset($add_country->country) ? $add_country->country : ''
                ])
                    ->where('hospital', "=",$hospital_name)
                    ->first();
                if($exist){
                    $repeat_count++;
                    continue;
                }
                $key = $hospital_code;

                if(!$exist){
                    $hospitals[$key] = ["hospital"=>$hospital_name,"hospital_id"=>$hospital_code,"province"=>$province,
                        "province_id"=>$add_province->province_id,"city"=>$city,
                        "city_id"=>$add_city->city_id,"country"=>empty($country)?"":$country,"country_id"=>isset($add_country)?$add_country->country_id:""];
                }
                else{
                    $repeat_count++;
                }
            }
            try{
                DB::beginTransaction();
                foreach ($hospitals as $hospital){
                    $d = new Hospital();
                    $d->hospital = $hospital["hospital"];
                    //$d->hospital_id = $hospital["hospital_id"];
                    $d->province = $hospital["province"];
                    $d->province_id = $hospital["province_id"];
                    $d->city = $hospital["city"];
                    $d->city_id = $hospital["city_id"];
                    $d->country = $hospital["country"];
                    $d->country_id = $hospital["country_id"];
                    $d->hospital_level = $hospital_level;
                    $d->save();
                }
                DB::commit();
                $count = count($hospitals);
                $success = true;
            }catch (\Exception $e){
                DB::rollBack();
                $msg = $e->getMessage();
            }
        }catch (\Exception $ex){
            $msg = $ex->getMessage();
        }
        return response()->json(["success"=>$success,"msg"=>$msg,"count"=>$count,"error_count"=>$error_num,"total_count"=>$total_num,"repeat"=>$repeat_count]);

    }

    public function doctors(Request $request){
        $hospital_name = $request->get("hospital");
        $province = $request->input('province');
        $city = $request->input('city');
        $area = $request->input('area');
        $query = Doctor::with(["hospital"]);
        if(!empty($hospital_name) || !empty($province)){
            $query->whereHas('hospital',function($query) use($request){
                $hospital_name = $request->get("hospital");
                $province = $request->input('province');
                $city = $request->input('city');
                $area = $request->input('area');
                if(!empty($hospital_name)){
                    $query->where('hospital','like','%'.$hospital_name.'%');
                }
                if(!empty($province)){
                    $query->where('province','like','%'.$province.'%');
                }
                if(!empty($city)){
                    $query->where('city','like','%'.$city.'%');
                }
                if(!empty($area)){
                    $query->where('country','like','%'.$area.'%');
                }
            });
        }
        if(!empty($request->get("name"))){
            $query->where('name','like','%'.$request->get("name").'%');
        }
        if(!empty($request->get("phone"))){
            $query->where('phone','like','%'.$request->get("phone").'%');
        }

        $doctors = $query->paginate(50);
        $doctors->setPath(url('/admin/doctor'));
        $offices = Office::all();
        $provinces = DB::select("select distinct province from mime_address");
        $hospitals = DB::select("select hospital,id from mime_hospitals GROUP BY hospital ORDER BY id limit 0,100");
        $query_arr = [
            "hospital"=>$hospital_name,
            "province"=>$province,
            "city"=>$city,
            "area"=>$area,
            "name"=>$request->get("name"),
            "phone"=>$request->get("phone")
        ];
        return view('admin.doctor')->with(["doctors"=>$doctors,"offices"=>$offices,"hospitals"=>$hospitals,"provinces"=>$provinces,"query_arr"=>$query_arr]);
    }

    public function deleteDoctor($id){
        $success = Doctor::destroy($id)>0;
        return response()->json(["success"=>$success]);
    }

    public function postDoctor(Request $request){
        $id = $request->get("id");
        $success = false;
        try{
            if(empty($id)){
                $doctor = new Doctor();
            }else{
                $doctor = Doctor::findOrFail($id);
            }
            $doctor->name = $request->get("name");
            $doctor->phone = $request->get("phone");
            $doctor->email = $request->get("email");
            $doctor->office = intval($request->get("office"));
            $doctor->hospital_id = intval($request->get("hospital"));
            $success = $doctor->save();
        }catch (\Exception $e){

        }
        return response()->json(["success"=>$success]);
    }

    public function searchHospital(Request $request){
        $q = $request->get('q');
        $items  = DB::table("hospitals")->where('hospital','like','%'.$q.'%')->groupBy('hospital')->select('id','hospital as text')->get();
        $total_count = count($items);
        return response()->json(["total_count"=>$total_count,'items'=>$items,'incomplete_results'=>false]);
    }

    public function exportDoctor(Request $request){
        $query = Doctor::with(["hospital"]);
        if(!empty($request->get("hospital")) || !empty($request->get('province'))){
            $query->whereHas('hospital',function($query) use($request){
                if(!empty($request->get("hospital"))){
                    $query->where('hospital','like','%'.$request->get("hospital").'%');
                }
                if(!empty($request->get('province'))){
                    $query->where('province','like','%'.$request->get('province').'%');
                }
                if(!empty($request->get('city'))){
                    $query->where('city','like','%'.$request->get('city').'%');
                }
                if(!empty($request->get('country'))){
                    $query->where('country','like','%'.$request->get('country').'%');
                }
            });
        }
        if(!empty($request->get("name"))){
            $query->where('name','like','%'.$request->get("name").'%');
        }
        if(!empty($request->get("phone"))){
            $query->where('phone','like','%'.$request->get("phone").'%');
        }
        $doctors = $query->get();

        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0)->setTitle('医生信息');
        $sheet->setCellValue('A1', '姓名')
            ->setCellValue('B1', '电话')
            ->setCellValue('C1', 'email')
            ->setCellValue('D1', 'qq')
            ->setCellValue('E1', '所属医院')
            ->setCellValue('F1', '所属科室');
        $row = 2;
        foreach ($doctors as $doctor){
            $sheet->setCellValue('A'.$row, $doctor->name)
                ->setCellValue('B'.$row, $doctor->phone)
                ->setCellValue('C'.$row, $doctor->email)
                ->setCellValue('D'.$row, $doctor->qq)
                ->setCellValue('E'.$row, isset($doctor->hospital)?$doctor->hospital->hospital:"")
                ->setCellValue('F'.$row, $doctor->office);
            $row++;

        }
        $sheet->getStyle('A1:F1')->applyFromArray(
            array('fill' 	=> array(
                'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                'color'		=> array('argb' => 'FFCCFFCC')
            )
            )
        );
        $borderStyle = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN));
        $sheet->getStyle('A1:F'.($row-1))->getBorders()->applyFromArray($borderStyle);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="doctors.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public');
        $objWriter->save("php://output");
        return;
    }

    public function postKzkt(Request $request){
        $id = $request->get("id");
//        dd($request->all());
        $voluteer = Volunteer::where('phone', $request->get('v_phone'))->first();
        if(!$voluteer){
            DB::rollBack();
            return response()->json(['code'=>500,'msg'=>"代表手机号不存在"]);
        }
        if(empty($id)){
            $kzkt = new KZKTClass();
            $doctor = new Doctor();
        }else{
            $kzkt = KZKTClass::find(intval($id));
            $doctor = Doctor::find(intval($kzkt['doctor_id']));
        }
        DB::beginTransaction();
        try{
            $doctor->name = $request->get('name');
            $doctor->phone = $request->get('phone');
            $doctor->email = $request->get('email');
            $doctor->office = $request->get("office");
            $doctor->hospital_id = $request->get("hospital");
            $doctor->save();

            $kzkt->doctor_id = $doctor->id;
            $kzkt->type = $request->get('type');
            $kzkt->status = $request->get('status');
            $kzkt->volunteer_id = $voluteer->id;
            $kzkt->save();
            DB::commit();
            return response()->json(['code'=>200]);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['code'=>500, 'msg' => '保存失败']);
        }
    }

    public function deleteKzkt($id){
        $success = KZKTClass::destroy($id)>0;
        return response()->json(["success"=>$success]);
    }
    public function getRegister(Request $request)
    {
        $area = $request->get("area");
        $v_phone = $request->get('v_phone');
        $phone = $request->get('phone');
        $v_dbm = $request->get('dbm');
        $query = KZKTClass::with(["volunteer","volunteer.represent","doctor",'doctor.hospital'])->where('status', 1);

        if(!empty($v_phone)){
            $query->whereHas('volunteer',function($query) use($v_phone){
                $query->where('phone','like','%'.$v_phone.'%');
            });
        }
        if(!empty($phone)){
            $query->whereHas('doctor',function($query) use($phone){
                $query->where('phone','like','%'.$phone.'%');
            });
        }
        if(!empty($area)){
            $query->whereHas('volunteer.represent',function($query) use($area){
                $query->where('belong_area','like','%'.$area.'%');
            });
        }
        if(!empty($v_dbm)){
            $query->whereHas('volunteer.represent',function($query) use($v_dbm){
                $query->where('belong_dbm','like','%'.$v_dbm.'%');
            });
        }

        $kzkt = $query->paginate(15);
//dd($kzkt);
        $type = DB::table('type')->get();
        $areas = DB::select('select DISTINCT belong_area as province from mime_represent_info');
        $dbms = DB::select('select DISTINCT belong_dbm from mime_represent_info');

        $offices = Office::all();
        $provinces = DB::select("select distinct province from mime_address");
        $hospitals = DB::select("select hospital,id from mime_hospitals GROUP BY hospital ORDER BY id limit 0,100");

        $kzkt->setPath(url('/admin/baoming'));
        $query_arr = [
            "area"=>$area,
            "phone"=>$phone,
            "v_phone"=>$v_phone,
            "dbm"=>$v_dbm,
        ];
//        dd($kzkt);
        return view('admin.register')->with(['kzkts' => $kzkt,'class' => $type, 'areas'=>$areas,'dbms'=>$dbms,"offices"=>$offices,"query_arr"=>$query_arr]);
    }

    public function getImportKzkt(){
        return view('admin.importKzkt');
    }

    public function downloadKzktExcel(){
        $excel_path = dirname(__FILE__)."/../../import_kzkt_sample.xls";
        return response()->download($excel_path,"import_kzkt_sample.xls",array("Content-Type"=>"application/vnd.ms-excel"));
    }
    public function importKzkt(Request $request){
        $file = $request->file('excel');
        $path = $file->getPathname();
        $extension = $file->getClientOriginalExtension();
        $fileType = "";
        $success = false;
        $msg = '';
        $count = 0;
        if($extension == 'xls'){
            $fileType = "Excel5";
        }else if($extension == 'xlsx'){
            $fileType = "Excel2007";
        }else{
            $msg = '文件格式不合法，只能是后缀为xls,xlsx的excel文件';
        }
        $error_num = 0;
        $total_num = 0;
        $repeat_count = 0;
        try{
            $objReader = PHPExcel_IOFactory::createReader($fileType);
            $objPHPExcel = $objReader->load($path);
            $objPHPExcel->setActiveSheetIndex(0);
            $objWorksheet = $objPHPExcel->getActiveSheet();

            $doctors = [];

            foreach($objWorksheet->getRowIterator() as $row){
                $rowIndex = $row->getRowIndex();
                if($rowIndex == 1){//第一行为标题
                    continue;
                }
                $total_num ++;
                $name = $objWorksheet->getCell("A".$rowIndex,true)->getValue();
                if(empty($name)){
                    $msg .= "单元格‘"."A".$rowIndex."’姓名不能为空<br/>";
                    $error_num ++;
                    continue;
                }
                $phone = $objWorksheet->getCell("B".$rowIndex,true)->getValue();
                if(empty($phone)){
                    $msg .= "单元格‘"."B".$rowIndex."’电话不能为空<br/>";
                    $error_num ++;
                    continue;
                }
                //$doctor_phone = Doctor::
                $email= $objWorksheet->getCell("C".$rowIndex,true)->getValue();
                $qq = $objWorksheet->getCell("D".$rowIndex,true)->getValue();

                $exist = Doctor::where('phone',$phone)
                    ->first();
                if($exist){
                    $msg .= '第'.$rowIndex.'行:医生电话信息已经存在<br/>';
                    $repeat_count++;
                    continue;
                }
                $hospital_name = $objWorksheet->getCell("E".$rowIndex,true)->getValue();
                $hospital = Hospital::where('hospital',$hospital_name)->first();
                if(!$hospital){
                    $msg .= '第'.$rowIndex.'行:医院-' . $hospital_name . '不存在<br/>';
                    $error_num ++;
                    continue;
                }
                $office_name = $objWorksheet->getCell("F".$rowIndex,true)->getValue();
                $office = Office::where('office_name',$office_name)->first();
                $type_name = $objWorksheet->getCell("G".$rowIndex,true)->getValue();
                $kzkt_type = DB::table('type')->where('type_name',$type_name)->first();
                $voluteer_phone = $objWorksheet->getCell("H".$rowIndex,true)->getValue();
                $voluteer = Volunteer::where('phone',$voluteer_phone)->first();
                if(!$voluteer){
                    $msg .= '第'.$rowIndex.'行:手机号-' . $voluteer_phone . '对应的代理不存在<br/>';
                    $error_num ++;
                    continue;
                }
                //$type_id = $kzkt_type

                $doctors[] = ["name"=>$name,"phone"=>$phone,"email"=>$email,
                    "qq"=>$qq,"hospital"=>empty($hospital)?0:$hospital->id,"office"=>empty($office)?0:$office->office_id,
                    "type_id"=>empty($kzkt_type)?0:$kzkt_type->id,"voluteer_id"=>empty($voluteer)?0:$voluteer->id
                ];
            }
            try{
                DB::beginTransaction();
                foreach ($doctors as $doctor){
                    $d = new Doctor();
                    $d->name = $doctor["name"];
                    $d->phone = $doctor["phone"];
                    $d->email = $doctor["email"];
                    $d->qq = $doctor["qq"];
                    $d->hospital_id = $doctor["hospital"];
                    $d->office = $doctor["office"];

                    $d->save();

                    $kzkt = new KZKTClass();
                    $kzkt->doctor_id = $d->id;
                    $kzkt->volunteer_id = $doctor['voluteer_id'];
                    $kzkt->type = $doctor['type_id'];
                    $kzkt->status = 1;
                    $kzkt->save();
                }
                DB::commit();
                $count = count($doctors);
                $success = true;
            }catch (\Exception $e){
                DB::rollBack();
                $msg = $e->getMessage();
            }
        }catch (\Exception $ex){
            $msg = $ex->getMessage();
        }
        return response()->json(["success"=>$success,"msg"=>$msg,"count"=>$count,"error_count"=>$error_num,"total_count"=>$total_num,"repeat"=>$repeat_count]);

    }

    public function exportRegister(Request $request){
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0)->setTitle('报名信息');
        $sheet->setCellValue('A1', '数据来源')
            ->setCellValue('B1', '姓名')
            ->setCellValue('C1', '手机号')
            ->setCellValue('D1', '邮箱')
            ->setCellValue('E1', '报名项目')
            ->setCellValue('F1', '报名课程')
            ->setCellValue('G1', '报名状态')
            ->setCellValue('H1', '所属医院')
            ->setCellValue('I1', '所属科室')
            ->setCellValue('J1', '所在地')
            ->setCellValue('K1', '所属大区')
            ->setCellValue('L1', '所属DBM');
        //$represents = KZKTClass::all();
        $area = $request->get("area");
        $v_phone = $request->get('phone');
        $v_dbm = $request->get('dbm');
        $query = KZKTClass::with(["volunteer","volunteer.represent","doctor",'doctor.hospital'])->where('status', 1);
//        if(!empty($doctor_name)){
//            $query->whereHas('doctor',function($query) use($doctor_name){
//                $query->where('name','like','%'.$doctor_name.'%');
//            });
//        }
        if(!empty($v_phone)){
            $query->whereHas('volunteer',function($query) use($v_phone){
                $query->where('phone','like','%'.$v_phone.'%');
            });
        }
        if(!empty($area)){
            $query->whereHas('volunteer.represent',function($query) use($area){
                $query->where('belong_area','like','%'.$area.'%');
            });
        }

        if(!empty($v_dbm)){
            $query->whereHas('volunteer.represent',function($query) use($v_dbm){
                $query->where('belong_dbm','like','%'.$v_dbm.'%');
            });
        }
        //$kzkt = $query->paginate(15);


        $represents = $query->get();
        $row = 2;
        foreach ($represents as $represent){
            $kc = '';
            if($represent['type'] == '1')
                $kc = '基础班';
            elseif($represent['type'] == '2')
                $kc = '高级班';
            elseif($represent['type'] == '3')
                $kc = '精品班';
            else{

            }

            $status = '';
            if($represent['status'] == '1')
                $status = '报名成功';
            elseif($represent['status'] == '2')
                $status = '报名失败';
            else{

            }

            $sheet->setCellValue('A'.$row, $represent['volunteer']['phone'])
                ->setCellValue('B'.$row, $represent['doctor']['name'])
                ->setCellValue('C'.$row, $represent['doctor']['phone'])
                ->setCellValue('D'.$row, $represent['doctor']['email'])
                ->setCellValue('E'.$row, '空中课堂')
                ->setCellValue('F'.$row, $kc)
                ->setCellValue('G'.$row, $status)
                ->setCellValue('H'.$row, $represent['doctor']['hospital']['hospital'])
                ->setCellValue('I'.$row, $represent['doctor']['office'])
                ->setCellValue('J'.$row, $represent['doctor']['hospital']['province'].$represent['doctor']['hospital']['city'].$represent['doctor']['hospital']['country'])
                ->setCellValue('K'.$row, $represent['volunteer']['represent']['belong_area'])
                ->setCellValue('L'.$row, $represent['volunteer']['represent']['belong_dbm']);
            $row++;

        }
        $sheet->getStyle('A1:G1')->applyFromArray(
            array('fill' 	=> array(
                'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
                'color'		=> array('argb' => 'FFCCFFCC')
            )
            )
        );
        $borderStyle = array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN));
        $sheet->getStyle('A1:G'.($row-1))->getBorders()->applyFromArray($borderStyle);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="kzkt.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public');
        $objWriter->save("php://output");
        return;
    }

    public function downloadDoctorExcel(){
        $excel_path = dirname(__FILE__)."/../../import_doctor_sample.xls";
        return response()->download($excel_path,"import_doctor_sample.xls",array("Content-Type"=>"application/vnd.ms-excel"));
    }

    public function getImportDoctor(){
        return view('admin.importDoctor');
    }

    public function importDoctor(Request $request){
        $file = $request->file('excel');
        $path = $file->getPathname();
        $extension = $file->getClientOriginalExtension();
        $fileType = "";
        $success = false;
        $msg = '';
        $count = 0;
        if($extension == 'xls'){
            $fileType = "Excel5";
        }else if($extension == 'xlsx'){
            $fileType = "Excel2007";
        }else{
            $msg = '文件格式不合法，只能是后缀为xls,xlsx的excel文件';
        }
        $error_num = 0;
        $total_num = 0;
        $repeat_count = 0;
        try{
            $objReader = PHPExcel_IOFactory::createReader($fileType);
            $objPHPExcel = $objReader->load($path);
            $objPHPExcel->setActiveSheetIndex(0);
            $objWorksheet = $objPHPExcel->getActiveSheet();

            $doctors = [];

            foreach($objWorksheet->getRowIterator() as $row){
                $rowIndex = $row->getRowIndex();
                if($rowIndex == 1){//第一行为标题
                    continue;
                }
                $total_num ++;
                $name = $objWorksheet->getCell("A".$rowIndex,true)->getValue();
                if(empty($name)){
                    $msg = "单元格‘"."A".$rowIndex."’姓名不能为空";
                    $error_num ++;
                    continue;
                }
                $phone = $objWorksheet->getCell("B".$rowIndex,true)->getValue();
                if(empty($phone) || strlen($phone) != 11){
                    $msg = "单元格‘"."B".$rowIndex."’电话不合法";
                    $error_num ++;
                    continue;
                }
                $email= $objWorksheet->getCell("C".$rowIndex,true)->getValue();
                $qq = $objWorksheet->getCell("D".$rowIndex,true)->getValue();

                $exist = Doctor::where('name',$name)
                    ->where('phone', $phone)
                    ->first();
                if($exist){
                    $repeat_count++;
                    continue;
                }
                $hospital_name = $objWorksheet->getCell("E".$rowIndex,true)->getValue();
                $hospital = Hospital::where('hospital',$hospital_name)->first();
                $office_name = $objWorksheet->getCell("F".$rowIndex,true)->getValue();
                $office = Office::where('office_name',$office_name)->first();

                $key = $name."_".$phone;
                if(!array_key_exists($key,$doctors)){
                    $doctors[$key] = ["name"=>$name,"phone"=>$phone,"email"=>$email,
                        "qq"=>$qq,"hospital"=>empty($hospital)?null:$hospital->id,"office"=>empty($office)?"":$office->office_id];
                }
                else{
                    $repeat_count++;
                }
            }
            try{
                DB::beginTransaction();
                foreach ($doctors as $doctor){
                    $d = new Doctor();
                    $d->name = $doctor["name"];
                    $d->phone = $doctor["phone"];
                    $d->email = $doctor["email"];
                    $d->qq = $doctor["qq"];
                    $d->hospital_id = $doctor["hospital"];
                    $d->office = $doctor["office"];
                    $d->save();
                }
                DB::commit();
                $count = count($doctors);
                $success = true;
            }catch (\Exception $e){
                DB::rollBack();
                $msg = $e->getMessage();
            }
        }catch (\Exception $ex){
            $msg = $ex->getMessage();
        }
        return response()->json(["success"=>$success,"msg"=>$msg,"count"=>$count,"error_count"=>$error_num,"total_count"=>$total_num,"repeat"=>$repeat_count]);

    }


    public function dataInif(Request $request){
        $success = false;
        $msg = '';
        $count = 0;
        $volunteers = Volunteer::where('represent_id','is','NULL')
            ->where('status','1')->get();
        DB::beginTransaction();
        try {

            foreach($volunteers as $volunteer){
                $count++;
                if(empty($volunteer['phone'])){
                    continue;
                }
                $represent = RepresentInfo::where('phone',$volunteer->phone)
                    ->where('initial',$volunteer->number)
                    ->first();
                if(!$represent){
                    $represent = new RepresentInfo();
                }

                $represent->name = $volunteer['name'];
                $represent->phone = $volunteer['phone'];
                $represent->initial = isset($volunteer['number'])?$volunteer['number'] : '';

                $unit = Unit::where('id',$volunteer['unit_id'])->first();
                $unit_name = '';
                if($unit){
                    $unit_name = $unit->full_name;
                }
                $represent->belong_company = $unit_name;
                //$represent->belong_company = isset($volunteer->unit)?$volunteer->unit->full_name:'';

                //$area = DB::select ("SELECT * FROM `area_details` where represent_code='YIM'")
                if(!empty($volunteer['number'])){
                    $area = DB::table('area_details') -> where('represent_code',$volunteer->number)->first();
                    if($area){
                        $represent->belong_area = $area->province;
                        $represent->belong_dbm  = $area->dbm_code;
                    }
                    $represent->belong_project = '空中课堂,黄埔学堂,医师助手,甲状腺病例讨论,E名医下基层,甲状腺公开课';
                }
                else{
                    $represent->belong_project = '';
                }


                $represent->save();

                $id = $represent->id;

                $volunteer->represent_id = $id;
                $volunteer->save();
            }
            DB::commit();
            $count = count($volunteers);
            $success = true;
        }
        catch (\Exception $e){
            DB::rollBack();
            $msg = $count . $e->getMessage();
        }

        return response()->json(["success"=>$success,"msg"=>$msg]);

    }

    public function addressTable(){
        $addresses = DB::select("select distinct province_id,province,city_id,city,country_id,country from mime_hospitals");

        foreach ($addresses as $address){
            $add  = new Address();
            $add->province_id = $address->province_id;
            $add->province = $address->province;
            $add->city_id = $address->city_id;
            $add->city = $address->city;
            $add->country_id = $address->country_id;
            $add->country = $address->country;
            $add->save();
        }
        return "";
    }



}