
Vue.component('sendresults', {
    props: ['searchid','email'],
    created () {

    },
    data () {
        return {
            guestEmail: '',
            showModal: '',
        }
    },
    methods: {

        setPost () {

            var url = '/api/send/result';

            if( this.guestEmail ){
                var email = this.guestEmail;
            }else{
                email = this.email;
            }

            var postData = {
                email: email,
                searchid: this.searchid,

            };

            $('.sendButton').prop('disabled', true);

            axios.post(url, postData).then(response=>{
                $('#messageResponse').html(response.data);
                this.message = '';
                $('.modal-wrapper').click();
            });

        },


    }

});
