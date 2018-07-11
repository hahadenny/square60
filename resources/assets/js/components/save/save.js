
Vue.component('save', {
    props: ['user','search','type','ids'],
    created () {
        this.getSearch();
    },
    data () {
        return {
            title: '',
            data: '',
            saveId: '',
            seen: false
        }
    },


    methods: {

        setPost () {

            console.log(this.saveId);

            var url = '/api/save-search/' + this.search;

            var postData = {
                user_id: this.user,
                search_id: this.search,
                title: this.title,
                saveId: this.saveId,
                type: this.type,
                ids: this.ids,
            };

            axios.post(url, postData).then(response=>{
               $('#messageResponse').html(response.data.msg);
               $('.modal-wrapper').click();

            });

        },

        getSearch() {

            var url = "/api/save-search/show";

            var postData = {
                user_id: this.user,
            };

            axios.post(url, postData).then(response=>{
                this.data = response.data
            });

            return this.data;
        },

        sendEmailListing() {
            console.log();
        },

    }

});