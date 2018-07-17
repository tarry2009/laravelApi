<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Mail;
use App\Film;

class FilmController {

/**
* @var $upload_dir_pics
* @var $upload_dir_files
* @var $file_access_base_url
*/
    private $upload_dir_pics;
    private $upload_dir_files;
    private $file_access_base_url;

/**
* Construct the base url and files directories.
*/
    public function __construct(){
        $this->upload_dir_pics = public_path()."/ng_pics/"; // Ending slah '/' is required. 
        $this->file_access_base_url = url('/'); // Ending slah '/' is required.
    }
	
	protected function ValidationResponse( array $errors)
    {
        return response()->json([
            'error' => $errors,
        ], Response::HTTP_BAD_REQUEST);
    }
    
    public function index(Request $request) {
		return response()->json([
            'data' => Film::all(),
            'status' => 200
        ]);
		 
        
    }
	
/**
* Create record. 
* @return json results
*/
    public function create(Request $request) {
		
		$valid = validator($request->only( 'name', 'ticket_price' ), [
        'name' => 'required|string|max:255',
        'ticket_price' => 'required|number',
		]);

		if ($valid->fails()) {
		   return $this->ValidationResponse($valid->errors()->all());
		}
    
	// Allow file to upload 
    $file_pic = 'file_pic';
 	$file_marketing_original_name = ''; 
		 
	if(isset($request->file)){ 
	 $file  = $request->file($file_pic);
	}
	 //time is short :)
	 //$rules = array($file_pic => 'required|file|max:2048|mimes:ace,arc,arj,asf,au,avi,bmp,bz2,cab,cda,css,csv,dmg,doc,docx,dotm,dotx,flv,gif,gpx,gz,hqx,ico,jar,jpeg,jpg,js,kml,m4a,m4v,mid,midi,mkv,mov,mp3,mp4,mpa,mpeg,mpg,ogg,ogv,pages,pcx,pdf,pkg,png,potm,potx,pps,ppt,pptx,ra,ram,rm,rtf,sit,sitx,tar,tgz,tif,tiff,txt,wav,webm,wma,wmv,xls,xlsx,xltm,xltx,zip,zipx');

	if(isset($file)) {  
		$file_marketing_original_name = $file->getClientOriginalName(); 
		$file->move($this->upload_dir_files, $file_marketing_original_name);

	} 
		 
		$Film_model = new \App\Film();  
		$Film_model->name = $request->name;
		$Film_model->description = $request->description; 
		$Film_model->realease_date = $request->realease_date; 
		$Film_model->rating = $request->rating; 
		$Film_model->ticket_price = $request->ticket_price; 
		$Film_model->country = $request->country;
		$Film_model->genre = $request->genre;    
		$Film_model->photo =  $file_pic_original_name; 

		if ($Film_model->save()) {
			 return $this->respond(array('saved'));
		 } 

	 
		 return $this->respond(array('Failed'));
 
    } 
        
}
