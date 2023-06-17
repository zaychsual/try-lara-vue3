<template>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-8">
                <router-link
                    :to="{ name: 'transaction.create'}"
                    class="btn btn-primary btn-sm rounded shadow mb-3"
                >
                Add
                </router-link>
                <div class="card rounded shadow">
                    <div class="card-header">
                        Transaction List
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(transaction, index) in transactions.data"
                                    :key="index">
                                    <td>{{ transaction.title }}</td>
                                    <td>{{ transaction.amount }}</td>
                                    <td>{{ transaction.type }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <router-link
                                                :to="{ name: 'transaction.edit', params:{id: transaction.id } }"
                                                class="btn btn-sm btn-outline-info"
                                            >
                                            Edit
                                            </router-link>
                                            <button class="btn btn-sm btn-outline-danger" @click.prevent="destroy(transaction.id)">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { onMounted, ref } from 'vue'
import Swal from 'sweetalert'

export default {
    setup() {
        // Reactive state
        let transactions = ref([]);

        onMounted(() => {
            // get data from api
            axios.get('http://localhost:7474/api/transaction')
            .then((result) => {
                transactions.value = result.data
            }).catch((err) => {
                console.log(err.response)
            });
        });
        // onmounted itu gunanya
        // buat ambil datanya dulu setelah dipanggil dimuat transactions
        // return dibawah gunanya untuk menampilkan datanya

        function destroy(id, index) {
            axios.delete('http://localhost:7474/api/transaction/'+id)
            .then((result) => {
                transactions.value.data.splice(index,1)
            }).catch((err) => {
                console.log(err.response.data);
            });
        }
        return {
            transactions,
            destroy
        }
    }
}
</script>
