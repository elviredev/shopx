<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Services\AlertService;
use App\Traits\FileUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KycController extends Controller
{
  use FileUploadTrait;

  /**
   * @desc Afficher la page KYC
   * @return View|RedirectResponse
   */
  public function index(): View | RedirectResponse
  {
    if(auth('web')->user()->kyc?->status == 'approved' || auth('web')->user()->kyc?->status == 'pending') {
      return redirect()->route('vendor.dashboard');
    }
    return view('frontend.pages.kyc');
  }

  /**
   * @desc Enregistrer les infos KYC
   * @param Request $request
   * @return RedirectResponse
   */
  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'full_name' => ['required', 'string', 'max:255'],
      'date_of_birth' => ['required', 'date'],
      'gender' => ['required', 'in:male,female'],
      'full_address' => ['required', 'string', 'max:255'],
      'document_type' => ['required', 'in:passport,driving_license,id_card'],
      'document_scan_copy' => ['required', 'mimes:png,pdf,csv,docx', 'max:10000'],
    ]);

    // si user connecté a une KYC, mettre à jour la valeur KYC
    if (Kyc::where('user_id', auth('web')->user()->id)->exists()) {
      $kyc = Kyc::where('user_id', auth('web')->user()->id)->first();
    } else { // sinon, insérer les nouvelles valeurs en bdd
      $kyc = new Kyc();
    }

    $kyc->user_id = auth('web')->user()->id;
    $kyc->full_name = $request->full_name;
    $kyc->status = 'pending';
    $kyc->date_of_birth = $request->date_of_birth;
    $kyc->gender = $request->gender;
    $kyc->full_address = $request->full_address;
    $kyc->document_type = $request->document_type;

    $filePath = $this->uploadPrivateFile($request->file('document_scan_copy'));
    $kyc->document_scan_copy = $filePath;

    $kyc->save();

    AlertService::created('Your KYC has been submitted successfully! Please wait for admin approval.');

    return redirect()->route('vendor.dashboard');
  }
}






