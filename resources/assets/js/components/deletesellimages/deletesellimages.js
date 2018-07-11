
Vue.component('deletesellimages', {
    props: ['num','list','names'],
    data () {
        return {

        }
    },
    methods: {

        deleteImage() {

            var url = '/api/delete/sell/image';

            var postData = {
                key: this.num,
                id: this.list,
                name: this.names,
            };

            if (document.getElementsByClassName(this.names+'-wrapper').length == 1) {
                swal('You need to have at least 1 item.');
                return false;
            }

            var elem =  this.names +'-'+ this.num;
            var result = confirm("Are you sure to delete it?");
            if (result) {
                axios.post(url, postData).then(response=>{
                    $('#messageResponse').html(response.data);
                    //console.log(response.data)
                });
                document.getElementById(elem).remove();
            }




        },


    }

});