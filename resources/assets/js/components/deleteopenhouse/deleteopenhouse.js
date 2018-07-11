
Vue.component('deleteopenhouse', {
    props: ['id','num'],
    data () {
        return {
        }
    },
    methods: {

        deleteOpenHouse() {

            var url = '/deleteopenhouse';

            var postData = {
                id: this.id,
            };

            var elem =  'openHouse-'+ this.num;
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