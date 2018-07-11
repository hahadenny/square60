
Vue.component('send', {
    props: ['name','listingid', 'responseMessage','agentemail'],
    created () {

    },
    data () {
        return {
            useremail: '',
            message: '',
            phone: '',
        }
    },
    methods: {

        setPost () {

            var url = '/send';

            var postData = {
                name: this.name,
                useremail: this.useremail,
                phone: this.phone,
                message: this.message,
                listingid: this.listingid,
                agentemail: this.agentemail,
            };

            $('#sendButton').prop('disabled', true);

            axios.post(url, postData).then(response=>{
                $('#sendButton').prop('disabled', false);
                $('#messageResponse').html(response.data);
                if (response.data.match(/success/g))
                    this.message = '';
                $('.modal-wrapper').click();
            });

        },


    }

});