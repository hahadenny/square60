
    <div slot="header">
        <div>
            <a class="modal-close" type="button" @click="showModal = false"></a>
        </div>
        <h3 class="has-text-centered">For more info</h3>
    </div>
    <div slot="body">
        <send inline-template v-bind:agentemail="'@if(isset($agent)){{$agent->email}}@else{{''}}@endif'" v-bind:name="'Guest'" v-bind:listingid="{{$result->id}}">
            <div>
                <div class="field">
                    <label class="label">Email</label>
                    <div class="control has-icons-left ">
                        <input v-model="useremail" class="input" type="email" placeholder="Email input" name="email" value="" required>
                        <span class="icon is-small is-left">
                                            <i class="fa fa-envelope"></i>
                                        </span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Phone</label>
                    <div class="control has-icons-left ">
                        <input v-model="phone" class="input" type="text" placeholder="Phone input" name="phone" value="" required>
                        <span class="icon is-small is-left">
                                        <i class="fa fa-phone"></i>
                                    </span>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Message</label>
                    <div class="control has-icons-left ">
                        <textarea v-model="message" class="textarea" placeholder="Message input" name="message" rows="3" required></textarea>
                    </div>
                </div>
                <button id="sendButton" class="button is-primary" type="button" v-on:click="setPost">Send</button><span id="messageResponse"></span>
            </div>
        </send>
    </div>

    <div slot="footer">

    </div>
<modal v-if="showModal" @close="showModal = false">
</modal>