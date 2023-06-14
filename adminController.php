<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\RegistForm;

class adminController extends Controller
{
    public function registform()
    {

        // memanggil view tambah
        return view('registform');

    }

	public function uploadproses(Request $request){
		$this->validate($request, [
			'idcard' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
            'payproof' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
            'eventPoster' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
            'logo' => 'required|file|image|mimes:jpeg,png,jpg|max:2048',
			//'keterangan' => 'required',
		]);

		// menyimpan data file yang diupload ke variabel $file
		$idcard = $request->file('idcard');
		$nama_idcard = $idcard->getClientOriginalName();

      	// isi dengan nama folder tempat kemana file diupload
		$tujuan_idcard = 'ID_CARD_DB';
		$idcard->move($tujuan_idcard,$nama_idcard);

        $payproof = $request->file('payproof');
		$nama_payproof = $payproof->getClientOriginalName();
        $tujuan_payproof = 'PAYMENT_PROOF_DB';
		$payproof->move($tujuan_payproof,$nama_payproof);

        $eventPoster = $request->file('eventPoster');
		$nama_poster = $eventPoster->getClientOriginalName();
        $tujuan_poster = 'poster_event';
		$eventPoster->move($tujuan_poster,$nama_poster);

        $logo = $request->file('logo');
		$nama_logo = $logo->getClientOriginalName();
        $tujuan_logo = 'logo_event';
		$logo->move($tujuan_logo,$nama_logo);

		DB::table('regist_forms')->insert([
            'id_form' => $request->id,
            'organizer' => $request->organizer,
            'address' => $request->address,
            'contact' => $request->contact,
            'email' => $request->email,
            'identity_card' => $idcard,
            'title' => $request->title,
            'event_location' => $request->eventlocation,
            'category' => $request->category,
            'ticket_price' => $request->ticketprice,
            'start_date' => $request->startdate,
            'end_date' => $request->enddate,
            'event_detail' => $request->eventdetail,
            'event_website' => $request->linkweb,
            'event_logo' => $logo,
            'event_poster' => $eventPoster,
            'account_number' => $request->accountNumber,
			'payment_proof' => $payproof,
		]);

		return redirect()->back()->with('message','Event Registered');
        return view('dashboardadmin',['regist_forms' => $regist_forms]);
	}

    // public function getEvent(){
    //     $getEvent = reg_form::Where('status', "1")->get();
    //     //if you want to get contacts on where condition use below code
    //     //$contacts = Contact::Where('tenant_id', "1")->get();
    //     return view('dashboardadmin', compact('reg_form'));
    // }
    //public function storeEvent(Request $request){
     //validation goes here
    //         $storeEvent = events::create([
    //         'id_form'=> $regist_forms->id_form,
    //         'title' => $regist_forms->title,
    //         'event_location' => $regist_forms->eventlocation,
    //         'category' => $regist_forms->category,
    //         'ticket_price' => $regist_forms->ticketprice,
    //         'start_date' => $regist_forms->startdate,
    //         'end_date' => $regist_forms->enddate,
    //         'event_detail' => $regist_forms->eventdetail,
    //         'event_website' => $regist_forms->linkweb,
    //         'event_logo' => $regist_forms->$logo,
    //         'event_poster' => $regist_forms->$eventPoster,
    //          ]);
    // }
    //     events->regist_forms->save();
    //     }

    public function dashboard_admin()
    {
        $regist_forms = DB::table('regist_forms')->get();
        // memanggil view tambah
        return view('dashboardadmin',['regist_forms' => $regist_forms]);

    }

    public function search(Request $request)
	{
		// menangkap data pencarian
		$search = $request->search;

		$event = DB::table('event')
		->where('eventName','like',"%".$search."%")
		->paginate();

		return view('resultspage',['event' => $event]);

	}
}
