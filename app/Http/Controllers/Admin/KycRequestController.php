<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kyc;
use App\Services\AlertService;
use App\Services\MailService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class KycRequestController extends Controller
{
  /**
   * @desc Afficher la page de la liste des KYC
   * @return View
   */
  public function index(): View
  {
    $kycRequests = Kyc::paginate(10);

    return view('admin.kyc.index', compact('kycRequests'));
  }

  /**
   * @desc Afficher la page des demandes KYC en attente
   * @return View
   */
  public function pending(): View
  {
    $kycRequests = Kyc::whereStatus('pending')->paginate(10);

    return view('admin.kyc.pending', compact('kycRequests'));
  }

  /**
   * @desc Afficher la page des demandes KYC rejetées
   * @return View
   */
  public function rejected(): View
  {
    $kycRequests = Kyc::whereStatus('rejected')->paginate(10);

    return view('admin.kyc.rejected', compact('kycRequests'));
  }

  /**
   * @desc Afficher la page des demandes KYC acceptées
   * @return View
   */
  public function approved(): View
  {
    $kycRequests = Kyc::whereStatus('approved')->paginate(10);

    return view('admin.kyc.approved', compact('kycRequests'));
  }

  /**
   * @desc Afficher les détails de la demande KYC
   * @param Kyc $kyc_request
   * @return View
   */
  public function show(Kyc $kyc_request): View
  {
    return view('admin.kyc.show', compact('kyc_request'));
  }

  /**
   * @desc Télécharger le document de la demande KYC
   * @param Kyc $kyc_request
   * @return StreamedResponse
   */
  public function download(Kyc $kyc_request): StreamedResponse
  {
    return Storage::disk('local')->download($kyc_request->document_scan_copy);
  }

  /**
   * Mettre à jour le statut de la demande KYC et envoyer un mail au vendeur pour l'informer
   * @param Kyc $kyc_request
   * @param Request $request
   * @return RedirectResponse
   */
  public function update(Kyc $kyc_request, Request $request): RedirectResponse
  {
    $kyc_request->update([
      'status' => $request->status,
    ]);

    if($kyc_request->status == 'approved') {
      MailService::send(
        to: $kyc_request->user->email,
        subject: 'KYC Application has been Approved',
        body: 'Congratulations! Your KYC application has been approved.'
      );
    } else if($kyc_request->status == 'rejected') {
      MailService::send(
        to: $kyc_request->user->email,
        subject: 'KYC Application has been Rejected',
        body: 'Sorry! Your KYC application has been rejected.'
      );
    }

    AlertService::updated('Status updated successfully.');

    return redirect()->route('admin.kyc.index');
  }
}



