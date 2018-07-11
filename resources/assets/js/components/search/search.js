Vue.component('search', {
    props: [
        'type',
        'value',
    ],

    data () {
        return {
            types: [
                { id: 1, title: 'Address' },
                { id: 2, title: 'Buildings' },
                { id: 3, title: 'Agents' }
            ],

            searchType: 1,
            searchValue: this.value,
            action: '/search/address',
            url: '/autocomplete/building',

            data_results: [],
            isActive: false,
        }
    },

    methods: {

        changeAction (type) {

            this.searchType = type.id;

            if(type.id == 1){
                this.action='/search/address';
                this.url='/autocomplete/building';
            } else if(type.id == 2){
                this.action='/search/building';
                this.url='/autocomplete/building';
            } else {
                this.action='/search/agent';
                this.url='/autocomplete/agent';
            }

        },

        autoComplete(){
            this.data_results = [];
            if(this.searchValue.length > 1){
                this.isActive = true;
                axios.get(this.url, {params: {search: this.searchValue}}).then(response => {
                    //console.log(response);
                    this.data_results = response.data;

                    var agent = 0;
                    var build = 0;
                    for (var key in response.data) {
                      // skip loop if the property is from prototype
                      if (!response.data.hasOwnProperty(key)) continue;
                      var obj = response.data[key];                      
                      if (obj['link'].match(/agent/g))
                          agent++;
                      else
                          build++;
                    }                    
                    if (agent > build)
                        document.searchForm.action = '/search/agent';
                    else
                        document.searchForm.action = '/search/address';
                });

            }
        }

    }

});