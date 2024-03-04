@extends('layouts.quickbooks.app')

@section('content')
    <div class="container">
        <div class="py-2 text-right">
            <a href="{{url('/')}}" class="btn btn-dark">
                <i class="bi bi-arrow-left-square-fill"></i>
                Back
            </a>
        </div>
        <div class="py-5 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" fill="currentColor" class="bi bi-book"
                 viewBox="0 0 16 16">
                <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
            </svg>
            <h2>QuickBooks Implementation</h2>
            <p class="lead">Below is an example form that was integrated with the Quickbooks.</p>
        </div>

        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10 order-md-1">
                <h4 class="mb-3">Add Customer Form</h4>
                @if(session()->has('message'))
                    <p class="alert alert-success"> {{ session()->get('message') }}</p>
                @endif
                <form class="needs-validation" method="post" action="{{route('quickbooks.save-customer')}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-1 mb-3">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Mr." value="">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="givenName">First Name</label>
                            <input type="text" class="form-control" id="givenName" name="givenName" value="">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="middleName">Middle Name</label>
                            <input type="text" class="form-control" id="middleName" name="middleName" value="">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="familyName">Last name</label>
                            <input type="text" class="form-control" id="familyName" name="familyName" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="displayName">Display Name</label>
                            <input type="text" class="form-control" id="displayName" name="displayName" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="companyName">Company Name</label>
                            <input type="text" class="form-control" id="companyName" name="companyName" value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email">Email </label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="you@example.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="primaryPhone">Primary Phone</label>
                            <input type="text" class="form-control" id="primaryPhone" name="primaryPhone" value="">
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St">
                        </div>
                        <div class="col-md-5">
                            <label for="country" class="form-label">Country</label>
                            <select class="custom-select d-block w-100" id="country" name="country">
                                <option value="">Choose...</option>
                                <option value="US">United States</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="countrySubDivisionCode" class="form-label">State</label>
                            <select class="custom-select d-block w-100" id="countrySubDivisionCode" name="countrySubDivisionCode">
                                <option value="">Choose...</option>
                                <option value="CA">California</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="postalCode" class="form-label">Zip</label>
                            <input type="text" class="form-control" name="postalCode" id="postalCode">
                        </div>
                    </div>

                    <hr class="mb-4">
                    <button class="btn btn-dark btn-lg btn-block" type="submit">Submit Customer</button>
                </form>
            </div>
        </div>

        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p class="mb-1">© 2017-2018 Company Name</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#">Privacy</a></li>
                <li class="list-inline-item"><a href="#">Terms</a></li>
                <li class="list-inline-item"><a href="#">Support</a></li>
            </ul>
        </footer>
    </div>
@endsection
