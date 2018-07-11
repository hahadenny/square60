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
            Do you want become Owner or Agent?
            <br>
            <br>
            <form id="changeRoleForm" name="changeRoleForm" action="{{route('changeRole')}}" method="POST">
                {{csrf_field()}}
                <div class="buttons has-addons">
                    <label class="button is-success is-selected" for="type_2">
                        <input id="type_2" type="radio" name="type" value="2" checked>
                        Owner
                    </label>
                    <label class="button" for="type_3">
                        <input id="type_3" type="radio" name="type" value="3" autocomplete=off>
                        Agent
                    </label>
                </div>
                <br>
                <input type="submit" value="Submit" class="button is-primary" onclick="submitRole();return false;">
            </form>
        </div>
    </div>
    <button class="modal-close"></button>
</div>