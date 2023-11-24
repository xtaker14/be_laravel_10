<?php

namespace App\Repositories;

use App\Interfaces\OrganizationRepositoryInterface;
use App\Models\Organization;
use App\Models\OrganizationDetail;
use App\Models\UserPartner;
use App\Models\Subdistrict;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    public function getAllOrganization()
    {
        return Organization::all();
    }

    public function dataTableOrganization()
    {
        
    }

    public function getOrganizationById($OrganizationId)
    {
        return Organization::findOrFail($OrganizationId);
    }

    public function getOrganizationByUser($userId)
    {
        return Organization::whereHas('partners.userspartners', function (Builder $query) use($userId) {
            $query->where('users_id', $userId);
        })->first();
    }

    public function getOrganizationDefault()
    {
        return Organization::orderBy('organization_id','asc')->first();
    }

    public function getOrganizationSummary($organizationId)
    {
        $organization = Organization::find($organizationId);

        $data = [];

        if ($organization) {
            $data['hub'] = $organization->hubs()->count();
            $data['vendor'] = $organization->partners()->count();


            $role_driver = Role::where('name','COURIER')->first();
            $data['courier'] = UserPartner::whereIn('partner_id', $organization->partners()->pluck('partner_id','partner_id'))
            ->whereHas('user', function (Builder $query) use($role_driver){
                $query->where('role_id', $role_driver->role_id);
            })->count();
            $data['destination'] = Subdistrict::count();
            $data['origin'] = Subdistrict::count();
            $data['user'] = UserPartner::whereIn('partner_id', $organization->partners()->pluck('partner_id','partner_id'))->count();
        } else {
            $data['hub'] = 0;
            $data['vendor'] = 0;
            $data['courier'] = 0;
            $data['destination'] = 0;
            $data['origin'] = 0;
            $data['user'] = 0;
        }

        return $data;
    }

    public function deleteOrganization($OrganizationId)
    {
        Organization::destroy($OrganizationId);
    }

    public function createOrganization(array $OrganizationDetails)
    {
        return Organization::create($OrganizationDetails);
    }

    public function updateOrganization($OrganizationId, array $newDetails)
    {
        return Organization::whereId($OrganizationId)->update($newDetails);
    }

    public function updateOrganizationDetail($OrganizationId, $request)
    {
        $organization = Organization::find($OrganizationId);
        if ($organization) {
            $organizationDetailCheck = OrganizationDetail::where('organization_id', $organization->organization_id)->first();
            if ($organizationDetailCheck) {
                $organizationDetail = OrganizationDetail::find($organizationDetailCheck->organization_detail_id);

                $old_company_logo = $organizationDetailCheck->company_logo;
                $old_background_login = $organizationDetailCheck->background_login;
                $old_dokumen_logo = $organizationDetailCheck->dokumen_logo;
            } else {
                $organizationDetail = new OrganizationDetail;
                $organizationDetail->organization_id = $OrganizationId;
                $organizationDetail->created_by = Auth::user()->full_name;

                $old_company_logo = 'old.png';
                $old_background_login = 'old.png';
                $old_dokumen_logo = 'old.png';
            }

            $organizationDetail->company_name = $request->input('company-name');
            $organizationDetail->application_name = $request->input('application-name');
            $organizationDetail->country_id = $request->input('company-country');
            $organizationDetail->province_id = $request->input('company-province');
            $organizationDetail->address = $request->input('company-address');
            $organizationDetail->postal_code = $request->input('postal-code');
            $organizationDetail->phone_number = $request->input('country-code').$request->input('phone-number');
            $organizationDetail->fax = $request->input('fax');
            $organizationDetail->bank_name = $request->input('company-bank-name');
            $organizationDetail->bank_account = $request->input('company-bank-account');
            $organizationDetail->bank_account_address = $request->input('company-bank-accounts-address');
            $organizationDetail->bank_account_name = $request->input('company-bank-accounts-name');
            $organizationDetail->number_of_employees = $request->input('number-of-employees');
            $organizationDetail->website_company = $request->input('website-company');
            $organizationDetail->email_company = $request->input('email-company');
            $organizationDetail->instagram_account = $request->input('instagram-account');
            $organizationDetail->twitter_account = $request->input('twitter-account');
            $organizationDetail->facebook_account = $request->input('facebook-account');
            $organizationDetail->linkedin_account = $request->input('linkedin-account');

            if ($request->hasFile('company-logo')) {
                $companyLogoName = 'company-logo-'.time().'.'.$request->file('company-logo')->extension();
                if(file_exists(public_path().'/storage/'. $old_company_logo)){
                    unlink(public_path().'/storage/'. $old_company_logo);
                }
                $request->file('company-logo')->storeAs('public/images/logo', $companyLogoName) ; 
                $organizationDetail->company_logo = 'images/logo/'.$companyLogoName;
            }
            if ($request->hasFile('background-login')) {
                $backgroundLoginName = 'background-login-'.time().'.'.$request->file('background-login')->extension();
                if(file_exists(public_path().'/storage/'.$old_background_login)){
                    unlink(public_path().'/storage/'.$old_background_login);
                }
                $request->file('background-login')->storeAs('public/images/logo', $backgroundLoginName);
                $organizationDetail->background_login = 'images/logo/'.$backgroundLoginName;
            }
            if ($request->hasFile('dokumen-logo')) {
                $dokumenLogoName = 'dokumen-logo-'.time().'.'.$request->file('dokumen-logo')->extension();
                if(file_exists(public_path().'/storage/'.$old_dokumen_logo)){
                    unlink(public_path().'/storage/'.$old_dokumen_logo);
                }
                $request->file('dokumen-logo')->storeAs('public/images/logo', $dokumenLogoName);
                $organizationDetail->dokumen_logo = 'images/logo/'.$dokumenLogoName;
            }

            $organizationDetail->modified_by = Auth::user()->full_name;
            return $organizationDetail->save();
        } else {
            return false;
        }
    }
    
}