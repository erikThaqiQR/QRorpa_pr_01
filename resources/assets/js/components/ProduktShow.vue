<template>
    <div>
       <h1>Produktet</h1>
        
        <!-- <ul>
            <li v-for="produkt in list" :key="produkt.id"> 
                {{produkt.emri}} {{produkt.pershkrimi}} {{produkt.qmimi}} {{produkt.foto}}
            </li>
        </ul> -->

        <!-- <ul>
            <li v-for="produkt in list">{{produkt->emri}}</li>

            {{list}}
        </ul> -->

       


    </div>
</template>
<script src="{{ asset('js/app.js') }}"></script>
<script>
export default {
    data() {
        return{
            list: [],
            produktet:{
                id:'',
                emri:'',
                pershkrimi:'',
                qmimi:'',
                foto:''
            }
        }
    },
    methods:{
        fetchProduktetList : function () {
            console.log('Produktet jane marr...');
            console.log('Fetching..........');
            var root = this;
            axios.get('api/produktetAdd')
            .then((response)=>{
                root.list = response.data;
            }

            );
            console.log(this.list);
        }
    },
    mounted(){
        this.fetchProduktetList();
         console.log('our list');
         console.log(this.list);
    }
    
}
   
</script>