
<template>
    <div>
        <h1>Shtoni produktin</h1>

        <form action="#" @submit.prevent ="edit ? updateProdukt(produktet.id) : createProdukt()">
            <div class="form-group">
                <label>Emri</label>
                <input v-model="produktet.emri" type="text" name="emri" class="form-control">
            </div>

            <div class="form-group">
                <label>Pershkrimi</label>
                <input v-model="produktet.pershkrimi" type="textarea" name="pershkrimi" class="form-control">
            </div>

            <div class="form-group">
                <label> Qmimi</label>
                <input v-model="produktet.qmimi" type="number" step="any" name="qmimi" class="form-control">
            </div>

            <div class="form-group">
                <label>Foto</label>
                <input v-model="produktet.foto" type="string" name="foto" class="form-control">
            </div>

            <div class="form-group">
                <button v-show="!edit" type="submit" class="btn btn-primary">Shto</button>
                <button v-show="edit" type="submit" class="btn btn-primary">Perditso</button>
            </div>



        </form>

        <!-- <ul>
            <li v-for="produkt in list">{{produkt.emri}}</li>
        </ul> -->
    </div>
</template>

<script>
export default {
    data: function(){
        return{
            edit:false,
            list:[],
            produktet:{
                id:'',
                emri:'',
                pershkrimi:'',
                qmimi:'',
                foto:''
            }
        }
    },
    mounted: function(){
        console.log('Produktet jane marr...');
        this.fetchProduktetList();
    },
    methods: {
        fetchProduktetList:function(){
            console.log('Fetching..........');
            axios.get('api/produktetAdd')
            .then((response) => {
                this.list = response.data;
                console.log(response.data);
            }).catch((error) =>{
                console.log(error);
            })
            return;
        },

        createProdukt: function(){
            console.log('Creating Produktet...');
            return;
            // let self = this;
            // let params =Object.assign({}, self.produktet);
            // axios.post('api/produktetAdd/store', params)
            //     .then(function(){
            //         self.produktet.emri = '';
            //         self.produktet.pershkrimi = '';
            //         self.produktet.qmimi = '';
            //         self.produktet.foto = '';
            //         self.edit = false;
            //         self.fetchProduktetList();
            //     })
            //     .catch(function(error){
            //         console.log(error);
            //     })
        },
        updateProdukt: function(id){

        }
    }
}
</script>


