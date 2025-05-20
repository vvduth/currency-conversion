<template>
    <form @submit.prevent="convert" class="max-w-md mx-auto mt-10 p-8 bg-white rounded-xl shadow-lg space-y-6"
        aria-labelledby="currency-conversion-form-title" role="form" autocomplete="off">
        <h2 id="currency-conversion-form-title" class="sr-only">Currency Conversion Form</h2>
        <div>
            <label for="amount-input" class="block text-gray-700 font-semibold mb-2">Amount</label>
            <input id="amount-input" type="number" v-model="amount" placeholder="Enter amount"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                required min="0" step="any" inputmode="decimal" aria-required="true" aria-label="Amount" />
        </div>
        <CurrencyAutoSuggest v-model="from" :currencies="currencies" label="From" aria-label="Base currency"
            id="from-currency" required />
        <CurrencyAutoSuggest v-model="to" :currencies="currencies" label="To" aria-label="Quote currency"
            id="to-currency" required />

        <button type="submit"
            class="w-full bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-700 transition"
            aria-label="Convert currency">
            <span v-if="isLoading === false">Convert</span>
            <span v-if="isLoading === true">Loading...</span>

        </button>

        <p v-if="result" class="mt-4 text-lg font-bold text-green-600 text-center bg-green-50 rounded p-3" role="status"
            aria-live="polite">
            {{ formattedAmount }} {{ from }} = {{ result }} {{ to }}
        </p>
    </form>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import CurrencyAutoSuggest from "./CurrencyAutoSuggest.vue";

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

const amount = ref(0);
const isLoading = ref(false);
const from = ref("");
const to = ref("");
const result = ref(null);
const currencies = ref([]);
const formattedAmount = computed(() => {
    if (!from.value || !amount.value) return "";
    try {
        return new Intl.NumberFormat(undefined, { style: "currency", currency: from.value }).format(amount.value);
    } catch {
        return amount.value;
    }
});

onMounted(async () => {
    await fetchCurrencies();

});

async function convert() {

    try {
        isLoading.value = true;
        await axios.get('/sanctum/csrf-cookie');
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
    } catch (error) {
        console.error("Error during conversion:", error);
        result.value = null;
    } finally {
        isLoading.value = false;
    }
}

async function fetchCurrencies() {
    const res = await axios.get("/api/currencies");
    if (res.data.success) {
        currencies.value = res.data.currencies;
    }
}
</script>
