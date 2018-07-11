<nav class="navbar is-info is-bold " role="navigation" aria-label="dropdown navigation">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-item" href="/">
                <img src="{{ asset('images/square_60_logo2.png') }}" alt="LOGO" width="112" height="28">
            </a>

            <div class="navbar-burger burger" data-target="navMenuTransparentExample">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <div id="navMenuTransparentExample" class="navbar-menu">
            <div class="navbar-start">

            </div>

            <div class="navbar-end">

                @if (Auth::guest())
                    <a href="{{ route('login') }}" class="navbar-item modal-button" data-target="#modal">Sign in</a>
                    <div class="navbar-item">/</div>
                    <a href="{{ route('register') }}" class="navbar-item modal-button" data-target="#modal">Sign up</a>
                    <a id="showModal" class="navbar-item modal-button">
                        Submit Listing
                    </a>
                @else
                        @if(Auth::user()->isAgent())
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                Marketing
                            </a>

                            <div class="navbar-dropdown">
                                <a href="{{ route('upgrade') }}" class="navbar-item modal-button">
                                    Upgrade to expert
                                </a>
                                <a href="{{ route('listing') }}" class="navbar-item modal-button">
                                    Feature listining
                                </a>
                                <a href="{{route('nameLabelBilling')}}" class="navbar-item modal-button">
                                    Name label on building
                                </a>
                            </div>
                        </div>

                        <div class="navbar-item has-dropdown is-hoverable">

                            <a class="navbar-link">
                                Agent Account
                            </a>

                            <div class="navbar-dropdown">
                                <a href="{{ route('profile') }}" class="navbar-item modal-button">
                                    My profile
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
                                    My listing
                                </a>
                                <a href="{{route('agentBilling')}}" class="navbar-item modal-button">
                                    Billing
                                </a>
                                <a href="{{route('openHouse')}}" class="navbar-item modal-button">
                                    Open House
                                </a>
                                <a href="{{route('nameLabelBilling')}}" class="navbar-item modal-button">
                                    Purchase name label
                                </a>
                                <a href="{{ route('billing') }}" class="navbar-item modal-button">
                                    Billing history
                                </a>
                                <a href="{{route('searchListing')}}" class="navbar-item modal-button">
                                    Saved items
                                </a>
                                <a href="{{ route('listing') }}" class="navbar-item modal-button">
                                    Submit Listing
                                </a>
                            </div>
                        </div>
                        @elseif(Auth::user()->isOwner())
                                <div class="navbar-item has-dropdown is-hoverable">
                                    <a class="navbar-link">
                                       Owner Account
                                    </a>

                                    <div class="navbar-dropdown">
                                        <a href="{{ route('profileOwner') }}" class="navbar-item modal-button">
                                            My profile
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
                                            Listing
                                        </a>
                                        <a href="{{ route('billing') }}" class="navbar-item modal-button">
                                            Billing
                                        </a>

                                        <a href="{{route('openHouse')}}" class="navbar-item modal-button">
                                            Open House
                                        </a>
                                        <a href="{{ route('listing') }}" class="navbar-item modal-button">
                                            Submit Listing
                                        </a>
                                    </div>
                                </div>
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                Saved item
                            </a>

                            <div class="navbar-dropdown">
                                <a href="{{route('searchListing')}}" class="navbar-item modal-button">
                                    Saved Search
                                </a>
                                <a href="#" class="navbar-item modal-button">
                                    Saved Building
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
                                Saved item
                            </a>

                            <div class="navbar-dropdown">
                                <a href="{{route('searchListing')}}" class="navbar-item modal-button">
                                    Saved Search
                                </a>
                                <a href="#" class="navbar-item modal-button">
                                    Saved Building
                                </a>
                            </div>
                        </div>
                        <a id="showModalSubmit" class="navbar-item modal-button">
                            Submit Listing
                        </a>
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
                                            Rental listing
                                        </a>
                                        <a href="{{ route('allSell') }}" class="navbar-item modal-button">
                                            Sell listing
                                        </a>
                                        <a href="{{ route('userList') }}" class="navbar-item modal-button">
                                            Users list
                                        </a>
                                        <a href="{{ route('agentsList') }}" class="navbar-item modal-button">
                                            Agents list
                                        </a>
                                        <a href="{{ route('newArticle') }}" class="navbar-item modal-button">
                                            New Article
                                        </a>
                                        <a href="{{ route('articlesList') }}" class="navbar-item modal-button">
                                            Article list
                                        </a>
                                    </div>
                                </div>
                                <a href="{{ route('logout') }}" class="navbar-item modal-button">
                                    Logout
                                </a>
                            @endif
                @endif

                <div id="modalMain" class="modal">
                    <div class="modal-background"></div>
                    <div class="modal-content">
                        <div class="box has-text-centered">
                            <p>Please <a href="{{ route('login') }}">sign in</a> if you are already a member<br>
                             or <a href="{{ route('register') }}">sign up</a> to become a member</p>

                        </div>
                    </div>
                    <button class="modal-close"></button>
                </div>

                    <div id="modalSubmit" class="modal">
                        <div class="modal-background"></div>
                        <div class="modal-content">
                            <div class="box has-text-centered">
                                Do you want become owner or agent?
                                <br>
                                <br>
                                <form action="{{route('changeRole')}}" method="POST">
                                    {{ csrf_field() }}

                                    <div class="buttons has-addons">
                                        <label class="button is-success is-selected">
                                            <input type="radio" name="type" value="2" checked>
                                            Owner
                                        </label>
                                        <label class="button">
                                            <input type="radio" name="type" value="3" >
                                            Agent
                                        </label>
                                    </div>
                                    <br>
                                    <input type="submit" value="submit" class="button is-primary">
                                </form>

                            </div>
                        </div>
                        <button class="modal-close"></button>
                    </div>

            </div>

            <a class="white" href="https://github.com/jgthms/bulma/archive/0.5.3.zip">

            </a>
        </div>
    </div>

</nav>
