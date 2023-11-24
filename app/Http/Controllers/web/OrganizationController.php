<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\OrganizationRepositoryInterface;
use App\Interfaces\CountryRepositoryInterface;
use App\Interfaces\ProvinceRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App;

class OrganizationController extends Controller
{
    private OrganizationRepositoryInterface $organizationRepository;

    public function __construct(OrganizationRepositoryInterface $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organization = $this->organizationRepository->getOrganizationByUser(Auth::user()->users_id);

        $summary = $this->organizationRepository->getOrganizationSummary($organization->organization_id);

        $countryRepository = App::make('App\Interfaces\CountryRepositoryInterface');
        $countries = $countryRepository->getPluckCountry('country_id','name');

        $provinceRepository = App::make('App\Interfaces\ProvinceRepositoryInterface');
        $provinces = $provinceRepository->getPluckProvince('province_id','name');

        return view('content.configuration.organization.index', compact('organization','summary','countries','provinces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'company-name' => 'required',
            'company-logo' => 'image|mimes:jpeg,png|max:2048',
            'background-login' => 'image|mimes:jpeg,png|max:2048',
            'dokumen-logo' => 'image|mimes:jpeg,png|max:2048',
        ]);

        $summary = $this->organizationRepository->updateOrganizationDetail($id, $request);
        if ($summary) {
            return redirect()->route('configuration.organization.index')->with('success','Success update detail organization');
        } else {
            return redirect()->route('configuration.organization.index')->with('failed','Failed update detail organization');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
