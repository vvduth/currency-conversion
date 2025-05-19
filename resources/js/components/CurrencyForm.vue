<template>
    <form
        @submit.prevent="convert"
        class="max-w-md mx-auto mt-10 p-8 bg-white rounded-xl shadow-lg space-y-6"
    >
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Amount</label>
            <input
                type="number"
                v-model="amount"
                placeholder="Enter amount"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
        </div>

        <div class="flex space-x-4">
            <div class="flex-1">
                <label class="block text-gray-700 font-semibold mb-2">From</label>
                <input
                    v-model="fromCurrency"
                    placeholder="From currency"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>
            <div class="flex-1">
                <label class="block text-gray-700 font-semibold mb-2">To</label>
                <input
                    type="text"
                    v-model="toCurrency"
                    placeholder="To currency"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>
        </div>

        <button
            class="w-full bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-700 transition"
        >
            Convert
        </button>

        <p
            v-if="result"
            class="mt-4 text-lg font-bold text-green-600 text-center bg-green-50 rounded p-3"
        >
            {{ amount }} {{ fromCurrency }} = {{ result }} {{ toCurrency }}
        </p>
    </form>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const amount = ref(0);
const fromCurrency = ref("EUR");
const toCurrency = ref("USD");
const result = ref(null);
const currencies = ref([]);

onMounted(() => {});

async function convert() {
    console.log("Converting...", amount.value, fromCurrency.value, toCurrency.value);
    const res = await axios.post("/api/convert", {
        amount: amount.value,
        base_currency: fromCurrency.value,
        quote_currency: toCurrency.value,
    });

    if (res.data.success) {
        result.value = res.data.converted_amount
    } else {
        result.value = null;
    }
}
</script>
