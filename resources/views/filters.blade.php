<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/bulma.css">
    <link rel="stylesheet" href="css/app.css">


    <title>Square 60 - New York</title>


</head>


<body>

{{--<div id="app">
   <div>
       --}}{{--<filters inline-template>
           <div>
               @{{ filters }}
               <div class="container">
                   <div class="row">


                          <ul>
                           <li v-for="filter in filters">
                               <h4>@{{ filter.label }}</h4>
                               <div :class="filter.name">
                                   <ul>
                                       <li v-for="sub_filter in filter.sub_filters">
                                           <div v-if="filter.name === 'district'">
                                               <a class="s_city" :id="sub_filter.value" v-on:click="showSubFilters">@{{ sub_filter.value }}</a>
                                           </div>
                                           <div id="sub-district" v-else-if="filter.name === 'sub_districts' && sub_filter.parent_id === 1">
                                               @{{ sub_filter.value }}
                                           </div>
                                           <div v-else-if="filter.name !== 'sub_districts'">
                                               @{{ sub_filter.value }}
                                           </div>
                                           <div v-else>
                                               @{{ sub_filter.value }}
                                           </div>
                                       </li>
                                   </ul>
                               </div>

                           </li>
                       </ul>


                   </div>
               </div>
           </div>
       </filters>

       <result inline-template v-bind:searchid="7">
           <div>

               <ul >
                   <li v-for="post in posts">

                       <pre>@{{ post }}</pre>

                       <ul>
                           <li v-for="advert in post.results">
                               @{{ advert.id }}<br>
                               @{{ advert.street }}
                               <p>@{{ advert.description }}</p>
                           </li>
                       </ul>

                   </li>
               </ul>
               <div class="text-center"
                    v-show="postsLoading">
                   Loading...
               </div>


           </div>

       </result>--}}{{--

   </div>
</div>--}}

<!-- template for the modal component -->


<!-- app -->
<div id="app">
    <button id="show-modal" @click="showModal = true">Show Modal</button>
    <!-- use the modal component, pass in the prop -->
    <modal v-if="showModal" @close="showModal = false">
        <h3 slot="header">Enter email</h3>
    </modal>
</div>




<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>