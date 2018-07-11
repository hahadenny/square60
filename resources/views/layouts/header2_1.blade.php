<ul>
    @if (Auth::guest())
    <li>
        <a href="{{ route('login') }}">Sign In</a>
    </li>
    <li>
        <a href="{{ route('register') }}">Sign Up</a>
    </li>
    <li>
        <a href="#" class="showModal modal-button">Submit Listing</a>
    </li>
    @else
        @if(Auth::user()->isAgent())
        <div class="navbar-item has-dropdown is-hoverable">

            <a class="navbar-link">
                Agent Account
            </a>

            <div class="navbar-dropdown">
                <a href="{{ route('profile') }}" class="navbar-item modal-button">
                    My Profile
                </a>
                <a href="{{ route('home') }}" class="navbar-item modal-button">
                    Settings
                </a>
                <hr class="navbar-divider">
                <a href="{{ route('logout') }}" class="navbar-item modal-button">
                    Logout
                </a>
            </div>
        </div>
        
        <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">
                Marketing
            </a>

            <div class="navbar-dropdown">
                <a href="{{ route('upgrade') }}" class="navbar-item modal-button">
                    Upgrade Your Membership
                </a>
                <a href="{{ route('listing') }}" class="navbar-item modal-button">
                    Feature Listings
                </a>
                @if (Auth::user()->isAgentVerified())
                <a href="{{route('nameLabelBilling')}}" class="navbar-item modal-button">
                    Name Label on Building
                </a>
                @else
                <a href="javascript:void(0)" onclick="swal('You agent account is not approved yet. Please give us 24 hours to verify your data.');" class="navbar-item modal-button">
                    Name Label on Building 
                </a>
                @endif
                @if (Auth::user()->premium)
                    @if(Auth::user()->isAgentVerified())
                    <a href="http://www.square60lot.com" target="_blank" class="navbar-item modal-button">
                        Owner Mailing List 
                    </a>
                    @else
                    <a href="javascript:void(0)" onclick="swal('You agent account is not approved yet. Please give us 24 hours to verify your data.');" class="navbar-item modal-button">
                        Owner Mailing List 
                    </a>
                    @endif
                @else 
                <a href="{{ route('upgrade') }}" class="navbar-item modal-button">
                    Owner Mailing List 
                </a>
                @endif
            </div>
        </div>

        <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">
                Tool
            </a>

            <div class="navbar-dropdown">
                <a href="{{ route('listing') }}" class="navbar-item modal-button">
                    My Listings
                </a>
                <a href="{{route('agentBilling')}}" class="navbar-item modal-button">
                    Billing Method
                </a>
                <a href="{{route('openHouse')}}" class="navbar-item modal-button">
                    Open House
                </a>
                @if (Auth::user()->isAgentVerified())
                <a href="{{route('nameLabelBilling')}}" class="navbar-item modal-button">
                    Purchase Name Label
                </a>
                @else
                <a href="javascript:void(0)" onclick="swal('You agent account is not approved yet. Please give us 24 hours to verify your data.');" class="navbar-item modal-button">
                    Purchase Name Label
                </a>
                @endif
                <a href="{{ route('billing') }}" class="navbar-item modal-button">
                    Billing History
                </a>
                <a href="{{route('searchListing')}}" class="navbar-item modal-button">
                    Saved Items
                </a>
                <a href="{{ route('listing') }}" class="navbar-item modal-button">
                    Submit Listings
                </a>
                @if (Auth::user()->premium)
                    @if(Auth::user()->isAgentVerified())
                    <a href="http://www.square60lot.com" target="_blank" class="navbar-item modal-button">
                        Owner Mailing List 
                    </a>
                    @else
                    <a href="javascript:void(0)" onclick="swal('You agent account is not approved yet. Please give us 24 hours to verify your data.');" class="navbar-item modal-button">
                        Owner Mailing List 
                    </a>
                    @endif
                @else 
                <a href="{{ route('upgrade') }}" class="navbar-item modal-button">
                    Owner Mailing List 
                </a>
                @endif
            </div>
        </div>
        @elseif(Auth::user()->isOwner())
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        Owner Account
                    </a>

                    <div class="navbar-dropdown">
                        <a href="{{ route('profileOwner') }}" class="navbar-item modal-button">
                            My Profile
                        </a>
                        <a href="{{ route('home') }}" class="navbar-item modal-button">
                            Settings
                        </a>
                        <hr class="navbar-divider">
                        <a href="{{ route('logout') }}" class="navbar-item modal-button">
                            Logout
                        </a>
                    </div>
                </div>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        Tool
                    </a>

                    <div class="navbar-dropdown">
                        <a href="{{ route('listing') }}" class="navbar-item modal-button">
                            My Listings
                        </a>
                        <a href="{{ route('upgrade') }}" class="navbar-item modal-button">
                            Upgrade Your Membership
                        </a>
                        <a href="{{ route('billing') }}" class="navbar-item modal-button">
                            Billing History
                        </a>

                        <a href="{{route('openHouse')}}" class="navbar-item modal-button">
                            Open House
                        </a>
                        <a href="{{route('searchListing')}}" class="navbar-item modal-button">
                            Saved Item
                        </a>
                        <a href="{{ route('listing') }}" class="navbar-item modal-button">
                            Submit Listings
                        </a>
                    </div>
                </div>
        @elseif(Auth::user()->isMan())
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        Management Account
                    </a>

                    <div class="navbar-dropdown">
                        <a href="{{ route('profileMan') }}" class="navbar-item modal-button">
                            My Profile
                        </a>
                        <a href="{{ route('home') }}" class="navbar-item modal-button">
                            Settings
                        </a>
                        <hr class="navbar-divider">
                        <a href="{{ route('logout') }}" class="navbar-item modal-button">
                            Logout
                        </a>
                    </div>
                </div>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        Tool
                    </a>

                    <div class="navbar-dropdown">
                        <a href="{{ route('listing') }}" class="navbar-item modal-button">
                            My Listings
                        </a>
                        <a href="{{ route('upgrade') }}" class="navbar-item modal-button">
                            Upgrade Your Membership
                        </a>
                        <a href="{{ route('billing') }}" class="navbar-item modal-button">
                            Billing History
                        </a>

                        <a href="{{route('openHouse')}}" class="navbar-item modal-button">
                            Open House
                        </a>
                        <a href="{{route('searchListing')}}" class="navbar-item modal-button">
                            Saved Item
                        </a>
                        <a href="{{ route('listing') }}" class="navbar-item modal-button">
                            Submit Listings
                        </a>
                    </div>
                </div>
        @elseif(Auth::user()->isUser())
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    User Account
                </a>
                <div class="navbar-dropdown">
                    <a href="{{ route('home') }}" class="navbar-item modal-button">
                        Settings
                    </a>
                    <hr class="navbar-divider">
                    <a href="{{ route('logout') }}" class="navbar-item modal-button">
                        Logout
                    </a>
                </div>
            </div>
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    Tool
                </a>

                <div class="navbar-dropdown">
                    <a href="{{route('searchListing')}}" class="navbar-item modal-button">
                        Saved Item
                    </a>
                    <a id="showModalSubmit" class="navbar-item modal-button" style="cursor:pointer;" onclick="$('#modalSubmit').addClass('is-active');">
                        Submit Listing
                    </a>
                </div>
            </div>
        @endif
        @if (Auth::user()->isAdmin())
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    Admin Tools
                </a>

                <div class="navbar-dropdown">
                    <a href="{{ route('buildings') }}" class="navbar-item modal-button">
                        Buildings
                    </a>
                    <a href="{{ route('addBuilding') }}" class="navbar-item modal-button">
                        New Building
                    </a>
                    <a href="{{ route('allRental') }}" class="navbar-item modal-button">
                        Rental Listing
                    </a>
                    <a href="{{ route('allSell') }}" class="navbar-item modal-button">
                        Sell Listing
                    </a>
                    <a href="{{ route('userList') }}" class="navbar-item modal-button">
                        Users List
                    </a>
                    <a href="{{ route('agentsList') }}" class="navbar-item modal-button">
                        Agents List
                    </a>
                    <a href="{{ route('newArticle') }}" class="navbar-item modal-button">
                        New Article
                    </a>
                    <a href="{{ route('articlesList') }}" class="navbar-item modal-button">
                        Article List
                    </a>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="navbar-item modal-button">
                Logout
            </a>
        @endif
@endif
</ul>