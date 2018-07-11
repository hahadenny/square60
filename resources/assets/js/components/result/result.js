
Vue.component('result', {
    props: ['sort','searchid'],
    created () {
        this.getPosts();
        window.addEventListener('scroll', this.handleScroll)
    },
    data () {
        return {
            posts: [],
            postsLoading: false,
            nextPage: 1,
        }
    },
    methods: {

        getPosts () {

            this.postsLoading = true;

            if(this.nextPage === 1){
                var url = '/apisearch/'+ this.searchid;
            }

            url = '/apisearch/' + this.searchid + '/' +  this.sort + '/' + this.nextPage;


            axios.get(url)
                .then(response => {
                    this.posts = this.posts.concat(response.data);
		if (response.data.next){
		 ++this.nextPage;
		}else {
		 this.nextPage = false;
		}
                   

                    this.postsLoading = false
                })
                .catch(error => {
                    console.log(error)
                })
        },

        handleScroll (event) {

            var d = event.target.firstElementChild;
            var offset = $(window).scrollTop() + window.innerHeight + 150;
            var height = d.offsetHeight;
            if (offset >= height) {

                $('.reload2').fadeIn('fast').delay(9000);
                $('.reload').fadeOut('fast').delay(9000);
                 if (this.nextPage != false) {
               	 this.getPosts();                 
                 }
            }
        }
    }

});