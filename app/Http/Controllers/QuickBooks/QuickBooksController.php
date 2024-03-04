<?php

namespace App\Http\Controllers\QuickBooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Data\IPPCustomer;
use QuickBooksOnline\API\Exception\ServiceException;
use QuickBooksOnline\API\Facades\Customer;
use App\Helpers\QuickBooksHelper;

class QuickBooksController extends Controller
{
    /**
     * Show the add customer form.
     */
    public function add(): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        return view('quickbooks.add-customer');
    }

    /**
     * Save the add customer form.
     */
    public function save(Request $request)
    {

        $config = config('quickbooks');
        $quickbooks_db_credentials = QuickBooksHelper::refreshAccessToken();

        $dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' => $config['client_secret'],
            'RedirectURI' => $config['redirect_uri'],
            'accessTokenKey' => $quickbooks_db_credentials['access_token'],
            'refreshTokenKey' => $quickbooks_db_credentials['refresh_token'],
            'QBORealmID' => $config['realm_id'],
            'baseUrl' => $config['base_url'],
        ]);
        $query = "Select * FROM Customer WHERE DisplayName = '{$request->displayName}'";
        $targetCustomerArray = $dataService->Query($query);
//        $targetCustomerArray = $dataService->FindAll('DisplayName');
        if (!empty($targetCustomerArray) && count($targetCustomerArray) > 0 && sizeof($targetCustomerArray) == 1) {
            $theCustomer = current($targetCustomerArray);
            $updateCustomer = Customer::update($theCustomer, [
                'Title' => $request->title ?? $theCustomer->Title,
                'GivenName' => $request->givenName ?? $theCustomer->GivenName,
                'MiddleName' => $request->middleName ?? $theCustomer->MiddleName,
                'FamilyName' => $request->familyName ?? $theCustomer->FamilyName,
                'DisplayName' => $request->displayName ?? $theCustomer->DisplayName,
                'CompanyName' => $request->companyName ?? $theCustomer->CompanyName,
                'PrimaryEmailAddr' => [
                    'Address' => $request->email ?? $theCustomer?->PrimaryEmailAddr?->Address
                ],
                'PrimaryPhone' => [
                    'FreeFormNumber' => $request->primaryPhone ?? $theCustomer?->PrimaryPhone?->FreeFormNumber
                ],
                'BillAddr' => [
                    'Line1' => $request->address ?? $theCustomer?->BillAddr?->Line1,
                    'City' => $request->city ?? $theCustomer?->BillAddr?->City,
                    'Country' => $request->country ?? $theCustomer?->BillAddr?->Country,
                    'CountrySubDivisionCode' => $request->countrySubDivisionCode ?? $theCustomer?->BillAddr?->CountrySubDivisionCode,
                    'PostalCode' => $request->postalCode ?? $theCustomer?->BillAddr?->PostalCode,
                ],
            ]);

            try {
                $updatedResult = $dataService->Update($updateCustomer);
                $error = $dataService->getLastError();
                if ($error) {
                    return redirect()->back($error->getHttpStatusCode())->with('message', $error->getOAuthHelperError());
                    // echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    // echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                    // echo "The Response message is: " . $error->getResponseBody() . "\n";
                }
                return redirect()->back(201)->with('message', 'Successfully Updated Customer.')->with('data', $updatedResult);
            } catch (ServiceException $e) {
                return redirect()->back(302)->with('message', 'Error message: ' . $e->getMessage());
            }
        } else {
            $addCustomer = Customer::create([
                'Title' => $request->title ?? null,
                'GivenName' => $request->givenName ?? null,
                'MiddleName' => $request->middleName ?? null,
                'FamilyName' => $request->familyName ?? null,
                'DisplayName' => $request->displayName ?? null,
                'CompanyName' => $request->companyName ?? null,
                'PrimaryEmailAddr' => ['Address' => $request->email ?? null],
                'PrimaryPhone' => ['FreeFormNumber' => $request->primaryPhone ?? null],
                'BillAddr' => [
                    'Line1' => $request->address ?? null,
                    'City' => $request->city ?? null,
                    'Country' => $request->country ?? null,
                    'CountrySubDivisionCode' => $request->countrySubDivisionCode ?? null,
                    'PostalCode' => $request->postalCode ?? null,
                ],
            ]);
            try {
                $addedResult = $dataService->Add($addCustomer);
                $error = $dataService->getLastError();
                if ($error) {
                    return redirect()->back($error->getHttpStatusCode())->with('message', $error->getOAuthHelperError());
                    // echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    // echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                    // echo "The Response message is: " . $error->getResponseBody() . "\n";
                }
                return redirect()->back(201)->with('message', 'Successfully Added Customer.')->with('data', $addedResult);
            } catch (ServiceException $e) {
                return redirect()->back(302)->with('message', 'Error message: ' . $e->getMessage());
            }
        }
    }
}
