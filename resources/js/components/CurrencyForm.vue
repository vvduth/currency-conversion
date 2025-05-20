<template>
    <form @submit.prevent="convert" class="max-w-md mx-auto mt-10 p-8 bg-white rounded-xl shadow-lg space-y-6">
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Amount</label>
            <input type="number" v-model="amount" placeholder="Enter amount"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>
        <CurrencyAutoSuggest v-model="from" :currencies="currencies" label="From" aria-label="base_currency" />
        <CurrencyAutoSuggest v-model="to" :currencies="currencies" label="To" aria-label="quote_currency" />

        <button class="w-full bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-700 transition">
            Convert
        </button>

        <p v-if="result" class="mt-4 text-lg font-bold text-green-600 text-center bg-green-50 rounded p-3">
            {{ amount }} {{ from }} = {{ result }} {{ to }}
        </p>
    </form>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import CurrencyAutoSuggest from "./CurrencyAutoSuggest.vue";

const amount = ref(0);
const from = ref("");
const to = ref("");
const result = ref(null);
const currencies = ref([]);

onMounted(async () => {
    await fetchCurrencies();

});

async function convert() {
    console.log("Converting...", amount.value, from.value, to.value);
    const res = await axios.post("/api/convert", {
        amount: amount.value,
        base_currency: from.value,
        quote_currency: to.value,
    });

    if (res.data.success) {
        result.value = res.data.converted_amount
    } else {
        result.value = null;
    }
}

async function fetchCurrencies() {
    const res = await axios.get("/api/currencies");
    if (res.data.success) {
        currencies.value = res.data.currencies;
    }
}
</script>
