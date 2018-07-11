Vue.component('filters', {

    props: [

    ],

    data() {
        return {
            filters: '',
            subfilters: '',
            s: this.filters,

        }
    },

    methods: {

        getFilters (){
            axios.get('/filters').then((response) => {
                this.filters = response.data;
        })
        },

        /*showSubFilters (){
            axios.get('/sub-filters').then((response) => {
                this.subfilters = response.data;
        })
        },*/
        showSubFilters (){
            /*axios.get('/sub-filters').then((response) => {
                this.subfilters = response.data;
            })*/
            var sub_district = this.filters

            return alert(1);

        },

    },
    created() {
        this.getFilters();
        //this.showSubFilters();
    },

});