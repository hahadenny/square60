
Vue.component('deletephoto', {
    props: ['num','list','names'],
    data () {
        return {
        }
    },
    methods: {

        deleteImage() {

            var url = '/deleteagentimage';

            var postData = {
                key: this.num,
                id: this.list,
                name: this.names,
            };

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