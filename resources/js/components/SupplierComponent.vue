<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1><b>Proveedores</b></h1>
                <hr>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Agregar proveedores
                </button>

                <table class="table my-2">
                    <thead class="thead-dark">
                        <tr>
                            <!-- <th scope="col">#</th> -->
                            <th scope="col">Nombre</th>
                            <th scope="col">RFC</th>
                            <th scope="col">Correo electrónico</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="supplier in suppliers" :key="supplier.id">
                            <!-- <th scope="row">1</th> -->
                            <td>{{ supplier.name }}</td>
                            <td>{{ supplier.rfc }}</td>
                            <td>{{ supplier.email }}</td>
                            <td>
                                <button class="btn btn-sm" v-bind:class="{'btn-danger': supplier.is_active == 0, 'btn-success': supplier.is_active == 1}" @click="access(supplier)">
                                    {{supplier.is_active == 1 ? 'Quitar acceso':'Dar acceso'}}
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agregar proveedores</h5>
                        <button type="button" class="close" data-dismiss    ="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="file" @change="redFile($event)" accept=".txt">
                        
                        <table class="table mt-2">
                            <thead class="thead-dark">
                                <tr>
                                    <!-- <th scope="col">#</th> -->
                                    <th scope="col">Nombre</th>
                                    <th scope="col">RFC</th>
                                    <th scope="col">Correo electrónico</th>
                        
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="supplier in suppliersList" :key="supplier.id">
                                    <!-- <th scope="row">1</th> -->
                                    <td>{{ supplier.name }}</td>
                                    <td>{{ supplier.rfc }}</td>
                                    <td>{{ supplier.email }}</td>
                                  
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" @click="saveSuppliers" class="btn btn-primary">Guardar</button>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                suppliers: [],
                suppliersList: []
            }
        },
        created(){
            console.log("Compenent created");
            this.getSuppliers();
        },
        methods : {
            async getSuppliers(){
                this.suppliers = [];
                let res = await axios.get('/suppliers');
                this.suppliers = res.data;
                //console.log(this.suppliers);
            },
            async saveSuppliers(){
                //console.log("save");
                if(this.suppliersList.length > 0){
                    console.log(this.suppliersList);
                    let res = await axios.post('/suppliers', this.suppliersList);
                     let status = res.data.status;
                    let message = res.data.message;

                    if(status){
                        location.reload();
                    }else{
                        alert(message);   
                    }
                }else{
                    alert("No hay proveedores para agregar");
                }
            },
            redFile(event) {
                const file = event.target.files[0];
                const reader = new FileReader();
                let suppliersList = [];

                reader.onload = function(event) {
                    // El texto del archivo se mostrará por consola aquí
                    let data = event.target.result;
                    let arrayData = data.split(",");

                    arrayData.forEach(supplier => {
                        let supplierData = supplier.split("-");
                        let name = supplierData[0].trim();
                        let rfc = supplierData[1].trim();
                        let email = supplierData[2].trim();
                        let is_active = 0;

                        suppliersList.push(
                            {'name':name, 'rfc':rfc, 'email':email}
                        );
                    });
        
                };
                reader.readAsText(file);    
                this.suppliersList = suppliersList;
                //console.log(this.suppliersList);
            },
            async access(supplier){
                //console.log('access', supplier);
                let res = await axios.post('/suppliersUpdate', supplier);
                let status = res.data.status;
                let message = res.data.message;

                if(status){
                    this.getSuppliers();
                }else{
                    alert(message);   
                }
                
            }
        }
    }
</script>

<style>
    .mostrar {
        display: list-item;
        opacity: 1;
        background: rgb(44, 38, 75, 0.849);
    }
</style>