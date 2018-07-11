<modal v-if="showModal" @close="showModal = false">
<div slot="header">
    <div class="is-size-3 has-text-centered">Sign in</div>
</div>
<div slot="body">
    <div class="main-content columns is-mobile is-centered">
        <div class="column is-narrow">
            <div class="hero">
                <div class="box">
                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control has-icons-left ">
                                <input class="input" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                            <span class="icon is-small is-left">
                                  <i class="fa fa-envelope"></i>
                                </span>
                            </div>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="field">
                            <label class="label">Password</label>
                            <div class="control">
                                <input class="input" id="password" type="password" name="password" required>
                            </div>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>

                        <div class="field">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                        </div>

                        <button type="submit" class="button is-primary">Login</button>

                        <br />
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a> If you aren't member <a class="btn btn-link" href="{{ route('register') }}">
                            registered
                        </a>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div slot="footer">
    <button class="button close" type="button" @click="showModal = false">Close</button>
</div>
</modal>